<?php
/**
 * Compatibility shims for running the 2008-era HoloCMS on PHP 8.x.
 * Loaded via auto_prepend_file. It re-implements / neutralises the language and
 * extension features the legacy code relies on that were removed after PHP 5.6:
 *
 *   1. The ext/mysql API  (mysql_*)        -> reimplemented over mysqli
 *   2. Bareword constants used as strings  -> define()'d to their own name
 *   3. session_is_registered() & friends   -> removed in PHP 5.4/7.0
 *
 * This layer is TEMPORARY: it disappears once the legacy code is fully migrated
 * to Laravel (see /docs/04-plan-fases.md, Fase 5).
 */

// ---------------------------------------------------------------------------
// 1. ext/mysql  ->  mysqli  shim
// ---------------------------------------------------------------------------
// The legacy uses the implicit "last opened link" model of ext/mysql, so we
// keep the connection in a global and ignore the optional $link argument the
// old API accepted (the code never passes it explicitly anyway).

if (!function_exists('mysql_query')) {

    // mysqli throws exceptions by default since PHP 8.1; the legacy expects the
    // old "return false / check mysql_error()" model, so silence reporting.
    mysqli_report(MYSQLI_REPORT_OFF);

    // Old fetch-mode constants the legacy may reference.
    if (!defined('MYSQL_ASSOC')) define('MYSQL_ASSOC', MYSQLI_ASSOC);
    if (!defined('MYSQL_NUM'))   define('MYSQL_NUM',   MYSQLI_NUM);
    if (!defined('MYSQL_BOTH'))  define('MYSQL_BOTH',  MYSQLI_BOTH);

    // ext/mysql returned a *resource* for SELECT queries; the legacy sometimes
    // (buggily) uses that handle in string context, which on PHP 5.6 produced
    // "Resource id #N" (a notice, never fatal). A mysqli_result is an *object*
    // and throws when cast to string. We wrap results so the same code keeps
    // rendering: __toString mimics the old resource string, and the shim's
    // fetch helpers transparently unwrap to the real mysqli_result.
    class HoloMysqlResult {
        public $res;
        public $id;
        public function __construct($res) {
            $this->res = $res;
            if (!isset($GLOBALS['__holo_res_seq'])) { $GLOBALS['__holo_res_seq'] = 4; }
            $this->id = ++$GLOBALS['__holo_res_seq'];
        }
        public function __toString() { return 'Resource id #' . $this->id; }
    }

    /** Resolve the link to operate on: an explicit mysqli, or the stored one. */
    function __holo_link($maybe = null) {
        if ($maybe instanceof mysqli) return $maybe;
        return isset($GLOBALS['__holo_mysqli']) ? $GLOBALS['__holo_mysqli'] : null;
    }

    /** Unwrap a HoloMysqlResult (or pass through a raw mysqli_result). */
    function __holo_res($r) {
        if ($r instanceof HoloMysqlResult) return $r->res;
        return $r;
    }

    function mysql_connect($host = null, $user = null, $pass = null, $new = false, $flags = 0) {
        $port = 3306;
        if ($host !== null && strpos($host, ':') !== false) {
            list($host, $p) = explode(':', $host, 2);
            if (is_numeric($p)) $port = (int) $p;
        }
        $link = @mysqli_connect($host, (string) $user, (string) $pass, '', $port);
        if (!$link) {
            return false; // emulate ext/mysql: caller does `or die(mysql_error())`
        }
        // The DB and the source files are latin1; match the old default charset.
        @mysqli_set_charset($link, 'latin1');
        $GLOBALS['__holo_mysqli'] = $link;
        return $link;
    }

    function mysql_select_db($db, $link = null) {
        $l = __holo_link($link);
        return $l ? mysqli_select_db($l, $db) : false;
    }

    function mysql_query($query, $link = null) {
        $l = __holo_link($link);
        if (!$l) return false;
        $r = mysqli_query($l, $query);
        return ($r instanceof mysqli_result) ? new HoloMysqlResult($r) : $r;
    }

    function mysql_error($link = null) {
        $l = __holo_link($link);
        if ($l) return mysqli_error($l);
        $e = mysqli_connect_error();
        return $e ? $e : '';
    }
    // Latent typo present in the legacy (only hit on query failure paths).
    function mysql_errors($link = null) { return mysql_error($link); }

    function mysql_fetch_assoc($result) {
        $result = __holo_res($result);
        return ($result instanceof mysqli_result) ? mysqli_fetch_assoc($result) : false;
    }
    // Latent typo present in the legacy.
    function mysql_get_assoc($result) { return mysql_fetch_assoc($result); }

    function mysql_fetch_array($result, $type = MYSQLI_BOTH) {
        $result = __holo_res($result);
        return ($result instanceof mysqli_result) ? mysqli_fetch_array($result, $type) : false;
    }

    function mysql_fetch_row($result) {
        $result = __holo_res($result);
        return ($result instanceof mysqli_result) ? mysqli_fetch_row($result) : false;
    }

    function mysql_fetch_object($result) {
        $result = __holo_res($result);
        return ($result instanceof mysqli_result) ? mysqli_fetch_object($result) : false;
    }

    function mysql_num_rows($result) {
        $result = __holo_res($result);
        return ($result instanceof mysqli_result) ? mysqli_num_rows($result) : 0;
    }

    function mysql_num_fields($result) {
        $result = __holo_res($result);
        return ($result instanceof mysqli_result) ? mysqli_num_fields($result) : 0;
    }

    /** ext/mysql mysql_result($res, $row, $field=0) — supports int|name|"table.field". */
    function mysql_result($result, $row, $field = 0) {
        $result = __holo_res($result);
        if (!($result instanceof mysqli_result)) return false;
        if (!mysqli_data_seek($result, $row)) return false;
        if (is_numeric($field)) {
            $r = mysqli_fetch_row($result);
            if (!is_array($r)) return false;
            return isset($r[(int) $field]) ? $r[(int) $field] : false;
        }
        $name = (strpos((string) $field, '.') !== false)
            ? substr(strrchr((string) $field, '.'), 1)
            : $field;
        $r = mysqli_fetch_assoc($result);
        if (!is_array($r)) return false;
        return isset($r[$name]) ? $r[$name] : false;
    }

    function mysql_real_escape_string($str, $link = null) {
        $l = __holo_link($link);
        return $l ? mysqli_real_escape_string($l, (string) $str) : addslashes((string) $str);
    }

    function mysql_affected_rows($link = null) {
        $l = __holo_link($link);
        return $l ? mysqli_affected_rows($l) : -1;
    }

    function mysql_insert_id($link = null) {
        $l = __holo_link($link);
        return $l ? mysqli_insert_id($l) : 0;
    }

    function mysql_data_seek($result, $row) {
        $result = __holo_res($result);
        return ($result instanceof mysqli_result) ? mysqli_data_seek($result, $row) : false;
    }

    function mysql_free_result($result) {
        $result = __holo_res($result);
        if ($result instanceof mysqli_result) { mysqli_free_result($result); return true; }
        return false;
    }

    function mysql_close($link = null) {
        $l = __holo_link($link);
        if ($l) { @mysqli_close($l); $GLOBALS['__holo_mysqli'] = null; return true; }
        return false;
    }

    function mysql_set_charset($charset, $link = null) {
        $l = __holo_link($link);
        return $l ? mysqli_set_charset($l, $charset) : false;
    }
}

