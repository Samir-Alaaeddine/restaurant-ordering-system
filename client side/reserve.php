<?php
    require_once("../cn.php");
    /** @var mysqli $link */
    $bookingSuccessMessage = "";
    $postedName = isset($_POST['txtName']) ? htmlspecialchars($_POST['txtName']) : '';
    $postedPhone = isset($_POST['txtPhone']) ? htmlspecialchars($_POST['txtPhone']) : '';$reserveError = "";

    if(isset($_POST['btnBook']))
    {
        $tableID =$_POST['selTable'];
        $name = trim($postedName);
        $phone = trim($postedPhone);

        if($name == "")
        {
            $reserveError = "Please enter your name.";
        }
        elseif (!preg_match('/^(3|70|71|76|78|79|81)[0-9]{6}$/',$phone))
        {
            $reserveError = "Phone format is incorrect.";
        }
        else
        {
            $rv_at = date("Y-m-d H:i:s");

            $sqlBook = mysqli_prepare($link, "INSERT INTO reservation (tableID, CustomerName, CustomerPhone, reserved_at, expire_at) VALUES (?, ?, ?, ?, DATE_ADD(?, INTERVAL 3 HOUR))");
            mysqli_stmt_bind_param($sqlBook, "issss", $tableID, $name,$phone, $rv_at,$rv_at);
            mysqli_stmt_execute($sqlBook);

            $sqlNum = mysqli_prepare($link, "SELECT `Number` FROM tables WHERE ID = ?");
            mysqli_stmt_bind_param($sqlNum, "i", $tableID);
            mysqli_stmt_execute($sqlNum);$resNum = mysqli_stmt_get_result($sqlNum);$rowNum = mysqli_fetch_array($resNum);$num = $rowNum[0];$holdTime = date("g:i a", strtotime("+3 hours"));

            $bookingSuccessMessage = "<div class='reservation-confirmed-box'>
                <div class='confirmed-status'>✔ Table #$num reserved for$name</div>
                <div class='confirmed-held'>Held until: $holdTime</div>
            </div>";

            $postedName = "";
            $postedPhone = "";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve a Table</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="reservation-page-body">

    <div class="reservation-card-wrapper">
        <form method="post" class="reservation-card">
            
            <div class="reservation-header">
                <h1>Make a reservation</h1>
                <p>Book your table ahead of time</p>
            </div>

            <fieldset class="reservation-fieldset">
                <?php
                    $sqlAvailable = mysqli_prepare($link, "SELECT ID,`Number`,Capacity FROM tables t WHERE NOT EXISTS(SELECT 1 FROM reservation r WHERE r.tableID = t.ID AND r.expire_at > NOW())");
                    mysqli_stmt_execute($sqlAvailable);
                    $resAvailable = mysqli_stmt_get_result($sqlAvailable);

                    if (mysqli_num_rows($resAvailable) == 0)
                    {
                        echo "<div class='no-tables-msg'>🚫 Sorry, we are completely full for now!</div>";
                    }
                    else
                    {
                        echo "<div class='customer-fields'>
                                <div class='input-group'>
                                    <label for='txtName'>Full name</label>
                                    <input type='text' id='txtName' name='txtName' value='$postedName' placeholder='e.g. John Doe'>
                                </div>
                                <div class='input-group'>
                                    <label for='txtPhone'>Phone number</label>
                                    <input type='text' id='txtPhone' name='txtPhone' value='$postedPhone' placeholder='e.g. 70123456'>
                                </div>
                              </div>";

                        echo "<div class='reservation-select-group'>
                                <label for='selTable'>Choose an available table:</label>
                                <select name='selTable' id='selTable' class='custom-select'>";
                                while($rowAvailable = mysqli_fetch_array($resAvailable))
                                {
                                    echo "<option value='$rowAvailable[0]'>Table #$rowAvailable[1] ($rowAvailable[2] seats)</option>";
                                }
                        echo "  </select>
                              </div>";

                        echo "<button type='submit' name='btnBook' class='reserve-btn'>Book now</button>";
                    }

                    if($reserveError != "")
                    {
                        echo "<p id='reserveError' class='reserve-error-msg'>$reserveError</p>";
                    }
                ?>

                <?php
                    if(!empty($bookingSuccessMessage))
                    {
                        echo $bookingSuccessMessage;
                    }
                ?>

                <div class="reserve-back-container">
                    <a href="menu.php" class="cart-continue-link">← Back to menu</a>
                </div>
            </fieldset>
        </form>
    </div>

    <script>
        var errorMsg = document.getElementById('reserveError');
        if (errorMsg) {
            errorMsg.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    </script>
</body>
</html>