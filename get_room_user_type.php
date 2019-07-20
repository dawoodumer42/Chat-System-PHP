<?php
	include('functions.php');
 
 	session_start();
	$room_id = $_POST['room_id'];
	$user_id = $_SESSION['user_id'];
	//print json_encode($user_id);

	$query = "SELECT type from chat_room_users WHERE chat_room_id = $room_id AND user_id = $user_id;";
	$res = $conn->query($query);
	$out = mysqli_fetch_assoc($res);
	print json_encode($out);
?>
