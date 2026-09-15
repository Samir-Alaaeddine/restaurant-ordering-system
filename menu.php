<?php
    require_once("authcheck.php");
    if(isset($_GET['type'])) 
    {
        $_SESSION['type'] = $_GET['type'];
    }

    if(!isset($_SESSION['type']))
    {
    header("Location:ordertype.php");
    exit();
    }
    require_once("cn.php");
    if (!isset($_SESSION['cart']))
    {
        $_SESSION['cart'] = [];
    }

    if (isset($_POST['btnAddToCart']))
    {
        $dishId = $_POST['btnAddToCart'];
        if (isset($_SESSION['cart'][$dishId]))
        {
            $_SESSION['cart'][$dishId]++;
        }
        else
        {
            $_SESSION['cart'][$dishId] = 1;
        }
        header("Location: menu.php");
        exit();
    }

    $cartCount = array_sum($_SESSION['cart']);

    $categories = ["Appetizers", "Salads", "Sandwiches", "Burgers", "Pizza", "Platters", "Desserts", "Cold Beverages", "Hot Beverages"];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
        <a href="ordertype.php" class="cart-back-link">&larr; Change order type</a>
        <form action="" method="post">
            <h1>Our menu</h1><hr>
            
            <?php 
                if($_SESSION['type']=="dine-in")
                {
                    echo "<a href='reserve.php' class='link-button'>Reserve now</a><hr>";
                }
                if($_SESSION['type']=="pickup" || $_SESSION['type']=="delivery")
                {
                    echo "<a href='cart.php' class='cart-float'><b>
                            🛒</b><span class='cart-count'>$cartCount</span>
                        </a>";
                }
            ?>
            <fieldset>
                <div class="menu-table-container">
                <table align="center">
                    <?php 
                        foreach($categories as $cat)
                        {
                            echo "<tr><td style='text-align: left;'><h2><b>$cat</b></h2><hr></td></tr>";

                            $sql = mysqli_prepare($link,"Select * from dish where Category = ? and Available = 1");
                            mysqli_stmt_bind_param($sql,"s",$cat);
                            mysqli_stmt_execute($sql);
                            $res = mysqli_stmt_get_result($sql);
                            while($row = mysqli_fetch_array($res))
                            {
                                echo "<tr class='menu-item-row'>
                                        <td class='menu-img-cell'>
                                            <img src='$row[5]' alt='Dish Image'>
                                        </td>
                                        <td class='menu-info-cell'>
                                            <div class='menu-header-line'>
                                                <h3 class='menu-item-name'>$row[1]</h3>
                                                    <span class='menu-item-price'>$row[3]$</span>
                                            </div>
                                            <p class='menu-item-desc'>$row[2]</p>
                                        </td>";
                                    if($_SESSION['type']=="pickup" || $_SESSION['type']=="delivery")
                                    {
                                        echo "<td class='menu-action-cell'>
                                                <button type='submit' name='btnAddToCart' class='add-to-cart-btn' value='$row[0]'>Add to cart</button>
                                            </td>";
                                    }
                                    echo "</tr>";
                            }
                        }
                    ?>
                </table>
                </div>
            </fieldset>
        </form>
    
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                var scrollPos = sessionStorage.getItem('menuScroll');
                if (scrollPos) {
                    window.scrollTo(0, parseInt(scrollPos));
                    sessionStorage.removeItem('menuScroll');
                }
            });

            document.querySelector('form').addEventListener('submit', function() {
                sessionStorage.setItem('menuScroll', window.scrollY);
            });
</script>
</body>
</html>