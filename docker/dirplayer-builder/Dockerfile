FROM rust:1-bookworm

ENV DEBIAN_FRONTEND=noninteractive

# Node.js + npm and basic native-build deps (for any node-gyp transitive deps).
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        nodejs npm curl ca-certificates python3 build-essential \
    && rm -rf /var/lib/apt/lists/*

# WASM target + wasm-pack (prebuilt binary).
RUN rustup target add wasm32-unknown-unknown
RUN curl -sSL https://github.com/rustwasm/wasm-pack/releases/download/v0.13.1/wasm-pack-v0.13.1-x86_64-unknown-linux-musl.tar.gz \
        | tar xz -C /tmp \
    && mv /tmp/wasm-pack-*/wasm-pack /usr/local/bin/wasm-pack \
    && chmod +x /usr/local/bin/wasm-pack \
    && wasm-pack --version

WORKDIR /build
ENTRYPOINT ["/bin/bash"]
