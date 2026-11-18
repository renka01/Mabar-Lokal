<?php
require_once __DIR__ . '/../../config/session.php';

requirePostMethod();
$input = getJsonInput();

$email    = strtolower(trim((string) ($input['email'] ?? '')));
$password = (string) ($input['password'] ?? '');
$remember = !empty($input['remember']);

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonError('Masukkan alamat email yang valid.', 422);
}

if ($password === '') {
    jsonError('Masukkan kata sandi Anda.', 422);
}

try {
    $pdo = getDB();
    $stmt = $pdo->prepare(
        'SELECT id, name, username_tag, email, password_hash, phone, city, bio, avatar_path, banner_path,
                pref_category, pref_games_json, questionnaire_completed, is_active
         FROM users
         WHERE LOWER(email) = ?
         LIMIT 1'
    );
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, (string) $user['password_hash'])) {
        jsonError('Email atau kata sandi salah.', 401);
    }

    if (!filter_var($user['is_active'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
        jsonError('Akun ini sedang dinonaktifkan.', 403);
    }

    loginUserSession($user, $remember);

    jsonSuccess('Berhasil masuk! Mengalihkan ke Beranda...', [
        'user'     => publicUserPayload($user),
        'redirect' => '../index.php',
    ]);
} catch (PDOException $e) {
    jsonError('Database PostgreSQL belum terhubung. Periksa service PostgreSQL, DB_PASS di .env, dan pastikan database/schema.sql sudah dijalankan di pgAdmin 4.', 500);
}

