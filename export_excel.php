<?php

include 'connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=DataPemesanan.xls");

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
        <td>user id</td>
        <td>placed on</td>
        <td>name</td>
        <td>email</td>
        <td>number</td>
        <td>address</td>
        <td>total products</td>
        <td>total price</td>
        <td>payment method</td>
        <td>Proof of Payment</td>
    </tr>
<?php
         $select_orders = $conn->prepare("SELECT * FROM transaksi");
         $select_orders->execute();
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
      ?>
    <tr>
        <td><?= $fetch_orders['user_id']; ?></td>
        <td><?= $fetch_orders['placed_on']; ?></td>
        <td><?= $fetch_orders['nama']; ?></td>
        <td><?= $fetch_orders['email']; ?></td>
        <td><span><?= $fetch_orders['nomor']; ?></td>
        <td><span><?= $fetch_orders['email']; ?></td>
        <td><span><?= $fetch_orders['total_produk']; ?></td>
        <td>Rp<?= $fetch_orders['total_harga']; ?></td>
        <td><?= $fetch_orders['metode']; ?></td>
        <td>
        <a href="uploaded_img/<?= $fetch_orders['image']; ?>" download>
            <img src="uploaded_img/<?= $fetch_orders['image']; ?>" alt="" height="200" width="200">
        </a>
        </td>
    </tr>
         <?php
         }
        }
         ?>
</table>
</body>
</html>