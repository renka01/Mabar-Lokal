<?php
require_once __DIR__ . '/../../config/session.php';

$user = currentUser(true);
if ($user) {
    jsonSuccess('Pengguna terautentikasi.', [
        'authenticated' => true,
        'guest'         => false,
        'user'          => publicUserPayload($user),
    ]);
}

jsonSuccess('Pengguna belum masuk.', [
    'authenticated' => false,
    'guest'         => isGuestSession(),
    'user'          => null,
]);

