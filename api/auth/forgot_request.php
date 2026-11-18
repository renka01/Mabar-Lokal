<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/mailer.php';

requirePostMethod();
$input = getJsonInput();

$email = strtolower(trim((string) ($input['email'] ?? '')));

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonError('Masukkan alamat email yang valid.', 422);
}

try {
    $pdo = getDB();

    $userStmt = $pdo->prepare('SELECT id, name, email, is_active FROM users WHERE LOWER(email) = ? LIMIT 1');
    $userStmt->execute([$email]);
    $user = $userStmt->fetch();

    if (!$user || !filter_var($user['is_active'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
        jsonError('Alamat email tersebut belum terdaftar di MabarLokal.', 404);
    }

    $now = date('Y-m-d H:i:s');

    $recentStmt = $pdo->prepare(
        'SELECT created_at FROM password_resets
         WHERE LOWER(email) = ? AND used_at IS NULL
         ORDER BY id DESC LIMIT 1'
    );
    $recentStmt->execute([$email]);
    $recent = $recentStmt->fetch();

    if ($recent && !empty($recent['created_at'])) {
        $elapsed = time() - strtotime((string) $recent['created_at']);
        if ($elapsed >= 0 && $elapsed < 25) {
            $wait = 25 - $elapsed;
            jsonError("Mohon tunggu {$wait} detik sebelum meminta kode OTP baru.", 429, [
                'retry_after' => $wait,
            ]);
        }
    }

    $invalidateStmt = $pdo->prepare('UPDATE password_resets SET used_at = ? WHERE LOWER(email) = ? AND used_at IS NULL');
    $invalidateStmt->execute([$now, $email]);

    $otp       = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $otpHash   = password_hash($otp, PASSWORD_DEFAULT);
    $expiresAt = date('Y-m-d H:i:s', time() + 300);
    $insertStmt = $pdo->prepare(
        'INSERT INTO password_resets (email, otp_hash, expires_at, created_at)
         VALUES (?, ?, ?, ?)
         RETURNING id'
    );
    $insertStmt->execute([$email, $otpHash, $expiresAt, $now]);
    $resetRowId = (int) $insertStmt->fetchColumn();

    $mailResult = sendOtpEmail($email, (string) $user['name'], $otp);

    if ($mailResult['sent'] === true) {
        jsonSuccess("Kode OTP 6 digit telah dikirim ke email {$email}.", [
            'delivery'        => 'email',
            'expires_in'      => 300,
            'resend_cooldown' => 30,
        ]);
    }

    $isLocalEnv = strtolower((string) env('APP_ENV', 'local')) === 'local';
    if (($mailResult['reason'] ?? '') === 'smtp_not_configured' && $isLocalEnv) {
        jsonSuccess("SMTP Gmail belum diisi di .env — menggunakan OTP mode dev lokal untuk {$email}.", [
            'delivery'        => 'dev_fallback',
            'dev_otp'         => $otp,
            'expires_in'      => 300,
            'resend_cooldown' => 30,
        ]);
    }

    $pdo->prepare('DELETE FROM password_resets WHERE id = ?')->execute([$resetRowId]);
    jsonError('Gagal mengirim email OTP: ' . ($mailResult['error'] ?? 'Kesalahan konfigurasi SMTP Gmail.'), 500);
} catch (PDOException $e) {
    jsonError('Database PostgreSQL belum terhubung. Periksa service PostgreSQL, DB_PASS di .env, dan pastikan database/schema.sql sudah dijalankan di pgAdmin 4.', 500);
}

