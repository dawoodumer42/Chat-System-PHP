<?php
include ('connection.php');

function open_session($user_id, $email, $type, $status){
	
	//session_start();
	$_SESSION['user_id'] = $user_id;
	$_SESSION['email'] = $email;
    $_SESSION['type'] = $type;
    $_SESSION['status'] = $status;

}

function authorize(){
	$current_page =  basename($_SERVER["SCRIPT_FILENAME"],'.php') . ".php";
	//echo $current_page;
	//die();
	session_start();
	$allowed_urls = array();
	if(isset($_SESSION['user_id']))
	{
		$status = $_SESSION['status'];
		$type = $_SESSION['type'];
		//var_dump($status);
		//var_dump($type);
		//die();
		
		switch($status){
			case "0":
				array_push($allowed_urls, "blocked.php");
			break;
			case "1":
				if($type == 'User')
					array_push($allowed_urls, "inbox.php");
				else if ($type == 'Moderator')
					array_push($allowed_urls, "inbox.php");
				else if ($type == 'Admin')
				{
					array_push($allowed_urls, "inbox.php");
					array_push($allowed_urls, "users.php");
					array_push($allowed_urls, "approve.php");
					array_push($allowed_urls, "block.php");
					array_push($allowed_urls, "unblock.php");
				}
			break;
			case "2":
				array_push($allowed_urls, "approval_message.php");
			break;
			case "3":
				array_push($allowed_urls, "verify.php");
				array_push($allowed_urls, "resend_activation.php");

			break;
		}
	} else {
		array_push($allowed_urls, "login.php");
		array_push($allowed_urls, "register.php");
	}
	$redirect = true;
	foreach ($allowed_urls as $url) {
		if ($current_page == $url)
			$redirect = false;
	}

	if($redirect)
		header('Location: '.$allowed_urls[0]);

    return;
}

function get_user($email) {
	$sql_query = "SELECT * FROM users WHERE email = '$email'";
	$conn = $GLOBALS['conn'];
	$res = $conn->query($sql_query);
	if($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        return $row;
    }
    else{
        return null;
    }
}

function get_all_users() {
    $sql="SELECT id, name, email, created_at, status, type FROM users";
	$conn = $GLOBALS['conn'];
	$res = $conn->query($sql);
	$users = mysqli_fetch_all($res);
	return $users;
}

function get_all_admins() {
    $sql="SELECT id, name, email FROM users WHERE type = 'Admin' and status = 'Active';";
	$conn = $GLOBALS['conn'];
	$res = $conn->query($sql);
	$users = mysqli_fetch_all($res);
	return $users;
}

function is_verified($id) {
	$sql = "SELECT email_verified_at FROM users WHERE id = $id";
	$conn = $GLOBALS['conn'];
	$res = $conn->query($sql);
	$row = mysqli_fetch_assoc($res);
	if($row["email_verified_at"] == NULL)
		return false;
	else
		return true;	
}

function send_activation_code($code, $email) {
		$subject = 'Activation Code | KStreams Chat System'; 
		$message = 'Please login to your account and copy paste the following activation code: \n';
		$message .= $code;
		$message .= "\nThanks."; 	                                 
		$headers = 'From:noreply@kstreams.com' . "\r\n";
		$out = mail($email, $subject, $message, $headers);
}

?>