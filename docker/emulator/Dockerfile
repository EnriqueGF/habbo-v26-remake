FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

# Mono runtime + C# compiler (mcs), unixODBC and the MariaDB Connector/ODBC.
# The emulator connects through ODBC using the driver name "MySQL ODBC 5.1 Driver",
# which the entrypoint aliases to libmaodbc.
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        mono-complete \
        unixodbc \
        odbc-mariadb \
        ca-certificates \
    && rm -rf /var/lib/apt/lists/*

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /emu
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
