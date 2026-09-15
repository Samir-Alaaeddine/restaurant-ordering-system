<?php
    require_once("authcheck.php");
    require_once("cn.php");

    if(isset($_POST['btnPlaceOrder']))
    {
        $userID = $_SESSION['userID'];
        $type = $_SESSION['type'];
        $method = isset($_POST['rbMethod']) ? $_POST['rbMethod'] : NULL;
        $status = "Preparing";
        $currDate = date("Y-m-d H:i:s");

        mysqli_begin_transaction($link);
        try
        {
            $calcTotal = 0;
            $sqlPrice = mysqli_prepare($link,"SELECT Price from dish where ID = ?");
            foreach($_SESSION['cart'] as $dishID => $qty)
            {
                mysqli_stmt_bind_param($sqlPrice,"i",$dishID);
                mysqli_stmt_execute($sqlPrice);
                $resCalc = mysqli_stmt_get_result($sqlPrice);
                $rowCalc = mysqli_fetch_array($resCalc);
                $calcTotal += $rowCalc[0] * $qty;
            }
            $sqlOrders = mysqli_prepare($link,"INSERT INTO orders(userID,`Type`,Method,`Status`,total,created_at) VALUES(?,?,?,?,?,?)");
            mysqli_stmt_bind_param($sqlOrders,"isssds",$userID,$type,$method,$status,$calcTotal,$currDate);
            mysqli_stmt_execute($sqlOrders);
            $orderID = mysqli_insert_id($link);

            $sqlItems = mysqli_prepare($link,"INSERT INTO orderitems(orderID,dishID,quantity,price_at_order) VALUES(?,?,?,?)");
            foreach($_SESSION['cart'] as $dishID => $qty)
            {
                mysqli_stmt_bind_param($sqlPrice,"i",$dishID);
                mysqli_stmt_execute($sqlPrice);
                $resPrice = mysqli_stmt_get_result($sqlPrice);
                $rowPrice = mysqli_fetch_array($resPrice);
                $price = $rowPrice[0];
                mysqli_stmt_bind_param($sqlItems,"iiid",$orderID,$dishID,$qty,$price);
                mysqli_stmt_execute($sqlItems);
            }
            mysqli_commit($link);
            $_SESSION['cart'] = [];
            
            header("Location: confirmation.php?orderID=" . $orderID);
            exit();
        }
        catch(mysqli_sql_exception $e)
        {
            mysqli_rollback($link);
            $_SESSION['checkout_error'] = "Something went wrong: " . $e->getMessage();
        }
    }

    if(isset($_GET['rmvID']))
    {
        unset($_SESSION['cart'][$_GET['rmvID']]);
        header("Location: cart.php");
        exit();
    }

    if (isset($_POST['btnIncrease']))
    {
        $_SESSION['cart'][$_POST['dishId']]++;
        header("Location: cart.php");
        exit();
    }
    
    if (isset($_POST['btnDecrease']))
    {
        $dishId = $_POST['dishId'];
        if ($_SESSION['cart'][$dishId] > 1)
        {
            $_SESSION['cart'][$dishId]--;
        }
        header("Location: cart.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="cart-page">
        <h1>My cart</h1><hr>
        <fieldset>
            <div class="cart-list">
            <?php 
                if (!isset($_SESSION['cart']) || empty($_SESSION['cart']))
                {
                    echo "<p>Cart is empty</p>";
                }
                else
                {
                    $total = 0;
                    foreach($_SESSION['cart'] as $dishID => $qty)
                    {
                        $sql = mysqli_prepare($link,"Select Name,Price,ImgPath from dish where ID=?");
                        mysqli_stmt_bind_param($sql,"i",$dishID);
                        mysqli_stmt_execute($sql);
                        $res = mysqli_stmt_get_result($sql);
                        $row = mysqli_fetch_array($res);

                        $lineTotal = $row[1] * $qty;
                        $total += $lineTotal;

                        echo "<div class='cart-row'>
                                <img src='$row[2]' alt='Dish Image' class='cart-row-img'>
                                <div class='cart-row-info'>
                                    <div class='cart-row-header'>
                                        <h3 class='cart-row-name'>$row[0]</h3>
                                        <span class='cart-row-price'>\$$lineTotal</span>
                                    </div>
                                    <p class='cart-row-desc'>\$$row[1] each</p>
                                </div>
                                <div class='cart-qty-group'>
                                    <form method='post'>
                                        <input type='hidden' name='dishId' value='$dishID'>
                                        <button type='submit' name='btnDecrease' class='cart-qty-btn'>-</button>
                                    </form>
                                    <span class='cart-qty-value'>$qty</span>
                                    <form method='post'>
                                        <input type='hidden' name='dishId' value='$dishID'>
                                        <button type='submit' name='btnIncrease' class='cart-qty-btn'>+</button>
                                    </form>
                                </div>
                                <a href='cart.php?rmvID=$dishID' class='cart-remove-btn'>🗑️</a>
                            </div>";
                    }
                    echo "<div class='cart-total-row'>
                            <span>Total</span>
                            <span>\$$total</span>
                        </div>";
                }
            ?>
            </div>
            <?php 
                if (isset($_SESSION['cart']) && !empty($_SESSION['cart']))
                {
                    echo "<form method='post' class='checkout-form'>";
                    
                    if($_SESSION['type'] == "delivery")
                    {
                        echo "<div class='payment-section'>
                                <p class='payment-title'>How would you like to pay?</p>
                                <div class='payment-options-group'>
                                    
                                    <label class='payment-option-card'>
                                        <input type='radio' name='rbMethod' value='Cash' checked>
                                        <span class='radio-custom'></span>
                                        <span class='option-text'>💵 Cash on Delivery</span>
                                    </label>
                                    
                                    <label class='payment-option-card'>
                                        <input type='radio' name='rbMethod' value='Card'>
                                        <span class='radio-custom'></span>
                                        <span class='option-text'>💳 Card on Delivery</span>
                                    </label>
                                    
                                </div>
                            </div>";
                    }
                    
                    echo "<button type='submit' name='btnPlaceOrder'>Place order ↗</button>";
                    echo "</form>";
                }

                if (isset($_SESSION['checkout_error'])) {
                    echo "<p class='checkout-error-msg'>" . $_SESSION['checkout_error'] . "</p>";
                    unset($_SESSION['checkout_error']); // Flash clear data rule
                }
            ?>
            <a href="menu.php" class="cart-continue-link">Continue browsing menu</a>
        </fieldset>
    </div>
</body>
</html>
