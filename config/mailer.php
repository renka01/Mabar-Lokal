<?php
require_once __DIR__ . '/env.php';

$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (is_file($autoloadPath)) {
    require_once $autoloadPath;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

if (!function_exists('isMailerConfigured')) {
    function isMailerConfigured(): bool
    {
        if (trim((string) env('BREVO_API_KEY', '')) !== '') {
            return true;
        }

        $username = trim((string) env('MAIL_USERNAME', ''));
        $password = str_replace(' ', '', (string) env('MAIL_PASSWORD', ''));

        return class_exists(PHPMailer::class) && $username !== '' && $password !== '';
    }
}

if (!function_exists('otpEmailHtml')) {
    function otpEmailHtml(string $safeName, string $safeOtp): string
    {
        return <<<HTML
<!doctype html>
<html lang="id">
<body style="margin:0;padding:24px;background-color:#080f18;font-family:Arial,Helvetica,sans-serif;color:#ffffff;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:520px;margin:0 auto;background-color:#0d1622;border:1px solid #232d39;border-radius:12px;overflow:hidden;">
    <tr>
      <td style="padding:22px 24px;border-bottom:1px solid #232d39;background-color:#0b121c;">
        <span style="font-size:18px;font-weight:800;letter-spacing:0.5px;color:#ffffff;">
          MABAR<span style="color:#ef233c;">LOKAL</span>
        </span>
      </td>
    </tr>
    <tr>
      <td style="padding:26px 24px;">
        <h2 style="margin:0 0 10px 0;font-size:18px;color:#ffffff;">Permintaan Reset Kata Sandi</h2>
        <p style="margin:0 0 16px 0;font-size:13px;line-height:1.6;color:#9ba5b4;">
          Halo <strong style="color:#ffffff;">{$safeName}</strong>, kami menerima permintaan untuk mengatur ulang kata sandi akun MabarLokal kamu.
        </p>
        <div style="margin:20px 0;padding:18px;border-radius:10px;background-color:#080f18;border:1px dashed rgba(239,35,60,0.45);text-align:center;">
          <div style="font-size:11px;text-transform:uppercase;letter-spacing:1.4px;color:#9ba5b4;margin-bottom:8px;">
            Kode Verifikasi OTP
          </div>
          <div style="font-size:30px;font-weight:800;letter-spacing:8px;color:#ef233c;font-family:'Courier New',monospace;">
            {$safeOtp}
          </div>
        </div>
        <p style="margin:0 0 12px 0;font-size:12px;line-height:1.6;color:#9ba5b4;">
          Kode OTP ini hanya berlaku selama <strong style="color:#ffffff;">5 menit</strong>. Jangan bagikan kode ini kepada siapa pun.
        </p>
        <p style="margin:0;font-size:11px;line-height:1.5;color:#6c7684;">
          Jika kamu tidak meminta reset kata sandi, cukup abaikan email ini.
        </p>
      </td>
    </tr>
  </table>
</body>
</html>
HTML;
    }
}

if (!function_exists('otpEmailText')) {
    function otpEmailText(string $toName, string $otp): string
    {
        return "Halo {$toName},\n\n"
            . "Kode OTP reset kata sandi MabarLokal kamu adalah: {$otp}\n"
            . "Kode berlaku selama 5 menit. Jangan bagikan kode ini kepada siapa pun.\n\n"
            . "Salam,\nTim MabarLokal";
    }
}

if (!function_exists('sendOtpViaBrevo')) {
    function sendOtpViaBrevo(string $apiKey, string $fromEmail, string $fromName, string $toEmail, string $toName, string $subject, string $html, string $text): array
    {
        if (!function_exists('curl_init')) {
            return ['sent' => false, 'reason' => 'smtp_error', 'error' => 'Ekstensi PHP cURL dibutuhkan untuk kirim email via Brevo.'];
        }

        $payload = json_encode([
            'sender'      => ['name' => $fromName, 'email' => $fromEmail],
            'to'          => [['email' => $toEmail, 'name' => $toName !== '' ? $toName : $toEmail]],
            'subject'     => $subject,
            'htmlContent' => $html,
            'textContent' => $text,
        ], JSON_UNESCAPED_UNICODE);

        $ch = curl_init('https://api.brevo.com/v3/smtp/email');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_HTTPHEADER     => [
                'api-key: ' . $apiKey,
                'content-type: application/json',
                'accept: application/json',
            ],
        ]);
        $response = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr  = curl_error($ch);

        if ($curlErr !== '') {
            return ['sent' => false, 'reason' => 'smtp_error', 'error' => 'Koneksi ke Brevo gagal: ' . $curlErr];
        }
        if ($httpCode < 200 || $httpCode >= 300) {
            $decoded = json_decode((string) $response, true);
            $message = is_array($decoded) ? (string) ($decoded['message'] ?? '') : '';
            return [
                'sent'   => false,
                'reason' => 'smtp_error',
                'error'  => 'Brevo menolak pengiriman (HTTP ' . $httpCode . '): ' . ($message !== '' ? $message : 'respons tidak dikenal'),
            ];
        }

        return ['sent' => true];
    }
}

