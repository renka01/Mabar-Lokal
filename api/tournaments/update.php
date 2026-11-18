<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/turnamen_helpers.php';

requirePostMethod();
$user  = requireAuth();
$input = !empty($_POST) ? $_POST : getJsonInput();

$trnKey = trim((string) ($input['tournament_id'] ?? $input['slug'] ?? ''));
if ($trnKey === '') {
    jsonError('ID turnamen tidak valid.', 422);
}

try {
    $pdo = getDB();
    $trn = turnamenFindByKey($pdo, $trnKey);
    if (!$trn) {
        jsonError('Turnamen tidak ditemukan.', 404);
    }

    if ($trn['creator_user_id'] === null || (int) $trn['creator_user_id'] !== (int) $user['id']) {
        jsonError('Hanya penyelenggara (Host) yang dapat mengubah pengaturan turnamen.', 403);
    }

    if (array_key_exists('bracket', $input) && !isset($input['title']) && !isset($_FILES['poster'])) {
        $bracketJson = is_string($input['bracket'])
            ? $input['bracket']
            : json_encode($input['bracket'], JSON_UNESCAPED_UNICODE);
        $pdo->prepare('UPDATE tournaments SET bracket_json = ? WHERE id = ?')
            ->execute([$bracketJson, (int) $trn['db_id']]);

        jsonSuccess('Perubahan bagan turnamen berhasil disimpan!', [
            'slug' => $trn['slug'],
        ]);
    }

    if (isset($_FILES['poster']) && is_array($_FILES['poster']) && !isset($input['title'])) {
        $upload = turnamenSaveUploadedPoster($_FILES['poster'], (int) $user['id'], (string) $trn['poster_path']);
        if (!$upload['ok']) {
            jsonError($upload['error'] ?? 'Gagal mengunggah poster.', 422);
        }
        $newPath = (string) ($upload['path'] ?? '');
        $pdo->prepare('UPDATE tournaments SET poster_path = ? WHERE id = ?')
            ->execute([$newPath !== '' ? $newPath : null, (int) $trn['db_id']]);

        jsonSuccess('Poster turnamen berhasil diperbarui!', [
            'poster_path' => $newPath,
            'slug'        => $trn['slug'],
        ]);
    }

    $title       = trim((string) ($input['title'] ?? $trn['judul']));
    $schedule    = trim((string) ($input['schedule_text'] ?? $trn['tanggal']));
    $city        = trim((string) ($input['city'] ?? $trn['kota']));
    $location    = trim((string) ($input['location'] ?? $trn['lokasi']));
    $prize       = trim((string) ($input['prize_pool'] ?? $trn['prize']));
    $formatLabel = trim((string) ($input['format_label'] ?? $trn['format']));
    $description = trim((string) ($input['description'] ?? $trn['deskripsi']));

    if ($title === '' || mb_strlen($title) < 3) {
        jsonError('Nama turnamen minimal 3 karakter.', 422);
    }

    $posterPath = (string) $trn['poster_path'];
    if (isset($_FILES['poster']) && is_array($_FILES['poster'])) {
        $upload = turnamenSaveUploadedPoster($_FILES['poster'], (int) $user['id'], $posterPath);
        if (!$upload['ok']) {
            jsonError($upload['error'] ?? 'Gagal mengunggah poster.', 422);
        }
        $posterPath = (string) ($upload['path'] ?? $posterPath);
    }

    $stmt = $pdo->prepare('
        UPDATE tournaments
        SET title = ?, schedule_text = ?, city = ?, location = ?, prize_pool = ?,
            format_label = ?, description = ?, poster_path = ?
        WHERE id = ?
    ');
    $stmt->execute([
        mb_substr($title, 0, 180),
        mb_substr($schedule, 0, 100),
        mb_substr($city, 0, 80),
        mb_substr($location, 0, 180),
        mb_substr($prize, 0, 80),
        mb_substr($formatLabel, 0, 120),
        $description,
        $posterPath !== '' ? $posterPath : null,
        (int) $trn['db_id'],
    ]);

    jsonSuccess('Perubahan informasi turnamen berhasil disimpan!', [
        'slug'        => $trn['slug'],
        'poster_path' => $posterPath,
    ]);
} catch (PDOException $e) {
    jsonError('Gagal menyimpan perubahan turnamen.', 500);
}
