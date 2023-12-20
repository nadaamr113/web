<?php

include_once('Connection.php');


if(!$conn)
{
    echo "Failed to connect!";
    die();
}
else{
    $FirstName = $_POST['firstname'];
    $LastName = $_POST['lastname'];
    $Email = $_POST['email'];
    $Password = $_POST['password'];
    $confirmpass=$_POST['confirmpassword'];

    $sql = "INSERT INTO signup(firstname,lastname,email,password,confirmpassword) VALUES ('$FirstName','$LastName','$Email','$Password','$confirmpass');";
    if(mysqli_query($conn,$sql))
    {
      
        echo "Sign Up Complete";
         header("location:home.html");
       
    }
    else{
        echo "Sign Up Failed!";


    }

    mysqli_close($conn);

}   
?>