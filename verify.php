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
    <div class="login-form">
        <form action="login.php" method="post">
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
            <p class="text-danger text-red" id="message"> <?php if(isset($_GET['message'])) echo $_GET['message']; ?> </p>

            <div class="clearfix">
                <!-- <label class="pull-left checkbox-inline"><input type="checkbox"> Remember me</label> -->
                <!-- <a href="#" class="pull-right">Forgot Password?</a> -->
            </div>        
        </form>
        <p class="text-center"><a href="resend_activation.php">Resend Activation Code</a></p>
    </div>
</body>
</html>                                		                            