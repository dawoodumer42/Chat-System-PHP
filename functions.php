<?php
include ('connection.php');

function mark_as_read($message_id){
	$conn = $GLOBALS['conn'];
	$sql = "UPDATE messages SET status = 'Read' WHERE id = {$message_id};";
	$res = $conn->query($sql);		
}

function open_session($user_id, $email, $type, $status, $name){
	
	//session_start();
	$_SESSION['user_id'] = $user_id;
	$_SESSION['email'] = $email;
	$_SESSION['name'] = $name;
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
				array_push($allowed_urls, "verify_manual.php");
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
	$sql="SELECT id, name, email FROM users WHERE type = 'Admin' and status = 1;";
	$conn = $GLOBALS['conn'];
	$res = $conn->query($sql);
	$users = mysqli_fetch_all($res);
	return $users;
}

function get_all_clients() {
	$sql="SELECT id, name, email FROM users WHERE type = 'User' and status = 1;";
	$conn = $GLOBALS['conn'];
	$res = $conn->query($sql);
	$users = mysqli_fetch_all($res);
	return $users;
}

function get_all_rooms() {
	if($_SESSION['type'] == 'Admin') {
		$sql="SELECT id, title FROM chat_rooms;";
	}
	else {
		$id = $_SESSION['user_id'];
		$sql="SELECT cr.id, cr.title FROM users u inner join chat_room_users cru on u.id = cru.user_id inner join chat_rooms cr on cru.chat_room_id = cr.id WHERE user_id = {$id};";
	}

	$conn = $GLOBALS['conn'];
	$res = $conn->query($sql);
	$users = mysqli_fetch_all($res);
	return $users;
}

function get_room_members($id) {
	$sql = "SELECT cr.title, cr.description, cru.id as entry_id, u.id as user_id, u.name FROM chat_rooms cr left join chat_room_users cru on cr.id = cru.chat_room_id left join users u on cru.user_id = u.id WHERE cr.id = {$id};";

	$conn = $GLOBALS['conn'];
	$res = $conn->query($sql);
	if($res->num_rows > 0)
		$users = mysqli_fetch_all($res);
	else
		$users = [];
	//print(json_encode($users));
	return $users;
}

function get_room_partners() {
	$id = $_SESSION['user_id'];
	//$sql = "SELECT DISTINCT user_id, chat_room_id, name, title FROM (SELECT DISTINCT cru2.user_id, cru2.chat_room_id, u.name, cr.title FROM chat_room_users cru inner join chat_room_users cru2 on cru.chat_room_id = cru2.chat_room_id inner join users u on cru2.user_id = u.id inner join chat_rooms cr on cru.chat_room_id = cr.id where cru.user_id = {$id} and cru2.user_id != {$id}) as result 	GROUP BY user_id";
	$sql = "SELECT DISTINCT cru2.user_id, cru2.chat_room_id, u.name 
	FROM chat_room_users cru inner join chat_room_users cru2 on cru.chat_room_id = cru2.chat_room_id inner join users u on cru2.user_id = u.id  where cru.user_id = {$id} and cru2.user_id != {$id}
	GROUP BY cru2.user_id";
	$conn = $GLOBALS['conn'];
	$res = $conn->query($sql);
	if($res->num_rows > 0)
		$users = mysqli_fetch_all($res);
	else
		$users = [];
	return $users;
}


function get_user_messages($user1, $user2, $num) {
	$query = "SELECT message, time, to_user_id, id FROM messages WHERE (from_user_id = {$user1} AND to_user_id = {$user2}) OR (from_user_id = {$user2} AND to_user_id = {$user1}) ORDER BY time DESC LIMIT {$num}";
	//echo $query;
	//return $query;
	$conn = $GLOBALS['conn'];
	$res = $conn->query($query);
	if($res->num_rows > 0)
		$msgs = mysqli_fetch_all($res);
	else
		$msgs = [];
	return $msgs;
}

function get_room_messages($user_id, $room_id, $num) {
	$query = "SELECT message, time, to_chat_room_id, from_user_id, u.name, crm.id as message_id  FROM chat_room_messages crm inner join users u on crm.from_user_id = u.id WHERE crm.to_chat_room_id = {$room_id} ORDER BY time DESC LIMIT {$num};";

	$conn = $GLOBALS['conn'];
	$res = $conn->query($query);
	if($res->num_rows > 0)
		$msgs = mysqli_fetch_all($res);
	else
		$msgs = [];
	foreach ($msgs as & $msg) {
		if($msg[3] == $user_id) {
			$msg[2] = '0';
		}
	}

	return $msgs;
}

