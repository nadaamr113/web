<?php
include 'init.php'; 
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  include "add.php";
  include "delete.php";
  include "update.php";
} else {
  // Handle non-POST request (if needed)
  echo " / This page processes only POST requests";
}

// Close the database connection
$con = null;
?>
<?php include "admin.html"?>;