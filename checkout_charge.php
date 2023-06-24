<?php
    include "config.php";

    $select_cart_items = $conn->prepare("SELECT * FROM keranjang WHERE user_id = ?");
    $select_cart_items->execute([$user_id]);
    $fetch_cart_items = $select_cart_items->fetch(PDO::FETCH_ASSOC);


    $token = $_POST["stripeToken"];
    $contact_name = $_POST["name"];
    $token_card_type = $_POST["stripeTokenType"];
    $phone           = $_POST["number"];
    $email           = $_POST["stripeEmail"];
    $address         = $_POST["street"];
    $amount          = $fetch_cart_items['jumlah'];; 
    $desc            = $_POST["name"];
    $charge = \Stripe\Charge::create([
      "amount" => $amount,
      "currency" => 'sgd',
      "description"=>$desc,
      "source"=> $token,
    ]);

    if($charge){
      header("Location: ordersc.php");
    }
?>