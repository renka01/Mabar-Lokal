<?php
require_once __DIR__ . '/../../config/session.php';

requirePostMethod();
$user  = requireAuth();
$input = getJsonInput();

$uid    = (int) $user['id'];
$action = strtolower(trim((string) ($input['action'] ?? '')));
$id     = isset($input['id']) && is_numeric($input['id']) ? (int) $input['id'] : 0;

try {
    $pdo = getDB();

    switch ($action) {
        case 'mark_read':
            if ($id <= 0) {
                jsonError('ID notifikasi tidak valid.', 422);
            }
            $pdo->prepare('UPDATE notifications SET is_read = TRUE WHERE id = ? AND user_id = ?')
                ->execute([$id, $uid]);
            $msg = 'Notifikasi ditandai sudah dibaca.';
            break;

        case 'mark_all_read':
            $pdo->prepare('UPDATE notifications SET is_read = TRUE WHERE user_id = ? AND is_read = FALSE')
                ->execute([$uid]);
            $msg = 'Semua notifikasi ditandai sudah dibaca.';
            break;

        case 'dismiss':
            if ($id <= 0) {
                jsonError('ID notifikasi tidak valid.', 422);
            }
            $pdo->prepare('DELETE FROM notifications WHERE id = ? AND user_id = ?')
                ->execute([$id, $uid]);
            $msg = 'Notifikasi dihapus.';
            break;

        case 'clear_read':
            $pdo->prepare('DELETE FROM notifications WHERE user_id = ? AND is_read = TRUE')
                ->execute([$uid]);
            $msg = 'Notifikasi yang sudah dibaca berhasil dihapus.';
            break;

        default:
            jsonError('Aksi notifikasi tidak dikenali.', 422);
    }

    $cntStmt = $pdo->prepare('SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = FALSE');
    $cntStmt->execute([$uid]);
    $unreadCount = (int) $cntStmt->fetchColumn();

    jsonSuccess($msg, [
        'unread_count' => $unreadCount,
    ]);
} catch (PDOException $e) {
    jsonError('Gagal memproses aksi notifikasi.', 500);
}
