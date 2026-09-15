<?php
    require_once("authcheck.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Type</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page-wrap">
        <h1>How would you like to order?</h1>
        <form>
            <fieldset>
                <legend><h2><u>Choose one</u></h2></legend>
                <table align="center">
                    <tr>
                        <td colspan="2">
                            <a href="menu.php?type=dine-in">
                                <h3><b>Dine-in</b></h3>
                                <img src="images/dinein.png" width="200" alt="">
                            </a>
                        </td>
                    </tr>
                    
                    <tr>
                        <td colspan="2">
                            <a href="menu.php?type=pickup">
                                <h3><b>Pickup</b></h3>
                                <img src="images/pickup.png" width="200" alt="">
                            </a>
                        </td>
                    </tr>
                    
                    <tr>
                        <td colspan="2">
                            <a href="menu.php?type=delivery">
                                <h3><b>Delivery</b></h3>
                                <img src="images/delivery.png" width="200" alt="">
                            </a>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </form>
    </div>
</body>
</html>