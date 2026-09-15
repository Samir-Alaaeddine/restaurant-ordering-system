<?php
    require_once("adminauth.php");
    require_once("cn.php");

    $sql = "SELECT t.Number, t.Capacity, u.FullName, r.reserved_at, r.expire_at
            FROM reservation r
            JOIN tables t ON r.tableID = t.ID
            JOIN user u ON r.userID = u.ID
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
    <link rel="stylesheet" href="style.css">
</head>
<body class="admin-body">
    <?php require_once("adminnav.php"); ?>
    
    <div class="page-wrap admin-wide">
        <h1>Reservations</h1>
        <fieldset>
            <legend><h2><u>Today's Active Tables</u></h2></legend>

            <table>
                <thead>
                    <tr>
                        <th>Table</th>
                        <th>Capacity</th>
                        <th>Customer</th>
                        <th>Time Slot</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if(mysqli_num_rows($res) == 0)
                    {
                        echo "<tr><td colspan='5' class='admin-empty-cell'>No active reservations for today.</td></tr>";
                    }
                    while($row = mysqli_fetch_array($res))
                    {
                        $startTime = date("g:i A", strtotime($row['reserved_at']));
                        $endTime = date("g:i A", strtotime($row['expire_at']));
                        
                        echo "<tr>
                                <td><strong>Table #{$row['Number']}</strong></td>
                                <td>{$row['Capacity']} Seats</td>
                                <td><strong>{$row['FullName']}</strong></td>
                                <td><span class='reservation-time'>$startTime – $endTime</span></td>
                                <td><span class='status-badge ready'><span class='live-dot'></span> Occupied</span></td>
                              </tr>";
                    }
                ?>
                </tbody>
            </table>
        </fieldset>
    </div>
</body>
</html>