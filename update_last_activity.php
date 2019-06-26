<?php
    include('functions.php');

    session_start();

    $query = "UPDATE user_activities 
        SET time = CURRENT_TIMESTAMP
        WHERE user_id = '".$_SESSION["user_id"]."';";

    $res = $conn->query($query);
?>