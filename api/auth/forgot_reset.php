<?php
require_once __DIR__ . '/../../config/session.php';

requirePostMethod();
$input = getJsonInput();

$email       = strtolower(trim((string) ($input['email'] ?? '')));
$resetToken  = trim((string) ($input['reset_token'] ?? ''));
$newPassword = (string) ($input['new_password'] ?? '');

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonError('Alamat email tidak valid.', 422);
}

if ($resetToken === '') {
    jsonError('Token verifikasi tidak ditemukan. Ulangi proses verifikasi OTP.', 422);
}

if (strlen($newPassword) < 6) {
    jsonError('Kata sandi baru minimal harus 6 karakter.', 422);
}

try {
    $pdo = getDB();

    $stmt = $pdo->prepare(
        'SELECT id, reset_token_hash, verified_at, expires_at
         FROM password_resets
         WHERE LOWER(email) = ? AND used_at IS NULL AND verified_at IS NOT NULL
         ORDER BY id DESC
         LIMIT 1'
    );
    $stmt->execute([$email]);
    $row = $stmt->fetch();

    if (!$row || empty($row['reset_token_hash'])) {
        jsonError('Sesi verifikasi OTP tidak ditemukan. Silakan verifikasi ulang.', 403);
    }

    $verifiedAtTs = strtotime((string) $row['verified_at']);
    if ($verifiedAtTs === false || (time() - $verifiedAtTs) > 600) {
        jsonError('Waktu sesi reset kata sandi telah habis. Silakan minta kode OTP baru.', 410);
    }

    if (!password_verify($resetToken, (string) $row['reset_token_hash'])) {
        jsonError('Token reset tidak valid.', 403);
    }

    $userStmt = $pdo->prepare('SELECT id FROM users WHERE LOWER(email) = ? LIMIT 1');
    $userStmt->execute([$email]);
    $user = $userStmt->fetch();
    if (!$user) {
        jsonError('Akun pengguna tidak ditemukan.', 404);
    }

    $now             = date('Y-m-d H:i:s');
    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

    $pdo->beginTransaction();
    $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?')
        ->execute([$newPasswordHash, (int) $user['id']]);
    $pdo->prepare('UPDATE password_resets SET used_at = ? WHERE LOWER(email) = ? AND used_at IS NULL')
        ->execute([$now, $email]);
    $pdo->commit();

    jsonSuccess('Kata sandi berhasil diperbarui! Silakan masuk dengan sandi baru.');
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    jsonError('Gagal menyimpan kata sandi baru.', 500);
}

