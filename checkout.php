<?php

include 'connect.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['order'])){

   $name = $_POST['name'];
   $number = $_POST['number'];
   $email = $_POST['email'];
   $method = $_POST['method'];
   $address = $_POST['flat'] .' '. $_POST['street'] .' '. $_POST['city'] .' '. $_POST['state'] .' '. $_POST['country'] .' - '. $_POST['pin_code'];

   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   
   $placed_on = date('d-M-Y');
   
   $cart_total = 0;
   $cart_products[] = '';

   $cart_query = $conn->prepare("SELECT * FROM keranjang WHERE user_id = ?");
   $cart_query->execute([$user_id]);
   if($cart_query->rowCount() > 0){
      while($cart_item = $cart_query->fetch(PDO::FETCH_ASSOC)){
         $cart_products[] = $cart_item['nama'].' ( '.$cart_item['jumlah'].' )';
         $sub_total = ($cart_item['harga'] * $cart_item['jumlah']);
         $cart_total += $sub_total;
      };
   };

   $total_products = implode(', ', $cart_products);

   $order_query = $conn->prepare("SELECT * FROM transaksi WHERE nama = ? AND nomor = ? AND email = ? AND metode = ? AND alamat = ? AND total_produk = ? AND total_harga = ? AND image = ?");
   $order_query->execute([$name, $number, $email, $method, $address, $total_products, $cart_total, $image]);

   if($cart_total == 0){
      $message[] = 'your cart is empty';
   }elseif($order_query->rowCount() > 0){
      $message[] = 'order placed already!';
   }else{
      $insert_order = $conn->prepare("INSERT INTO transaksi(user_id, nama, nomor, email, metode, alamat, total_produk, total_harga, placed_on, image) VALUES(?,?,?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $cart_total, $placed_on, $image]);
      move_uploaded_file($image_tmp_name, $image_folder);
      $delete_cart = $conn->prepare("DELETE FROM keranjang WHERE user_id = ?");
      $delete_cart->execute([$user_id]);
      $message[] = 'order placed successfully!';
   }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <link rel="stylesheet" href="css/style2.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="display-orders">

   <?php
      $cart_grand_total = 0;
      $select_cart_items = $conn->prepare("SELECT * FROM keranjang WHERE user_id = ?");
      $select_cart_items->execute([$user_id]);
      if($select_cart_items->rowCount() > 0){
         while($fetch_cart_items = $select_cart_items->fetch(PDO::FETCH_ASSOC)){
            $cart_total_price = ($fetch_cart_items['harga'] * $fetch_cart_items['jumlah']);
            $cart_grand_total += $cart_total_price;
   ?>
   <p> <?= $fetch_cart_items['nama']; ?> <span>(<?= 'Rp'.$fetch_cart_items['harga'].' x '. $fetch_cart_items['jumlah']; ?>)</span> </p>
   <?php
    }
   }else{
      echo '<p class="empty">your cart is empty!</p>';
   }
   ?>
   <div class="grand-total">grand total : <span>Rp<?= $cart_grand_total; ?></span></div>
</section>

<section class="checkout-orders">

   <form method="POST" action="<?php $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">

      <h3>Place Your Order</h3>
      <div class="flex">
         <div class="inputBox">
            <span>Your Name :</span>
            <input type="text" name="name" placeholder="enter your name" class="box" required>
         </div>
         <div class="inputBox">
            <span>Your Phone Number :</span>
            <input type="number" name="number" placeholder="enter your number" class="box" required>
         </div>
         <div class="inputBox">
            <span>Your E-mail :</span>
            <input type="email" name="email" placeholder="enter your email" class="box" required>
         </div>
         <div class="inputBox">
            <span>Payment Method :</span>
            <select name="method" class="box" required>
               <option value="Cash on Delivery">Cash on Delivery</option>
               <option value="BRI">BRI</option>
               <option value="IBBRI">Internet Banking BRI</option>
               <option value="BRImo">BRImo</option>
               <option value="BCA">BCA</option>
               <option value="IBBCA">Internet Banking BCA</option>
               <option value="m-BCA">M-BCA</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Address Line 01 :</span>
            <input type="text" name="flat" placeholder="e.g. flat number" class="box" required>
         </div>
         <div class="inputBox">
            <span>Address Line 02 :</span>
            <input type="text" name="street" placeholder="e.g. street name" class="box" required>
         </div>
         <div class="inputBox">
            <span>City :</span>
            <input type="text" name="city" placeholder="e.g. Bogor" class="box" required>
         </div>
         <div class="inputBox">
            <span>State :</span>
            <input type="text" name="state" placeholder="e.g. West Java" class="box" required>
         </div>
         <div class="inputBox">
            <span>Country :</span>
            <input type="text" name="country" placeholder="e.g. Indonesia" class="box" required>
         </div>
         <div class="inputBox">
            <span>Postal Code :</span>
            <input type="number" min="0" name="pin_code" placeholder="e.g. 52111" class="box" required>
         </div>
         <div class="inputBox">
            <span>Proof of Payment :</span>
            <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png" required>
         </div>
      </div>
      <a href="TATACARATRANSFERPEMBAYARAN.pdf" download><h2 style="text-align: center;">Click here for payment methods details!</h2></a>
      
      <input type="submit" name="order" class="btn <?= ($cart_grand_total > 1)?'':'disabled'; ?>" value="place order">

   </form>

</section>








<?php include 'footer.php'; ?>

<script src="javascript/script.js"></script>

</body>
</html>