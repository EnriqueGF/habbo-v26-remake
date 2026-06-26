#!/usr/bin/env bash
#
# Crea (o recarga) la base de datos de test a partir del esquema semilla y la
# convierte a InnoDB para que los tests puedan usar transacciones (rollback).
# Lo usan tanto la CI como el entorno local.
#
# Variables (con valores por defecto para la CI):
#   DB_HOST, DB_PORT, DB_ROOT_USER, DB_ROOT_PASSWORD, DB_TEST
set -euo pipefail

HOST="${DB_HOST:-127.0.0.1}"
PORT="${DB_PORT:-3306}"
USER="${DB_ROOT_USER:-root}"
PASS="${DB_ROOT_PASSWORD:-habboroot}"
DB="${DB_TEST:-v26_test}"
APP_USER="${DB_APP_USER:-habbo}"

DIR="$(cd "$(dirname "$0")" && pwd)"

run() { mysql -h"$HOST" -P"$PORT" -u"$USER" -p"$PASS" "$@"; }

echo "==> (re)creando $DB"
run -e "DROP DATABASE IF EXISTS \`$DB\`;
        CREATE DATABASE \`$DB\` CHARACTER SET latin1 COLLATE latin1_general_ci;
        GRANT ALL ON \`$DB\`.* TO '$APP_USER'@'%';"

echo "==> cargando esquema semilla"
run "$DB" < "$DIR/seed/holodb.sql"

# Los fixups llevan 'USE v26;' — lo quitamos para que apliquen a la BD de test.
for f in "$DIR"/fixups/*.sql; do
    [ -f "$f" ] || continue
    echo "==> aplicando $(basename "$f")"
    sed '/^[[:space:]]*USE[[:space:]]\+v26[[:space:]]*;/Id' "$f" | run "$DB"
done

echo "==> convirtiendo MyISAM -> InnoDB"
run -Nse "SELECT CONCAT('ALTER TABLE \`', table_name, '\` ENGINE=InnoDB;')
          FROM information_schema.tables
          WHERE table_schema='$DB' AND engine='MyISAM';" | run "$DB"

echo "==> BD de test $DB lista (InnoDB)."
