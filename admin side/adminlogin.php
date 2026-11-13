<?php 
    session_start();
    require_once("../cn.php");
    /** @var mysqli $link */

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="page-wrap">
        <form method="post">
            <h1>Admin Login</h1>
            <fieldset>
                <legend><h2><u>Login</u></h2></legend>
                <table align="center">
                    <tr>
                        <td>Username:</td>
                        <td><input type="text" name="txtUsername" id=""></td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td><input type="password" name="txtPassword" id=""></td>
                    </tr>
                    <tr>
                        <td colspan="2"><button type="submit" name="btnLogin">Login</button></td>
                    </tr>
                </table>
            </fieldset>
        </form>
        <?php 
            if(isset($_POST['btnLogin']))
            {
                $username = $_POST['txtUsername'];
                $password = $_POST['txtPassword'];
                $sql = mysqli_prepare($link,"Select ID,`Password` from admin where Username=?");
                mysqli_stmt_bind_param($sql,"s",$username);
                mysqli_stmt_execute($sql);
                $res = mysqli_stmt_get_result($sql);
                if(mysqli_num_rows($res)==0) 
                {
                    echo "<p>Incorrect username or password!</p>";
                    return;
                }
                while($row = mysqli_fetch_array($res))
                {
                    if(password_verify($password,$row[1]))
                    {
                        $_SESSION['adminID'] = $row[0];
                        header("Location:admin.php");
                        exit();
                    }
                    else
                    {
                        echo "<p>Incorrect username or password!</p>";
                        return;
                    }
                }
            }
        ?>
    </div>
</body>
</html>