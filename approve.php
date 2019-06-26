<?php
    include ('functions.php');
    authorize();
    if(isset($_GET['user_id'])) {
        $id = $_GET['user_id'];
        $query_approve = "UPDATE users SET status = 1, approved_at = CURRENT_TIMESTAMP WHERE id = $id and approved_at is NULL;";
        $res = $conn->query($query_approve);
        header('Location: users.php');
    }
?>