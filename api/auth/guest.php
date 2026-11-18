<?php
require_once __DIR__ . '/../../config/session.php';

requirePostMethod();
setGuestSession();

jsonSuccess('Masuk sebagai Tamu. Selamat menjelajah!', [
    'guest'    => true,
    'redirect' => '../index.php',
]);

