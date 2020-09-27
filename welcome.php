<?php
    session_start();

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }

    include "config.php";

    $product_name = $product_price = $product_quantity = $first_image_name = $second_image_name = $third_image_name = $keywords = "";
    $product_name_err = $product_price_err = $product_quantity_err = $upload_picture_err = $keywords_error = "";

    if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_REQUEST['add_product'])){
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

    $seller_name = $seller_username = $seller_password = $seller_password_confirm = "";
    $seller_name_err = $seller_username_err = $seller_password_err = "";

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_REQUEST["seller_submit"])){
        if(empty(trim($_REQUEST['seller_name']))){
            $seller_name_err = "Please enter seller name";
        }else{
            $seller_name = trim($_REQUEST['seller_name']);
        }

        if(empty(trim($_REQUEST['seller_username']))){
            $seller_username_err = "Please enter seller username";
        }else{
            $seller_username_sql = "SELECT * FROM admin WHERE username = :username";
            if($stmt = $pdo->prepare($seller_username_sql)){
                $stmt->bindParam(":username",$param_seller_username,PDO::PARAM_STR);
                $param_seller_username = $_REQUEST["seller_username"];
                if($stmt->execute()){
                    if($stmt->rowcount() == 1){
                        $seller_username_err = "This username is already taken";
                    }else{
                        $seller_username = $_REQUEST["seller_username"];
                    }
                }
                unset($stmt);
            }
        }

        if(empty(trim($_REQUEST["seller_password"])) || empty(trim($_REQUEST["seller_password_confirm"]))){
            $seller_password_err = "Please enter passwords correctly";
        }else{
            if($_REQUEST["seller_password"]!=$_REQUEST["seller_password_confirm"]){
                $seller_password_err = "Passwords are not matching";
            }else{
                $seller_password = trim($_REQUEST["seller_password"]);
            }
        }

        if(empty($seller_name_err) && empty($seller_username_err) && empty($seller_password_err)){
            $sql_for_add_seller = "INSERT INTO `admin`(`username`, `password`, `displayname`, `role`) VALUES (:sellerUsername, :sellerPassword, :sellerName, :sellerRole)";
            if($stmt = $pdo->prepare($sql_for_add_seller)){
                $stmt->bindParam(":sellerUsername",$param_seller_username_next, PDO::PARAM_STR);
                $stmt->bindParam(":sellerPassword",$param_seller_password, PDO::PARAM_STR);
                $stmt->bindParam(":sellerName", $param_seller_name, PDO::PARAM_STR);
                $stmt->bindParam(":sellerRole", $param_seller_role, PDO::PARAM_STR);
                $param_seller_username_next = trim($_REQUEST["seller_username"]);
                $param_seller_password = md5(trim($_REQUEST["seller_password"]));
                $param_seller_name = trim($_REQUEST["seller_name"]);
                $param_seller_role = "seller";

                if($stmt->execute()){
                    header("location:welcome.php");
                }else{
                    echo "something went wrong";
                }
            }
        }
    }
    $profile_pic_error = "";
    if($_SERVER["REQUEST_METHOD"]=="POST" && $_REQUEST["change_picture"]){
        if(isset($_FILES['profile_picture_upload']) && $_FILES['profile_picture_upload']['error'] === UPLOAD_ERR_OK){
            $sql_for_get_profile_image2 = 'SELECT * FROM `profilepicture` WHERE ownerId = (SELECT id FROM admin WHERE username = :username)';
            if($stmt = $pdo->prepare($sql_for_get_profile_image2)){
                $stmt->bindParam(':username',$_SESSION['username'],PDO::PARAM_STR);
                if($stmt->execute()){
                    if($stmt->rowcount() == 1){
                        $sql2 = 'UPDATE `profilepicture` SET `profilepicture`=:profilePicture WHERE ownerId = (SELECT id FROM admin WHERE username = :username2 )';
                    }else{
                        $sql3 = 'INSERT INTO `profilepicture`(`profilepicture`, `ownerId`) VALUES (:profilePicture,:newId)';
                    }
                }
                unset($stmt);
            }
            
            
        }else{
            $profile_pic_error = "First, upload a picture";
        }
        if(empty($profile_pic_error)){
            $profile_pic_name = $_FILES['profile_picture_upload']['name'];
            $profile_pic_path = $_FILES['profile_picture_upload']['tmp_name'];
            $profile_pic_cmps = explode(".",$profile_pic_name);
            $profile_pic_extension = strtolower(end($profile_pic_cmps));
            $new_profile_pic_name = md5(time().$profile_pic_name).'.'.$profile_pic_extension;
            $uploadFileDir = './images/';
            $dest_path_profile_pic = $uploadFileDir.$new_profile_pic_name;
            move_uploaded_file($profile_pic_path,$dest_path_profile_pic);

            if($sql2){
                if($stmt = $pdo->prepare($sql2)){
                    $stmt->bindParam(":username2",$_SESSION["username"],PDO::PARAM_STR);
                    $stmt->bindParam(":profilePicture",$new_profile_pic_name, PDO::PARAM_STR);
                    if($stmt->execute()){
                        header("location:welcome.php");
                    }else{
                        echo "something went wrong";
                    }
                    unset($stmt);
    
                }
            }
            if($sql3){
                if($stmt = $pdo->prepare($sql3)){
                    $stmt->bindParam(":newId",$_SESSION["id"],PDO::PARAM_STR);
                    $stmt->bindParam(":profilePicture",$new_profile_pic_name, PDO::PARAM_STR);
                    if($stmt->execute()){
                        header("location:welcome.php");
                    }else{
                        echo "something went wrong";
                    }
                    unset($stmt);
    
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lobster+Two:ital@1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/welcome.css">
    <title>Document</title>
</head>
<body>

    <div class="navBar">
        <ul>
            <li><a href="logout.php"><i class="fa fa-sign-in" aria-hidden="true"></i> Logout</a></li>
            <li id="myAccount"><a href="#"><i class="fa fa-user" aria-hidden="true"></i> My account</a></li>
            <li><a href="#"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Cart</a></li>
            <li><a href="#"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
            <li id="modifyLogo"><a href="index.php"><img src="images/modifylklogo_small.png" alt=""></a></li>
        </ul>
    </div>

    <div class="body">
        <div class="left_section">
            <div class="profile_picture" style="background: url('images/<?php 
                include 'config.php';
                $sql_for_get_profile_image='SELECT * FROM `profilepicture` WHERE ownerId = (SELECT id FROM admin WHERE username = :username)';
                if($stmt = $pdo->prepare($sql_for_get_profile_image)){
                    $stmt->bindParam(':username',$_SESSION['username'],PDO::PARAM_STR);
                    if($stmt->execute()){
                        if($stmt->rowcount() == 1){
                            if($row_new = $stmt->fetch()){
                                echo $row_new['profilepicture'];
                            }
                        }else{
                            echo 'placeholder.png';
                        }
                    }
                }
            ?>') 50% 50% no-repeat; background-size:200px 200px">
                <div class="upload_profile_pic">
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
                        <div class="photo_uploading">
                            <input type="file" name="profile_picture_upload" id="profile_picture_id" accept="image/*">
                            <label for="profile_picture_id"><i class="fa fa-upload" aria-hidden="true"></i>Upload</label>
                            <input type="submit" value="update picture" name="change_picture">
                            
                        </div>
                    </form>
                </div>
                
            </div>
            <span><p><?php echo $profile_pic_error?></p></span>
            <h1><?php echo $_SESSION["name"]; ?></h1>
        </div>
        <div class="right_section">
            <div class="add_product" style="display:<?php echo ($_SESSION["role"]=='admin' || $_SESSION["role"]=='seller')?'block':'none'; ?>">
                <h1>ADD PRODUCT</h1>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <td>
                                <span for="product_name"><h4>Name</h4></span>
                                <span><?php echo $product_name_err; ?></span>
                            </td>
                            <td>
                            <input type="text" name="product_name" value="<?php echo $product_name; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span for="product_price"><h4>Price</h4></span> 
                                <span><?php echo $product_price_err; ?></span>
                            </td>
                            <td>
                            <input type="text" name="product_price" value="<?php echo $product_price; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span for="product_quantity"><h4>Quantity</h4></span>
                                <span><?php echo $product_quantity_err; ?></span>
                            </td>
                            <td>
                            <input type="text" name="product_quantity" value="<?php echo $product_quantity; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><h4>Upload pictures</h4></span>
                            </td>
                            <td>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><h4>Main picture</h4></span>
                            </td>
                            <td>
                                <input type="file" name="default_picture">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><h4>Upload two more pictures</h4></span>
                            </td>
                            <td>
                                <span><?php echo $upload_picture_err ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                
                            </td>
                            <td>
                                <input type="file" name="third_picture"><br>
                                <input type="file" name="second_picture">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><h4>Key words</h4></span>
                                <span><?php echo $keywords_error ?></span>
                            </td>
                            <td>
                                <input type="text" name="keywords" id="keywords_id" value="<?php echo $keywords ?>">
                            </td>
                        </tr>
                    </table>
                    <input type="submit" value="Add product" name="add_product">

                </form>
            </div>

            <hr>

            <div class="add_seller" style="display:<?php echo ($_SESSION['role'] == 'admin')? 'block':'none'; ?>">
                <h1>Add seller</h1>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <td>
                                <span><h4>Name :</h4></span>
                                <span><p><?php echo $seller_name_err ?></p></span>
                            </td>
                            <td>
                                <input type="text" name="seller_name" id="">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><h4>Username :</h4></span>
                                <span><p><?php echo $seller_username_err ?></p></span>
                            </td>
                            <td>
                                <input type="text" name="seller_username" id="">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><h4>Password :</h4></span>
                                <span><p><?php echo $seller_password_err ?></p></span>
                            </td>
                            <td>
                                <input type="password" name="seller_password" id="">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><h4>Confirm Password :</h4></span>
                            </td>
                            <td>
                                <input type="password" name="seller_password_confirm" id="">
                            </td>
                        </tr>
                    </table>
                    <input type="submit" value="Add seller" name="seller_submit">
                </form>
            </div>
        </div>
    </div>

    
</body>
</html>