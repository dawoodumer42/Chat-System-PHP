<?php
	include 'functions.php';

	if(isset($_POST['room_id'])) {
		$id = $_POST['room_id'];
		delete_room($id);
	}


?>