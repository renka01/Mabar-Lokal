<?php
require_once __DIR__ . '/../../config/session.php';

requirePostMethod();
$user  = requireAuth();
$input = getJsonInput();

$categoryRaw = strtolower(trim((string) ($input['category'] ?? 'keduanya')));
$allowedCats = ['esports', 'olahraga', 'keduanya'];
$category    = in_array($categoryRaw, $allowedCats, true) ? $categoryRaw : 'keduanya';

$gamesInput = $input['games'] ?? [];
if (is_string($gamesInput)) {
    $decoded = json_decode($gamesInput, true);
    $gamesInput = is_array($decoded) ? $decoded : [];
}
if (!is_array($gamesInput)) {
    $gamesInput = [];
}

$games = [];
foreach ($gamesInput as $g) {
    $clean = trim((string) $g);
    if ($clean !== '' && mb_strlen($clean) <= 60) {
        $games[] = $clean;
    }
}
$games = array_values(array_unique($games));
$gamesJson = json_encode($games, JSON_UNESCAPED_UNICODE);

try {
    $pdo = getDB();
    $stmt = $pdo->prepare(
        'UPDATE users
         SET pref_category = ?, pref_games_json = ?, questionnaire_completed = TRUE
         WHERE id = ?'
    );
    $stmt->execute([$category, $gamesJson, (int) $user['id']]);

    $updatedUser = currentUser(true);

    jsonSuccess('Preferensi bermain berhasil disimpan!', [
        'user'     => $updatedUser ? publicUserPayload($updatedUser) : null,
        'redirect' => '../index.php?onboarded=1',
    ]);
} catch (PDOException $e) {
    jsonError('Gagal menyimpan preferensi ke database.', 500);
}

