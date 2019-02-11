<?php
#connect to the database
include(app_path().'/includes/connect.php');

$item_name='%'.mysqli_real_escape_string($conn,$_GET["product_name"]).'%';
$options="";

$sql="SELECT item_id, item_name FROM items WHERE item_name LIKE '$item_name'";
$result = mysqli_query($conn, $sql);	
if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$option=$row['item_name'];
		 $options.=$option.',';
		};
}
else{
	$options="product not found";
}
echo (string) $options;
?>