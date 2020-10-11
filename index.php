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
    <title>ModifyLK</title>
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
            <div class="bannerSlides fade" style="background-image: url('images/car_wallpaper001.jpg');background-repeat: no-repeat;background-size: cover; background-position-y: -280px;">
                <h1>Sport models</h1>
                <a href="results.php?key=sport"><h3>Read more&#10095;</h3></a>
            </div>
            <div class="bannerSlides fade" style="background-image: url('images/suv_wallpaper001.jpg');background-repeat: no-repeat;background-size: cover; background-position-y: -200px;">
                <h1>SUV models</h1>
                <a href="results.php?key=suv"><h3>Read more&#10095;</h3></a>
            </div>
            <div class="bannerSlides fade" style="background-image: url('images/hatchback_wallpaper001.jpg');background-repeat: no-repeat;background-size: cover; background-position-y: -200px;">
                <h1>Hatchback models</h1>
                <a href="results.php?key=hatchback"><h3>Read more&#10095;</h3></a>
            </div>
            <div class="bannerSlides fade" style="background-image: url('images/wagon_wallpaper001.jpg');background-repeat: no-repeat;background-size: cover; background-position-y: -200px;">
                <h1>Wagon models</h1>
                <a href="results.php?key=wagon"><h3>Read more&#10095;</h3></a>
            </div>
            <div class="bannerSlides fade" style="background-image: url('images/van_wallpaper001.jpg');background-repeat: no-repeat;background-size: cover; background-position-y: -400px;">
                <h1>Van</h1>
                <a href="results.php?key=van"><h3>Read more&#10095;</h3></a>
            </div>
        </div>

        <div class="category-container">
            <div class="category-item">
                <a href="results.php?key=bmw">
                    <div class="square" id="bmw-logo"></div>
                    <h4>BMW</h4>
                </a>
                
            </div>
            <div class="category-item">
                <a href="results.php?key=maserati">
                    <div class="square" id="maserati-logo"></div>
                    <h4>Maserati</h4>
                </a>
                
            </div>
            <div class="category-item">
                <a href="results.php?key=audi">
                    <div class="square" id="audi-logo"></div>
                    <h4>Audi</h4>
                </a>
                
            </div>
            <div class="category-item">
                <a href="results.php?key=benz">
                    <div class="square" id="benz-logo"></div>
                    <h4>Benz</h4>
                </a>
                
            </div>
            <div class="category-item">
                <a href="results.php?key=ferrari">
                    <div class="square" id="ferrari-logo"></div>
                    <h4>Ferrari</h4>
                </a>
                
            </div>
        </div>

        <div class="grid-container">

            <?php
                $number_of_items_per_page = 3;
                $sql_for_total_result = "SELECT * FROM product";
                $stmt_for_total_result = $pdo->prepare($sql_for_total_result);
                $stmt_for_total_result->execute();
                $row_for_total_result = $stmt_for_total_result->fetchAll();
                $number_of_results = count($row_for_total_result);
                if(!isset($_REQUEST['pgn'])){
                    $page_number = 1;
                }else{
                    $page_number = $_REQUEST['pgn'];
                }

                $starting_number_of_a_page = ($page_number-1)*$number_of_items_per_page;

                $sql = "SELECT * FROM product LIMIT ".$starting_number_of_a_page.",".$number_of_items_per_page;
                if($stmt = $pdo->prepare($sql)){
                    $stmt->execute();
                    while($row = $stmt->fetch()){
                        echo "<hr>";
                        echo "<div class='grid-item'>";
                        echo "<div class='grid-image'>";
                        echo "<img src='data:".$row['fmime'].";base64,".base64_encode($row['fdata'])."'>";
                        echo "</div>";
                        echo "<div class='grid-detail'>";
                        echo "<h4>".$row['name']."</h4>";
                        echo "<p>".$row['price']."</p>";
                        echo "</div>";
                        echo "</div>";
                        
                    }
                }

            ?>

        </div>
        <div class="pagination">
            <?php
                
                

                

                $number_of_pages=ceil($number_of_results/$number_of_items_per_page);
                
                $startPage = $page_number - 1;
                $endPage = $page_number + 1;
                if($startPage <=0){
                    $endPage -= ($startPage - 1);
                    $startPage = 1;
                }
                if($endPage > $number_of_pages){
                    $endPage = $number_of_pages;
                }

                if($startPage > 1) echo "<a href='index.php?pgn=1'>First</a> <span>...</span> ";
                for($i = $startPage; $i<=$endPage; $i++){
                    if($i == $page_number){
                        echo " <a href='index.php?pgn=$i' class='active'>$i</a> ";
                    }else{
                        echo " <a href='index.php?pgn=$i'>$i</a> ";
                    }
                    
                } 
                if($endPage<$number_of_pages) echo " <span>...</span> <a href='index.php?pgn=$number_of_pages'>Last</a> ";

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
            setTimeout(showSlides,3000);
        }
    </script>

    <script src="scripts/index.js"></script>
</body>
</html>