<?php
// Create connection
$conn = mysqli_connect("localhost", "root", "", "login_register");

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

?>