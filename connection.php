<?php
/* Database connection start */
$servername = "localhost";
$username = "user-name";
$password = "pass-word";
$dbname = "Kalite";
 
$conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
 
?>
