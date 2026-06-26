<?php

namespace App\Legacy;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Executes the legacy HoloCMS in-process, on the same PHP 8.3 runtime as Laravel.
 *
 * This is the "strangler" bridge: every route NOT yet rewritten as a native
 * Laravel route falls through here. Laravel stays the single front controller —
 * there is no second runtime and no HTTP proxy (see /docs/02-arquitectura-objetivo.md).
 *
 * The legacy lives under base_path(LEGACY_PATH) and relies on the pre-PHP-8 world,
 * which the compat-layer (legacy/_compat.php: mysql_* shim, bareword constants,
 * session/magic_quotes shims) restores. This class disappears in Fase 5, once the
 * last legacy route is migrated.
 */
class LegacyRunner
{
    public function __invoke(Request $request, ?string $path = null): Response
    {
        $root = rtrim(base_path(env('LEGACY_PATH', 'legacy')), '/');
        $target = $this->resolve($root, ltrim((string) $path, '/'));

        if ($target === null) {
            abort(404);
        }

        // Static assets are streamed directly; only .php is executed.
        if (! str_ends_with(strtolower($target), '.php')) {
            return $this->serveStatic($target);
        }

        return $this->runPhp($target, $root);
    }

    /** Map a URL path to a real file inside the legacy root, blocking traversal. */
    private function resolve(string $root, string $path): ?string
    {
        $candidate = $path === '' ? $root.'/index.php' : $root.'/'.$path;

        if (is_dir($candidate)) {
            $candidate = rtrim($candidate, '/').'/index.php';
        }

        $real = realpath($candidate);
        $realRoot = realpath($root);

        if ($real === false || $realRoot === false) {
            return null;
        }
        if ($real !== $realRoot && ! str_starts_with($real, $realRoot.DIRECTORY_SEPARATOR)) {
            return null; // outside the legacy root
        }

        return is_file($real) ? $real : null;
    }

    private function serveStatic(string $file): Response
    {
        $response = new BinaryFileResponse($file);
        $response->headers->set('Content-Type', $this->mimeFor($file));

        return $response;
    }

    /**
     * Replicate the mod_php environment the legacy assumes (CWD = script dir,
     * compat-layer loaded), run the script, and wrap whatever it produced.
     *
     * If the legacy calls exit/die (most redirects do), PHP flushes the output
     * buffer and headers on shutdown, so the response is still delivered — we
     * just never return a Response object, which is fine.
     */
    private function runPhp(string $file, string $root): Response
    {
        require_once $root.'/_compat.php';

        // Laravel forces error_reporting(-1) and turns every PHP warning/notice
        // into a fatal ErrorException. The 2008 legacy emits those constantly
        // (undefined variables/array keys), so restore its lenient reporting —
        // matching the original php.ini (error_reporting = E_ERROR) — for the
        // duration of the legacy script only.
        $previousLevel = error_reporting(E_ERROR);
        $previousCwd = getcwd();
        chdir(dirname($file));

        ob_start();
        try {
            (static function (string $legacyScript): void {
                require $legacyScript;
            })($file);
        } finally {
            $body = ob_get_clean();
            if ($previousCwd !== false) {
                chdir($previousCwd);
            }
            error_reporting($previousLevel);
        }

        $response = new Response($body, http_response_code() ?: 200);

        // The legacy emits latin1 bytes and rarely sets Content-Type explicitly;
        // PHP's implicit default (default_charset) is not visible via headers_list()
        // while output is buffered, so default it here. A legacy header() — e.g.
        // an XML/JSON endpoint — overrides this in the copy loop below.
        $charset = ini_get('default_charset') ?: 'ISO-8859-1';
        $response->headers->set('Content-Type', 'text/html; charset='.$charset);

        // Carry over headers/cookies the legacy set (explicit Content-Type,
        // Location redirects, Set-Cookie for PHPSESSID / remember-me, X-JSON…).
        foreach (headers_list() as $header) {
            $pos = strpos($header, ':');
            if ($pos === false) {
                continue;
            }
            $name = trim(substr($header, 0, $pos));
            if (strcasecmp($name, 'Content-Length') === 0) {
                continue;
            }
            // Set-Cookie may legitimately repeat; everything else replaces.
            $replace = strcasecmp($name, 'Set-Cookie') !== 0;
            $response->headers->set($name, trim(substr($header, $pos + 1)), $replace);
        }

        // Prevent PHP from emitting those same headers a second time.
        if (! headers_sent()) {
            header_remove();
        }

        return $response;
    }

    private function mimeFor(string $file): string
    {
        static $map = [
            'css' => 'text/css', 'js' => 'application/javascript', 'mjs' => 'application/javascript',
            'json' => 'application/json', 'map' => 'application/json',
            'gif' => 'image/gif', 'png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg',
            'svg' => 'image/svg+xml', 'ico' => 'image/x-icon', 'bmp' => 'image/bmp',
            'xml' => 'application/xml', 'txt' => 'text/plain', 'htm' => 'text/html', 'html' => 'text/html',
            'htc' => 'text/x-component', 'swf' => 'application/x-shockwave-flash', 'wasm' => 'application/wasm',
            'dcr' => 'application/x-director', 'cct' => 'application/x-director', 'cst' => 'application/x-director',
            'ttf' => 'font/ttf', 'woff' => 'font/woff', 'woff2' => 'font/woff2', 'eot' => 'application/vnd.ms-fontobject',
        ];

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        return $map[$ext] ?? 'application/octet-stream';
    }
}
