<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/storage.php';

requirePostMethod();
$user = requireAuth();

$type = strtolower(trim((string) ($_POST['type'] ?? '')));
if (!in_array($type, ['avatar', 'banner'], true)) {
    jsonError('Tipe media harus avatar atau banner.', 422);
}

if (!isset($_FILES['file']) || !is_array($_FILES['file'])) {
    jsonError('File gambar tidak ditemukan pada permintaan.', 422);
}

$file = $_FILES['file'];
if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    jsonError('Upload file gagal. Periksa ukuran atau file yang dipilih.', 400);
}

$maxBytes = 5 * 1024 * 1024;
if (($file['size'] ?? 0) <= 0 || $file['size'] > $maxBytes) {
    jsonError('Ukuran gambar terlalu besar. Maksimal 5MB.', 422);
}

$tmpPath = (string) ($file['tmp_name'] ?? '');
if ($tmpPath === '' || !is_uploaded_file($tmpPath)) {
    jsonError('File upload tidak valid.', 422);
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime  = (string) $finfo->file($tmpPath);
$extMap = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
    'image/gif'  => 'gif',
];

if (!isset($extMap[$mime])) {
    jsonError('Format gambar harus JPG, PNG, WEBP, atau GIF.', 422);
}

$ext       = $extMap[$mime];
$subFolder = $type === 'avatar' ? 'avatars' : 'banners';
$filename  = sprintf('%s_%d_%s.%s', $type, (int) $user['id'], bin2hex(random_bytes(8)), $ext);

$uploaded = storageUploadImage($tmpPath, $subFolder, $filename, $mime);
if (!$uploaded['ok']) {
    jsonError($uploaded['error'] ?? 'Gagal menyimpan file gambar ke server.', 500);
}
$savedPath = (string) ($uploaded['path'] ?? '');

$column  = $type === 'avatar' ? 'avatar_path' : 'banner_path';
$oldPath = (string) ($user[$column] ?? '');

try {
    $pdo = getDB();
    $stmt = $pdo->prepare("UPDATE users SET {$column} = ? WHERE id = ?");
    $stmt->execute([$savedPath, (int) $user['id']]);

    if ($oldPath !== '' && $oldPath !== $savedPath) {
        storageDeleteMedia($oldPath, $subFolder);
    }

    $updatedUser = currentUser(true);
    $label = $type === 'avatar' ? 'Foto profil' : 'Foto banner';

    jsonSuccess("{$label} berhasil diperbarui!", [
        'type' => $type,
        'path' => $savedPath,
        'user' => $updatedUser ? publicUserPayload($updatedUser) : null,
    ]);
} catch (PDOException $e) {
    storageDeleteMedia($savedPath, $subFolder);
    jsonError('Gagal memperbarui path media di database.', 500);
}

