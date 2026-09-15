<?php
    require_once("adminauth.php");
    require_once("cn.php");

    $categories = ["Appetizers", "Salads", "Sandwiches", "Burgers", "Pizza", "Platters", "Desserts", "Cold Beverages", "Hot Beverages"];

    $editMode = isset($_GET['dishID']);
    $dishID = $editMode ? $_GET['dishID'] : null;

    $name = ""; $desc = ""; $price = ""; $cat = $categories[0]; $img = ""; $available = 1;

    if($editMode)
    {
        $sql = mysqli_prepare($link,"SELECT Name, Description, Price, Category, ImgPath, Available FROM dish WHERE ID=?");
        mysqli_stmt_bind_param($sql,"i",$dishID);
        mysqli_stmt_execute($sql);
        $res = mysqli_stmt_get_result($sql);
        $row = mysqli_fetch_array($res);
        if(!$row)
        {
            header("Location: admin_dishes.php");
            exit();
        }
        $name = $row[0];
        $desc = $row[1];
        $price = $row[2];
        $cat = $row[3];
        $img = $row[4];
        $available = $row[5];
    }

    if(isset($_POST['btnSave']))
    {
        $name = $_POST['txtName'];
        $desc = $_POST['txtDesc'];
        $price = $_POST['txtPrice'];
        $cat = $_POST['selCategory'];
        $img = $_POST['txtImg'];
        $available = isset($_POST['chkAvailable']) ? 1 : 0;

        if($editMode)
        {
            $sqlSave = mysqli_prepare($link,"UPDATE dish SET Name=?, Description=?, Price=?, Category=?, ImgPath=?, Available=? WHERE ID=?");
            mysqli_stmt_bind_param($sqlSave,"ssdssii",$name,$desc,$price,$cat,$img,$available,$dishID);
        }
        else
        {
            $sqlSave = mysqli_prepare($link,"INSERT INTO dish(Name, Description, Price, Category, ImgPath, Available) VALUES(?,?,?,?,?,?)");
            mysqli_stmt_bind_param($sqlSave,"ssdssi",$name,$desc,$price,$cat,$img,$available);
        }
        mysqli_stmt_execute($sqlSave);
        header("Location: admin_dishes.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $editMode ? "Edit Dish" : "Add Dish"; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="admin-body">
    <?php require_once("adminnav.php"); ?>
    
    <div class="page-wrap dish-form-wrap">
        <h1><?php echo $editMode ? "Edit Dish" : "Add a New Dish"; ?></h1>
        <fieldset>
            <legend><h2><u><?php echo $editMode ? "Edit Details" : "New Dish Details"; ?></u></h2></legend>
            
            <form method="post" class="dish-management-form">
                <div class="dish-form-group">
                    <label for="txtName">Dish Name</label>
                    <input type="text" name="txtName" id="txtName" value="<?php echo htmlspecialchars($name); ?>" required placeholder="e.g. Buffalo Wings">
                </div>

                <div class="dish-form-group">
                    <label for="txtDesc">Description</label>
                    <textarea name="txtDesc" id="txtDesc" rows="3" required placeholder="Describe the dish ingredients..."><?php echo htmlspecialchars($desc); ?></textarea>
                </div>

                <div class="dish-form-row">
                    <div class="dish-form-group flex-1">
                        <label for="txtPrice">Price ($)</label>
                        <input type="number" step="0.01" min="0" name="txtPrice" id="txtPrice" value="<?php echo htmlspecialchars($price); ?>" required placeholder="0.00">
                    </div>

                    <div class="dish-form-group flex-1">
                        <label for="selCategory">Category</label>
                        <select name="selCategory" id="selCategory">
                            <?php
                                foreach($categories as $c)
                                {
                                    $sel = ($c == $cat) ? "selected" : "";
                                    echo "<option value='$c' $sel>$c</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="dish-form-group">
                    <label for="txtImg">Image Path</label>
                    <input type="text" name="txtImg" id="txtImg" value="<?php echo htmlspecialchars($img); ?>" placeholder="images/dish.jpg" required>
                </div>

                <?php if($img != ""): ?>
                    <div class="dish-preview-container">
                        <span class="preview-label">Image Preview:</span>
                        <img src="<?php echo htmlspecialchars($img); ?>" alt="Preview" class="dish-preview-img">
                    </div>
                <?php endif; ?>

                <div class="dish-checkbox-group">
                    <label>
                        <input type="checkbox" name="chkAvailable" <?php if($available) echo "checked"; ?>>
                        Available on menu
                    </label>
                </div>

                <button type="submit" name="btnSave"><?php echo $editMode ? "Save Changes" : "Add Dish"; ?></button>
            </form>

            <a href="admin_dishes.php" class="cart-continue-link">← Back to Menu Manager</a>
        </fieldset>
    </div>
</body>
</html>