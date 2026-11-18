<?php
require_once __DIR__ . '/env.php';

if (!function_exists('storageIsSupabaseEnabled')) {
    function storageIsSupabaseEnabled(): bool
    {
        return trim((string) env('SUPABASE_URL', '')) !== ''
            && trim((string) env('SUPABASE_SERVICE_KEY', '')) !== '';
    }
}

if (!function_exists('storageSupabaseConfig')) {
    function storageSupabaseConfig(): array
    {
        return [
            'url'    => rtrim(trim((string) env('SUPABASE_URL', '')), '/'),
            'key'    => trim((string) env('SUPABASE_SERVICE_KEY', '')),
            'bucket' => trim((string) env('SUPABASE_STORAGE_BUCKET', 'mabar-media')) ?: 'mabar-media',
        ];
    }
}

if (!function_exists('storageUploadImage')) {
    function storageUploadImage(string $tmpPath, string $subFolder, string $filename, string $mime): array
    {
        $subFolder = trim($subFolder, '/\\');

        if (storageIsSupabaseEnabled()) {
            if (!function_exists('curl_init')) {
                return ['ok' => false, 'error' => 'Ekstensi PHP cURL dibutuhkan untuk upload ke Supabase Storage.'];
            }

            $cfg        = storageSupabaseConfig();
            $objectPath = "{$subFolder}/{$filename}";
            $endpoint   = "{$cfg['url']}/storage/v1/object/{$cfg['bucket']}/{$objectPath}";
            $bytes      = @file_get_contents($tmpPath);

            if ($bytes === false) {
                return ['ok' => false, 'error' => 'Gagal membaca file gambar sementara.'];
            }

            $ch = curl_init($endpoint);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST  => 'POST',
                CURLOPT_POSTFIELDS     => $bytes,
                CURLOPT_TIMEOUT        => 20,
                CURLOPT_HTTPHEADER     => [
                    'Authorization: Bearer ' . $cfg['key'],
                    'apikey: ' . $cfg['key'],
                    'Content-Type: ' . $mime,
                    'x-upsert: true',
                ],
            ]);
            $response = curl_exec($ch);
            $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlErr  = curl_error($ch);
            curl_close($ch);

            if ($curlErr !== '' || $httpCode < 200 || $httpCode >= 300) {
                return ['ok' => false, 'error' => 'Gagal mengunggah gambar ke Supabase Storage.'];
            }

            $publicUrl = "{$cfg['url']}/storage/v1/object/public/{$cfg['bucket']}/{$objectPath}";
            return ['ok' => true, 'path' => $publicUrl];
        }

        $projectDir = realpath(__DIR__ . '/..');
        if ($projectDir === false) {
            return ['ok' => false, 'error' => 'Direktori proyek tidak ditemukan.'];
        }

        $targetDir = $projectDir . DIRECTORY_SEPARATOR . 'asset' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $subFolder;
        if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
            return ['ok' => false, 'error' => 'Gagal membuat direktori penyimpanan upload.'];
        }

        $destFullPath = $targetDir . DIRECTORY_SEPARATOR . $filename;
        if (!move_uploaded_file($tmpPath, $destFullPath)) {
            return ['ok' => false, 'error' => 'Gagal menyimpan file gambar ke server.'];
        }

        return ['ok' => true, 'path' => "asset/uploads/{$subFolder}/{$filename}"];
    }
}

if (!function_exists('storageDeleteMedia')) {
    function storageDeleteMedia(string $oldPath, string $allowedSubFolder): void
    {
        $oldPath          = trim($oldPath);
        $allowedSubFolder = trim($allowedSubFolder, '/\\');
        if ($oldPath === '') {
            return;
        }

        $localPrefix = "asset/uploads/{$allowedSubFolder}/";
        if (str_starts_with($oldPath, $localPrefix)) {
            $projectDir = realpath(__DIR__ . '/..');
            if ($projectDir !== false) {
                $oldFullPath = $projectDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $oldPath);
                if (is_file($oldFullPath)) {
                    @unlink($oldFullPath);
                }
            }
            return;
        }

        if (storageIsSupabaseEnabled() && function_exists('curl_init')) {
            $cfg          = storageSupabaseConfig();
            $publicPrefix = "{$cfg['url']}/storage/v1/object/public/{$cfg['bucket']}/";
            if (str_starts_with($oldPath, $publicPrefix)) {
                $objectPath = substr($oldPath, strlen($publicPrefix));
                if (str_starts_with($objectPath, "{$allowedSubFolder}/")) {
                    $endpoint = "{$cfg['url']}/storage/v1/object/{$cfg['bucket']}/{$objectPath}";
                    $ch = curl_init($endpoint);
                    curl_setopt_array($ch, [
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST  => 'DELETE',
                        CURLOPT_TIMEOUT        => 10,
                        CURLOPT_HTTPHEADER     => [
                            'Authorization: Bearer ' . $cfg['key'],
                            'apikey: ' . $cfg['key'],
                        ],
                    ]);
                    curl_exec($ch);
                    curl_close($ch);
                }
            }
        }
    }
}
