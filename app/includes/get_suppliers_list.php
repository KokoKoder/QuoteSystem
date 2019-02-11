<?php
if (!isset($conn)){
include(app_path().'/includes/connect.php');
}
$sql="SELECT * FROM suppliers";
$result=  mysqli_query($conn, $sql);
$suppliers_list=[];
if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$suppliers_list[]=$row['supplier_id'].",".$row['supplier_name'];
		};
}

?>