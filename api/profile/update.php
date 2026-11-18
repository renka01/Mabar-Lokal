<?php
require_once __DIR__ . '/../../config/session.php';

requirePostMethod();
$user  = requireAuth();
$input = getJsonInput();

$name  = trim((string) ($input['name'] ?? ''));
$email = strtolower(trim((string) ($input['email'] ?? '')));
$city  = trim((string) ($input['city'] ?? ''));
$phone = trim((string) ($input['phone'] ?? ''));
$bio   = trim((string) ($input['bio'] ?? ''));

if ($name === '' || mb_strlen($name) < 2) {
    jsonError('Nama profil minimal 2 karakter.', 422);
}

if (mb_strlen($name) > 100) {
    jsonError('Nama profil maksimal 100 karakter.', 422);
}

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonError('Masukkan alamat email yang valid.', 422);
}

if ($city === '') {
    $city = (string) ($user['city'] ?: 'Kota Malang');
}

if ($phone !== '' && !validateIndonesianPhone($phone)) {
    jsonError('Nomor WhatsApp/HP tidak valid. Gunakan format seluler Indonesia aktif (contoh: 08123456789).', 422);
}

$cleanPhone = $phone !== '' ? normalizeIndonesianPhone($phone) : null;

try {
    $pdo = getDB();

    if ($email !== strtolower((string) $user['email'])) {
        $dupStmt = $pdo->prepare('SELECT id FROM users WHERE LOWER(email) = ? AND id <> ? LIMIT 1');
        $dupStmt->execute([$email, (int) $user['id']]);
        if ($dupStmt->fetch()) {
            jsonError('Alamat email tersebut sudah digunakan oleh akun lain.', 409);
        }
    }

    $usernameTag = (string) $user['username_tag'];
    if ($name !== (string) $user['name']) {
        $usernameTag = generateUniqueUsernameTag($pdo, $name, (int) $user['id']);
    }

    $stmt = $pdo->prepare(
        'UPDATE users
         SET name = ?, username_tag = ?, email = ?, city = ?, phone = ?, bio = ?
         WHERE id = ?'
    );
    $stmt->execute([
        $name,
        $usernameTag,
        $email,
        $city,
        $cleanPhone,
        $bio,
        (int) $user['id'],
    ]);

    $updatedUser = currentUser(true);

    jsonSuccess('Perubahan profil berhasil disimpan!', [
        'user' => $updatedUser ? publicUserPayload($updatedUser) : null,
    ]);
} catch (PDOException $e) {
    jsonError('Gagal memperbarui profil di database.', 500);
}

