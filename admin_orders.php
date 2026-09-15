<?php
    require_once("adminauth.php");
    require_once("cn.php");

    $validTypes = ["", "pickup", "delivery"];
    $validStatuses = ["", "Preparing", "Ready", "On the way", "Completed", "Delivered"];

    $typeFilter = isset($_GET['selType']) && in_array($_GET['selType'], $validTypes) ? $_GET['selType'] : "";
    $statusFilter = isset($_GET['selStatus']) && in_array($_GET['selStatus'], $validStatuses) ? $_GET['selStatus'] : "";

    $sql = "SELECT ID, `Type`, Method, `Status`, total, created_at FROM orders WHERE 1=1";
    $params = [];
    $types = "";

    if($typeFilter != "") {
        $sql .= " AND `Type` = ?";
        $params[] = $typeFilter;
        $types .= "s";
    }
    if($statusFilter != "") {
        $sql .= " AND `Status` = ?";
        $params[] = $statusFilter;
        $types .= "s";
    }
    $sql .= " ORDER BY ID DESC";

    $stmt = mysqli_prepare($link, $sql);
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="admin-body">
    <?php require_once("adminnav.php"); ?>
    
    <div class="page-wrap admin-wide">
        <h1>Orders Dashboard</h1>
        <fieldset>
            <legend><h2><u>All Orders</u></h2></legend>

            <!-- Compact Filter Controls -->
            <form method="get" class="admin-filter-form">
                <div class="filter-group">
                    <label for="selType">Type:</label>
                    <select name="selType" id="selType" onchange="this.form.submit()">
                        <option value="" <?php if($typeFilter=="") echo "selected"; ?>>All Types</option>
                        <option value="pickup" <?php if($typeFilter=="pickup") echo "selected"; ?>>Pickup</option>
                        <option value="delivery" <?php if($typeFilter=="delivery") echo "selected"; ?>>Delivery</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="selStatus">Status:</label>
                    <select name="selStatus" id="selStatus" onchange="this.form.submit()">
                        <option value="" <?php if($statusFilter=="") echo "selected"; ?>>All Statuses</option>
                        <?php foreach(array_slice($validStatuses, 1) as $s): ?>
                            <option value="<?php echo $s; ?>" <?php if($statusFilter==$s) echo "selected"; ?>><?php echo $s; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <!-- Orders Table -->
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Placed At</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if(mysqli_num_rows($res) == 0)
                    {
                        echo "<tr><td colspan='5' class='admin-empty-cell'>No orders found matching your filters.</td></tr>";
                    }
                    while($row = mysqli_fetch_array($res))
                    {
                        $prefix = ($row['Type'] == "pickup") ? "P" : "D";
                        $displayID = "#" . $prefix . str_pad($row['ID'], 4, "0", STR_PAD_LEFT);
                        $statusClass = strtolower(str_replace(" ", "-", $row['Status']));
                        
                        echo "<tr>
                                <td><a href='admin_order.php?orderID={$row['ID']}' class='order-link'><strong>$displayID</strong></a></td>
                                <td style='text-transform: capitalize;'>{$row['Type']}</td>
                                <td><span class='status-badge $statusClass'>{$row['Status']}</span></td>
                                <td>\$" . number_format($row['total'], 2) . "</td>
                                <td>{$row['created_at']}</td>
                              </tr>";
                    }
                ?>
                </tbody>
            </table>
        </fieldset>
    </div>
</body>
</html>