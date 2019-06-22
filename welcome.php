
<?php
	
	session_start();
	if(isset($_SESSION["user_id"])) {
		
		//echo $_SESSION["user_id"];
		
		
		$conn = new mysqli("localhost", "root", "", "chat_system");
		
		if($conn->connect_error) {
			
			echo "Problem in connecting to the server";
		}
		else {
			$my_query = "SELECT * FROM users WHERE id = " . $_SESSION["user_id"];
			$result = $conn->query($my_query);
			if($result->num_rows > 0) {
				
				$row = $result->fetch_assoc();
				echo $row["id"] . "<br />";
				echo $row["name"] . "<br />";
				echo $row["email"] . "<br />";
				echo $row["status"] . "<br />";
				echo $row["type"] . "<br />";
				
				
			}
		}
		
		
	}
	else {
		
		echo "Nothing Opened!";
	}
	
	

?>

<html>

	<head>
		<title> Welcome </title>
	</head>
	
	<body>
	
	<body>

</html>