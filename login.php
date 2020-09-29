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
    <link rel="stylesheet" href="styles/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lobster+Two:ital@1&display=swap" rel="stylesheet">
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

    <form action="<?php echo (!empty($username_err)) ? 'has-error' : ''; ?>" method="POST" enctype="multipart/form-data">
        <table>
            <caption><h1>Login</h1></caption>
            <tr>
                <td class="tags"><h4>Username :</h4></td>
                <td><input type="text" name="username" id="usernameId" placeholder="Username"><span><?php echo $username_err; ?></span></td>
            </tr>
            <tr>
                <td class="tags"><h4>Password :</h4></td>
                <td><input type="text" name="password" id="passwordId" placeholder="Password"><span><?php echo $password_err; ?></span></td>
            </tr>
        </table>
        <input type="submit" value="Continue" name="submitBtn">
    </form>


    <script src="scripts/login.js"></script>
    
</body>
</html>