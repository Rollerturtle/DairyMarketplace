<?php
try{
    $conn = new PDO("mysql: host=localhost; dbname=marketbuah", "root", ""); 
}catch(Exception $e){
    echo "ERROR: {$e->getMessage()}";
}
?>

<head>
    <link rel="icon" type="image/x-icon" href="img/logo3.png">
</head>
