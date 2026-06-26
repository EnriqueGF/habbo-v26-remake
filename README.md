<p align="center">
  <img src="docs/img/hotel.png" alt="Habbo Hotel v26" width="756">
</p>

<h1 align="center">Habbo V26 · Remake</h1>

<p align="center">
  <i>A personal project to bring my favourite version of Habbo Hotel back to life: <b>v26</b> (2008–2009).</i>
</p>

<p align="center">
  <img alt="status" src="https://img.shields.io/badge/status-in%20development-orange">
  <img alt="docker" src="https://img.shields.io/badge/Docker-Compose-2496ED?logo=docker&logoColor=white">
  <img alt="php" src="https://img.shields.io/badge/PHP-8.3%20(Laravel%2013)-777BB4?logo=php&logoColor=white">
  <img alt="wasm" src="https://img.shields.io/badge/client-WebAssembly-654FF0?logo=webassembly&logoColor=white">
</p>

---

## 👋 What is this?

**v26** was the Habbo Hotel release from **2008–2009**: the classic client built on
**Adobe Shockwave**, with its hotel view, room navigator and that pixel-art so many of
us remember fondly. It was **my favourite version**, and this repo is my personal
attempt to **rescue it and bring it up to date** so it runs in a modern world.

The catch: v26 relied on **Shockwave** — a dead technology **no current browser runs** —
and on an emulator built for **Windows**. This project fixes that: it spins up the whole
hotel —website, database, emulator and client— with a **single `docker compose up`**,
no Windows, no plugins, and with the client running **inside the browser via WebAssembly**.

> ⚠️ **Personal, educational project**, non-commercial. Not affiliated with Sulake.
> *Habbo* and *Habbo Hotel* are trademarks of **Sulake Oy**; all original assets belong
> to their owners.

---

## ✨ What already works

- 🐳 **Fully dockerized** — a single `docker compose up` brings up the database, the
  website, the client assets, the emulator and the network bridge.
- 🎮 **The v26 client runs in the browser** — the Shockwave client runs via
  **[DirPlayer](https://github.com/igorlira/dirplayer-rs)** (a Director/Shockwave player
  in Rust → WebAssembly). It renders the hotel, connects to the emulator, logs in and
  browses rooms. **No Shockwave, no PaleMoon, no plugins.**
- 🧬 **No Windows for the emulator** — the *Holograph Emulator* (.NET) is **recompiled
  with Mono** and talks to the database over **ODBC**, all inside a Linux container.
- 🌐 **WebSocket ↔ TCP bridge** — since browsers can't open raw TCP sockets, a proxy
  translates the client's connection to the emulator.
- 🧑‍🎨 **Avatar editor** — the original Flash editor runs with
  **[Ruffle](https://ruffle.rs/)** (Flash → WebAssembly); you can change your look and
  save it.
- 🌍 **Fully localized in Spanish** — CMS and client translated end to end, with the
  **Spanish hotel view**.
- 🖼️ **Locally served avatars** — avatar image generation with its own cache.

---

## 🛠️ What I'm reworking

This is the heart of the project: modernise the internals without losing the v26 soul.

| Area | From | To | Status |
|------|------|----|--------|
| **Web / CMS** | HoloCMS (PHP 5.2, 2008) | **Laravel 13 / PHP 8.3** | 🟡 phased migration (*strangler pattern*) |
| **Client** | Shockwave plugin | **DirPlayer (WASM)** | 🟢 playable, polishing |
| **Avatar editor** | Flash plugin | **Ruffle (WASM)** | 🟢 working |
| **Emulator** | Windows binary | **Mono + ODBC (Linux)** | 🟢 working |
| **Infra** | manual Windows VPS | **Docker Compose** | 🟢 working |
| **Language** | French / English | **Spanish** | 🟢 done |

The legacy CMS is being replaced **piece by piece** by Laravel while staying functional
at every step (instead of a big-bang rewrite). The full plan and tracking live in
[`/docs`](docs/).

**Next steps:** keep migrating CMS views to Laravel, polish the client's visual
fidelity, and move towards a 100% local avatar imager.

---

## 🚀 Getting started

Requirements: **Docker** and **Docker Compose**.

```bash
git clone https://github.com/EnriqueGF/habbo-v26-remake.git
cd habbo-v26-remake
docker compose up -d        # first run compiles the emulator (Mono) — takes a moment
```

Once the containers are up:

| Service | URL / Port |
|---------|------------|
| 🌐 **Hotel website** | http://localhost:8090 |
| 📦 Client assets (DCRs) | http://localhost:8091 |
| 🎮 Emulator (game / MUS) | `127.0.0.1:1232` · `30000` |
| 🔌 WebSocket↔TCP bridge | `8092` · `8093` |
| 🗄️ Database (MariaDB) | `127.0.0.1:3307` |

**Sample admin account:** `admin` / `admin`

To enter the hotel: register or log in with the admin account and click **"Enter Habbo"**.
The client loads in the browser (**Chrome/Edge** recommended for their WebGPU support,
which gives the best rendering of the avatar editor).

> The credentials in `docker-compose.yml` are for **local development**. Change them if
> you expose this beyond your own machine.

---

## 🧩 Architecture

```
Browser ──┬─ Web (Laravel 13 / PHP 8.3 + legacy HoloCMS in-process)     :8090
          ├─ Shockwave client   → DirPlayer (WASM)
          │        │
          │        └─ WebSocket ─→ proxy ─→ TCP ─→ Emulator (Mono+ODBC) :1232/:30000
          ├─ Avatar editor      → Ruffle (WASM)
          └─ DCRs / assets (nginx)                                       :8091
                                 Database: MariaDB                        :3307
```

### Repo layout

```
apps/web/        Modern web (Laravel 13) + legacy HoloCMS under /legacy
services/
  emulator/      Holograph Emulator (.NET) — compiled with Mono at runtime
assets/dcr/      v26 client Shockwave assets (.dcr / .cct)
database/        DB dump + boot-time fixups
docker/          Dockerfile per service
docs/            Modernisation plan, phases and decisions
```

---

## 🙏 Credits

This project stands on the work of many people in the Habbo retro community:

- **Base v26 pack** — [HRWCMS](https://github.com/EudesFR/HRWCMS) (HRW CMS, Holograph
  Emulator and DCRs).
- **HoloCMS** — Meth0d · **Holograph Emulator** — Meth0d / the Holograph team.
- **[DirPlayer](https://github.com/igorlira/dirplayer-rs)** — Shockwave emulator in
  Rust/WASM, by igorlira (with chameleonxxl's *bobba-xtra*).
- **[Ruffle](https://ruffle.rs/)** — Flash emulator in Rust/WASM.

Thanks to everyone who kept v26 alive.

---

## ⚖️ Legal notice

A **personal, educational, non-commercial** project, made out of nostalgia. Not
affiliated with, associated with or endorsed by **Sulake Oy**. *Habbo*, *Habbo Hotel*
and the related names, graphics and assets are **property of Sulake**. If you are a
rights holder and want something removed, please open an issue.

The **original modernisation code** (Laravel, Docker, integrations) is published for
learning purposes. The original Habbo assets are **not** mine and are **not** licensed
here.
