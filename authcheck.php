<?php
    session_start();
    if(!isset($_SESSION['userID']) && isset($_COOKIE['userID']))
    {
        $_SESSION['userID'] = $_COOKIE['userID'];
    }

        if (!isset($_SESSION['userID']))
    {
        header("Location: login.php");
        exit();
    }      
?>