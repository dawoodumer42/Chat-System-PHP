<?php
    include ('functions.php');
    authorize();
    
	$error_message = "";
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		
		$mail_check = "SELECT id FROM users WHERE email = '" .$_POST["email"]. "'";
		
		$result = $conn->query($mail_check);		
		if($result->num_rows > 0) {			
			$error_message = "This email has already been registered.";			
		}
		else {
			$code = password_hash(date('Y-m-d H:i:s'), PASSWORD_DEFAULT);
			$encrypted_password = password_hash($_POST["password"], PASSWORD_DEFAULT);
			
			$insertQuery = "INSERT INTO
				users(email, password, name, activation_code)
				VALUES('" . $_POST["email"] . "' , '" . $encrypted_password . 
				"' , '" . $_POST["name"] . "' , '" .$code . "')";
			
			if($conn->query($insertQuery) === true) {
				$user_query = "SELECT id, email, status, type, activation_code FROM users
					WHERE email = '" .$_POST["email"]. "'";
				$query_res = $conn->query($user_query);
				
				$row = $query_res->fetch_assoc();				
                open_session($row["id"], $row["email"], $row["type"],  $row["status"]);
                send_activation_code($row["activation_code"], $row["email"]);

                $query_activity = "INSERT INTO user_activities (user_id, time) VALUES ({$row['id']}, CURRENT_TIMESTAMP);";
                $res = $conn->query($query_activity);
                //echo $query_activity;
				header('Location: verify.php');
			}
			else
			{
				$error_message = "Something has gone wrong in the registration process.";
			}
		}
		$conn->close();
	}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
    <link rel="stylesheet" href="styles.css">
    <script type="text/javascript">

        function checkPassword(str)
        {
            var re = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/;
            return re.test(str);
        }

        function validate(form)
        {
            document.getElementById('error').innerHTML = '';
            if(form.password.value != "" && form.password.value == form.confirm_password.value) {
                if(!checkPassword(form.password.value)) {
                    var paragraph = document.getElementById("error");
                    var text = document.createTextNode("\n*Password should be of atleast 6 length, must contain atleast 1 number, 1 uppercase and 1 lowercase letter.");
                    paragraph.appendChild(text);

                    return false;
                }
            } else {
                var paragraph = document.getElementById("error");
                var text = document.createTextNode("\n*Passwords do not match.");
                paragraph.appendChild(text);
                
                return false;
            }   
            return true;
        }

    </script>
</head>
<body>
    <div class="login-form">
        <form action="register.php" method="post" onsubmit="return validate(this);">
            <h2 class="text-center" >
                Chat System                
            </h1>
            <h3 class="text-center">
                Register                
            </h2>       
            <hr/>
            <div class="form-group">
                    <input name="name" type="text" class="form-control" placeholder="Full Name" required="required">
            </div> 
            <div class="form-group">
                <input name="email" type="email" class="form-control" placeholder="Email" required="required">
            </div>
            <div class="form-group">
                <input name="password" type="password" class="form-control" placeholder="Password" required="required">
            </div>
            <div class="form-group">
                    <input name="confirm_password" type="password" class="form-control" placeholder="Retype Password" required="required">
                </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Register</button>
            </div>
            <p class="text-danger text-red" id="error">
				<?php echo $error_message; ?>
			</p>
            <div class="clearfix">
                <!-- <label class="pull-left checkbox-inline"><input type="checkbox"> Remember me</label> -->
                <!-- <a href="#" class="pull-right">Forgot Password?</a> -->
            </div>        
        </form>
        <p class="text-center"><a href="login.php">Already Have an Account? Log In here.</a></p>
    </div>
</body>

</html>                                		                            