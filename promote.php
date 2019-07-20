<?php
	include('functions.php');
 
	$id = $_POST['id'];
	$query = "UPDATE chat_room_users SET type = 'Moderator' WHERE id = $id;";
	$res = $conn->query($query);
	print json_encode($res);
?>
