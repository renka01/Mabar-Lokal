<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/mabar_helpers.php';

requirePostMethod();
$user  = requireAuth();
$input = getJsonInput();

$sessionKey = trim((string) ($input['session_id'] ?? $input['slug'] ?? ''));
if ($sessionKey === '') {
    jsonError('ID sesi tidak valid.', 422);
}

try {
    $pdo     = getDB();
    $session = mabarFindSession($pdo, $sessionKey);
    if (!$session) {
        jsonError('Sesi tidak ditemukan.', 404);
    }

    if ((int) $session['host_user_id'] !== (int) $user['id']) {
        jsonError('Hanya Host sesi yang dapat mengubah pengaturan.', 403);
    }

    $title       = trim((string) ($input['title'] ?? $session['title']));
    $description = trim((string) ($input['description'] ?? ''));
    $joinRule    = trim((string) ($input['join_rule'] ?? $session['join_rule']));
    $startTime   = trim((string) ($input['start_time'] ?? $session['start_time']));
    $endTime     = trim((string) ($input['end_time'] ?? $session['end_time']));

    if ($title === '' || mb_strlen($title) < 3) {
        jsonError('Nama sesi minimal 3 karakter.', 422);
    }
    if (mb_strlen($title) > 50) {
        $title = mb_substr($title, 0, 50);
    }
    if (mb_strlen($description) > 250) {
        $description = mb_substr($description, 0, 250);
    }

    if (!in_array($joinRule, ['Langsung Gabung', 'Otomatis', 'Ask For Join'], true)) {
        $joinRule = 'Ask For Join';
    }

    $filledSlot = (int) $session['filled_slot'];
    $maxCap     = max(mabarGameCapacity((string) $session['game'], (string) $session['category']), 22);
    $maxSlot    = isset($input['max_slot']) && is_numeric($input['max_slot'])
        ? (int) $input['max_slot']
        : (int) $session['max_slot'];
    $maxSlot = max(2, min($maxSlot, $maxCap));

    if ($maxSlot < $filledSlot) {
        jsonError("Jumlah slot tidak boleh lebih kecil dari peserta yang sudah bergabung ({$filledSlot} pemain).", 422);
    }

    $rulesRaw = $input['rules'] ?? [];
    if (is_string($rulesRaw)) {
        $rulesRaw = preg_split('/\r\n|\r|\n/', $rulesRaw) ?: [];
    }
    $rules = [];
    if (is_array($rulesRaw)) {
        foreach ($rulesRaw as $r) {
            $clean = trim((string) $r);
            if ($clean !== '') {
                $rules[] = mb_substr($clean, 0, 120);
            }
        }
    }

    $specsRaw = $input['specs'] ?? [];
    $specs    = [];
    if (is_array($specsRaw)) {
        foreach ($specsRaw as $k => $v) {
            $cleanKey = preg_replace('/[^a-zA-Z0-9_]/', '', (string) $k);
            if ($cleanKey !== '') {
                $specs[$cleanKey] = mb_substr(trim((string) $v), 0, 120);
            }
        }
    }

    $scheduleLabel = $session['schedule_label'];
    if ($startTime !== '') {
        $scheduleLabel = $endTime !== '' ? "Hari ini, {$startTime} - {$endTime} WIB" : "Hari ini, {$startTime} WIB";
    }

    $stmt = $pdo->prepare('
        UPDATE mabar_sessions
        SET title = ?, description = ?, join_rule = ?, start_time = ?, end_time = ?,
            schedule_label = ?, max_slot = ?, rules_json = ?, specs_json = ?
        WHERE id = ?
    ');
    $stmt->execute([
        $title,
        $description,
        $joinRule,
        $startTime !== '' ? $startTime : '19:00',
        $endTime !== '' ? $endTime : '22:00',
        $scheduleLabel,
        $maxSlot,
        json_encode($rules, JSON_UNESCAPED_UNICODE),
        json_encode($specs, JSON_UNESCAPED_UNICODE),
        (int) $session['id'],
    ]);

    mabarSyncSessionStatus($pdo, (int) $session['id']);
    $updated = mabarFindSession($pdo, (string) $session['id']);

    jsonSuccess('Perubahan sesi berhasil disimpan!', [
        'session' => $updated ? mabarFormatSessionCard($updated) : null,
    ]);
} catch (PDOException $e) {
    jsonError('Gagal menyimpan pengaturan sesi.', 500);
}
