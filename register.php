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
            <p class="text-danger text-red" id="error"> </p>
            <div class="clearfix">
                <!-- <label class="pull-left checkbox-inline"><input type="checkbox"> Remember me</label> -->
                <!-- <a href="#" class="pull-right">Forgot Password?</a> -->
            </div>        
        </form>
        <p class="text-center"><a href="login.php">Already Have an Account? Log In here.</a></p>
    </div>
</body>

</html>                                		                            