// ---------------------------------------------------------------------------
// 2. Bareword constants used as strings (FATAL since PHP 8.0)
// ---------------------------------------------------------------------------
// The legacy relies on the pre-PHP-8 behaviour where an undefined constant
// `FOO` evaluated to the string "FOO". We restore exactly that for the
// identifiers the code uses unquoted (as array keys / function arguments).
// If a page ever fatals with `Undefined constant "X"`, add X to this list.
foreach ([
    // session / housekeeping flags
    'username', 'acp', 'hkusername', 'hkpassword',
    // function name passed to function_exists()
    'SendMUSData',
    // array keys / concatenated barewords used unquoted
    'REMOTE_ADDR', 'badge', 'credits', 'date', 'name', 'num',
    'price', 'shortstory', 'tid', 'title', 'type', 'url', 'shortname',
] as $__holo_const) {
    if (!defined($__holo_const)) {
        define($__holo_const, $__holo_const);
    }
}
unset($__holo_const);

// ---------------------------------------------------------------------------
// 2b. Magic-quotes functions (removed in PHP 8.0). Magic quotes were disabled
//     by default since PHP 5.4, so returning "off" is the correct behaviour.
// ---------------------------------------------------------------------------
if (!function_exists('get_magic_quotes_gpc')) {
    function get_magic_quotes_gpc() { return 0; }
}
if (!function_exists('get_magic_quotes_runtime')) {
    function get_magic_quotes_runtime() { return 0; }
}
if (!function_exists('set_magic_quotes_runtime')) {
    function set_magic_quotes_runtime($v = false) { return false; }
}

// ---------------------------------------------------------------------------
// 3. session_register / session_is_registered / session_unregister
//    (removed in PHP 5.4)
// ---------------------------------------------------------------------------
if (!function_exists('session_is_registered')) {
    function session_is_registered($name) {
        return isset($_SESSION) && array_key_exists($name, $_SESSION);
    }
}
if (!function_exists('session_register')) {
    function session_register() {
        if (!isset($_SESSION)) { $_SESSION = array(); }
        foreach (func_get_args() as $name) {
            $_SESSION[$name] = isset($GLOBALS[$name]) ? $GLOBALS[$name] : null;
        }
        return true;
    }
}
if (!function_exists('session_unregister')) {
    function session_unregister($name) {
        unset($_SESSION[$name]);
        return true;
    }
}
