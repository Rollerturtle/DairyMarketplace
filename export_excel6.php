<?php

include 'connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=DataFeedback.xls");

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
        <td>Name</td>
        <td>Number</td>
        <td>Email</td>
        <td>Messages</td>
    </tr>
    <?php
      $select_message = $conn->prepare("SELECT * FROM pesan");
      $select_message->execute();
      if($select_message->rowCount() > 0){
         while($fetch_message = $select_message->fetch(PDO::FETCH_ASSOC)){
    ?>
    <tr>
        <td><?= $fetch_message['user_id']; ?></td>
        <td><?= $fetch_message['nama']; ?></td>
        <td><?= $fetch_message['nomor']; ?></td>
        <td><?= $fetch_message['email']; ?></td>
        <td><?= $fetch_message['pesan']; ?></td>
    </tr>
         <?php
         }
        }
         ?>
</table>
</body>
</html>