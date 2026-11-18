<?php
require_once __DIR__ . '/env.php';

if (!function_exists('getDB')) {
    function getDB(): PDO
    {
        static $pdo = null;
        if ($pdo instanceof PDO) {
            return $pdo;
        }

        $driver  = strtolower(trim((string) env('DB_DRIVER', 'pgsql')));
        $host    = trim((string) env('DB_HOST', ''));
        $port    = trim((string) env('DB_PORT', ''));
        $name    = trim((string) env('DB_NAME', ''));
        $user    = trim((string) env('DB_USER', ''));
        $pass    = (string) env('DB_PASS', '');
        $sslmode = trim((string) env('DB_SSLMODE', ''));

        $databaseUrl = trim((string) env('DATABASE_URL', ''));
        if ($host === '' && $databaseUrl !== '') {
            $parsed = parse_url($databaseUrl);
            if (is_array($parsed)) {
                $scheme = strtolower((string) ($parsed['scheme'] ?? 'pgsql'));
                if ($scheme === 'postgres' || $scheme === 'postgresql') {
                    $driver = 'pgsql';
                } elseif ($scheme === 'mysql') {
                    $driver = 'mysql';
                }
                $host = (string) ($parsed['host'] ?? '127.0.0.1');
                $port = isset($parsed['port']) ? (string) $parsed['port'] : ($driver === 'mysql' ? '3306' : '5432');
                $name = ltrim((string) ($parsed['path'] ?? '/postgres'), '/');
                $user = isset($parsed['user']) ? rawurldecode((string) $parsed['user']) : 'postgres';
                $pass = isset($parsed['pass']) ? rawurldecode((string) $parsed['pass']) : '';
                if (!empty($parsed['query'])) {
                    parse_str((string) $parsed['query'], $queryParams);
                    if ($sslmode === '' && !empty($queryParams['sslmode'])) {
                        $sslmode = trim((string) $queryParams['sslmode']);
                    }
                }
            }
        }

        if ($host === '') {
            $host = '127.0.0.1';
        }
        if ($port === '') {
            $port = $driver === 'mysql' ? '3306' : '5432';
        }
        if ($name === '') {
            $name = 'mabar_lokal';
        }
        if ($user === '') {
            $user = $driver === 'mysql' ? 'root' : 'postgres';
        }

        if ($driver === 'mysql') {
            $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $name);
        } else {
            $dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s', $host, $port, $name);
            if ($sslmode !== '') {
                $dsn .= ';sslmode=' . $sslmode;
            }
        }

        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);

        if ($driver === 'mysql') {
            $pdo->exec("SET time_zone = '+07:00'");
        } else {
            $pdo->exec("SET TIME ZONE 'Asia/Jakarta'");
        }

        return $pdo;
    }
}

if (!function_exists('tryGetDB')) {
    function tryGetDB(): ?PDO
    {
        try {
            return getDB();
        } catch (Throwable $e) {
            return null;
        }
    }
}
