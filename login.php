<?php
include("connection.php");

session_start();
if(!$conn){
    echo "failed to connect";
    die();
}
else{
    $username = $_POST["firstname"];
    $password = sha1($_POST["password"]);
    
    $sql = "SELECT * FROM  signup WHERE firstname = '$username' And password = '$password';";
   

    if(mysqli_num_rows(mysqli_query($conn,$sql)) == 1)
    {
        echo "Login success!";
        $row = mysqli_fetch_assoc(mysqli_query($conn,$sql));
        echo 'Welcome '. $row['firstname'] . " " . $row['password'];
    
        header("location:home.html");
    }
    else
    {
        echo "Incorrect credentials!";
        header("location:index.php");
    }

    mysqli_close($conn);
}

?>
