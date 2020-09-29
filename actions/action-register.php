<?php
require "../config/configuration.php";

$isAccountCreated = RegisterUser($_POST);

if ($isAccountCreated) {
    $data = $isAccountCreated;
    $redirect_url = SITEURL_ADMIN . 'index.php';
} else {
    $_SESSION['response'] = 'invalid';
    $redirect_url = ADMIN_PAGES . 'register.php';
}
redirect($redirect_url);
exit;
