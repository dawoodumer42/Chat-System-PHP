<?php
	include('functions.php');
 
	$id = $_POST['id'];
	$query = "DELETE FROM chat_room_messages WHERE id = $id;";
	$res = $conn->query($query);
	//print json_encode($res);
?>