if (!function_exists('sendOtpEmail')) {
    function sendOtpEmail(string $toEmail, string $toName, string $otp): array
    {
        $username  = trim((string) env('MAIL_USERNAME', ''));
        $password  = str_replace(' ', '', (string) env('MAIL_PASSWORD', ''));
        $host      = trim((string) env('MAIL_HOST', 'smtp.gmail.com'));
        $port      = (int) env('MAIL_PORT', 587);
        $fromEmail = trim((string) env('MAIL_FROM_EMAIL', '')) ?: $username;
        $fromName  = trim((string) env('MAIL_FROM_NAME', 'MabarLokal Support'));
        $brevoKey  = trim((string) env('BREVO_API_KEY', ''));

        $safeName = htmlspecialchars($toName !== '' ? $toName : 'Pemain MabarLokal', ENT_QUOTES, 'UTF-8');
        $safeOtp  = htmlspecialchars($otp, ENT_QUOTES, 'UTF-8');
        $subject  = "Kode OTP Reset Kata Sandi MabarLokal: {$otp}";
        $html     = otpEmailHtml($safeName, $safeOtp);
        $text     = otpEmailText($toName !== '' ? $toName : 'Pemain MabarLokal', $otp);

        // Render memblokir port SMTP keluar; Brevo lewat HTTPS 443.
        if ($brevoKey !== '') {
            if ($fromEmail === '') {
                return ['sent' => false, 'reason' => 'smtp_not_configured', 'error' => 'MAIL_FROM_EMAIL belum diisi; harus sama dengan sender terverifikasi di Brevo.'];
            }
            return sendOtpViaBrevo($brevoKey, $fromEmail, $fromName, $toEmail, $toName, $subject, $html, $text);
        }

        if (!class_exists(PHPMailer::class)) {
            return [
                'sent'   => false,
                'reason' => 'library_missing',
                'error'  => 'Library PHPMailer belum terpasang (jalankan composer install).',
            ];
        }

        if ($username === '' || $password === '') {
            return [
                'sent'   => false,
                'reason' => 'smtp_not_configured',
                'error'  => 'Kredensial Gmail SMTP (MAIL_USERNAME / MAIL_PASSWORD) belum diisi di .env.',
            ];
        }

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = $host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $username;
            $mail->Password   = $password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $port;
            $mail->CharSet    = 'UTF-8';
            $mail->Timeout    = 15;

            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($toEmail, $toName !== '' ? $toName : $toEmail);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $html;
            $mail->AltBody = $text;

            $mail->send();
            return ['sent' => true];
        } catch (MailException $e) {
            return [
                'sent'   => false,
                'reason' => 'smtp_error',
                'error'  => $mail->ErrorInfo !== '' ? $mail->ErrorInfo : $e->getMessage(),
            ];
        } catch (Throwable $e) {
            return [
                'sent'   => false,
                'reason' => 'smtp_error',
                'error'  => $e->getMessage(),
            ];
        }
    }
}