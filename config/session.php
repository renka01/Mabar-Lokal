<?php
require_once __DIR__ . '/database.php';

if (session_status() === PHP_SESSION_NONE) {
    $forwardedProto = strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ''));
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || $forwardedProto === 'https';
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

if (!function_exists('resolveMediaUrl')) {
    function resolveMediaUrl(?string $path, string $basePath = ''): string
    {
        $clean = trim((string) $path);
        if ($clean === '') {
            return '';
        }
        if (preg_match('/^(?:https?:\/\/|data:|blob:|\/)/i', $clean)) {
            return $clean;
        }
        if ($basePath !== '' && str_starts_with($clean, $basePath)) {
            return $clean;
        }
        return $basePath . $clean;
    }
}

if (!function_exists('jsonResponse')) {
    function jsonResponse(array $payload, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
}

if (!function_exists('jsonSuccess')) {
    function jsonSuccess(string $message, array $data = [], int $status = 200): never
    {
        jsonResponse(array_merge([
            'ok'      => true,
            'message' => $message,
        ], $data), $status);
    }
}

if (!function_exists('jsonError')) {
    function jsonError(string $message, int $status = 400, array $extra = []): never
    {
        jsonResponse(array_merge([
            'ok'      => false,
            'message' => $message,
        ], $extra), $status);
    }
}

if (!function_exists('getJsonInput')) {
    function getJsonInput(): array
    {
        $raw = file_get_contents('php://input');
        if ($raw === false || trim($raw) === '') {
            return $_POST;
        }
        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : $_POST;
    }
}

if (!function_exists('requirePostMethod')) {
    function requirePostMethod(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            jsonError('Metode HTTP tidak diizinkan. Gunakan POST.', 405);
        }
    }
}

if (!function_exists('validateIndonesianPhone')) {
    function validateIndonesianPhone(string $phone): bool
    {
        $clean = preg_replace('/[\s\-\(\)]+/', '', trim($phone));
        return (bool) preg_match(
            '/^(?:\+62|62|0)8(1[1-9]|2[1-3]|3[1-38]|5[1-35-9]|7[78]|8[1-9]|9[5-9])[0-9]{6,9}$/',
            $clean
        );
    }
}

if (!function_exists('normalizeIndonesianPhone')) {
    function normalizeIndonesianPhone(string $phone): string
    {
        return preg_replace('/[\s\-\(\)]+/', '', trim($phone));
    }
}

if (!function_exists('generateUniqueUsernameTag')) {
    function generateUniqueUsernameTag(PDO $pdo, string $name, ?int $excludeUserId = null): string
    {
        $base = strtolower((string) preg_replace('/[^a-zA-Z0-9]+/', '', $name));
        if ($base === '') {
            $base = 'pemain';
        }
        $base = substr($base, 0, 24);
        $candidate = $base;
        $suffix = 1;

        while (true) {
            if ($excludeUserId !== null) {
                $stmt = $pdo->prepare('SELECT id FROM users WHERE username_tag = ? AND id <> ? LIMIT 1');
                $stmt->execute([$candidate, $excludeUserId]);
            } else {
                $stmt = $pdo->prepare('SELECT id FROM users WHERE username_tag = ? LIMIT 1');
                $stmt->execute([$candidate]);
            }
            if (!$stmt->fetch()) {
                return $candidate;
            }
            $suffix++;
            $candidate = $base . $suffix;
        }
    }
}

if (!function_exists('publicUserPayload')) {
    function publicUserPayload(array $user): array
    {
        $prefGames = [];
        if (!empty($user['pref_games_json'])) {
            $decoded = json_decode((string) $user['pref_games_json'], true);
            if (is_array($decoded)) {
                $prefGames = array_values(array_filter(array_map('strval', $decoded)));
            }
        }

        return [
            'id'                      => (int) $user['id'],
            'name'                    => (string) ($user['name'] ?? ''),
            'username_tag'            => (string) ($user['username_tag'] ?? ''),
            'email'                   => (string) ($user['email'] ?? ''),
            'phone'                   => (string) ($user['phone'] ?? ''),
            'city'                    => (string) ($user['city'] ?? ''),
            'bio'                     => (string) ($user['bio'] ?? ''),
            'avatar_path'             => (string) ($user['avatar_path'] ?? ''),
            'banner_path'             => (string) ($user['banner_path'] ?? ''),
            'pref_category'           => $user['pref_category'] ?? null,
            'pref_games'              => $prefGames,
            'questionnaire_completed' => (bool) ($user['questionnaire_completed'] ?? false),
        ];
    }
}

if (!function_exists('currentUserId')) {
    function currentUserId(): ?int
    {
        if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
            $id = (int) $_SESSION['user_id'];
            return $id > 0 ? $id : null;
        }
        return null;
    }
}

if (!function_exists('isGuestSession')) {
    function isGuestSession(): bool
    {
        return currentUserId() === null && !empty($_SESSION['is_guest']);
    }
}

if (!function_exists('currentUser')) {
    function currentUser(bool $forceRefresh = false): ?array
    {
        static $cachedUser = false;

        if (!$forceRefresh && $cachedUser !== false) {
            return $cachedUser;
        }

        $userId = currentUserId();
        if ($userId === null) {
            $cachedUser = null;
            return null;
        }

        $pdo = tryGetDB();
        if ($pdo === null) {
            $cachedUser = null;
            return null;
        }

        try {
            $stmt = $pdo->prepare(
                'SELECT id, name, username_tag, email, phone, city, bio, avatar_path, banner_path,
                        pref_category, pref_games_json, questionnaire_completed, is_active, created_at
                 FROM users
                 WHERE id = ? AND is_active = TRUE
                 LIMIT 1'
            );
            $stmt->execute([$userId]);
            $row = $stmt->fetch();

            if (!$row) {
                unset($_SESSION['user_id']);
                $cachedUser = null;
                return null;
            }

            $cachedUser = $row;
            return $row;
        } catch (Throwable $e) {
            $cachedUser = null;
            return null;
        }
    }
}

if (!function_exists('requireAuth')) {
    function requireAuth(): array
    {
        $user = currentUser(true);
        if (!$user) {
            jsonError('Sesi login Anda tidak aktif. Silakan masuk terlebih dahulu.', 401);
        }
        return $user;
    }
}

if (!function_exists('loginUserSession')) {
    function loginUserSession(array $user, bool $remember = false): void
    {
        session_regenerate_id(true);
        unset($_SESSION['is_guest']);
        $_SESSION['user_id'] = (int) $user['id'];

        if ($remember) {
            $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
            setcookie(session_name(), session_id(), [
                'expires'  => time() + (60 * 60 * 24 * 14),
                'path'     => '/',
                'domain'   => '',
                'secure'   => $isHttps,
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
        }
    }
}

if (!function_exists('setGuestSession')) {
    function setGuestSession(): void
    {
        session_regenerate_id(true);
        unset($_SESSION['user_id']);
        $_SESSION['is_guest'] = true;
    }
}

if (!function_exists('logoutUserSession')) {
    function logoutUserSession(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'] ?? '/',
                $params['domain'] ?? '',
                (bool) ($params['secure'] ?? false),
                (bool) ($params['httponly'] ?? true)
            );
        }
        session_destroy();
    }
}

