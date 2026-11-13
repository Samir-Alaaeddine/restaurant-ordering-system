<?php
    require_once("adminauth.php");
    require_once("../cn.php");
    /** @var mysqli $link */

    header('Content-Type: application/json');

    $sql = "SELECT MAX(ID) as maxID FROM orders";
    $res = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($res);
    $maxID = $row['maxID'] ? (int)$row['maxID'] : 0;

    echo json_encode(["maxID" => $maxID]);
?>