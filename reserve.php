<?php
    require_once("authcheck.php");
    require_once("cn.php");

    $bookingSuccessMessage = ""; 

    if(isset($_POST['btnBook']))
    {
        $tableID = $_POST['selTable'];
        $userID = $_SESSION['userID'];
        $rv_at = date("Y-m-d H:i:s");
        
        $sqlBook = mysqli_prepare($link, "INSERT INTO reservation (tableID, userID, reserved_at, expire_at) VALUES (?, ?, ?, DATE_ADD(?, INTERVAL 3 HOUR))");    
        mysqli_stmt_bind_param($sqlBook, "iiss", $tableID, $userID, $rv_at, $rv_at);
        mysqli_stmt_execute($sqlBook);

        $sqlName = mysqli_prepare($link,"SELECT FullName from user where ID=?");
        mysqli_stmt_bind_param($sqlName,"i",$userID);
        mysqli_stmt_execute($sqlName);
        $resName = mysqli_stmt_get_result($sqlName);
        $rowName = mysqli_fetch_array($resName);
        $name = $rowName[0];

        $sqlNum = mysqli_prepare($link, "SELECT `Number` FROM tables WHERE ID = ?");
        mysqli_stmt_bind_param($sqlNum, "i", $tableID);
        mysqli_stmt_execute($sqlNum);
        $resNum = mysqli_stmt_get_result($sqlNum);
        $rowNum = mysqli_fetch_array($resNum);
        $num = $rowNum[0];
        $holdTime = date("g:i a", strtotime("+3 hours"));

        $bookingSuccessMessage = "<div class='reservation-confirmed-box'>
            <div class='confirmed-status'>✔ Table#$num reserved for $name</div>
            <div class='confirmed-held'>Held until: $holdTime</div>
        </div>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="post">
        <h1>Make a reservation!</h1>
        <fieldset>
            <legend><h2><u>Reserve a table</u></h2></legend>
            <?php 
                $sqlAvailable = mysqli_prepare($link, "SELECT ID,`Number`,Capacity FROM tables t WHERE NOT EXISTS(SELECT 1 FROM reservation r WHERE r.tableID = t.ID AND r.expire_at > NOW())");                    
                mysqli_stmt_execute($sqlAvailable);
                $resAvailable = mysqli_stmt_get_result($sqlAvailable);
                
                if (mysqli_num_rows($resAvailable) == 0) 
                {
                    echo "<p>🚫 Sorry, we are completely full for now!</p>";
                } 
                else 
                {
                    echo "<div class='reservation-select-group'>
                            <label for='selTable'>Choose an available table:</label>
                            <select name='selTable' id='selTable'>";
                            while($rowAvailable = mysqli_fetch_array($resAvailable))
                            {
                                echo "<option value='$rowAvailable[0]'>Table#$rowAvailable[1] - $rowAvailable[2] seats</option>";
                            }
                    echo "  </select>
                          </div>";
                    
                    echo "<button type='submit' name='btnBook'>Book now</button>";
                }
            ?>
            
            <?php 
                if(!empty($bookingSuccessMessage))
                {
                    echo $bookingSuccessMessage;
                }
            ?>
            
            <div style="text-align: center; margin-top: 18px;">
                <a href="menu.php" class="cart-continue-link">Back to menu</a>
            </div>
        </fieldset>
    </form>
</body>
</html>
