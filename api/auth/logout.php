<?php
require_once __DIR__ . '/../../config/session.php';

logoutUserSession();

if (!empty($_GET['redirect'])) {
    header('Location: ../../pages/auth.php?logged_out=1');
    exit;
}

jsonSuccess('Anda telah keluar dari akun.', [
    'redirect' => '../../pages/auth.php',
]);

