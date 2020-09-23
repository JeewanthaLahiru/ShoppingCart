<?php
    session_start();

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }
    
    /*if(isset($_POST['add_product'])&&$_POST['add_product']=='Add product'){
        $default_picture_name=$_FILES['default_picture']['name'];
        $default_picture_path=$_FILES['default_picture']['tmp_name'];
        $second_picture_name=$_FILES['second_picture']['name'];
        $second_picture_path=$_FILES['second_picture']['tmp_name'];
        $third_picture_name=$_FILES['third_picture']['name'];
        $third_picture_path=$_FILES['third_picture']['tmp_name'];
        echo $default_picture_name." ".$second_picture_name." ".$third_picture_name;
    }*/

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        echo "hello";
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
    <h1>welcome</h1>
    <p><?php echo $_SESSION["username"]?></p>
    <a href="logout.php">logout</a>
    <hr>

    <div class="add_product">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
            <span for="product_name">Name</span> <input type="text" name="product_name" ><br>
            <span for="product_price">Price</span> <input type="text" name="product_price"><br>
            <span for="product_quantity">Quantity</span> <input type="text" name="product_quantity"><br>
            <span>Upload pictures</span><br>
            <h3>main picture</h3>
            <input type="file" name="default_picture">
            <h4>upload other photos</h4>
            <input type="file" name="second_picture">
            <input type="file" name="third_picture"><br><br>
            <input type="submit" value="Add product" name="add_product">

        </form>
    </div>

    <hr>
</body>
</html>