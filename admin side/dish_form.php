<?php
    require_once("adminauth.php");
    require_once("../cn.php");
    /** @var mysqli $link */

    $categories = ["Appetizers", "Salads", "Sandwiches", "Burgers", "Pizza", "Platters", "Desserts", "Cold Beverages", "Hot Beverages"];
    $allowedExt = ["jpg", "jpeg", "png", "webp"];

    $editMode = isset($_GET['dishID']);
    $dishID = $editMode ? $_GET['dishID'] : null;

    $name = ""; $desc = ""; $price = ""; $cat = $categories[0]; $img = ""; $available = 1;
    $imgError = "";

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
        $available = isset($_POST['chkAvailable']) ? 1 : 0;

        if(isset($_FILES['fileImg']) && $_FILES['fileImg']['error'] == UPLOAD_ERR_OK)
        {
            $filename = $_FILES['fileImg']['name'];
            $tmpName = $_FILES['fileImg']['tmp_name'];
            $parts = explode('.', $filename);
            $ext = strtolower(end($parts));

            if(!in_array($ext, $allowedExt))
            {
                $imgError = "Please upload a JPG, PNG, or WEBP image.";
            }
            elseif(getimagesize($tmpName) === false)
            {
                $imgError = "That file doesn't look like a valid image.";
            }
            else
            {
                $savename = uniqid('dish_') . "." . $ext;
                move_uploaded_file($tmpName, "../images/" . $savename);
                $img = "images/" . $savename;
            }
        }
        elseif($editMode)
        {
            $img = $_POST['hdnCurrentImg']; // no new file chosen — keep the existing image
        }
        else
        {
            $imgError = "Please choose an image for this dish.";
        }

        if($imgError == "")
        {
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
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $editMode ? "Edit Dish" : "Add Dish"; ?></title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="admin-body">
    <?php require_once("adminnav.php"); ?>

    <div class="page-wrap dish-form-wrap">
        <div class="admin-form-header">
            <h1><?php echo $editMode ? "Edit Dish" : "Add a New Dish"; ?></h1>
            <p class="admin-subtitle"><?php echo $editMode ? "Update dish details and availability" : "Fill in the details to add an item to the menu"; ?></p>
        </div>

        <div class="admin-card dish-form-card">
            <h2 class="dish-card-title"><?php echo $editMode ? "Edit Details" : "New Dish Details"; ?></h2>

            <form method="post" class="dish-management-form" enctype="multipart/form-data">
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
                    <label>Dish Photo</label>
                    
                    <!-- CUSTOM FILE UPLOAD DROPZONE -->
                    <div class="custom-file-dropzone">
                        <input type="file" name="fileImg" id="fileImg" accept="image/png, image/jpeg, image/webp" <?php echo $editMode ? '' : 'required'; ?> onchange="handleFileSelect(this)">
                        <div class="dropzone-content">
                            <svg class="dropzone-icon" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                            <span class="dropzone-title" id="fileDropLabel">Click to upload or drag & drop</span>
                            <span class="dropzone-sub">PNG, JPG, or WEBP</span>
                        </div>
                    </div>

                    <input type="hidden" name="hdnCurrentImg" value="<?php echo htmlspecialchars($img); ?>">
                    <?php if($editMode): ?>
                        <small class="dish-form-hint">Leave empty to keep the current photo.</small>
                    <?php endif; ?>
                </div>

                <?php if($imgError != ""): ?>
                    <p class="dish-form-error"><?php echo htmlspecialchars($imgError); ?></p>
                <?php endif; ?>

                <?php if($img != ""): ?>
                    <div class="dish-preview-container">
                        <span class="preview-label">Current / Selected Photo:</span>
                        <div class="preview-img-wrapper">
                            <img id="dishPreview" src="../<?php echo htmlspecialchars($img); ?>" alt="Preview" class="dish-preview-img">
                        </div>
                    </div>
                <?php endif; ?>

                <div class="dish-checkbox-group">
                    <label class="custom-checkbox-label">
                        <input type="checkbox" name="chkAvailable" <?php if($available) echo "checked"; ?>>
                        <span>Available on menu</span>
                    </label>
                </div>

                <button type="submit" name="btnSave" class="btn-primary-action"><?php echo $editMode ? "Save Changes" : "Add Dish"; ?></button>
            </form>

            <div class="dish-form-footer">
                <a href="admin_dishes.php" class="back-link">← Back to Menu Manager</a>
            </div>
        </div>
    </div>

    <script>
        function handleFileSelect(input) {
            const label = document.getElementById('fileDropLabel');
            const preview = document.getElementById('dishPreview');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                label.textContent = file.name;
                label.style.fontWeight = '700';

                // Instant image preview
                if (preview) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            }
        }
    </script>
</body>
</html>