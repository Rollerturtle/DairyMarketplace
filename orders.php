<?php

include 'connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['update_order'])){

   $order_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   $update_orders = $conn->prepare("UPDATE transaksi SET status_pembayaran = ? WHERE id = ?");
   $update_orders->execute([$update_payment, $order_id]);
   $message[] = 'Payment has been updated!';

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_orders = $conn->prepare("DELETE FROM transaksi WHERE id = ?");
   $delete_orders->execute([$delete_id]);
   header('location:orders.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders List</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <link rel="stylesheet" href="css/admin.css">

   <style>
.button-9 {
  appearance: button;
  backface-visibility: hidden;
  background-color: rgba(39,174,96,255);
  border-radius: 6px;
  border-width: 0;
  box-shadow: rgba(39,174,96,255) 0 0 0 1px inset,rgba(39,174,96,255) 0 2px 5px 0,rgba(39,174,96,255) 0 1px 1px 0;
  box-sizing: border-box;
  color: #fff;
  cursor: pointer;
  font-family: -apple-system,system-ui,"Segoe UI",Roboto,"Helvetica Neue",Ubuntu,sans-serif;
  font-size: 100%;
  height: 44px;
  line-height: 1.15;
  margin: 12px 0 0;
  outline: none;
  overflow: hidden;
  padding: 0 25px;
  position: relative;
  text-align: center;
  text-transform: none;
  transform: translateZ(0);
  transition: all .2s,box-shadow .08s ease-in;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
  width: 50%;
  top: 50%;
  left: 50%;
  -ms-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
}

.button-9:disabled {
  cursor: default;
}

.button-9:focus {
  box-shadow: rgba(39,174,96,255) 0 0 0 1px inset, rgba(39,174,96,255) 0 6px 15px 0, rgba(39,174,96,255) 0 2px 2px 0, rgba(39,174,96,255) 0 0 0 4px;
}
   </style>
</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="placed-orders">

   <h1 class="title">placed orders</h1>

   <div class="box-container">

      <?php
         $select_orders = $conn->prepare("SELECT * FROM transaksi");
         $select_orders->execute();
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="box">
         <p> user id : <span><?= $fetch_orders['user_id']; ?></span> </p>
         <p> placed on : <span><?= $fetch_orders['placed_on']; ?></span> </p>
         <p> name : <span><?= $fetch_orders['nama']; ?></span> </p>
         <p> email : <span><?= $fetch_orders['email']; ?></span> </p>
         <p> number : <span><?= $fetch_orders['nomor']; ?></span> </p>
         <p> address : <span><?= $fetch_orders['alamat']; ?></span> </p>
         <p> total products : <span><?= $fetch_orders['total_produk']; ?></span> </p>
         <p> total price : <span>Rp<?= $fetch_orders['total_harga']; ?></span> </p>
         <p> payment method : <span><?= $fetch_orders['metode']; ?></span> </p>
         <p> proof of payment :</p>
         <a href="uploaded_img/<?= $fetch_orders['image']; ?>" download>
            <img src="uploaded_img/<?= $fetch_orders['image']; ?>" alt="" height="200" width="200">
         </a>
         <form action="" method="POST">
            <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
            <select name="update_payment" class="drop-down">
               <option value="" selected disabled hidden><?= $fetch_orders['status_pembayaran']; ?></option>
               <option value="pending">pending</option>
               <option value="shipped">shipped</option>
               <option value="completed">completed</option>
            </select>
            <div class="flex-btn">
               <input type="submit" name="update_order" class="option-btn" value="update">
               <a href="orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
            </div>
         </form>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no orders placed yet!</p>';
      }
      ?>

   </div>
      <br><br><br><br>
   <button class="button-9" role="button">
		<h2><a target="_blank" href="export_excel.php">EXPORT TO EXCEL</a></h2>
   </button>
</section>













<script src="javascript/script.js"></script>

</body>
</html>