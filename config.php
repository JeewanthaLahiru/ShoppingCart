<?php

$servername = "localhost";
$username = "root";
$passowrd = "";

try{
    $pdo = new PDO("mysql:host=$servername;dbname=shoppingcart",$username,$passowrd);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    die("Error");
}

?>