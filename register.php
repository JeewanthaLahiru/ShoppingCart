<?php

//Include config file
require_once "config.php";
//define variables and initialize with empty values
$username = $password = $confirm_password = $display_name = "";
$username_err = $password_err = $confirm_password_err = $display_name_err = "";

if($_SERVER["REQUEST_METHOD"]=="POST"){
//validate username
if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT * FROM admin WHERE username = :username";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }

    //validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password";
    }elseif(strlen(trim($_POST["password"]))<6){
        $password_err = "Password must have atleast 6 characters.";
    }else{
        $password = trim($_POST["password"]);
    }

    //Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password";
    }else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match";
        }
    }

    //validate name
    if(empty(trim($_POST["display_name"]))){
        $display_name_err = "Please insert name";
    }else{
        $display_name = trim($_POST["display_name"]);
    }

    //check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($display_name_err) && empty($confirm_password_err)){
        $role = trim($_POST['role']);
        $sql = "INSERT INTO `admin`(`username`, `password`, `displayname`, `role`) VALUES (:username, :password, :displayname, :role)";
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":username",$param_username, PDO::PARAM_STR);
            $stmt->bindParam(":password",$param_password, PDO::PARAM_STR);
            $stmt->bindParam(":displayname",$display_name, PDO::PARAM_STR);
            $stmt->bindParam(":role",$role, PDO::PARAM_STR);

            $param_username = $username;
            $param_password = md5($password);

            if($stmt->execute()){
                header("location:login.php");
            }else{
                echo "something went wrong";
            }
        }
        unset($pdo);
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/register.css?v=<?php echo time(); ?>">
    <title>Document</title>
</head>
<body>

    <div class="navBar" id="navBarId">
        <ul>
            <li class="modifylkicon"><a href="index.php" ><img src="images/modifylklogo_small.png" alt="modifylk"></a></li>
            <li class="iconLi"><a href="javascript:void(0);" class="icon" onclick="navBarFunction()"><i class="fa fa-bars"></i></a></li>
            <li><a href="#">Cart</a></li>
            <li><a href="index.php">Home</a></li>
        </ul>
        
    </div>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <input type="text" name="username" value="<?php echo $username;?>" placeholder="Username">
        <span><?php echo $username_err; ?></span><br>

        <input type="text" name="password" value="<?php echo $password; ?>" placeholder="Password">
        <span><?php echo $password_err ?></span><br>

        <input type="text" name="confirm_password" value="<?php echo $confirm_password; ?>" placeholder="Confirm Password">
        <span><?php echo $confirm_password_err ?></span><br>

        <input type="text" name="display_name" value="<?php echo $display_name; ?>" placeholder="Name for display">
        <span><?php echo $display_name_err ?></span><br>

        <input type="radio" name="role" value="admin"><label for="admin">admin</label>
        <input type="radio" name="role" value="user" checked><label for="user">user</label><br>

        <input type="submit" value="Submit">
    </form>
    
</body>
</html>