<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/scrim_helpers.php';

requirePostMethod();
$user  = requireAuth();
$input = getJsonInput();

$scrimKey = trim((string) ($input['scrim_id'] ?? $input['slug'] ?? ''));
if ($scrimKey === '') {
    jsonError('ID scrim tidak valid.', 422);
}

try {
    $pdo   = getDB();
    $scrim = scrimFindByKey($pdo, $scrimKey);
    if (!$scrim) {
        jsonError('Tantangan scrim tidak ditemukan.', 404);
    }

    if ((int) $scrim['creator_user_id'] !== (int) $user['id']) {
        jsonError('Hanya Kapten Host yang dapat mengubah pengaturan match.', 403);
    }

    $dateVal     = trim((string) ($input['date'] ?? 'Hari ini'));
    $timeVal     = trim((string) ($input['time'] ?? '20:30 WIB'));
    $formatShort = trim((string) ($input['format_short'] ?? $scrim['format_short']));
    $formatLabel = trim((string) ($input['format_label'] ?? scrimFormatLabelFromShort($formatShort)));
    $venueVal    = trim((string) ($input['venue'] ?? $scrim['lokasi']));
    $notesVal    = trim((string) ($input['notes'] ?? ''));
    $roomId      = trim((string) ($input['room_id'] ?? ''));
    $roomPass    = trim((string) ($input['room_password'] ?? ''));

    $scheduleLabel = $dateVal !== '' && $timeVal !== '' ? "{$dateVal}, {$timeVal}" : ($dateVal ?: $timeVal ?: $scrim['jadwal']);

    $lowerDate   = strtolower($dateVal);
    $dayCategory = 'lainnya';
    if (str_contains($lowerDate, 'hari ini')) {
        $dayCategory = 'hari_ini';
    } elseif (str_contains($lowerDate, 'sabtu') || str_contains($lowerDate, 'minggu') || str_contains($lowerDate, 'weekend')) {
        $dayCategory = 'weekend';
    }

    $stmt = $pdo->prepare('
        UPDATE scrim_matches
        SET schedule_label = ?,
            day_category = ?,
            format_short = ?,
            format_label = ?,
            location_label = ?,
            notes = ?,
            room_id = ?,
            room_password = ?
        WHERE id = ?
    ');
    $stmt->execute([
        mb_substr($scheduleLabel, 0, 100),
        $dayCategory,
        mb_substr($formatShort, 0, 20),
        mb_substr($formatLabel, 0, 100),
        mb_substr($venueVal, 0, 180),
        mb_substr($notesVal, 0, 600),
        $roomId !== '' ? mb_substr($roomId, 0, 80) : null,
        $roomPass !== '' ? mb_substr($roomPass, 0, 80) : null,
        (int) $scrim['db_id'],
    ]);

    jsonSuccess('Pengaturan jadwal tanding & rules berhasil disimpan!', [
        'jadwal'        => $scheduleLabel,
        'format_short'  => $formatShort,
        'format_label'  => $formatLabel,
        'lokasi'        => $venueVal,
        'catatan'       => $notesVal,
        'room_id'       => $roomId,
        'room_password' => $roomPass,
    ]);
} catch (PDOException $e) {
    jsonError('Gagal menyimpan pengaturan scrim.', 500);
}
