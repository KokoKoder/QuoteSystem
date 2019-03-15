<?php
if (!isset($conn)){
include(app_path().'/includes/connect.php');
}
$sql="SELECT * FROM suppliers";
$result=  mysqli_query($conn, $sql);
$suppliers_list=[];
$suppliers_details=[];
if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$suppliers_list[]=$row['supplier_id'].",".$row['supplier_name'];
		$suppliers_details[]=$row['supplier_id'].",".$row['supplier_name'].",".$row['pickup_address'].",".$row['supplier_phone1'].",".$row['supplier_mail'];
		};
}

?>