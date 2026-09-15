<?php 
    require_once("cn.php");
    if(isset($_POST['selGov'])) $gov=$_POST['selGov'];
    else $gov = 1;
    if(isset($_POST['selArea'])) $area=$_POST['selArea'];
    else $area = 1;

    if(isset($_POST['txtFullName']))
    {
        $fullname = $_POST['txtFullName'];
    }
    else
    {
        $fullname = "";
    }
    if(isset($_POST['txtPhone']))
    {
        $phone = $_POST['txtPhone'];
    }
    else
    {
        $phone = "";
    }
    if(isset($_POST['txtEmail']))
    {
        $email = $_POST['txtEmail'];
    }
    else
    {
        $email = "";
    }
    if(isset($_POST['txtPassword']))
    {
        $password = $_POST['txtPassword'];
    }
    else
    {
        $password = "";
    }
    if(isset($_POST['txtConfirm']))
    {
        $confirm = $_POST['txtConfirm'];
    }
    else
    {
        $confirm = "";
    }
    if(isset($_POST['txtStreet']))
    {
        $street = $_POST['txtStreet'];
    }
    else
    {
        $street = "";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page-wrap">
        <form method="post">
            <h1>Welcome to [Restaurant]</h1>
            <fieldset>
                <legend><h2><u>Signup</u></h2></legend>
                <table align="center">
                    <tr>
                        <td>Full name:</td>
                        <td><input type="text" name="txtFullName" id="" value="<?php echo "$fullname"; ?>" required></td>
                    </tr>
                    <tr>
                        <td>Phone:</td>
                        <td><input type="text" name="txtPhone" id="" placeholder="(+961)" value="<?php echo "$phone"; ?>" required></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><input type="email" name="txtEmail" id="" value="<?php echo "$email"; ?>" required></td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td><input type="password" name="txtPassword" id="" value="<?php echo "$password"; ?>" required></td>
                    </tr>
                    <tr>
                        <td>Confirm password:</td>
                        <td><input type="password" name="txtConfirm" id="" value="<?php echo "$confirm"; ?>" required></td>
                    </tr>
                </table>
                <div class="gov-area-row">
                    <label>Governorate:
                        <select name="selGov" id="" onchange="this.form.submit()">
                        <?php  
                            $sql1 = "Select * from governorate";
                            $res1 = mysqli_query($link,$sql1);
                            while($row1 = mysqli_fetch_array($res1))
                            {
                                $s = "";
                                if($row1[0] == $gov) $s = "selected";
                                echo "<option value='$row1[0]' $s>$row1[1]</option>";
                            }
                        ?>
                        </select>
                    </label>
                    <label>Area:
                        <select name="selArea" id="">
                        <?php   
                            $sql2 = "Select ID,Name From Area where governorateID = $gov";
                            $res2 = mysqli_query($link,$sql2);
                            while($row2 = mysqli_fetch_array($res2))
                            {
                                $s = "";
                                if($row2[0] == $area) $s = "selected";
                                echo "<option value='$row2[0]' $s>$row2[1]</option>";
                            }
                        ?>
                        </select>
                    </label>
                </div>
                <table align="center">
                    <tr>
                        <td>Street:</td>
                        <td><input type="text" name="txtStreet" id="" value="<?php echo "$street"; ?>" required></td>
                    </tr>
                    <tr>
                        <td colspan="2"><button type="submit" name="btnSignup">Signup</button></td>
                    </tr>
                    <tr>
                        <td colspan="2">Already have an account? <a href="login.php">Login</a></td>
                    </tr>
                </table>
            </fieldset>    
        </form>
        <?php 
            if(isset($_POST['btnSignup']))
            {
                $fullname = $_POST['txtFullName'];
                $phone = $_POST['txtPhone'];
                if (!preg_match('/^(3|70|71|76|78|79|81)[0-9]{6}$/', $phone))
                {
                    echo "<p>Phone format is incorrect</p>";
                    return;
                }
                $email = $_POST['txtEmail'];
                $password = $_POST['txtPassword'];
                $confirm = $_POST['txtConfirm'];
                if($password != $confirm){
                    echo "<p>Passwords do not match!</p>";
                    return;
                }
                $hash = password_hash($password,PASSWORD_DEFAULT);
                $street = $_POST['txtStreet'];
                $area = $_POST['selArea'];
                $sqlI = mysqli_prepare($link,"INSERT INTO user(FullName,Phone,Email,`Password`,Street,Area) VALUES(?,?,?,?,?,?)");
                mysqli_stmt_bind_param($sqlI,"sssssi",$fullname,$phone,$email,$hash,$street,$area);
                try {
                    $resI = mysqli_stmt_execute($sqlI);
                    header("Location:login.php");
                    exit();
                } 
                catch (mysqli_sql_exception $e)
                {
                    echo "<p>Account already exists(phone or email already used)!</p>";
                    return;
                }
            }
        ?>
    </div>
</body>
</html>