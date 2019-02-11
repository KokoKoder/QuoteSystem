<?php
if (!isset($conn)){
include(app_path().'/includes/connect.php');
}
$sql="SELECT * FROM order_status_list";
$result=  mysqli_query($conn, $sql);
$order_status_list=[];
if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$order_status_list[]=$row['order_status_id'].",".$row['order_status_name'];
		};
}

?>