<?php

function open_session($user_id, $type, $status){
	
	session_start();
    $_SESSION['user_id'] = $user_id;
    $_SESSION['type'] = $type;
    $_SESSION['status'] = $status;

}

function authorize($current_page){
    session_start();
	if(isset($_SESSION['user_id'])
	{
		$status = $_SESSION['status'];
		$type = $_SESSION['type'];
		$url = "";
		$url2 = "";
		switch($status){
			case 0:
				$url = "blocked.php";
			break;
			case 1:
				if($type == 'User')
					$url = 'inbox.php';
				else if ($type == 'Moderator')
					$url = 'inbox.php';
				else if ($type == 'Admin')
				{
					$url = "inbox.php";
					$url2 = "users.php";
				}
			break;
			case 2:
				$url = "approval_message.php";
			break;
			case 3:
				$url = "verify.php";
			break;
		}
	} else {
		$url = 'login.php';
		$url2 = 'register.php';
	}
    if($current_page != $url && $current_page != $url2)
        header('Location: '.$url);

    return;
}


?>