<?php
include ('functions.php');

session_start();
if(isset($_POST['id'])) {
	$user_id =  $_SESSION['user_id'];
	$other_id = $_POST['id'];
	$num = $_POST['num'];
	$type = $_POST['type'];
	
	if($type == 'user') {
		$messages = get_user_messages($user_id, $other_id, $num);
		foreach ($messages as $message) {
			mark_as_read($message[3]);
		}
	} else {
		$messages = get_room_messages($user_id, $other_id, $num);
		if(count($messages) > 0)
			last_seen_message_id($user_id, $other_id, $messages[0][5]);
	}
	
		//print($out);
	
} else {
	$messages = [];
}
print json_encode($messages);
?>