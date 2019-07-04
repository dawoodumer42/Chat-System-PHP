<?php
	include ('functions.php');
    authorize();

	$error_message = "";	
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $user = get_user($_POST['email']);

		if($user != null) {
            $hash = $user["password"];

            if(password_verify($_POST['password'], $hash))
            {
                open_session($user["id"], $user["email"], $user["type"],  $user["status"], $user["name"]);
                header('Location: inbox.php');
            }
            else
            {
                $error_message = "Invalid Password.";
            }
        }
        else{
            $error_message = "Invalid Email Address.";
        }
	}
?>
	

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
    <link rel="stylesheet" href="styles.css">
    
</head>
<body>
    <div class="login-form">
        <form action="login.php" method="post">
            <h2 class="text-center" >
                Chat System                
            </h1>
            <h3 class="text-center">
                Log in                
            </h2>     
            <hr/>  
            <div class="form-group">
                <input name="email" type="email" class="form-control" placeholder="Email" required="required">
            </div>
            <div class="form-group">
                <input name="password" type="password" class="form-control" placeholder="Password" required="required">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Log in</button>
            </div>
            <div class="clearfix">
                <label class="pull-left checkbox-inline"><input type="checkbox"> Remember me</label>
                <!-- <a href="#" class="pull-right">Forgot Password?</a> -->
            </div>     
            <br/>
            <p class="text-danger text-red" id="error">
				<?php echo $error_message; ?>
			</p>   
        </form>
        <p class="text-center"><a href="register.php">Create an Account</a></p>
    </div>
</body>
</html>                                		                            