<?php
require_once __DIR__ . '/../../config/session.php';

requirePostMethod();
$input = getJsonInput();

$name            = trim((string) ($input['name'] ?? ''));
$email           = strtolower(trim((string) ($input['email'] ?? '')));
$phoneRaw        = trim((string) ($input['phone'] ?? ''));
$city            = trim((string) ($input['city'] ?? ''));
$password        = (string) ($input['password'] ?? '');
$confirmPassword = isset($input['confirm_password']) ? (string) $input['confirm_password'] : $password;
$agree           = array_key_exists('agree', $input) ? (bool) $input['agree'] : true;

if ($name === '' || mb_strlen($name) < 2) {
    jsonError('Nama lengkap minimal 2 karakter.', 422);
}

if (mb_strlen($name) > 100) {
    jsonError('Nama lengkap maksimal 100 karakter.', 422);
}

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonError('Masukkan alamat email yang valid.', 422);
}

if (!validateIndonesianPhone($phoneRaw)) {
    jsonError('Nomor WhatsApp tidak valid. Gunakan nomor seluler Indonesia aktif (contoh: 08123456789).', 422);
}

if ($city === '') {
    jsonError('Silakan pilih kota domisili Anda.', 422);
}

if (strlen($password) < 6) {
    jsonError('Kata sandi minimal harus 6 karakter.', 422);
}

if ($password !== $confirmPassword) {
    jsonError('Kata sandi dan konfirmasi kata sandi tidak cocok.', 422);
}

if (!$agree) {
    jsonError('Anda harus menyetujui Syarat & Ketentuan terlebih dahulu.', 422);
}

$phone = normalizeIndonesianPhone($phoneRaw);

try {
    $pdo = getDB();

    $checkStmt = $pdo->prepare('SELECT id FROM users WHERE LOWER(email) = ? LIMIT 1');
    $checkStmt->execute([$email]);
    if ($checkStmt->fetch()) {
        jsonError('Alamat email tersebut sudah terdaftar. Silakan masuk atau gunakan email lain.', 409);
    }

    $usernameTag  = generateUniqueUsernameTag($pdo, $name);
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $defaultBio   = 'Main santai, komunikasi aktif, wajib no toxic & siap koordinasi tim!';

    $insertStmt = $pdo->prepare(
        'INSERT INTO users (name, username_tag, email, password_hash, phone, city, bio, questionnaire_completed)
         VALUES (?, ?, ?, ?, ?, ?, ?, FALSE)
         RETURNING id'
    );
    $insertStmt->execute([
        $name,
        $usernameTag,
        $email,
        $passwordHash,
        $phone,
        $city,
        $defaultBio,
    ]);

    $newId = (int) $insertStmt->fetchColumn();
    $userStmt = $pdo->prepare(
        'SELECT id, name, username_tag, email, phone, city, bio, avatar_path, banner_path,
                pref_category, pref_games_json, questionnaire_completed, is_active
         FROM users WHERE id = ? LIMIT 1'
    );
    $userStmt->execute([$newId]);
    $newUser = $userStmt->fetch();

    if ($newUser) {
        loginUserSession($newUser, true);
    }

    jsonSuccess('Pendaftaran berhasil! Mengatur preferensi game & olahraga...', [
        'user'     => $newUser ? publicUserPayload($newUser) : null,
        'redirect' => 'kuesioner.php',
    ], 201);
} catch (PDOException $e) {
    jsonError('Database PostgreSQL belum terhubung. Periksa service PostgreSQL, DB_PASS di .env, dan pastikan database/schema.sql sudah dijalankan di pgAdmin 4.', 500);
}

