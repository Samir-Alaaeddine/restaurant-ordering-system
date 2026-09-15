<?php
    require_once("authcheck.php");
    require_once("cn.php");
    if(!isset($_GET['orderID']))
    {
        header("Location:menu.php");
        exit();
    }
    $orderID = $_GET['orderID'];
    if($_SESSION['type']=="pickup")
    {
        $status = ["Preparing", "Ready", "Completed"];
    }
    if($_SESSION['type'] == "delivery")
    {
        $status = ["Preparing", "On the way", "Delivered"];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php 
        if($_SESSION['type']=="pickup")
        {
            $prefix = "P";
        }
        if($_SESSION['type'] == "delivery")
        {
            $prefix = "D";
        }    
        $displayID = "#" . $prefix . str_pad($orderID, 4, "0", STR_PAD_LEFT);
        
        $sqlO = mysqli_prepare($link,"SELECT `Type`,Method,created_at,total,`Status` from orders where ID=? and userID=?");
        mysqli_stmt_bind_param($sqlO,"ii",$orderID,$_SESSION['userID']);
        mysqli_stmt_execute($sqlO);
        $resO = mysqli_stmt_get_result($sqlO);
        $rowO = mysqli_fetch_array($resO);
        if (!$rowO)
        {
            header("Location: menu.php");
            exit();
        }
        $type = $rowO[0];
        $method = $rowO[1];
        if($type == "pickup")
        {
            $desc = "$type";
        }
        else
        {
            $desc = "$type . $method";
        }
        $dateO = $rowO[2];
        $total = $rowO[3];
        $currStatus = $rowO[4];

        $sqlName = mysqli_prepare($link,"SELECT FullName, Street, a.Name from user u, Area a where u.Area = a.ID and u.ID=?");
        mysqli_stmt_bind_param($sqlName,"i",$_SESSION['userID']);
        mysqli_stmt_execute($sqlName);
        $resName = mysqli_stmt_get_result($sqlName);
        $rowName = mysqli_fetch_array($resName);
        $name = $rowName[0];
        $street = $rowName[1];
        $areaName = $rowName[2];
        $fullAddress = "$street, $areaName";

        echo "<fieldset>
            <legend><h2><u>Checkout</u></h2></legend>
            <div class='receipt-main-title'>Order received $displayID</div>
            
            <div class='receipt-meta-container'>
                <div class='receipt-meta-row'><span>Placed At:</span> <strong>$dateO</strong></div>
                <div class='receipt-meta-row'><span>Order Type:</span> <strong style='text-transform: capitalize;'>$desc</strong></div>
                <div class='receipt-meta-row'><span>Customer:</span> <strong>$name</strong></div>";
        
        if($type == "delivery")
        {
            echo "<div class='receipt-meta-row'><span>Address:</span> <strong>$fullAddress</strong></div>";
        }

        echo "</div>";

        echo "<div class='status-tracking-group'>";
        $currentKey = array_search($currStatus, $status);
        if ($currentKey === false)
        {
            $currentKey = 0; 
        }

        foreach($status as $index => $step)
        {
            if ($index <= $currentKey) 
            {
                $class = "status-step active";
                $icon = "✔"; 
            } 
            else
            {
                $class = "status-step pending";
                $icon = "○"; 
            }
            echo "<div class='$class'>
                    <span class='status-icon'>$icon</span>
                    <span class='status-text'>$step</span>
                </div>";
        }
        echo "</div> <hr>";

        $sqlItem = mysqli_prepare($link,"SELECT quantity,price_at_order,`Name` from orderitems oi,dish d where oi.dishID = d.ID and oi.orderID = ?");
        mysqli_stmt_bind_param($sqlItem,"i",$orderID);
        mysqli_stmt_execute($sqlItem);
        $resItem = mysqli_stmt_get_result($sqlItem);
        echo "<div class='receipt-items-section'>";
        while($rowItem = mysqli_fetch_array($resItem))
        {
            $lineCost = $rowItem[0] * $rowItem[1];
            echo "<div class='receipt-item-row'>
                    <span class='item-name-qty'>$rowItem[2] <small>x$rowItem[0]</small></span>
                    <span class='item-price-display'>\$" . number_format($lineCost, 2) . "</span>
                  </div>";
        }
        echo "</div>";
        echo "<div class='receipt-total-footer'>
                <span>Grand Total</span>
                <span class='total-accent-price'>\$" . number_format($total, 2) . "</span>
              </div>";
        echo "<a href='menu.php' class='link-button' style='margin-top: 24px;'>Back to menu</a>
              </fieldset>";
    ?>
</body>
</html>
