<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/turnamen_helpers.php';

requirePostMethod();
$user  = requireAuth();
$input = !empty($_POST) ? $_POST : getJsonInput();

$category = strtolower(trim((string) ($input['category'] ?? 'esports')));
if (!in_array($category, ['esports', 'olahraga'], true)) {
    $category = 'esports';
}

$title    = trim((string) ($input['title'] ?? ''));
$game     = trim((string) ($input['game'] ?? ($category === 'olahraga' ? 'FUTSAL' : 'VALORANT')));
$city     = trim((string) ($input['city'] ?? ''));
$schedule = trim((string) ($input['schedule_text'] ?? 'Pekan Ini'));
$prize    = trim((string) ($input['prize_pool'] ?? 'Rp5.000.000'));
$maxTeams = max(4, min(32, (int) ($input['max_teams'] ?? 16)));

if ($title === '' || mb_strlen($title) < 3) {
    jsonError('Nama turnamen minimal 3 karakter.', 422);
}
if (mb_strlen($title) > 180) {
    $title = mb_substr($title, 0, 180);
}

$isOlahraga = $category === 'olahraga';
if ($city === '' || str_contains(strtolower($city), 'bebas')) {
    $city = $isOlahraga ? ($user['city'] ?: 'Kota Malang') : 'Malang';
}
if ($schedule === '') {
    $schedule = 'Pekan Ini';
}
if ($prize === '') {
    $prize = 'Gratis';
}

$posterPath = null;
if (isset($_FILES['poster']) && is_array($_FILES['poster'])) {
    $upload = turnamenSaveUploadedPoster($_FILES['poster'], (int) $user['id']);
    if (!$upload['ok']) {
        jsonError($upload['error'] ?? 'Gagal mengunggah poster.', 422);
    }
    $posterPath = ($upload['path'] ?? '') !== '' ? $upload['path'] : null;
}

$gameUpper   = strtoupper($game);
$gameIcon    = mabarGameIcon($game, $category);
$location    = $isOlahraga ? "{$city} • Arena Olahraga Komunitas" : "Online Discord & Custom Server ({$city})";
$formatLabel = $isOlahraga ? 'Grup & Gugur' : 'Single Elimination (BO3)';
$organizer   = (string) ($user['name'] ?: 'Komunitas Mabar Lokal');
$description = 'Turnamen komunitas resmi MabarLokal. Siapkan roster terbaikmu dan junjung tinggi sportivitas.';
$slug        = 'trn-' . bin2hex(random_bytes(4));

$distribusi = json_encode([
    'Juara 1' => '60% Total Hadiah + E-Certificate',
    'Juara 2' => '30% Total Hadiah + E-Certificate',
    'Juara 3' => '10% Total Hadiah',
], JSON_UNESCAPED_UNICODE);

try {
    $pdo  = getDB();
    $stmt = $pdo->prepare("
        INSERT INTO tournaments
            (slug, creator_user_id, title, game, category, status_label, status_badge, status_type, is_featured,
             city, location, schedule_text, prize_pool, fee_label, max_teams, format_label, organizer,
             poster_path, game_icon, description, prize_distribution_json)
        VALUES
            (?, ?, ?, ?, ?, 'AKAN DATANG', 'Slot Terbuka', 'upcoming', FALSE,
             ?, ?, ?, ?, 'Gratis', ?, ?, ?, ?, ?, ?, ?)
        RETURNING id
    ");
    $stmt->execute([
        $slug,
        (int) $user['id'],
        $title,
        $gameUpper,
        $category,
        $city,
        $location,
        $schedule,
        $prize,
        $maxTeams,
        $formatLabel,
        $organizer,
        $posterPath,
        $gameIcon,
        $description,
        $distribusi,
    ]);
    $newId = (int) $stmt->fetchColumn();

    jsonSuccess("Turnamen \"{$title}\" berhasil dibuat!", [
        'id'       => $newId,
        'slug'     => $slug,
        'redirect' => 'detail.php?id=' . urlencode($slug),
    ], 201);
} catch (PDOException $e) {
    jsonError('Gagal membuat turnamen di PostgreSQL.', 500);
}
