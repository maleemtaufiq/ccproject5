<?php
require "../config/configuration.php";
logout();
header("Location: " . SITEURL_ADMIN . 'index.php');
