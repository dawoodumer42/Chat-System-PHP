<?php
	include('functions.php');

	$id = $_POST['id'];
	$pass = $_POST['password'];

	$encrypted_password = password_hash($pass, PASSWORD_DEFAULT);

	$sql = "UPDATE users SET password = '$encrypted_password' WHERE id = $id;";
    $res = $conn->query($sql);
	echo ($res);
	echo $encrypted_password;
	//header('Location: users.php');

?>