<?php
    include ('functions.php');
    authorize();

    if(isset($_GET['user_id'])) {
        $id = $_GET['user_id'];

        $query_verify = "UPDATE users SET status = 2 WHERE id = $id";
    
        $res = $conn->query($query_verify);
        header('Location: users.php');
    }
?>