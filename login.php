<?php
include 'init.php'; // Assuming this file contains your database connection details
include $tpl . 'header.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['user'];
    $password = $_POST['pass'];
    $hashedPass = sha1($password);

    $stmt = $con->prepare("SELECT email, password, group_ID FROM users WHERE email = ? AND password = ?");
    $stmt->execute(array($username, $hashedPass));
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $count = $stmt->rowCount();

    if ($count > 0) {
        if ($user['group_ID'] > 0) {
            header("Location: admin.php");
            exit();
        } else {
            echo "hi user";
            exit();
        }
    } else {
        echo "Invalid username or password";
    }
}
?>
<!-- HTML content -->
<div class="center">
    <h1>Admin Login</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <!-- Input fields for username and password -->
        <div class="txt_field">
            <input type="text" name="user" required>
            <span></span>
            <label>E-mail</label>
        </div>
        <div class="txt_field">
            <input type="password" name="pass" required>
            <span></span>
            <label>Password</label>
        </div>
        <div class="pass">Forgot password?</div>
        
        <!-- Submit button -->
        <input type="submit" value="Login" name="submit">

        <!-- Signup link -->
        <div class="signup"> Not a Client? <a href="signup.php">Sign-Up</a></div>
    </form>
</div>
</body>
</html>
