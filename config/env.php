<?php
if (!defined('MABAR_ENV_LOADED')) {
    define('MABAR_ENV_LOADED', true);
    date_default_timezone_set('Asia/Jakarta');

    $envFile = __DIR__ . '/../.env';
    if (is_file($envFile) && is_readable($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (is_array($lines)) {
            foreach ($lines as $line) {
                $trimmed = trim($line);
                if ($trimmed === '' || str_starts_with($trimmed, '#')) {
                    continue;
                }
                if (str_starts_with($trimmed, 'export ')) {
                    $trimmed = trim(substr($trimmed, 7));
                }
                $eqPos = strpos($trimmed, '=');
                if ($eqPos === false) {
                    continue;
                }
                $key = trim(substr($trimmed, 0, $eqPos));
                $val = trim(substr($trimmed, $eqPos + 1));
                if ($key === '') {
                    continue;
                }

                if (
                    (strlen($val) >= 2 && str_starts_with($val, '"') && str_ends_with($val, '"')) ||
                    (strlen($val) >= 2 && str_starts_with($val, "'") && str_ends_with($val, "'"))
                ) {
                    $val = substr($val, 1, -1);
                }

                $_ENV[$key] = $val;
                if (getenv($key) === false) {
                    putenv($key . '=' . $val);
                }
            }
        }
    }
}

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, $_ENV)) {
            return $_ENV[$key];
        }
        $val = getenv($key);
        return $val !== false ? $val : $default;
    }
}

