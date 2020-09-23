<?php

try{
    $servername = "localhost";
    $username = "root";
    $password = "";

    $conn = new PDO("mysql:host=$servername;dbname=shoppingcart",$username,$password);

}catch(PDOException $e){
    echo "Connection error";
}

?>