<?php
require_once("../cn.php");
/** @var mysqli $link */

$areas = ["Barja", "Shhim", "Mazboud", "Jiyeh", "Rmaileh"]; // placeholder — swap per client

$postedType = isset($_POST['rbType']) ? $_POST['rbType'] : 'pickup';
$postedName = isset($_POST['txtName']) ? htmlspecialchars($_POST['txtName']) : '';
$postedPhone = isset($_POST['txtPhone']) ? htmlspecialchars($_POST['txtPhone']) : '';
$postedArea = isset($_POST['selArea']) ? $_POST['selArea'] : '';
$postedAddress = isset($_POST['txtAddress']) ? htmlspecialchars($_POST['txtAddress']) : '';
$postedMethod = isset($_POST['rbMethod']) ? $_POST['rbMethod'] : 'Cash';

if (isset($_POST['btnPlaceOrder'])) {
    $type = $postedType;
    $name = trim($postedName);
    $phone = trim($postedPhone);
    $area = ($type == "delivery") ? $postedArea : NULL;
    $address = ($type == "delivery") ? trim($postedAddress) : NULL;
    $method = ($type == "delivery") ? $postedMethod : NULL;
    $status = "Preparing";
    $currDate = date("Y-m-d H:i:s");

    $valid = true;
    if ($name == "") {
        $_SESSION['checkout_error'] = "Please enter your name.";
        $valid = false;
    } elseif (!preg_match('/^(3|70|71|76|78|79|81)[0-9]{6}$/', $phone)) {
        $_SESSION['checkout_error'] = "Phone format is incorrect.";
        $valid = false;
    } elseif ($type == "delivery" && $address == "") {
        $_SESSION['checkout_error'] = "Please enter your delivery address.";
        $valid = false;
    }

    if ($valid) {
        mysqli_begin_transaction($link);
        try {
            $calcTotal = 0;
            $sqlPrice = mysqli_prepare($link, "SELECT Price from dish where ID = ?");
            foreach ($_SESSION['cart'] as $dishID => $qty) {
                mysqli_stmt_bind_param($sqlPrice, "i", $dishID);
                mysqli_stmt_execute($sqlPrice);
                $resCalc = mysqli_stmt_get_result($sqlPrice);
                $rowCalc = mysqli_fetch_array($resCalc);
                $calcTotal += $rowCalc[0] * $qty;
            }

            $deliveryFee = 2.00; // placeholder — swap per client
            if ($type == "delivery") {
                $calcTotal += $deliveryFee;
            }

            $token = bin2hex(random_bytes(16));

            $sqlOrders = mysqli_prepare($link, "INSERT INTO orders(`Type`,Method,`Status`,total,created_at,CustomerName,CustomerPhone,Area,Address,tracking_token) VALUES(?,?,?,?,?,?,?,?,?,?)");
            mysqli_stmt_bind_param($sqlOrders, "sssdssssss", $type, $method, $status, $calcTotal, $currDate, $name, $phone, $area, $address, $token);
            mysqli_stmt_execute($sqlOrders);
            $orderID = mysqli_insert_id($link);

            $sqlItems = mysqli_prepare($link, "INSERT INTO orderitems(orderID,dishID,quantity,price_at_order) VALUES(?,?,?,?)");
            foreach ($_SESSION['cart'] as $dishID => $qty) {
                mysqli_stmt_bind_param($sqlPrice, "i", $dishID);
                mysqli_stmt_execute($sqlPrice);
                $resPrice = mysqli_stmt_get_result($sqlPrice);
                $rowPrice = mysqli_fetch_array($resPrice);
                $price = $rowPrice[0];
                mysqli_stmt_bind_param($sqlItems, "iiid", $orderID, $dishID, $qty, $price);
                mysqli_stmt_execute($sqlItems);
            }
            mysqli_commit($link);
            $_SESSION['cart'] = [];

            header("Location: confirmation.php?token=" . $token);
            exit();
        } catch (mysqli_sql_exception $e) {
            mysqli_rollback($link);
            $_SESSION['checkout_error'] = "Something went wrong: " . $e->getMessage();
        }
    }
}

if (isset($_GET['rmvID'])) {
    unset($_SESSION['cart'][$_GET['rmvID']]);
    header("Location: cart.php");
    exit();
}

if (isset($_POST['btnIncrease'])) {
    $_SESSION['cart'][$_POST['dishId']]++;
    header("Location: cart.php");
    exit();
}

