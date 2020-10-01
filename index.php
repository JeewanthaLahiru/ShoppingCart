<?php
    include "config.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles/index2.css">
    <title>Document</title>
</head>
<body>
    <div class="navBar" id="navBarId">
        <ul>
            <li class="iconLi"><a href="javascript:void(0);" class="icon" onclick="responsiveNavBar()"><i class="fa fa-bars" aria-hidden="true"></i></a></li>
            <li><a href="register.php">Register</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="index.php">Home</a></li>
        </ul>
    </div>
    <div class="body">
        <div class="grid-container">

            <?php
            
                $sql = "SELECT * FROM products";
                if($stmt = $pdo->prepare($sql)){
                    $stmt->execute();
                    while($row = $stmt->fetch()){
                        echo "<div class='grid-item'>";
                        $sql2 = "SELECT * FROM productimages WHERE product_id=:pid";
                        if($stmt2 = $pdo->prepare($sql2)){
                            $stmt2->bindParam(":pid",$row['id']);
                            $stmt2->execute();
                            $row2 = $stmt2->fetch();
                            echo "<img class='main_image' src='data:".$row2['imagemime_one'].";base64,".base64_encode($row2['imagedata_one'])."'>";
                            echo "<div class='second_images'>";
                            echo "<img src='data:".$row2['imagemime_two'].";base64,".base64_encode($row2['imagedata_two'])."'>";
                            echo "<img src='data:".$row2['imagemime_three'].";base64,".base64_encode($row2['imagedata_three'])."'>";
                            echo "</div>";
                        }
                        echo "<h4>".$row['name']."</h4>";
                        echo "<p>".$row['price']."</p>";
                        echo "</div>";
                    }
                }
            
            ?>
        
        </div>
    
    </div>

    <script src="scripts/index.js"></script>
</body>
</html>