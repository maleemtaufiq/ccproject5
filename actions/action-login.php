<?php
require "../config/configuration.php";

$email = htmlspecialchars(trim($_POST["email"]));
$password = htmlspecialchars(trim($_POST["password"]));
$isCredentialsTrue = loginUserApi($email, $password);

if ($isCredentialsTrue) {
    $data = $isCredentialsTrue;
    setUserSession($data);
    echo "OKAY";
    die();
    $redirect_url = SITEURL_ADMIN . 'index.php';
} else {

    $_SESSION['response'] = 'invalid';
    $redirect_url = SITEURL_ADMIN . 'index.php';
    echo "Invalid";
    die();
}
redirect($redirect_url);
exit;
