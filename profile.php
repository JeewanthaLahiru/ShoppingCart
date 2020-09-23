<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        session_start();
        
        include "db.php";

        $uname = $_REQUEST['username'];
        $pass = md5($_REQUEST['password']);
        if(isset($_REQUEST['submitBtn']) && $_REQUEST['submitBtn']=='Create account'){
            $dispname = $_REQUEST['displayname'];
            $role = $_REQUEST['role'];

            $sql = "INSERT INTO `admin`(`username`, `password`, `displayname`, `role`) VALUES ('".$uname."','".$pass."','".$dispname."','".$role."')";
            $conn->exec($sql);

        }elseif(($_REQUEST['submitBtn']) && $_REQUEST['submitBtn']=='Continue'){
            $sql = "SELECT * FROM admin WHERE username=:un AND password=:pw";
        }

        $sql2 = "SELECT * FROM admin WHERE username=:un AND password=:pw";

        $stmt = $conn->prepare($sql2);
        $stmt->bindParam(':un',$uname);
        $stmt->bindParam(':pw',$pass);
        
        $stmt->execute();
        

        $result = $stmt->fetchAll();
        foreach($result as $row){
            $loggedUserName = $row['username'];
            $loggedDisplayName = $row['displayname'];
            $loggedRole = $row['role'];
        }

        if($stmt->rowCount()==1){
            $logged = 1;
        }else{
            header("Location:index.php?er=1");
        }
        


        //echo $uname."<br>".$pass;
    ?>

    <h1>hello</h1>
    <div style="display:<?php if($loggedRole=='admin'){echo 'block';}else{echo 'none';}?>">
        <p>These are your products</p>
        <p><?php
                echo "username : ".$loggedUserName."<br>";
                echo "displayname : ".$loggedDisplayName."<br>";
                echo "role : ".$loggedRole."<br>";
            ?>
        </p>

        <h2>add sellers</h2>
        <form action="sellerSignup.php" method="post">
            seller name: <input type="text" name="sellerName" id="sellerNameId"><br>
            seller username: <input type="text" name="sellerUserName" id="sellerUserNameId"><br>
            seller password: <input type="text" name="sellerPassword" id="sellerPasswordId"><br>
            <input type="submit" value="Add seller" name="submitBtn">
        </form>
    
    </div>
    
</body>
</html>