<?php
    include "config.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles/index2.css?v=<?php echo time(); ?>">
    <title>Document</title>
</head>
<body>
    <div class="navBar" id="navBarId">
        <ul>
            <li class="logo"><a href="index.php"><img src="images\modifylklogo_small.png" alt=""></a></li>
            <li class="iconLi"><a href="javascript:void(0);" class="icon" onclick="responsiveNavBar()"><i class="fa fa-bars" aria-hidden="true"></i></a></li>
            <li><a href="register.php">Register</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="index.php">Home</a></li>
        </ul>
    </div>
    <div class="body">

        <div class="banner">
            <div class="bannerSlides fade" style="background-image: url('images/car_wallpaper001.jpg');background-repeat: no-repeat;background-size: 100%; background-position-y: -280px;">
                <h1>Sport models</h1>
                <a href="#"><h3>Read more&#10095;</h3></a>
            </div>
            <div class="bannerSlides fade" style="background-image: url('images/suv_wallpaper001.jpg');background-repeat: no-repeat;background-size: 100%; background-position-y: -200px;">
                <h1>SUV models</h1>
                <a href="#"><h3>Read more&#10095;</h3></a>
            </div>
            <div class="bannerSlides fade" style="background-image: url('images/hatchback_wallpaper001.jpg');background-repeat: no-repeat;background-size: 100%; background-position-y: -200px;">
                <h1>Hatchback models</h1>
                <a href="#"><h3>Read more&#10095;</h3></a>
            </div>
            <div class="bannerSlides fade" style="background-image: url('images/wagon_wallpaper001.jpg');background-repeat: no-repeat;background-size: 100%; background-position-y: -200px;">
                <h1>Wagon models</h1>
                <a href="#"><h3>Read more&#10095;</h3></a>
            </div>
        </div>

        <div class="category-container">
            <div class="category-item">
                <div class="square" id="bmw-logo"></div>
                <h4>BMW</h4>
            </div>
            <div class="category-item">
                <div class="square" id="maserati-logo"></div>
                <h4>Maserati</h4>
            </div>
            <div class="category-item">
                <div class="square" id="audi-logo"></div>
                <h4>Audi</h4>
            </div>
            <div class="category-item">
                <div class="square" id="benz-logo"></div>
                <h4>Benz</h4>
            </div>
            <div class="category-item">
                <div class="square" id="ferrari-logo"></div>
                <h4>Ferrari</h4>
            </div>
        </div>

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
    <script>
        var slideIndex = 0;
        showSlides();
        function showSlides(){
            var i;
            var slides = document.getElementsByClassName("bannerSlides");
            for(i=0;i<slides.length;i++){
                slides[i].style.display = "none";
            }
            slideIndex++;
            if(slideIndex > slides.length){slideIndex = 1}
            slides[slideIndex-1].style.display = "block";
            setTimeout(showSlides,4000);
        }
    </script>

    <script src="scripts/index.js"></script>
</body>
</html>