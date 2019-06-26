<?php
    include ('functions.php');
    authorize();
    if(isset($_GET['user_id'])) {
        $id = $_GET['user_id'];
        $query_block = "UPDATE users SET status = 0 WHERE id = $id;";
        $res = $conn->query($query_block);
        header('Location: users.php');
    }
?>