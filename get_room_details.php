<?php
	include ('functions.php');

	if(isset($_POST['id'])) {
		$room_id = $_POST['id'];
		$users = get_room_members($room_id);
		print(json_encode($users));
	}
	else {
		echo "Error.";
	}


?>