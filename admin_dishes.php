<?php
    require_once("adminauth.php");
    require_once("cn.php");

    if(isset($_GET['toggleID']))
    {
        $sqlToggle = mysqli_prepare($link,"UPDATE dish SET Available = NOT Available WHERE ID = ?");
        mysqli_stmt_bind_param($sqlToggle,"i",$_GET['toggleID']);
        mysqli_stmt_execute($sqlToggle);
        header("Location: admin_dishes.php");
        exit();
    }

    if(isset($_GET['delID']))
    {
        $sqlDel = mysqli_prepare($link,"DELETE FROM dish WHERE ID = ?");
        mysqli_stmt_bind_param($sqlDel,"i",$_GET['delID']);
        mysqli_stmt_execute($sqlDel);
        header("Location: admin_dishes.php");
        exit();
    }

    // Fetch all dishes in a single query sorted by category order
    $sql = "SELECT ID, Name, Category, Price, Available, ImgPath FROM dish ORDER BY FIELD(Category, 'Appetizers', 'Salads', 'Sandwiches', 'Burgers', 'Pizza', 'Platters', 'Desserts', 'Cold Beverages', 'Hot Beverages'), Name";
    $res = mysqli_query($link, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="admin-body">
    <?php require_once("adminnav.php"); ?>
    
    <div class="page-wrap admin-wide">
        <div class="admin-header-row">
            <h1>Menu Manager</h1>
            <!-- Consolidated Add Dish Link -->
            <a href="dish_form.php" class="admin-add-btn">+ Add new dish</a>
        </div>

        <fieldset>
            <legend><h2><u>All Dishes</u></h2></legend>

            <table>
                <thead>
                    <tr>
                        <th style="width: 60px;">Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    while($row = mysqli_fetch_array($res))
                    {
                        $availClass = $row['Available'] ? "ready" : "preparing";
                        $availText = $row['Available'] ? "Available" : "Hidden";
                        echo "<tr>
                                <td><img src='{$row['ImgPath']}' alt='Dish' class='admin-dish-thumb'></td>
                                <td><strong>{$row['Name']}</strong></td>
                                <td>{$row['Category']}</td>
                                <td>\$" . number_format($row['Price'], 2) . "</td>
                                <td><span class='status-badge $availClass'>$availText</span></td>
                                <td style='text-align: right;' class='admin-actions-cell'>
                                    <!-- Consolidated Edit Dish Link -->
                                    <a href='dish_form.php?dishID={$row['ID']}'>Edit</a>
                                    <span class='action-sep'>|</span>
                                    <a href='admin_dishes.php?toggleID={$row['ID']}'>Toggle</a>
                                    <span class='action-sep'>|</span>
                                    <a href='admin_dishes.php?delID={$row['ID']}' class='del-link' onclick=\"return confirm('Delete this dish permanently?')\">Delete</a>
                                </td>
                            </tr>";
                    }
                ?>
                </tbody>
            </table>
        </fieldset>
    </div>
</body>
</html>