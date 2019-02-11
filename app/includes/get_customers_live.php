<?php

session_start();

#connect to the database
require(app_path().'/includes/connect.php');

$customer_name='%'.mysqli_real_escape_string($conn,$_GET["customer_name"]).'%';
$options="";

$sql="SELECT customer_name FROM customers WHERE customer_name LIKE '$customer_name'";
$result = mysqli_query($conn, $sql);	
if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$option=$row['customer_name'];
		 $options.=$option.',';
		};
}
else{
	$options="customer not found";
}
echo (string) $options;
?>