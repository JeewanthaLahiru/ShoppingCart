<?php
    session_start();

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }

    include "config.php";

    $product_name = $product_price = $product_quantity = $first_image_name = $second_image_name = $third_image_name = $keywords = "";
    $product_name_err = $product_price_err = $product_quantity_err = $upload_picture_err = $keywords_error = "";

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if(empty(trim($_POST["product_name"]))){
            $product_name_err = "please enter a product name";
        }else{
            $product_name = trim($_POST["product_name"]);
        }

        if(empty(trim($_POST["product_price"]))){
            $product_price_err = "please enter a product price";
        }else{
            $product_price = trim($_POST["product_price"]);
        }

        if(empty(trim($_POST["product_quantity"]))){
            $product_quantity_err = "please enter a product quantity";
        }else{
            $product_quantity = trim($_POST["product_quantity"]);
        }

        if(empty(trim($_POST["keywords"]))){
            $keywords_error = "please enter key words";
        }else{
            $keywords = $_POST["keywords"];
        }

        if(empty(trim($_FILES["default_picture"]["name"])) || empty(trim($_FILES["second_picture"]["name"])) || empty(trim($_FILES["third_picture"]["name"]))){
            $upload_picture_err = "please upload three pictures";
        }else{
            $first_image_name = $_FILES["default_picture"]["name"];
            $first_image_path = $_FILES["default_picture"]["tmp_name"];
            $first_image_cmps = explode(".",$first_image_name);
            $first_image_Extention = strtolower(end($first_image_cmps));
            $first_image_new_name = md5(time().$first_image_name).'.'.$first_image_Extention;

            $second_image_name = $_FILES["second_picture"]["name"];
            $second_image_path = $_FILES["second_picture"]["tmp_name"];
            $second_image_cmps = explode(".",$second_image_name);
            $second_image_Extention = strtolower(end($second_image_cmps));
            $second_image_new_name = md5(time().$second_image_name).'.'.$second_image_Extention;

            $third_image_name = $_FILES["third_picture"]["name"];
            $third_image_path = $_FILES["third_picture"]["tmp_name"];
            $third_image_cmps = explode(".",$third_image_name);
            $third_image_Extention = strtolower(end($third_image_cmps));
            $third_image_new_name = md5(time().$third_image_name).'.'.$third_image_Extention;
        }

        if(empty($product_name_err) && empty($product_price_err) && empty($product_quantity_err && empty($upload_picture_err)) && empty($keywords_error)){
            $uploadFileDir = './images/';
            $dest_path_one = $uploadFileDir.$first_image_new_name;
            $dest_path_two = $uploadFileDir.$second_image_new_name;
            $dest_path_three = $uploadFileDir.$third_image_new_name;
            
            move_uploaded_file($first_image_path,$dest_path_one);
            move_uploaded_file($second_image_path,$dest_path_two);
            move_uploaded_file($third_image_path,$dest_path_three);

            $sql_for_owner_id = "SELECT id FROM admin WHERE username = :username";
            if($stmt = $pdo->prepare($sql_for_owner_id)){
                $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
                $param_username = trim($_SESSION["username"]);
                if($stmt->execute()){
                    if($stmt->rowcount()==1){
                        if($row = $stmt->fetch()){
                            $ownerId = $row['id'];
                        }
                    }
                }
                unset($stmt);
            }
            


            $sql = "INSERT INTO `products`(`name`, `price`, `quantity`, `imageOne`, `imageTwo`, `imageThree`, `keywords`, `ownerId`) VALUES (:productName, :productPrice, :productQuantity, :imageOne, :imageTwo, :imageThree, :keywords, :ownerId)";
            if($stmt = $pdo->prepare($sql)){
                $stmt->bindParam(":productName",$product_name, PDO::PARAM_STR);
                $stmt->bindParam(":productPrice",$product_price, PDO::PARAM_STR);
                $stmt->bindParam(":productQuantity",$product_quantity, PDO::PARAM_STR);
                $stmt->bindParam(":imageOne",$first_image_new_name, PDO::PARAM_STR);
                $stmt->bindParam(":imageTwo",$second_image_new_name, PDO::PARAM_STR);
                $stmt->bindParam(":imageThree",$third_image_new_name, PDO::PARAM_STR);
                $stmt->bindParam(":keywords",$keywords, PDO::PARAM_STR);
                $stmt->bindParam(":ownerId",$ownerId, PDO::PARAM_STR);
                if($stmt->execute()){
                    header("location:welcome.php");
                }else{
                    echo "something went wrong";
                }
            }

            
        }

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
            <span for="product_name">Name</span> <input type="text" name="product_name" value="<?php echo $product_name; ?>">
            <span><?php echo $product_name_err; ?></span><br>

            <span for="product_price">Price</span> <input type="text" name="product_price" value="<?php echo $product_price; ?>">
            <span><?php echo $product_price_err; ?></span><br>

            <span for="product_quantity">Quantity</span> <input type="text" name="product_quantity" value="<?php echo $product_quantity; ?>">
            <span><?php echo $product_quantity_err; ?></span><br>

            <span>Upload pictures</span><br>
            <h3>main picture</h3>
            <input type="file" name="default_picture">
            <h4>upload other photos</h4>
            <input type="file" name="second_picture">
            <input type="file" name="third_picture"><br><br>
            <span><?php echo $upload_picture_err ?></span><br>

            <span>Key words</span>
            <input type="text" name="keywords" id="keywords_id" value="<?php echo $keywords ?>">
            <span><?php echo $keywords_error ?></span>

            <input type="submit" value="Add product" name="add_product">

        </form>
    </div>

    <hr>
</body>
</html>