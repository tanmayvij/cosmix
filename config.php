<?php

include 'conn.php';

$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS) or header("Location: /install.php");
$db = mysqli_select_db($link, DB_NAME) or header("Location: /install.php");

$user_registration = 1;

define("COOKIE_TIME_OUT", 10);
define('SALT_LENGTH', 9);

define ("ADMIN_LEVEL", 5);
define ("USER_LEVEL", 1);

include 'functions.php';

?>