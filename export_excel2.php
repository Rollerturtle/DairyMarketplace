<?php

include 'connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=DataProduk.xls");

if(!isset($admin_id)){
   header('location:login.php');
};
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Data Pemesanan kedalam Excel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<table>
    <tr>
        <td>Product Price</td>
        <td>Product Name</td>
        <td>Product Category</td>
        <td>Product Details</td>
    </tr>
    <?php
      $show_products = $conn->prepare("SELECT * FROM produk");
      $show_products->execute();
      if($show_products->rowCount() > 0){
         while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
    ?>
    <tr>
        <td>Rp<?= $fetch_products['harga']; ?></td>
        <td><?= $fetch_products['nama']; ?></td>
        <td><?= $fetch_products['kategori']; ?></td>
        <td><?= $fetch_products['detail']; ?></td>
    </tr>
         <?php
         }
        }
         ?>
</table>
</body>
</html>