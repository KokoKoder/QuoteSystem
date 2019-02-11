<?php
if (!isset($conn)){
include(app_path().'/includes/connect.php');
}
$sql="SELECT * FROM status";
$result=  mysqli_query($conn, $sql);
$options=[];
if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$options[]=$row['status_id'].",".$row['status_name'];
		};
}

?>