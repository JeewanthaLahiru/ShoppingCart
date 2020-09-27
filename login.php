<?php

session_start();

//check uf the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}

require_once "config.php";

$username = $password = "";
$username_err = $password_err = "";

if($_SERVER["REQUEST_METHOD"]=="POST"){
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username";
    }else{
        $username = trim($_POST["username"]);
    }

    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password";
    }else{
        $password = trim($_POST["password"]);
    }

    //validate credentials
    if(empty($username_err) && empty($password_err)){
        $sql = "SELECT id,username,password,displayname,role FROM admin WHERE username = :username";

        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $param_username = trim($_POST["username"]);

            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $id = $row["id"];
                        $username = $row["username"];
                        $hashed_password = $row["password"];
                        $role = $row["role"];
                        $name = $row["displayname"];
                        if($hashed_password == md5($password)){
                            session_start();

                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["role"] = $role;
                            $_SESSION["name"] = $name;

                            header("location:welcome.php");
                        }else{
                            $password_err = "The passowrd is not valid";
                        }

                    }
                }else{
                    $username_err = "No account found with that username";
                }
            }else{
                echo "oops: something went wrong. Please try again later";
            }
            unset($stmt);
        }
    }
    unset($pdo);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <form action="<?php echo (!empty($username_err)) ? 'has-error' : ''; ?>" method="POST" enctype="multipart/form-data">
        <h1>Login</h1>
        <input type="text" name="username" id="usernameId" placeholder="Username">
        <span><?php echo $username_err; ?></span><br>
        <input type="text" name="password" id="passwordId" placeholder="Password">
        <span><?php echo $password_err; ?></span><br>
        <input type="submit" value="Continue" name="submitBtn">
    </form>
    
</body>
</html>