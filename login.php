<?php
include("connection.php");

if(isset($_POST['submit'])){
    $username =  $_POST["userName"];
    $password =  $_POST["password"];
    
    $sql = "SELECT * FROM  login WHERE username = '$username' And password = '$password';";
   
     

   

    if(mysqli_num_rows(mysqli_query($conn,$sql)) == 1)
    {
        echo "Login success!";
        $row = mysqli_fetch_assoc(mysqli_query($conn,$sql));
        echo 'Welcome '. $row['username'] . " " . $row['password'];
    
        header("location:menu.html");
    }
    else
    {
        echo "Incorrect credentials!";
        header("location:index.php");
    }

    mysqli_close($conn);

}

?>
