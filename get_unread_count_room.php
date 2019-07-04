<?php
    include('functions.php');

    session_start();
    $user_id = $_SESSION['user_id'];
    $room_ids = $_POST['ids'];

    $counts = [];
    $i = 0;
    foreach ($room_ids as $room_id) {
  		$sql =  "SELECT COUNT(*) as count FROM chat_room_messages WHERE id > (SELECT last_seen_message_id FROM chat_room_users WHERE user_id = {$user_id} AND chat_room_id = {$room_id}) AND to_chat_room_id = {$room_id}";
    	$res = $conn->query($sql);

    	$r = mysqli_fetch_assoc($res);
    	$counts[] = $r['count'];

  		$i = $i + 1;
    }

		print json_encode($counts);

?>
