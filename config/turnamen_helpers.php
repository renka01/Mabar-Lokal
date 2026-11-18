<?php
require_once __DIR__ . '/mabar_helpers.php';

if (!function_exists('turnamenFormatRow')) {
    function turnamenFormatRow(array $row): array
    {
        $category   = strtolower((string) ($row['category'] ?? 'esports'));
        $game       = (string) ($row['game'] ?? 'VALORANT');
        $statusType = strtolower((string) ($row['status_type'] ?? 'upcoming'));
        $maxTeams   = (int) ($row['max_teams'] ?? 16);
        $regCount   = (int) ($row['registered_count'] ?? 0);
        $unitLabel  = str_contains(strtolower($game), 'bulu') || str_contains(strtolower($game), 'badminton') ? 'Pasang' : 'Tim';

        $timText = $statusType === 'done'
            ? "{$maxTeams} {$unitLabel} Peserta"
            : "{$regCount} / {$maxTeams} {$unitLabel}";

        $distribusi = [];
        if (!empty($row['prize_distribution_json'])) {
            $decoded = json_decode((string) $row['prize_distribution_json'], true);
            if (is_array($decoded)) {
                $distribusi = $decoded;
            }
        }
        if (empty($distribusi)) {
            $distribusi = [
                'Juara 1' => '60% Total Hadiah + E-Certificate',
                'Juara 2' => '30% Total Hadiah + E-Certificate',
                'Juara 3' => '10% Total Hadiah',
            ];
        }

        $bracket = null;
        if (!empty($row['bracket_json'])) {
            $decodedB = json_decode((string) $row['bracket_json'], true);
            if (is_array($decodedB)) {
                $bracket = $decodedB;
            }
        }

        $gameIcon = !empty($row['game_icon']) ? (string) $row['game_icon'] : mabarGameIcon($game, $category);

        return [
            'db_id'           => (int) $row['id'],
            'id'              => (string) $row['slug'],
            'slug'            => (string) $row['slug'],
            'creator_user_id' => $row['creator_user_id'] !== null ? (int) $row['creator_user_id'] : null,
            'judul'           => (string) $row['title'],
            'game'            => $game,
            'kategori'        => $category,
            'status'          => (string) ($row['status_label'] ?: strtoupper($statusType)),
            'statusBadge'     => (string) ($row['status_badge'] ?: 'Slot Terbuka'),
            'statusType'      => $statusType,
            'is_featured'     => filter_var($row['is_featured'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'gameIcon'        => $gameIcon,
            'poster_path'     => (string) ($row['poster_path'] ?? ''),
            'kota'            => (string) ($row['city'] ?: 'Malang'),
            'lokasi'          => (string) ($row['location'] ?: 'Arena Komunitas'),
            'tanggal'         => (string) ($row['schedule_text'] ?: 'Pekan Ini'),
            'prize'           => (string) ($row['prize_pool'] ?: 'Gratis'),
            'biaya'           => (string) ($row['fee_label'] ?: 'Gratis'),
            'max_teams'       => $maxTeams,
            'registered_count'=> $regCount,
            'tim'             => $timText,
            'format'          => (string) ($row['format_label'] ?: 'Single Elimination (BO3)'),
            'penyelenggara'   => (string) ($row['organizer'] ?: 'Komunitas Mabar Lokal'),
            'deskripsi'       => (string) ($row['description'] ?? ''),
            'distribusi'      => $distribusi,
            'bracket'         => $bracket,
            'pemenang'        => (string) ($row['winner_team'] ?: 'Menunggu Hasil'),
            'runnerUp'        => (string) ($row['runner_up_team'] ?: '-'),
            'skorFinal'       => (string) ($row['final_score'] ?: '-'),
        ];
    }
}

if (!function_exists('turnamenFetchGrouped')) {
    function turnamenFetchGrouped(PDO $pdo): array
    {
        $stmt = $pdo->query("
            SELECT t.*,
                   (SELECT COUNT(*) FROM tournament_teams tt WHERE tt.tournament_id = t.id AND tt.status IN ('verified', 'pending')) AS registered_count
            FROM tournaments t
            ORDER BY t.is_featured DESC, t.created_at DESC, t.id DESC
        ");
        $all = array_map('turnamenFormatRow', $stmt->fetchAll());

        $unggulan    = [];
        $akandatang  = [];
        $berlangsung = [];
        $selesai     = [];

        foreach ($all as $t) {
            if ($t['is_featured'] && $t['statusType'] !== 'done') {
                $unggulan[] = $t;
            } elseif ($t['statusType'] === 'upcoming') {
                $akandatang[] = $t;
            } elseif ($t['statusType'] === 'live') {
                $berlangsung[] = $t;
            } else {
                $selesai[] = $t;
            }
        }

        return [
            'unggulan'    => $unggulan,
            'akandatang'  => $akandatang,
            'berlangsung' => $berlangsung,
            'selesai'     => $selesai,
        ];
    }
}

if (!function_exists('turnamenFindByKey')) {
    function turnamenFindByKey(PDO $pdo, string $key): ?array
    {
        $key = trim($key);
        if ($key === '') {
            return null;
        }
        $sql = "
            SELECT t.*,
                   (SELECT COUNT(*) FROM tournament_teams tt WHERE tt.tournament_id = t.id AND tt.status IN ('verified', 'pending')) AS registered_count
            FROM tournaments t
        ";
        if (ctype_digit($key)) {
            $stmt = $pdo->prepare($sql . ' WHERE t.id = ? OR t.slug = ? LIMIT 1');
            $stmt->execute([(int) $key, $key]);
        } else {
            $stmt = $pdo->prepare($sql . ' WHERE t.slug = ? LIMIT 1');
            $stmt->execute([$key]);
        }
        $row = $stmt->fetch();
        return $row ? turnamenFormatRow($row) : null;
    }
}

if (!function_exists('turnamenFetchTeams')) {
    function turnamenFetchTeams(PDO $pdo, int $tournamentId): array
    {
        $stmt = $pdo->prepare("
            SELECT tt.*,
                   u.avatar_path AS captain_avatar
            FROM tournament_teams tt
            LEFT JOIN users u ON u.id = tt.user_id
            WHERE tt.tournament_id = ? AND tt.status <> 'rejected'
            ORDER BY CASE WHEN tt.status = 'verified' THEN 0 ELSE 1 END, tt.created_at ASC, tt.id ASC
        ");
        $stmt->execute([$tournamentId]);
        $rows = $stmt->fetchAll();

        $teams = [];
        foreach ($rows as $idx => $r) {
            $roster = [];
            if (!empty($r['roster_json'])) {
                $decoded = json_decode((string) $r['roster_json'], true);
                if (is_array($decoded)) {
                    $roster = array_values(array_filter(array_map('strval', $decoded)));
                }
            }
            if (empty($roster)) {
                $roster = [(string) $r['captain_name']];
            }
            $teams[] = [
                'id'             => (int) $r['id'],
                'tournament_id'  => (int) $r['tournament_id'],
                'user_id'        => $r['user_id'] !== null ? (int) $r['user_id'] : null,
                'nama'           => (string) $r['team_name'],
                'kapten'         => (string) $r['captain_name'],
                'kontak'         => (string) ($r['captain_contact'] ?? ''),
                'kota'           => (string) ($r['city'] ?: 'Malang'),
                'roster'         => $roster,
                'seed'           => (string) ($r['seed_label'] ?: ('Seed #' . ($idx + 1))),
                'status'         => (string) ($r['status'] ?: 'pending'),
                'captain_avatar' => (string) ($r['captain_avatar'] ?? ''),
            ];
        }
        return $teams;
    }
}

if (!function_exists('turnamenSaveUploadedPoster')) {
    function turnamenSaveUploadedPoster(?array $file, int $userId, string $oldPath = ''): array
    {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return ['ok' => true, 'path' => $oldPath];
        }
        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            return ['ok' => false, 'error' => 'Upload poster gagal.'];
        }
        if (($file['size'] ?? 0) <= 0 || $file['size'] > 5 * 1024 * 1024) {
            return ['ok' => false, 'error' => 'Ukuran file poster maksimal 5MB.'];
        }
        $tmpPath = (string) ($file['tmp_name'] ?? '');
        if ($tmpPath === '' || !is_uploaded_file($tmpPath)) {
            return ['ok' => false, 'error' => 'File poster tidak valid.'];
        }

        $finfo  = new finfo(FILEINFO_MIME_TYPE);
        $mime   = (string) $finfo->file($tmpPath);
        $extMap = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
        ];
        if (!isset($extMap[$mime])) {
            return ['ok' => false, 'error' => 'Format poster harus JPG, PNG, atau WEBP.'];
        }

        require_once __DIR__ . '/storage.php';

        $filename = sprintf('poster_%d_%s.%s', $userId, bin2hex(random_bytes(8)), $extMap[$mime]);
        $uploaded = storageUploadImage($tmpPath, 'posters', $filename, $mime);
        if (!$uploaded['ok']) {
            return ['ok' => false, 'error' => $uploaded['error'] ?? 'Gagal menyimpan file poster.'];
        }

        $savedPath = (string) ($uploaded['path'] ?? '');
        if ($oldPath !== '' && $oldPath !== $savedPath) {
            storageDeleteMedia($oldPath, 'posters');
        }

        return ['ok' => true, 'path' => $savedPath];
    }
}
