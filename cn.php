<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("config.php");
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$link) {
    die("Connection failed!");
}
