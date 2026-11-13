<?php
    require_once("adminauth.php");
    require_once("../cn.php");
    /** @var mysqli $link */

    $sql = "SELECT t.Number, t.Capacity, r.CustomerName, r.CustomerPhone, r.reserved_at, r.expire_at
            FROM reservation r
            JOIN tables t ON r.tableID = t.ID
            WHERE r.expire_at > NOW()
            ORDER BY r.reserved_at ASC";
    $res = mysqli_query($link, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Today's Reservations</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="admin-body">
    <?php require_once("adminnav.php"); ?>

    <div class="page-wrap admin-wide">
        <div class="admin-card">
            
            <div class="admin-card-header">
                <h1>Reservations</h1>
                <span class="admin-subtitle">Today's Active Tables</span>
            </div>

            <div class="table-responsive">
                <table class="admin-table res-table">
                    <thead>
                        <tr>
                            <th>Table</th>
                            <th>Capacity</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Time Slot</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if(mysqli_num_rows($res) == 0)
                        {
                            echo "<tr><td colspan='6' class='admin-empty-cell'>No active reservations for today.</td></tr>";
                        }
                        while($row = mysqli_fetch_array($res))
                        {
                            $startTime = date("g:i A", strtotime($row['reserved_at']));
                            $endTime = date("g:i A", strtotime($row['expire_at']));

                            echo "<tr>
                                    <td data-label='Table'><strong>Table #{$row['Number']}</strong></td>
                                    <td data-label='Capacity'>{$row['Capacity']} Seats</td>
                                    <td data-label='Customer'><strong>{$row['CustomerName']}</strong></td>
                                    <td data-label='Phone'>{$row['CustomerPhone']}</td>
                                    <td data-label='Time Slot'><span class='reservation-time'>$startTime – $endTime</span></td>
                                    <td data-label='Status'><span class='status-badge ready'><span class='live-dot'></span> Occupied</span></td>
                                </tr>";
                        }
                    ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</body>
</html>