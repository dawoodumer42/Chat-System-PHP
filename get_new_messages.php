<?php
include('functions.php');

session_start();
$user_id = $_SESSION['user_id'];
$id = $_POST['id'];
$type = $_POST['type'];
$message_id = $_POST['message_id'];
$rows = array();

if($type == 'user') {
	$sql = "SELECT * FROM messages WHERE ((from_user_id = {$user_id} AND to_user_id = {$id}) OR (from_user_id = {$id} AND to_user_id = {$user_id})) AND id > {$message_id} ORDER BY time DESC";
	$res = $conn->query($sql);

	while($r = mysqli_fetch_assoc($res)) {
		$rows[] = $r;
		if($r['from_user_id'] == $id) {
			mark_as_read($r['id']);
		}
	}

}
else {
	$query = "SELECT message, time, to_chat_room_id, from_user_id, u.name, crm.id as message_id  FROM chat_room_messages crm inner join users u on crm.from_user_id = u.id WHERE crm.to_chat_room_id = {$id} AND crm.id > (SELECT last_seen_message_id FROM chat_room_users WHERE user_id = {$user_id} AND chat_room_id = {$id}) ORDER BY time DESC;";

	$res = $conn->query($query);

	while($r = mysqli_fetch_assoc($res)) {
		$rows[] = $r;
	}

	foreach ($rows as & $row) {
		if($row['from_user_id'] == $user_id) {
			$row['to_chat_room_id'] = '0';
		}
	}
	if(count($rows) > 0)
		//var_dump($rows[0]['message_id']);
		last_seen_message_id($user_id, $id, $rows[0]['message_id']);
}

print json_encode($rows);
?>
