<?php
require "../config/configuration.php";
$data = $_POST;
$data['user_id'] = $_SESSION['user_id'];
$result = SavePortfolio($data);
if ($result) {
    $redirect_url = ADMIN_PAGES . 'storage.php';
} else {
    $_SESSION['response'] = 'invalid';
    $redirect_url = ADMIN_PAGES . 'new-portfolio.php';
}
redirect($redirect_url);
exit;
