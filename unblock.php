<?php
    include ('functions.php');
    authorize();

    if(isset($_GET['user_id'])) {
        $id = $_GET['user_id'];

        if(is_verified($id))
        {
            $query_unblock = "UPDATE users SET status = 1 WHERE id = $id;";
        }
        else
        {
            $query_unblock = "UPDATE users SET status = 3 WHERE id = $id;";
        }

        $res = $conn->query($query_unblock);
        header('Location: users.php');
    }
?>