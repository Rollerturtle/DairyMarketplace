<?php
    require_once "stripe-php-master/init.php";

    $stripeDetails = array(
        "secretKey" => "sk_test_51M7U7SD3M58GZ8k1jFDWHZTKBackauB9EKDYPBgbu0eSmAVM5JtItfo6PipA0oCEJhRfAECefnxJXz8lpNioarhq00aeTzvhVI",
        "publishableKey" => "pk_test_51M7U7SD3M58GZ8k1RZiWUVTnI4p3CvBZkzh3hlWsrOn3HCq9Mk7JK8tcFamEXnnYkW9PIIEKttM7PkwVtqr7zYrP00AhFtl1Al"
    );

    \Stripe\Stripe::setApiKey($stripeDetails["secretKey"]);
?>