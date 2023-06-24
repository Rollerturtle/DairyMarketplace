<?php
    include "connect.php";
    if(isset($_POST['submit'])){
        $nama = $_POST['nama'];
        $email = $_POST['email'];
        $pass = hash('sha256',hash('sha256',hash('sha256',$_POST['pass'])));
        $cpass = hash('sha256',hash('sha256',hash('sha256',$_POST['cpass'])));
    
        $image = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'uploaded_img/'.$image;

        $select = $conn->prepare('SELECT * FROM pengguna WHERE email = ?');
        $select->execute([$email]);

        if($select->rowCount()>0){
            $message[] = "Email already used in the database";
        }else{
            if($pass != $cpass){
                $message[] = "Confirm password does not match the password input!";
            }else{
                $insert = $conn->prepare('INSERT INTO pengguna (nama, email, password, image) VALUES (?, ?, ?, ?)');
                $insert->execute([$nama, $email, $pass, $image]);
                
                if($insert){
                    if($image_size > 2000000){
                        $image[] = "Image size is too large!";
                    }else{
                        move_uploaded_file($image_tmp_name, $image_folder);
                        $message[] = "Registered Succesfully!"; 
                        header('location:login.php');
                    }
                }
            }
        }

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grocie. - Affordable, Fresh and Healthy</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    <?php
        if(isset($message)){
            foreach($message as $message){
                echo "
                <div class='message'>
                    <span>$message</span>
                    <i class='fas fa-times' onclick='this.parentElement.remove()'></i>
                </div>
                ";
            }
        }
    ?>
    <img class="background" src="img/back1.jpg">
    <section class="form-container">
        <form enctype="multipart/form-data" method="post">
            <h3> Registration </h3>
            <input type="text" name="nama" class="box" placeholder="Enter your name" required>
            <input type="email" name="email" class="box" placeholder="Enter your email" required>
            <input type="password" name="pass" class="box" placeholder="Enter your password" required>
            <input type="password" name="cpass" class="box" placeholder="Confirm your password" required>
            <input type="file" name="image" class="box" required accept="image/jpg, image/png, image/jpeg">
            <input type="submit" name="submit" value="Register" class="btn">
            <p>Already have an account?<a href="login.php">login here</a></p>
        </form>
    </section>
</body>
</html>