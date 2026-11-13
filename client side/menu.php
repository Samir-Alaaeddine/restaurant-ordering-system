<?php
    require_once("../cn.php");
    /** @var mysqli $link */

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

    $whatsappNumber = "96179177139";
    $mapsLink = "https://maps.app.goo.gl/CFjgUihTbmxbsiUL9";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="menu-body">

    <!-- Standalone Fixed Top-Right Cart Button -->
    <a href="cart.php" class="floating-cart-btn" title="View Cart">
        <span class="cart-icon">🛒</span>
        <span class="cart-label">Cart</span>
        <span class="cart-count"><?php echo $cartCount; ?></span>
    </a>

    <!-- Centered Category Navigation Dock -->
    <div class="top-nav-dock">
        <div class="category-nav">
            <button type="button" class="cat-filter-btn active" data-target="all">All</button>
            <?php foreach($categories as $cat): ?>
                <button type="button" class="cat-filter-btn" data-target="<?php echo $cat; ?>"><?php echo $cat; ?></button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Main Menu Container -->
    <form action="" method="post" class="menu-form-container">
        <div class="menu-main-card">
            
            <h1 class="menu-title">Our menu</h1>
            
            <div class="reserve-container">
                <a href="reserve.php" class="reserve-inline-btn">Reserve a table</a>
            </div>

            <fieldset class="menu-fieldset">
                <div class="menu-list-container">
                    <?php
                        foreach($categories as $cat)
                        {
                            echo "<div class='menu-category-section' data-category='$cat'>";
                            echo "<div class='category-header'><h2><b>$cat</b></h2><hr></div>";

                            $sql = mysqli_prepare($link,"Select * from dish where Category = ? and Available = 1");
                            mysqli_stmt_bind_param($sql,"s",$cat);
                            mysqli_stmt_execute($sql);
                            $res = mysqli_stmt_get_result($sql);
                            while($row = mysqli_fetch_array($res))
                            {
                                $imgPath = "../" . $row[5];
                                echo "<div class='menu-item-row'>
                                        <div class='menu-img-cell'>
                                            <img src='$imgPath' alt='Dish Image'>
                                        </div>
                                        <div class='menu-info-cell'>
                                            <div class='menu-header-line'>
                                                <h3 class='menu-item-name'>$row[1]</h3>
                                                <span class='menu-item-price'>$row[3]$</span>
                                            </div>
                                            <p class='menu-item-desc'>$row[2]</p>
                                        </div>
                                        <div class='menu-action-cell'>
                                            <button type='submit' name='btnAddToCart' class='add-to-cart-btn' value='$row[0]'>Add to cart</button>
                                        </div>
                                      </div>";
                            }
                            echo "</div>";
                        }
                    ?>
                </div>
            </fieldset>
        </div>
    </form>

    <div class="floating-actions">
        <div class="floating-icon-row">
            <a href="https://wa.me/<?php echo $whatsappNumber; ?>" target="_blank" class="floating-btn floating-btn-whatsapp" title="Order via WhatsApp">
                <img src="../images/whatsapp-icon.png" alt="WhatsApp">
            </a>
            <a href="<?php echo $mapsLink; ?>" target="_blank" class="floating-btn floating-btn-maps" title="Find us on Maps"><span>📍</span></a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var filterButtons = document.querySelectorAll('.cat-filter-btn');
            var sections = document.querySelectorAll('.menu-category-section');

            // Handle category filtering on click
            filterButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    filterButtons.forEach(function(b) { b.classList.remove('active'); });
                    btn.classList.add('active');

                    var target = btn.getAttribute('data-target');
                    sections.forEach(function(section) {
                        if (target === 'all' || section.getAttribute('data-category') === target) {
                            section.style.display = '';
                        } else {
                            section.style.display = 'none';
                        }
                    });
                });
            });

            // Restore scroll position after page reload
            var scrollPos = sessionStorage.getItem('menuScroll');
            if (scrollPos) {
                window.scrollTo(0, parseInt(scrollPos));
                sessionStorage.removeItem('menuScroll');
            }

            // Restore selected category tab after adding item to cart
            var savedCategory = sessionStorage.getItem('menuCategory');
            if (savedCategory) {
                var targetBtn = document.querySelector('.cat-filter-btn[data-target="' + savedCategory + '"]');
                if (targetBtn) {
                    targetBtn.click();
                }
                sessionStorage.removeItem('menuCategory');
            }
        });

        // Save scroll position and active tab before submitting cart forms
        var menuForm = document.querySelector('form');
        if (menuForm) {
            menuForm.addEventListener('submit', function() {
                sessionStorage.setItem('menuScroll', window.scrollY);
                var activeBtn = document.querySelector('.cat-filter-btn.active');
                if (activeBtn) {
                    sessionStorage.setItem('menuCategory', activeBtn.getAttribute('data-target'));
                }
            });
        }
    </script>
</body>
</html>