function last_seen_message_id($user_id, $room_id, $msg_id) {
	$conn = $GLOBALS['conn'];
	$sql = "UPDATE chat_room_users SET last_seen_message_id = {$msg_id} WHERE user_id = {$user_id} AND chat_room_id = {$room_id};";
	$res = $conn->query($sql);
}

function get_room_last_message($room_id) {
	$user_id = $_SESSION['user_id'];
	$sql = "SELECT last_seen_message_id FROM chat_room_users WHERE user_id = {$user_id} AND chat_room_id = {$room_id};";
	//$query = "SELECT id FROM chat_room_messages crm WHERE crm.to_chat_room_id = {$room_id} ORDER BY id DESC LIMIT 1;";
	//echo $query;
	//return $query;
	$conn = $GLOBALS['conn'];
	$res = $conn->query($sql);
	if($res->num_rows > 0)
		$msgs = mysqli_fetch_assoc($res)['last_seen_message_id'];
	else
		$msgs = "";

	return $msgs;
}


function send_message($from, $to, $msg, $type) {
	if($type == 'user') {
		$sql="INSERT INTO messages (from_user_id, to_user_id, message) VALUES ({$from}, {$to}, '{$msg}')";
	} else {
		$sql = "INSERT INTO chat_room_messages (message, from_user_id, to_chat_room_id) VALUES ('{$msg}', {$from}, {$to});";
	}
	$conn = $GLOBALS['conn'];
	$res = $conn->query($sql);
}

function create_room($title, $desc, $users) {
	session_start();
	$id = $_SESSION['user_id'];
	$conn = $GLOBALS['conn'];

	$sql = "INSERT INTO chat_rooms (title, description, created_by) VALUES ('{$title}', '{$desc}', {$id});";
	$conn->query($sql);
	$row_id = $conn->insert_id;
	
	foreach($users as $user) {
		$sql = "INSERT INTO chat_room_users (chat_room_id, user_id, added_by) VALUES ({$row_id}, {$user}, {$id});";
		$conn->query($sql);
		
	}
	$sql ="INSERT INTO chat_room_users (user_id, chat_room_id, added_by) VALUES ({$id}, {$row_id}, {$id});";
	$conn->query($sql);
	//return $row_id;
}

function update_room($room_id, $title, $desc, $users) {
	session_start();
	$user_id = $_SESSION['user_id'];
	$conn = $GLOBALS['conn'];

	$list = join(",",$users);
	//foreach ($users as $user) {
	$sql = "DELETE FROM chat_room_users WHERE chat_room_id = {$room_id} AND user_id NOT IN ({$list})";
	$conn->query($sql);

	$sql = "UPDATE chat_rooms SET title = '{$title}', description = '{$desc}' WHERE id = {$room_id};";
	$conn->query($sql);

	foreach($users as $user) {
		//$sql = "INSERT INTO chat_room_users (chat_room_id, user_id, added_by) VALUES ({$room_id}, {$user}, {$user_id});";
		//$conn->query($sql);

		$sql = "SELECT id FROM chat_room_users WHERE chat_room_id = {$room_id} AND user_id = {$user}";
		$res = $conn->query($sql);

		if($res->num_rows == 0) {
			$sql ="INSERT INTO chat_room_users (user_id, chat_room_id, added_by) VALUES ({$user}, {$room_id}, {$user_id});";
			$conn->query($sql);
		}
	}
	$sql ="INSERT INTO chat_room_users (user_id, chat_room_id, added_by) VALUES ({$user_id}, {$row_id}, {$user_id});";
	$conn->query($sql);
}

function delete_room($room_id) {
	session_start();
	$conn = $GLOBALS['conn'];

	$sql = "DELETE FROM chat_room_users WHERE chat_room_id = {$room_id}";
	$conn->query($sql);
	$sql = "DELETE FROM chat_room_messages WHERE to_chat_room_id = {$room_id}";
	$conn->query($sql);
	$sql = "DELETE FROM chat_rooms WHERE id = {$room_id}";
	$conn->query($sql);
	
}
?>