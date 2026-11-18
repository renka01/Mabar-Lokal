<?php
require_once __DIR__ . '/../../config/session.php';

requirePostMethod();
$input = getJsonInput();

$email = strtolower(trim((string) ($input['email'] ?? '')));
$otp   = preg_replace('/\D+/', '', (string) ($input['otp'] ?? ''));

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonError('Alamat email tidak valid.', 422);
}

if (strlen($otp) !== 6) {
    jsonError('Masukkan tepat 6 digit kode OTP.', 422);
}

try {
    $pdo = getDB();

    $stmt = $pdo->prepare(
        'SELECT id, otp_hash, attempts, expires_at
         FROM password_resets
         WHERE LOWER(email) = ? AND used_at IS NULL
         ORDER BY id DESC
         LIMIT 1'
    );
    $stmt->execute([$email]);
    $row = $stmt->fetch();

    if (!$row) {
        jsonError('Tidak ada permintaan kode OTP aktif. Silakan kirim ulang kode.', 404);
    }

    $now = date('Y-m-d H:i:s');
    if (strtotime((string) $row['expires_at']) < time()) {
        jsonError('Kode OTP sudah kedaluwarsa. Silakan kirim ulang kode baru.', 410);
    }

    $attempts = (int) ($row['attempts'] ?? 0);
    if ($attempts >= 5) {
        jsonError('Batas percobaan OTP terlampaui. Silakan kirim ulang kode baru.', 429);
    }

    if (!password_verify($otp, (string) $row['otp_hash'])) {
        $nextAttempts = $attempts + 1;
        $pdo->prepare('UPDATE password_resets SET attempts = ? WHERE id = ?')
            ->execute([$nextAttempts, (int) $row['id']]);

        $sisa = max(0, 5 - $nextAttempts);
        jsonError("Kode OTP tidak sesuai. Sisa percobaan: {$sisa}.", 422);
    }

    $resetToken     = bin2hex(random_bytes(24));
    $resetTokenHash = password_hash($resetToken, PASSWORD_DEFAULT);

    $pdo->prepare(
        'UPDATE password_resets SET reset_token_hash = ?, verified_at = ? WHERE id = ?'
    )->execute([$resetTokenHash, $now, (int) $row['id']]);

    jsonSuccess('Kode OTP terverifikasi! Silakan buat kata sandi baru.', [
        'reset_token' => $resetToken,
    ]);
} catch (PDOException $e) {
    jsonError('Terjadi kesalahan koneksi database.', 500);
}

