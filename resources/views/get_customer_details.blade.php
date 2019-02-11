<?php

session_start();

#connect to the database
require(app_path().'/includes/connect.php');

$customer_name=mysqli_real_escape_string($conn,$_GET["customer_name"]);
$customer_details="";

$sql="SELECT customer_name, customer_id, customer_phone, customer_address, customer_mail FROM customers WHERE customer_name = '$customer_name'";
$result = mysqli_query($conn, $sql);	
if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		 $customer_details=$row['customer_name'].','.$row['customer_address'].','.$row['customer_phone'].','.$row['customer_id'].','.$row['customer_mail'];
		};
}
else{
	$customer_details="customer not found";
}
echo (string) $customer_details;
?>