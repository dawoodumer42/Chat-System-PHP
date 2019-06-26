<?php
    include('functions.php');
    authorize();

    $email = $_SESSION['email'];
    $user = get_user($email);
    send_activation_code($user["activation_code"], $email);
    header('Location: verify.php?message=Activation Code Sent !');
?>