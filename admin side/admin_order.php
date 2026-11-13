<?php
    require_once("adminauth.php");
    require_once("../cn.php");
    /** @var mysqli $link */

    if(!isset($_GET['orderID']))
    {
        header("Location: admin_orders.php");
        exit();
    }
    $orderID =$_GET['orderID'];

    if(isset($_POST['btnUpdateStatus']))
    {
        $newStatus =$_POST['selStatus'];
        $sqlUpdate = mysqli_prepare($link,"UPDATE orders SET `Status` = ? WHERE ID = ?");
        mysqli_stmt_bind_param($sqlUpdate,"si",$newStatus,$orderID);
        mysqli_stmt_execute($sqlUpdate);
        header("Location: admin_order.php?orderID=" . $orderID);
        exit();
    }

    $sqlO = mysqli_prepare($link,"SELECT `Type`,Method,created_at,total,`Status`,CustomerName,CustomerPhone,Area,Address
                                   from orders where ID = ?");
    mysqli_stmt_bind_param($sqlO,"i",$orderID);
    mysqli_stmt_execute($sqlO);
    $resO = mysqli_stmt_get_result($sqlO);
    $rowO = mysqli_fetch_array($resO);

    if(!$rowO)
    {
        header("Location: admin_orders.php");
        exit();
    }

    $type =$rowO[0];
    $method =$rowO[1];
    $dateO =$rowO[2];
    $total =$rowO[3];
    $currStatus =$rowO[4];
    $custName =$rowO[5];
    $custPhone =$rowO[6];
    $areaName =$rowO[7];
    $street =$rowO[8];

    if($type == "pickup")
    {
        $statuses = ["Preparing","Ready","Completed"];
    }
    else
    {
        $statuses = ["Preparing","On the way","Delivered"];
    }

    $prefix = ($type == "pickup") ? "P" : "D";
    $displayID = "#" . $prefix . str_pad($orderID, 4, "0", STR_PAD_LEFT);

    $sqlItem = mysqli_prepare($link,"SELECT quantity,price_at_order,`Name` from orderitems oi,dish d where oi.dishID = d.ID and oi.orderID = ?");
    mysqli_stmt_bind_param($sqlItem,"i",$orderID);
    mysqli_stmt_execute($sqlItem);
    $resItem = mysqli_stmt_get_result($sqlItem);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order <?php echo $displayID; ?></title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="admin-body">
    <?php require_once("adminnav.php"); ?>

    <div class="page-wrap admin-order-wrap">
        <div class="admin-card">
            
            <div class="admin-card-header">
                <h1>Order <?php echo $displayID; ?></h1>
                <span class="admin-subtitle">Order Details</span>
            </div>

            <div class="admin-order-meta">
                <div class="meta-row"><span>Placed:</span> <strong><?php echo $dateO; ?></strong></div>
                <div class="meta-row"><span>Type:</span> <strong style="text-transform:capitalize;"><?php echo $type; ?></strong></div>
                <?php if($method): ?>
                <div class="meta-row"><span>Payment:</span> <strong><?php echo $method; ?></strong></div>
                <?php endif; ?>
                <div class="meta-row"><span>Customer:</span> <strong><?php echo $custName; ?></strong></div>
                <div class="meta-row"><span>Phone:</span> <strong><?php echo $custPhone; ?></strong></div>
                <?php if($type == "delivery"): ?>
                <div class="meta-row"><span>Address:</span> <strong><?php echo "$street,$areaName"; ?></strong></div>
                <?php endif; ?>
            </div>

            <div class="admin-divider"></div>

            <h3 class="admin-section-title">Purchased Items</h3>
            <div class="admin-order-items">
                <?php
                    while($rowItem = mysqli_fetch_array($resItem))
                    {
                        $lineCost = $rowItem[0] *$rowItem[1];
                        echo "<div class='admin-item-row'>
                                <span class='item-name'>$rowItem[2] <small>x$rowItem[0]</small></span>
                                <span class='item-price'>\$" . number_format($lineCost,2) . "</span>
                              </div>";
                    }
                ?>
                <div class="admin-total-row">
                    <span>Total</span>
                    <span>$<?php echo number_format($total,2); ?></span>
                </div>
            </div>

            <div class="admin-divider"></div>

            <h3 class="admin-section-title">Update Order Status</h3>
            <div class="admin-status-box">
                <div class="status-current-line">
                    <span>Current Status:</span>
                    <span class="status-badge <?php echo strtolower(str_replace(" ","-",$currStatus)); ?>"><?php echo $currStatus; ?></span>
                </div>
                <form method="post" class="admin-status-form">
                    <select name="selStatus" class="admin-select">
                        <?php
                            foreach($statuses as$s)
                            {
                                $sel = ($s ==$currStatus) ? "selected" : "";
                                echo "<option value='$s' $sel>$s</option>";
                            }
                        ?>
                    </select>
                    <button type="submit" name="btnUpdateStatus" class="admin-btn-primary">Update Status</button>
                </form>
            </div>

            <div class="admin-card-footer">
                <a href="admin_orders.php" class="back-link">← Back to orders</a>
            </div>

        </div>
    </div>
</body>
</html>