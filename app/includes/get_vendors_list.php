<?php
if (!isset($conn)){
include(app_path().'/includes/connect.php');
}
$sql="SELECT * FROM vendor";
$result=  mysqli_query($conn, $sql);
$vendors_list=[];
if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$vendors_list[]=$row['vendor_id'].",".$row['vendor_name'];
		};
}

?>