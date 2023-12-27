<?php

include_once('connection.php');


if(!$conn)
{
    echo "Failed to connect!";
    die();
}
else{
    $FirstName = $_POST['firstname'];
    $LastName = $_POST['lastname'];
    $Email = $_POST['email'];
    $Password = sha1($_POST['password']);
    $confirmpass= sha1($_POST['confirmpassword']);

    if ($Password !== $confirmpass) {
        echo '<script>alert("Passwords don\'t match!");</script>';
    }
        
    else{

        $sql = "INSERT INTO signup(firstname,lastname,email,password,confirmpassword) VALUES ('$FirstName','$LastName','$Email','$Password','$confirmpass');";
        if(mysqli_query($conn,$sql))
        {
          
            echo "Sign Up Complete";
             header("location:index.php");
           
        }
        else{
            echo "Sign Up Failed!";
    
    
        }
    
        mysqli_close($conn);
    }
   

}   

?>
