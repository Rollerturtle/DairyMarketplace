<?php

include 'connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_product'])){

   $name = $_POST['name'];
   $price = $_POST['price'];
   $category = $_POST['category'];
   $details = $_POST['details'];
   
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $select_products = $conn->prepare("SELECT * FROM produk WHERE nama = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'product name already exist!';
   }else{

      $insert_products = $conn->prepare("INSERT INTO produk(nama, kategori, detail, harga, image) VALUES(?,?,?,?,?)");
      $insert_products->execute([$name, $category, $details, $price, $image]);

      if($insert_products){
         if($image_size > 2000000){
            $message[] = 'image size is too large!';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'new product added!';
         }

      }

   }

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $select_delete_image = $conn->prepare("SELECT image FROM produk WHERE id = ?");
   $select_delete_image->execute([$delete_id]);
   $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   $delete_products = $conn->prepare("DELETE FROM produk WHERE id = ?");
   $delete_products->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM wishlist WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM keranjang WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:addproduct.php');


}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Grocie. - Insert Product</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
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

<section class="add-products">

   <h1 class="title">Insert new product</h1>

   <form method="POST" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
         <input type="text" name="name" class="box" required placeholder="Enter product name">
         <select name="category" class="box" required>
            <option value="" selected disabled>Select Category</option>
               <option value="vegetables">Vegetables</option>
               <option value="fruits">Fruits</option>
               <option value="meat">Meat</option>
               <option value="dairy">Dairy</option>
         </select>
         </div>
         <div class="inputBox">
         <input type="number" min="0" name="price" class="box" required placeholder="Enter product price">
         <input type="file" name="image" required class="box" accept="image/jpg, image/jpeg, image/png">
         </div>
      </div>
      <textarea name="details" class="box" required placeholder="Enter product details" cols="30" rows="10"></textarea>
      <input type="submit" class="btn" value="Insert Product" name="add_product">
   </form>

</section>

<section class="show-products">

   <h1 class="title">Product Added</h1>

   <div class="box-container">

   <?php
      $show_products = $conn->prepare("SELECT * FROM produk");
      $show_products->execute();
      if($show_products->rowCount() > 0){
         while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <div class="box">
      <div class="price">Rp<?= $fetch_products['harga']; ?>/Kg</div>
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="name"><?= $fetch_products['nama']; ?></div>
      <div class="cat"><?= $fetch_products['kategori']; ?></div>
      <div class="details"><?= $fetch_products['detail']; ?></div>
      <div class="flex-btn">
         <a href="updProduct.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
         <a href="addproduct.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
      </div>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">now products added yet!</p>';
   }
   ?>

   </div>

   <br><br><br><br>
   <button class="button-9" role="button">
		<h2><a target="_blank" href="export_excel2.php">EXPORT TO EXCEL</a></h2>
   </button>

</section>

<script src="javascript/script.js"></script>

</body>
</html>