<?php
    include('functions.php');

    session_start();

    $query = "SELECT user_id FROM user_activities WHERE CURRENT_TIMESTAMP <= TIMESTAMPADD(SECOND,10, time);";
    $res = $conn->query($query);

 //    $users = mysqli_fetch_all($res);
 //    //var_dump(json_encode($users));
	// echo json_encode($users);

	$rows = array();
	while($r = mysqli_fetch_assoc($res)) {
	    $rows[] = $r;
	}
	print json_encode($rows);
?>
