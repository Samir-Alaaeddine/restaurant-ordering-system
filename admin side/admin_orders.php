<?php
    require_once("adminauth.php");
    require_once("../cn.php");
    /** @var mysqli $link */

    $validTypes = ["", "pickup", "delivery"];
    $validStatuses = ["", "Preparing", "Ready", "On the way", "Completed", "Delivered"];

    $typeFilter = isset($_GET['selType']) && in_array($_GET['selType'], $validTypes) ?$_GET['selType'] : "";
    $statusFilter = isset($_GET['selStatus']) && in_array($_GET['selStatus'], $validStatuses) ?$_GET['selStatus'] : "";

    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    if($page < 1)$page = 1;
    $perPage = 10;
    $offset = ($page - 1) *$perPage;

    $whereClause = " WHERE 1=1";
    $params = [];$types = "";

    if($typeFilter != "") {
        $whereClause .= " AND `Type` = ?";
        $params[] =$typeFilter;
        $types .= "s";
    }
    if($statusFilter != "") {
        $whereClause .= " AND `Status` = ?";
        $params[] =$statusFilter;
        $types .= "s";
    }

    // Count total matching rows first, so we know how many pages exist
    $sqlCount = "SELECT COUNT(*) FROM orders" . $whereClause;
    $stmtCount = mysqli_prepare($link,$sqlCount);
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmtCount, $types, ...$params);
    }
    mysqli_stmt_execute($stmtCount);$resCount = mysqli_stmt_get_result($stmtCount);$totalOrders = mysqli_fetch_array($resCount)[0];$totalPages = max(1, ceil($totalOrders / $perPage));

    if($page >$totalPages) $page =$totalPages;
    $offset = ($page - 1) *$perPage;

    // Then fetch just this page's rows
    $sql = "SELECT ID, `Type`, Method, `Status`, total, created_at FROM orders" . $whereClause . " ORDER BY ID DESC LIMIT ? OFFSET ?";
    $pagedParams =$params;
    $pagedParams[] =$perPage;
    $pagedParams[] =$offset;
    $pagedTypes =$types . "ii";

    $stmt = mysqli_prepare($link,$sql);
    mysqli_stmt_bind_param($stmt, $pagedTypes, ...$pagedParams);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    // Build the query string once so Prev/Next/filter changes all preserve each other
    function buildPageLink($p, $typeFilter,$statusFilter)
    {
        $q = ["page" => $p];
        if($typeFilter != "") $q["selType"] = $typeFilter;
        if($statusFilter != "") $q["selStatus"] = $statusFilter;
        return "admin_orders.php?" . http_build_query($q);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="admin-body">
    <?php require_once("adminnav.php"); ?>

    <div class="page-wrap admin-wide orders-page-wrap">
        <div class="admin-page-header">
            <h1>Orders Dashboard</h1>
            <p class="admin-subtitle">Track, filter, and manage incoming customer orders</p>
        </div>

        <div class="admin-card orders-card">
            <div class="orders-card-header">
                <h2>All Orders</h2>

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
                            <?php foreach(array_slice($validStatuses, 1) as$s): ?>
                                <option value="<?php echo $s; ?>" <?php if($statusFilter==$s) echo "selected"; ?>><?php echo $s; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="admin-orders-table">
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
                                    <td data-label='Order ID'><a href='admin_order.php?orderID={$row['ID']}' class='order-link'><strong>$displayID</strong></a></td>
                                    <td data-label='Type' style='text-transform: capitalize;'>{$row['Type']}</td>
                                    <td data-label='Status'><span class='status-badge $statusClass'>{$row['Status']}</span></td>
                                    <td data-label='Total'>\$" . number_format($row['total'], 2) . "</td>
                                    <td data-label='Placed At'>{$row['created_at']}</td>
                                </tr>";
                        }
                    ?>
                    </tbody>
                </table>
            </div>

            <div class="admin-pagination">
                <?php if($page > 1): ?>
                    <a href="<?php echo buildPageLink($page - 1, $typeFilter,$statusFilter); ?>" class="pagination-link">« Previous</a>
                <?php else: ?>
                    <span class="pagination-link disabled">« Previous</span>
                <?php endif; ?>

                <span class="pagination-info">Page <?php echo $page; ?> of <?php echo$totalPages; ?></span>

                <?php if($page <$totalPages): ?>
                    <a href="<?php echo buildPageLink($page + 1, $typeFilter,$statusFilter); ?>" class="pagination-link">Next »</a>
                <?php else: ?>
                    <span class="pagination-link disabled">Next »</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>