<?php
	include ('functions.php');
    authorize();
    
    $error_message = "";
    if(isset($_GET['message']))
	    $error_message = $_GET['message'];	
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $user = get_user($_SESSION['email']);
        
        if($user != null) {
           
            if($user["activation_code"] == $_POST['code']) {
                $query_verify = "UPDATE users SET status = 2, email_verified_at = CURRENT_TIMESTAMP WHERE activation_code = '" .  $_POST['code'] . "';";
                $res = $conn->query($query_verify);
                $_SESSION['status'] = 2;
                header('Location: approval_message.php');
            }
            else {
                $error_message = "Invalid Activation Code.";
            }
        }
        else
        {
            $error_message = "System Error.";
        }
	}
?>
	


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Account</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
    <link rel="stylesheet" href="styles.css">
    
</head>
<body>
    <div class="logout_button">
        <a href="logout.php" class="btn btn-info btn-lg">
          <span class="glyphicon glyphicon-log-out"></span> Log out
        </a>
    </div>
    <div class="login-form">
        <form action="verify.php" method="post">
            <h2 class="text-center" >
                Chat System                
            </h1>
            <h3 class="text-center">
                Account Verification                
            </h2>       
            <hr/>
            <p>Activation code has been sent via Email. Please enter Activation Code here.</p>
            <div class="form-group">
                <input name="code" type="text" class="form-control" placeholder="Activation Code" required="required">
            </div>            
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Verify</button>
            </div>
            <p class="text-danger text-red" id="error">
				<?php echo $error_message; ?>
			</p>
            <div class="clearfix">
                <!-- <label class="pull-left checkbox-inline"><input type="checkbox"> Remember me</label> -->
                <!-- <a href="#" class="pull-right">Forgot Password?</a> -->
            </div>        
        </form>
        <p class="text-center"><a href="resend_activation.php">Resend Activation Code</a></p>
    </div>
</body>
</html>                                		                            