<?php

include 'connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=AllUsersData.xls");

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
    <title>Export Data User kedalam Excel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<table>
    <tr>
        <td>User ID</td>
        <td>Username</td>
        <td>Email</td>
        <td>User Types</td>
    </tr>
    <?php
         $select_users = $conn->prepare("SELECT * FROM pengguna");
         $select_users->execute();
         while($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)){
      ?>
    <tr>
        <td><?= $fetch_users['id']; ?></td>
        <td><?= $fetch_users['nama']; ?></td>
        <td><?= $fetch_users['email']; ?></td>
        <td><?= $fetch_users['user_type']; ?></td>
    </tr>
         <?php
         }
         ?>
</table>
</body>
</html>