<?php
include 'init.php'; // Assuming this file contains your database connection details
include $tpl . 'header.php';
session_start();

// Check if the user is coming from an HTTP POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['user'];
    $password = $_POST['pass'];
    $hashedPass = sha1($password);

    // Check if the user is in the database
    $stmt = $con->prepare("SELECT email, password FROM users WHERE email = ? AND password = ? AND group_ID = 1");
    $stmt->execute(array($username, $hashedPass));
    $count = $stmt->rowCount();
    echo $count;
    
    if($count>0){
        echo 'Welcom' . $username;
    }
}
?>

<!-- HTML content should not be placed after PHP logic that sends headers (e.g., header("Location: ...")) -->
<div class="center">
    <h1>Admin Login</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="txt_field">
            <input type="text" name="user" required>
            <span></span>
            <label>Username</label>
        </div>
        <div class="txt_field">
            <input type="password" name="pass" required>
            <span></span>
            <label>Password</label>
        </div>
        <div class="pass">Forgot password?</div>
        
        <input type="submit" value="Login" name="submit">

        <div class="signup"> Not a Client? <a href="signup.html">Sign-Up</a></div>
    </form>
</div>
</body>
</html>
