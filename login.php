<?php
    include "connect.php";
    session_start();
    if(isset($_POST['submit'])){
        $email = $_POST['email'];
        $pass = hash('sha256',hash('sha256',hash('sha256',$_POST['pass'])));

        $select = $conn->prepare('SELECT * FROM pengguna WHERE email = ? AND password = ?');
        $select->execute([$email, $pass]);
        $row = $select->fetch(PDO::FETCH_ASSOC);

        if($select->rowCount()>0){
            if($row['user_type']=='admin'){
                $_SESSION['admin_id'] = $row['id'];
                header('location:admin_page.php');
            }elseif($row['user_type']=='user'){
                $_SESSION['user_id'] = $row['id'];
                header('location:homepage.php');
            }else{
                $message[] = 'User is not listed in the database!';
            }
        }else{
            $message[]="Incorrect email or password!";
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
            <h3> Log in Account </h3>
            <input type="email" name="email" class="box" placeholder="Enter your email" required>
            <input type="password" name="pass" class="box" placeholder="Enter your password" required>
            <input type="submit" name="submit" value="Log in" class="btn">
            <p>Don't have an account?<a href="register.php">register here</a></p>
        </form>
    </section>
</body>
</html>