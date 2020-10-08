<?php
    session_start();
    include "config.php";

    //if(!isset($_SESSION["logged"]) || $_SESSION["logged"]!==true){
    //    header("location:login.php");
    //    exit;
    //}
    $profile_err = $bio_err = "";
    $profile_picture_name = $profile_picture_type = $profile_picture_data = $bio = "";
    if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_REQUEST["profile_pic_update"])){
        if($_FILES["profile_picture"]["size"]==0){
            $profile_err = "1";
        }else{
            $profile_picture_name = $_FILES["profile_picture"]["name"];
            $profile_picture_type = $_FILES["profile_picture"]["type"];
            $profile_picture_data = file_get_contents($_FILES["profile_picture"]["tmp_name"]);
        }

        if(empty(trim($_REQUEST["bio"]))){
            $bio_err = "1";
        }else{
            $bio = $_REQUEST["bio"];
        }

        if(empty($profile_err) && empty($bio_err)){
            $sql = "INSERT INTO `profilepictures`(`name`, `mime`, `data`, `ownerid`, `bio`) VALUES (:name,:mime,:data,:ownerid,:bio)";
            if($stmt = $pdo->prepare($sql)){
                $stmt->bindParam(":name",$profile_picture_name);
                $stmt->bindParam(":mime",$profile_picture_type);
                $stmt->bindParam(":data",$profile_picture_data);
                $stmt->bindParam(":ownerid",$_SESSION["id"]);
                $stmt->bindParam(":bio",$bio);
                if($stmt->execute()){
                    header("location:profile.php?msg=1");
                }else{
                    echo "error";
                }
            }else{
                echo "error";
            }
        }
    }
    $prname_err = $prprice_err = $prquantity_err = $primage_err = $prkeyword_err = "";
    $prname = $prprice = $prquantity = $prkeyword = "";
    if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_REQUEST["add_product"])){
        if(empty(trim($_REQUEST["product_name"]))){
            $prname_err = "1";
        }else{
            $prname = $_REQUEST["product_name"];
        }

        if(empty(trim($_REQUEST["product_price"]))){
            $prprice_err = "1";
        }else{
            $prprice = $_REQUEST["product_price"];
        }

        if(empty(trim($_REQUEST["product_quantity"]))){
            $prquantity_err = "1";
        }else{
            $prquantity = $_REQUEST["product_quantity"];
        }

        if($_FILES["first_image"]["size"]==0 || $_FILES["second_image"]["size"]==0 || $_FILES["third_image"]["size"]==0){
            $primage_err = "1";
        }else{
            $first_mime = $_FILES["first_image"]["type"];
            $second_mime = $_FILES["second_image"]["type"];
            $third_mime = $_FILES["third_image"]["type"];

            $first_data = file_get_contents($_FILES["first_image"]["tmp_name"]);
            $second_data = file_get_contents($_FILES["second_image"]["tmp_name"]);
            $third_data = file_get_contents($_FILES["third_image"]["tmp_name"]);
        }

        if(empty(trim($_REQUEST["product_keywords"]))){
            $prkeyword_err = "1";
        }else{
            $prkeyword = $_REQUEST["product_keywords"];
        }

        if(empty($prname_err) && empty($prprice_err) && empty($prquantity_err) && empty($primage_err) && empty($prkeyword_err)){
            $sql3 = "INSERT INTO `product`(`name`, `price`, `quantity`, `fmime`, `smime`, `tmime`, `fdata`, `sdata`, `tdata`, `keywords`, `ownerid`) VALUES (:name,:price,:quantity,:fmime,:smime,:tmime,:fdata,:sdata,:tdata,:keywords,:ownerid)";
            if($stmt3 = $pdo->prepare($sql3)){
                $stmt3->bindParam(":name",$prname);
                $stmt3->bindParam(":price",$prprice);
                $stmt3->bindParam(":quantity",$prquantity);
                $stmt3->bindParam(":fmime",$first_mime);
                $stmt3->bindParam(":smime",$second_mime);
                $stmt3->bindParam(":tmime",$third_mime);
                $stmt3->bindParam(":fdata",$first_data);
                $stmt3->bindParam(":sdata",$second_data);
                $stmt3->bindParam(":tdata",$third_data);
                $stmt3->bindParam(":keywords",$prkeyword);
                $stmt3->bindParam(":ownerid",$_SESSION["id"]);
                if($stmt3->execute()){
                    header("location:profile.php?msg=11");
                }else{
                    echo "error";
                }
            }else{
                echo "error";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles/profile.css?v=<?php echo time(); ?>">
    <title>Profile</title>
</head>
<body>

    <div class="navBar">
            <a href="javascript:void(0);" onclick="navBarFunction()" id="icon"><i class="fa fa-bars"></i></a>
            <a href="logout.php">Logout</a>
            <a href="#">My cart</a>
            <a href="index.php">Home</a>
    </div>

    <div class="body">
        <div class="leftBody">
            <?php
                $sql2 = "SELECT * FROM profilepictures WHERE ownerid=:ownerid";
                $stmt2 = $pdo->prepare($sql2);
                    $stmt2->bindParam(":ownerid",$_SESSION["id"]);
                    $stmt2->execute();
                    $row2 = $stmt2->fetch();
                    
                
            ?>
            <div class="profilePicture" style="background-image: url('<?php echo "data:" . $row2['mime'] . ";base64," . base64_encode($row2['data']); ?>');background-repeat: no-repeat; background-size: cover;">
            </div>
            <p><?php echo $profile_err; ?></p>
            <button onclick="changeProfile()"><i class="fa fa-wrench" aria-hidden="true"></i> Update</button>
            <h1><?php echo $_SESSION["name"]; ?></h1>
            <h2><?php echo $row2['bio']; ?></h2>
        </div>
        <div class="rightBody">
            <div class="add-product">
                <h1>Add product</h1>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
                    <input type="text" name="product_name" id="" placeholder="Name">
                    <input type="text" name="product_price" id="" placeholder="Price">
                    <input type="text" name="product_quantity" id="" placeholder="Quantity">
                    <label for="first_image_id"><i class="fa fa-camera" aria-hidden="true"></i> Upload image</label>
                    <input type="file" name="first_image" id="first_image_id">
                    <label for="second_image_id"><i class="fa fa-camera" aria-hidden="true"></i> Second image</label>
                    <input type="file" name="second_image" id="second_image_id">
                    <label for="third_image_id"><i class="fa fa-camera" aria-hidden="true"></i> Third image</label>
                    <input type="file" name="third_image" id="third_image_id">
                    <input type="text" name="product_keywords" id="" placeholder="Keywords">
                    <input type="submit" value="Add product" name="add_product">
                </form>
            </div>
            <hr>
            <div class="add-seller">
                <h1>add</h1>
            </div>
        </div>
        
    </div>

    <div class="update_profile_picture" id="update_profile_picture_id">
            <div class="update_profile_picture_body">
                <div class="close">
                    <a href="javascript:void(0)" onclick="changeProfile()" class="closeBtn"><i class="fa fa-times" aria-hidden="true"></i></a>
                </div>
                <h1>Update profile details</h1>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <td>
                                <input type="file" name="profile_picture" id=""><br>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="bio" id="" placeholder="Add bio"><br>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="submit" value="Update" name="profile_pic_update">
                            </td>
                        </tr>
                    </table> 
                </form>
            </div>
            
        </div>


    <script src="scripts/profile.js"></script>
    
</body>
</html>