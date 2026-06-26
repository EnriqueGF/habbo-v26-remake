-- Post-import fixups for the dockerized Habbo V26 stack.
-- Runs once on first DB initialisation (after holodb.sql).

USE v26;

-- --- Emulator socket configuration -------------------------------------------
-- Original dump used mus port 22 (privileged / SSH) and an external IP.
UPDATE `system_config` SET `sval` = '30000' WHERE `skey` = 'server_mus_port';
UPDATE `system_config` SET `sval` = 'emu'   WHERE `skey` = 'server_mus_host';
UPDATE `system_config` SET `sval` = '1232'  WHERE `skey` = 'server_game_port';

-- --- CMS / client loader configuration ---------------------------------------
-- The browser (Shockwave client) reaches the published host ports:
--   game server -> 127.0.0.1:1232   |   DCR assets -> localhost:8091   |   CMS -> localhost:8090
UPDATE `cms_system` SET
    `sitename`   = 'Habbo V26',
    `shortname`  = 'Habbo',
    `site_closed`= '0',
    `language`   = 'en',
    `ip`         = '127.0.0.1',
    `port`       = '1232',
    `texts`      = 'http://localhost:8091/texts.txt',
    `variables`  = 'http://localhost:8091/vars.txt',
    `dcr`        = 'http://localhost:8091/habbo.dcr',
    `reload_url` = 'http://localhost:8090/client.php',
    `localhost`  = '1';
