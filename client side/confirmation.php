<?php
    require_once("../cn.php");
    /** @var mysqli $link */

    if(!isset($_GET['token']))
    {
        header("Location: menu.php");
        exit();
    }
    $token =$_GET['token'];

    $sqlO = mysqli_prepare($link,"SELECT ID,`Type`,Method,`Status`,total,created_at,CustomerName,CustomerPhone,Area,Address from orders where tracking_token=?");
    mysqli_stmt_bind_param($sqlO,"s",$token);
    mysqli_stmt_execute($sqlO);
    $resO = mysqli_stmt_get_result($sqlO);
    $rowO = mysqli_fetch_array($resO);

    if (!$rowO)
    {
        header("Location: menu.php");
        exit();
    }

    $orderID =$rowO[0];
    $type =$rowO[1];
    $method =$rowO[2];
    $currStatus =$rowO[3];
    $total =$rowO[4];
    $dateO =$rowO[5];
    $name =$rowO[6];
    $phone =$rowO[7];
    $area =$rowO[8];
    $address =$rowO[9];

    if($type == "pickup")
    {
        $status = ["Preparing", "Ready", "Completed"];
        $prefix = "P";
        $desc =$type;
    }
    else
    {
        $status = ["Preparing", "On the way", "Delivered"];
        $prefix = "D";
        $desc = "$type -$method";
    }

    $displayID = "#" . $prefix . str_pad($orderID, 4, "0", STR_PAD_LEFT);
    $fullAddress = "$address,$area";

    $sqlItem = mysqli_prepare($link,"SELECT quantity,price_at_order,`Name` from orderitems oi,dish d where oi.dishID = d.ID and oi.orderID = ?");
    mysqli_stmt_bind_param($sqlItem,"i",$orderID);
    mysqli_stmt_execute($sqlItem);
    $resItem = mysqli_stmt_get_result($sqlItem);

    $items = [];
    while($rowItem = mysqli_fetch_array($resItem))
    {
        $items[] = ["name" => $rowItem[2], "qty" => $rowItem[0], "lineCost" => $rowItem[0] *$rowItem[1]];
    }

    // Delivery fee — derived from the difference, not a separate stored column
    $subtotal = 0;
    foreach($items as$item)
    {
        $subtotal +=$item['lineCost'];
    }
    $deliveryFee = ($type == "delivery") ? ($total -$subtotal) : 0;

    // Placeholder — swap this per client when customizing for a buyer
    $whatsappNumber = "96179177139";

    if($method == "Whish Money")
    {
        $waLines = [];$waLines[] = "Order $displayID -$name";
        foreach($items as$item)
        {
            $waLines[] = $item['name'] . " x" . $item['qty'] . " - $" . number_format($item['lineCost'], 2);
        }
        if($type == "delivery" && $deliveryFee > 0)         {$waLines[] = "Delivery fee - $" . number_format($deliveryFee, 2);
        }
        $waLines[] = "Total: $" . number_format($total, 2);$waLines[] = "Pays with Whish 📲 — please confirm payment";
        $waMessage = rawurlencode(implode("\n", $waLines));
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Confirmation</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="client-body">

    <div class="page-wrap confirmation-wrap">
        <div class="client-card">
            
            <div class="client-card-header">
                <h1>Checkout</h1>
                <span class="client-subtitle">Order received <?php echo $displayID; ?></span>
            </div>

            <!-- Tracking Link Box -->
            <div class="tracking-link-box">
                <button type="button" id="copyLinkBtn" class="btn-copy-link">🔗 Copy tracking link</button>
                <p class="tracking-link-note">Save this link to check your order status later.</p>
            </div>

            <!-- Meta Details -->
            <div class="confirmation-meta-group">
                <div class="meta-row"><span>Placed At:</span> <strong><?php echo $dateO; ?></strong></div>
                <div class="meta-row"><span>Order Type:</span> <strong style="text-transform: capitalize;"><?php echo $desc; ?></strong></div>
                <div class="meta-row"><span>Customer:</span> <strong><?php echo $name; ?></strong></div>
                <?php if($type == "delivery"): ?>
                <div class="meta-row"><span>Address:</span> <strong><?php echo $fullAddress; ?></strong></div>
                <?php endif; ?>
            </div>

            <div class="client-divider"></div>

            <!-- Order Status Pipeline -->
            <div class="status-tracking-group">
                <?php
                    $currentKey = array_search($currStatus,$status);
                    if ($currentKey === false)
                    {
                        $currentKey = 0;
                    }

                    foreach($status as $index =>$step)
                    {
                        if ($index <=$currentKey)
                        {
                            $class = "status-step active";
                            $icon = "✓";
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
                ?>
            </div>

            <div class="client-divider"></div>

            <!-- Purchased Items Breakdown -->
            <div class="receipt-items-section">
                <?php foreach($items as$item): ?>
                    <div class="receipt-item-row">
                        <span class="item-name-qty"><?php echo $item['name']; ?> <small>x<?php echo$item['qty']; ?></small></span>
                        <span class="item-price-display">$<?php echo number_format($item['lineCost'], 2); ?></span>
                    </div>
                <?php endforeach; ?>

                <?php if($type == "delivery" && $deliveryFee > 0): ?>
                    <div class="receipt-subtotal-row">
                        <span>Subtotal</span>
                        <span>$<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="receipt-item-row">
                        <span class="item-name-qty">Delivery fee</span>
                        <span class="item-price-display">$<?php echo number_format($deliveryFee, 2); ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Total -->
            <div class="receipt-total-footer">
                <span>Grand Total</span>
                <span class="total-accent-price">$<?php echo number_format($total, 2); ?></span>
            </div>

            <!-- Action Buttons -->
            <div class="confirmation-actions">
                <?php if($method == "Whish Money"): ?>
                    <a href="https://wa.me/<?php echo $whatsappNumber; ?>?text=<?php echo$waMessage; ?>" target="_blank" class="btn-whatsapp">
                        📲 Confirm Whish payment on WhatsApp
                    </a>
                <?php endif; ?>
                <a href="menu.php" class="btn-secondary-action">Back to menu</a>
            </div>

        </div>
    </div>

    <script>
        document.getElementById('copyLinkBtn').addEventListener('click', function() {
            navigator.clipboard.writeText(window.location.href).then(function() {
                var btn = document.getElementById('copyLinkBtn');
                var original = btn.textContent;
                btn.textContent = '✔ Link copied!';
                setTimeout(function() { btn.textContent = original; }, 2000);
            });
        });
    </script>
</body>
</html>