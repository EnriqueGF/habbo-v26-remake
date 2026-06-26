-- Default administrator account (HoloCMS rank 7, the $sysadmin id = 1).
-- Password is hashed exactly like the CMS does it: md5(salt . password),
-- salt "235x17aXCaRb" (see CMS/includes/inc.crypt.php). Password here = "admin".
-- Runs once on first DB initialisation (holodb.sql ships no seed users).

USE v26;

INSERT IGNORE INTO `users`
    (`id`,`name`,`password`,`rank`,`email`,`birth`,`hbirth`,`figure`,`sex`,`credits`,`mission`,`shockwaveid`,`consolemission`)
VALUES
    (1,'admin', MD5(CONCAT('235x17aXCaRb','admin')), 7,
     'admin@habbo.local','1990-01-01','1-1-1990',
     'hr-115-42.hd-190-10.ch-215-66.lg-285-77.sh-290-80','M',
     99999,'Administrator','','');
