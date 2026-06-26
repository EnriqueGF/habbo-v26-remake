#!/usr/bin/env bash
set -e

EMU_DIR=/emu

# --- Register the MariaDB ODBC driver under the name the emulator expects ------
DRIVER_SO="$(find /usr/lib -name 'libmaodbc.so' 2>/dev/null | head -1)"
if [ -z "$DRIVER_SO" ]; then
    echo "ERROR: MariaDB ODBC driver (libmaodbc.so) not found." >&2
    exit 1
fi
cat > /etc/odbcinst.ini <<EOF
[MySQL ODBC 5.1 Driver]
Description = MariaDB Connector/ODBC (aliased as MySQL ODBC 5.1 Driver)
Driver      = $DRIVER_SO
UsageCount  = 1
EOF
echo "[entrypoint] Registered ODBC driver -> $DRIVER_SO"

# --- Generate mysql.ini from environment --------------------------------------
mkdir -p "$EMU_DIR/bin"
cat > "$EMU_DIR/bin/mysql.ini" <<EOF
[mysql]
host=${DB_HOST:-db}
port=${DB_PORT:-3306}
database=${DB_NAME:-v26}
username=${DB_USER:-habbo}
password=${DB_PASS:-habbo}
EOF
echo "[entrypoint] Wrote $EMU_DIR/bin/mysql.ini (host=${DB_HOST:-db} db=${DB_NAME:-v26})"

# --- Compile the (patched) emulator with Mono if needed -----------------------
cd "$EMU_DIR"
if [ ! -f holo.exe ] || [ "${REBUILD:-0}" = "1" ]; then
    echo "[entrypoint] Compiling Holograph Emulator with Mono (mcs)..."
    # NUL-delimited so paths containing spaces (e.g. "Source/Socket servers") work.
    find Source Properties -name '*.cs' -print0 | xargs -0 \
        mcs -target:exe -sdk:4 -out:holo.exe \
        -r:System.Data \
        -r:System.Data.DataSetExtensions \
        -r:System.Core \
        -r:System.Xml \
        -r:System.Xml.Linq
    if [ ! -f holo.exe ]; then
        echo "[entrypoint] ERROR: compilation failed (holo.exe not produced)." >&2
        exit 1
    fi
    echo "[entrypoint] Build complete."
fi

echo "[entrypoint] Starting Holograph Emulator..."
exec mono holo.exe
