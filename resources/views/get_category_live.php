<?php

session_start();

#connect to the database
require(app_path().'/includes/connect.php');

$customer_name='%'.mysqli_real_escape_string($conn,$_GET["customer_name"]).'%';
$options="";

$sql="SELECT category_name FROM categories WHERE category_name LIKE '$category_name'";
$result = mysqli_query($conn, $sql);	
if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$option=$row['category_name'];
		 $options.=$option.',';
		};
}
else{
	$options="category not found";
}
echo (string) $options;
?>