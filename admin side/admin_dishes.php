<?php
    require_once("adminauth.php");
    require_once("../cn.php");
    /** @var mysqli $link */

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

    $categories = ["Appetizers", "Salads", "Sandwiches", "Burgers", "Pizza", "Platters", "Desserts", "Cold Beverages", "Hot Beverages"];

    $sql = "SELECT ID, Name, Category, Price, Available, ImgPath FROM dish ORDER BY FIELD(Category, 'Appetizers', 'Salads', 'Sandwiches', 'Burgers', 'Pizza', 'Platters', 'Desserts', 'Cold Beverages', 'Hot Beverages'), Name";
    $res = mysqli_query($link, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Manager</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="admin-body">
    <?php require_once("adminnav.php"); ?>

    <div class="page-wrap admin-wide">
        <div class="admin-header-row">
            <div>
                <h1>Menu Manager</h1>
                <p class="admin-subtitle">Organize dishes, categories, and item availability</p>
            </div>
            <a href="dish_form.php" class="admin-add-btn">+ Add new dish</a>
        </div>

        <div class="category-nav admin-category-nav">
            <button type="button" class="cat-filter-btn active" data-target="all">All</button>
            <?php foreach($categories as $cat): ?>
                <button type="button" class="cat-filter-btn" data-target="<?php echo $cat; ?>"><?php echo $cat; ?></button>
            <?php endforeach; ?>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h2 id="dishesHeading">All Dishes</h2>
            </div>

            <div class="table-responsive">
                <table class="admin-dishes-table">
                    <thead>
                        <tr>
                            <th style="width: 70px;">Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="dishTableBody">
                    <?php
                        while($row = mysqli_fetch_array($res))
                        {
                            $availClass = $row['Available'] ? "ready" : "preparing";
                            $availText = $row['Available'] ? "Available" : "Hidden";
                            $toggleText = $row['Available'] ? "Hide" : "Show";
                            $imgPath = !empty($row['ImgPath']) ? "../" . htmlspecialchars($row['ImgPath']) : "../images/default.jpg";

                            echo "<tr class='dish-row' data-category='" . htmlspecialchars($row['Category']) . "'>
                                <td data-label='Image'>
                                    <img src='$imgPath' alt='" . htmlspecialchars($row['Name']) . "' class='admin-dish-thumb' style='width: 48px; height: 48px; object-fit: cover; border-radius: 8px;'>
                                </td>
                                <td data-label='Name'><strong>" . htmlspecialchars($row['Name']) . "</strong></td>
                                <td data-label='Category'>" . htmlspecialchars($row['Category']) . "</td>
                                <td data-label='Price'>\$" . number_format($row['Price'], 2) . "</td>
                                <td data-label='Status'><span class='status-badge $availClass'>$availText</span></td>
                                <td data-label='Actions' class='actions-cell'>
                                    <div class='actions-cell-wrap'>
                                        <a href='dish_form.php?dishID={$row['ID']}' class='dish-action-link btn-sm btn-edit'>Edit</a>
                                        <a href='admin_dishes.php?toggleID={$row['ID']}' class='dish-action-link btn-sm btn-toggle'>$toggleText</a>
                                        <a href='admin_dishes.php?delID={$row['ID']}' class='dish-action-link btn-sm btn-delete' onclick=\"return confirm('Delete this dish?');\">Delete</a>
                                    </div>
                                </td>
                            </tr>";
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        var filterButtons = document.querySelectorAll('.cat-filter-btn');
        var rows = document.querySelectorAll('.dish-row');
        var heading = document.getElementById('dishesHeading');

        function applyFilter(target) {
            rows.forEach(function(row) {
                if (target === 'all' || row.getAttribute('data-category') === target) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            heading.textContent = (target === 'all') ? 'All Dishes' : target;
        }

        filterButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                filterButtons.forEach(function(b) { b.classList.remove('active'); });
                btn.classList.add('active');
                applyFilter(btn.getAttribute('data-target'));
            });
        });

        window.addEventListener('DOMContentLoaded', function() {
            var scrollPos = sessionStorage.getItem('dishScroll');
            if (scrollPos) {
                window.scrollTo(0, parseInt(scrollPos));
                sessionStorage.removeItem('dishScroll');
            }

            var savedCategory = sessionStorage.getItem('dishCategory');
            if (savedCategory) {
                var targetBtn = document.querySelector('.cat-filter-btn[data-target="' + savedCategory + '"]');
                if (targetBtn) {
                    targetBtn.click();
                }
                sessionStorage.removeItem('dishCategory');
            }
        });

        document.querySelectorAll('.dish-action-link').forEach(function(link) {
            link.addEventListener('click', function() {
                sessionStorage.setItem('dishScroll', window.scrollY);
                var activeBtn = document.querySelector('.cat-filter-btn.active');
                if (activeBtn) {
                    sessionStorage.setItem('dishCategory', activeBtn.getAttribute('data-target'));
                }
            });
        });
    </script>
</body>
</html>