

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="center"> 
            <h1>Login</h1>
            <form  action ="login.php" method="POST">
                <div class="txt_field">
                    <input type="text" name="userName" required>
                    <span></span>
                    <label> Username</label>
                </div>
                <div class="txt_field">
                    <input type="password"  name="password" required>
                    <span></span>
                    <label>password</label>
                </div>
                <div class="pass">Forgot password?</div>
                
                 <input type="submit" value="Login" name="submit">


                <div class="signup"> Not a Client? <a href="signup.html">Sign-Up</a></div>
            </form>
        </div>
</body>
</html>