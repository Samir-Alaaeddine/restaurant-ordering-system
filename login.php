<?php 
    session_start();
    require_once("cn.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page-wrap">
        <form method="post">
            <h1>Welcome back!</h1>
            <p class="tagline">Sip slow. Eat well. Stay awhile.</p>            
            <fieldset>
                <legend><h2><u>Login</u></h2></legend>
                <table align="center">
                    <tr>
                        <td>Phone:</td>
                        <td><input type="text" name="txtPhone" id="" placeholder="(+961)"></td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td><input type="password" name="txtPassword" id=""></td>
                    </tr>
                    <tr>
                        <td colspan="2"><input type="checkbox" name="chkRememberMe" id="">Remember me</td>
                    </tr>
                    <tr>
                        <td colspan="2"><button type="submit" name="btnLogin">Login</button></td>
                    </tr>
                    <tr>
                        <td colspan="2">Don't have an account? <a href="signup.php">Signup</a></td>
                    </tr>
                </table>
            </fieldset>
        </form>
        <?php 
            if(isset($_POST['btnLogin']))
            {
                $phone = $_POST['txtPhone'];
                $password = $_POST['txtPassword'];
                $sql = mysqli_prepare($link,"Select ID,`Password` from user where Phone=?");
                mysqli_stmt_bind_param($sql,"s",$phone);
                mysqli_stmt_execute($sql);
                $res = mysqli_stmt_get_result($sql);
                if(mysqli_num_rows($res)==0) 
                {
                    echo "<p>Incorrect phone or password!</p>";
                    return;
                }
                while($row = mysqli_fetch_array($res))
                {
                    if(password_verify($password,$row[1]))
                    {
                        if(isset($_POST['chkRememberMe']))
                        {
                            setcookie("userID",$row[0],time()+60*60*24*30);
                        }
                        $_SESSION['userID'] = $row[0];
                        header("Location:ordertype.php");
                        exit();
                    }
                    else
                    {
                        echo "<p>Incorrect phone or password!</p>";
                        return;
                    }
                }
            }
        ?>
    </div>
</body>
</html>