if (isset($_POST['btnDecrease'])) {
    $dishId = $_POST['dishId'];
    if ($_SESSION['cart'][$dishId] > 1) {
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
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <div class="cart-page">
        <h1>My cart</h1>
        <hr>
        <fieldset>
            <div class="cart-list">
                <?php
                if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                    echo "<p>Cart is empty</p>";
                } else {
                    $total = 0;
                    foreach ($_SESSION['cart'] as $dishID => $qty) {
                        $sql = mysqli_prepare($link, "Select Name,Price,ImgPath from dish where ID=?");
                        mysqli_stmt_bind_param($sql, "i", $dishID);
                        mysqli_stmt_execute($sql);
                        $res = mysqli_stmt_get_result($sql);
                        $row = mysqli_fetch_array($res);

                        $lineTotal = $row[1] * $qty;
                        $total += $lineTotal;
                        $imgPath = "../" . $row[2];

                        echo "<div class='cart-row'>
                                <img src='$imgPath' alt='Dish Image' class='cart-row-img'>
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
            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                $checkedPickup = ($postedType == 'pickup') ? 'checked' : '';
                $checkedDelivery = ($postedType == 'delivery') ? 'checked' : '';
                $checkedCash = ($postedMethod == 'Cash') ? 'checked' : '';
                $checkedWhish = ($postedMethod == 'Whish Money') ? 'checked' : '';

                echo "<form method='post' class='checkout-form' id='checkoutForm'>";

                echo "<div class='order-type-section'>
                            <p class='payment-title'>How would you like to get your order?</p>
                            <div class='payment-options-group'>
                                <label class='payment-option-card'>
                                    <input type='radio' name='rbType' value='pickup' $checkedPickup>
                                    <span class='radio-custom'></span>
                                    <span class='option-text'>📤 Pickup</span>
                                </label>
                                <label class='payment-option-card'>
                                    <input type='radio' name='rbType' value='delivery' $checkedDelivery>
                                    <span class='radio-custom'></span>
                                    <span class='option-text'>🛵 Delivery</span>
                                </label>
                            </div>
                          </div>";

                echo "<div class='customer-fields'>
                            <label>Full name</label>
                            <input type='text' name='txtName' value='$postedName' required>
                            <label>Phone number</label>
                            <input type='text' name='txtPhone' value='$postedPhone' placeholder='(+961)' required>
                          </div>";

                echo "<div id='deliveryFields' class='delivery-fields'>
                            <label>Area</label>
                            <select name='selArea'>";
                foreach ($areas as $a) {
                    $sel = ($a == $postedArea) ? 'selected' : '';
                    echo "<option value='$a' $sel>$a</option>";
                }
                echo "    </select>
                            <label>Address</label>
                            <input type='text' name='txtAddress' value='$postedAddress' placeholder='Street, building, floor...'>
                          </div>";

                echo "<div id='paymentSection' class='payment-section'>
                            <p class='payment-title'>How would you like to pay?</p>
                            <div class='payment-options-group'>
                                <label class='payment-option-card'>
                                    <input type='radio' name='rbMethod' value='Cash' $checkedCash>
                                    <span class='radio-custom'></span>
                                    <span class='option-text'>💵 Cash on Delivery</span>
                                </label>
                                <label class='payment-option-card'>
                                    <input type='radio' name='rbMethod' value='Whish Money' $checkedWhish>
                                    <span class='radio-custom'></span>
                                    <span class='option-text'>📲 Whish Money</span>
                                </label>
                            </div>
                          </div>";

                echo "<button type='submit' name='btnPlaceOrder'>Place order ↗</button>";
                echo "</form>";
            }

            if (isset($_SESSION['checkout_error'])) {
                echo "<p class='checkout-error-msg' id='checkoutError'>" . $_SESSION['checkout_error'] . "</p>";
                unset($_SESSION['checkout_error']);
            }
            ?>
            <a href="menu.php" class="cart-continue-link">Continue browsing menu</a>
        </fieldset>
    </div>

    <script>
        var typeRadios = document.querySelectorAll('input[name="rbType"]');
        var deliveryFields = document.getElementById('deliveryFields');
        var paymentSection = document.getElementById('paymentSection');
        var addressInput = document.querySelector('input[name="txtAddress"]');

        function toggleDeliveryFields() {
            var selected = document.querySelector('input[name="rbType"]:checked');
            if (!selected) return;
            var isDelivery = selected.value === 'delivery';
            if (deliveryFields) deliveryFields.style.display = isDelivery ? 'block' : 'none';
            if (paymentSection) paymentSection.style.display = isDelivery ? 'block' : 'none';
            if (addressInput) addressInput.required = isDelivery;
        }

        typeRadios.forEach(function(r) {
            r.addEventListener('change', toggleDeliveryFields);
        });
        toggleDeliveryFields();
        var errorMsg = document.getElementById('checkoutError');
        if (errorMsg) {
            errorMsg.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    </script>
</body>

</html>