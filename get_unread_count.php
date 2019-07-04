<?php
    include('functions.php');

    session_start();
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT m.from_user_id, COUNT(*) as count  FROM messages m WHERE m.to_user_id = {$user_id} AND status = 'Unread' GROUP BY m.from_user_id";
    $res = $conn->query($sql);

	$rows = array();
	while($r = mysqli_fetch_assoc($res)) {
	    $rows[] = $r;
	}
	print json_encode($rows);
?>
