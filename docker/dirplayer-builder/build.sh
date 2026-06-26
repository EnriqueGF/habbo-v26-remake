#!/usr/bin/env bash
# Builds the DirPlayer WASM VM, the Habbo (bobba) xtra, and the self-contained
# browser polyfill. Run inside the builder image with /build = dirplayer-rs.
set -euo pipefail

cd /build

export ELECTRON_SKIP_BINARY_DOWNLOAD=1
export PUPPETEER_SKIP_DOWNLOAD=1
export PLAYWRIGHT_SKIP_BROWSER_DOWNLOAD=1
export HUSKY=0
export CI=true
export NODE_OPTIONS=--max-old-space-size=4096

# The root package.json has `"vm-rust": "file:vm-rust/pkg"`, so the VM (wasm-pack
# output) must exist BEFORE npm install. wasm-pack needs only cargo, not node_modules.
echo "===> build VM (Rust -> WASM) [before npm install]"
( cd vm-rust && wasm-pack build --target web )

echo "===> npm install"
npm install --no-audit --no-fund --no-save

echo "===> build xtras (bobba-xtra etc.)"
node ./scripts/build-xtras.mjs || echo "WARN: xtras build reported issues, continuing"

echo "===> build polyfill (vite)"
npx vite build -c vite.config.polyfill.js

echo "===> copy xtras into dist-polyfill"
node ./scripts/copy-xtras.mjs dist-polyfill || true

echo "===> done. Artifacts:"
ls -la dist-polyfill/ || true
ls -la public/*.wasm 2>/dev/null || true
