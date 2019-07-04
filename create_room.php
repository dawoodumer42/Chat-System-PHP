<?php
include('functions.php');


if(isset($_POST['title'])){
	$type = $_POST['modal_type'];
	$title = $_POST['title'];
	$desc = $_POST['description'];
	$users= $_POST['final_users'];
	$users =  explode (",", $users);

	if($type == 'create') {		
		if(count($users) > 0 && $users[0] != "") {
			create_room($title, $desc, $users);
			header('Location: inbox.php');
		}
		else
			echo "Error. Please add atleast 1 member to the room.";
	} else {
		$room_id = $_POST['room_id'];
		//print("Updating");
		print(update_room($room_id, $title, $desc, $users));
		header('Location: inbox.php');
	}
}


?>