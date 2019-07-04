<?php
	include ('functions.php');

	session_start();
	if(isset($_POST['message']) && isset($_POST['to_user']) && isset($_POST['type'])) {
		$from =  $_SESSION['user_id'];
		$to = $_POST['to_user'];
		$msg = $_POST['message'];
		$type = $_POST['type'];

		send_message($from, $to, $msg, $type);
		print("Success");
	} else {
		print ("Error");
	}
?>