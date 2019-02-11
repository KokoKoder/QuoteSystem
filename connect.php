<?php
#connect to the database
$servername = "localhost:3306.";
$username = "root";
$password = "";
$db = 'quote_system';

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


#Set the encoding to utf8
mysqli_query($conn,"SET NAMES 'utf8'");
?>