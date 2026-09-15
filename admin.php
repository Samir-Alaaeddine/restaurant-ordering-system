<?php
    require_once("adminauth.php");
    require_once("cn.php");

    $sqlActive = "Select count(*) from orders where Status in ('Preparing','Ready','On the way')";
    $resActive = mysqli_query($link,$sqlActive);
    $rowActive = mysqli_fetch_array($resActive);
    $activeOrders = $rowActive[0];

    $sqlTables = "Select count(*) from reservation where expire_at > NOW()";
    $resTables = mysqli_query($link,$sqlTables);
    $rowTables = mysqli_fetch_array($resTables);
    $tablesInUse = $rowTables[0];

    $sqlHidden = "Select count(*) from dish where Available = 0";
    $resHidden = mysqli_query($link,$sqlHidden);
    $rowHidden = mysqli_fetch_array($resHidden);
    $hiddenDishes = $rowHidden[0];

    $sqlRecent = "Select ID,`Type`,Method,`Status`,total from orders order by ID desc limit 5";
    $resRecent = mysqli_query($link,$sqlRecent);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="admin-body">
    <?php require_once("adminnav.php"); ?>
    
    <div class="page-wrap admin-wide">
        <h1>Welcome back, admin</h1>
        <fieldset>
            <legend><h2><u>Overview</u></h2></legend>

            <div class="admin-stat-group">
                <div class="admin-stat-card">
                    <span class="admin-stat-number"><?php echo $activeOrders; ?></span>
                    <span class="admin-stat-label">Active orders</span>
                </div>
                <div class="admin-stat-card">
                    <span class="admin-stat-number"><?php echo $tablesInUse; ?></span>
                    <span class="admin-stat-label">Tables in use</span>
                </div>
                <div class="admin-stat-card">
                    <span class="admin-stat-number"><?php echo $hiddenDishes; ?></span>
                    <span class="admin-stat-label">Menu items hidden</span>
                </div>
            </div>

            <h3>Recent orders</h3>
            <table>
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    while($row = mysqli_fetch_array($resRecent))
                    {
                        $prefix = ($row[1] == "pickup") ? "P" : "D";
                        $displayID = "#" . $prefix . str_pad($row[0], 4, "0", STR_PAD_LEFT);
                        $statusClass = strtolower(str_replace(" ", "-", $row[3]));
                        echo "<tr>
                                <td><a href='admin_order.php?orderID=$row[0]'>$displayID</a></td>
                                <td>" . ucfirst($row[1]) . "</td>
                                <td><span class='status-badge $statusClass'>$row[3]</span></td>
                                <td>\$$row[4]</td>
                              </tr>";
                    }
                ?>
                </tbody>
            </table>
        </fieldset>
    </div>
</body>
</html>