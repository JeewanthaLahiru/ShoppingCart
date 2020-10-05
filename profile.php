<?php
    session_start();

    //if(!isset($_SESSION["logged"]) || $_SESSION["logged"]!==true){
    //    header("location:login.php");
    //    exit;
    //}
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
            <div class="profilePicture">
            </div>
            <button onclick="changeProfile()">Change profile picture</button>
            <h1>Name</h1>
            <h2>Bio</h2>
        </div>
        <div class="rightBody">
            <div class="add-product">
                <h1>add</h1>
            </div>
            <div class="add-seller">
                <h1>add</h1>
            </div>
        </div>
        <div class="update_profile_picture" id="update_profile_picture_id">
            <div class="update_profile_picture_body">
                <div class="close">
                    <a href="javascript:void(0)" onclick="changeProfile()"><i class="fa fa-times" aria-hidden="true"></i></a>
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
    </div>


    <script src="scripts/profile.js"></script>
    
</body>
</html>