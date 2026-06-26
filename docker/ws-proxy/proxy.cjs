/**
 * WebSocket <-> TCP bridge so the in-browser DirPlayer client can reach the
 * Holograph emulator (browsers cannot open raw TCP sockets).
 *
 *   ws://0.0.0.0:GAME_WS_PORT -> tcp://GAME_TCP_HOST:GAME_TCP_PORT  (game server)
 *   ws://0.0.0.0:MUS_WS_PORT  -> tcp://MUS_TCP_HOST:MUS_TCP_PORT    (MUS server)
 *
 * Adapted from dirplayer-rs/ws-tcp-proxy-all.cjs.
 */
const WebSocket = require('ws');
const net = require('net');

function createProxy(wsPort, tcpHost, tcpPort, name) {
  const wss = new WebSocket.Server({ host: '0.0.0.0', port: wsPort });
  console.log(`[${name}] ws://0.0.0.0:${wsPort} -> tcp://${tcpHost}:${tcpPort}`);

  wss.on('connection', (ws, req) => {
    console.log(`[${name}] WS connect from ${req.socket.remoteAddress}`);
    const tcp = net.createConnection({ host: tcpHost, port: tcpPort }, () => {
      console.log(`[${name}] -> TCP connected ${tcpHost}:${tcpPort}`);
    });

    // Director sends/expects raw bytes; forward verbatim in both directions.
    tcp.on('data', (data) => { if (ws.readyState === WebSocket.OPEN) ws.send(data); });
    ws.on('message', (data) => { if (tcp.writable) tcp.write(data); });

    tcp.on('close', () => { console.log(`[${name}] TCP closed`); try { ws.close(); } catch {} });
    ws.on('close',  () => { console.log(`[${name}] WS closed`);  try { tcp.end(); } catch {} });
    tcp.on('error', (e) => { console.error(`[${name}] TCP error ${e.message}`); try { ws.close(); } catch {} });
    ws.on('error',  (e) => { console.error(`[${name}] WS error ${e.message}`);  try { tcp.end(); } catch {} });
  });

  wss.on('error', (e) => console.error(`[${name}] server error ${e.message}`));
  return wss;
}

const GAME_WS_PORT = parseInt(process.env.GAME_WS_PORT || '8092', 10);
const GAME_TCP_HOST = process.env.GAME_TCP_HOST || 'emu';
const GAME_TCP_PORT = parseInt(process.env.GAME_TCP_PORT || '1232', 10);

const MUS_WS_PORT = parseInt(process.env.MUS_WS_PORT || '8093', 10);
const MUS_TCP_HOST = process.env.MUS_TCP_HOST || 'emu';
const MUS_TCP_PORT = parseInt(process.env.MUS_TCP_PORT || '30000', 10);

console.log('DirPlayer WebSocket<->TCP proxy starting...');
createProxy(GAME_WS_PORT, GAME_TCP_HOST, GAME_TCP_PORT, 'Game');
createProxy(MUS_WS_PORT, MUS_TCP_HOST, MUS_TCP_PORT, 'MUS');
console.log('Ready.');
