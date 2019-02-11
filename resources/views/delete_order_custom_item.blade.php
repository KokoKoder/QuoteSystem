<?php
session_start();
include(app_path().'/includes/connect.php');


$id=mysqli_real_escape_string($conn,$_GET["id"]);


echo $id;
$sql="DELETE FROM order_custom_items
WHERE id ='$id'";

if ($conn->query($sql) === TRUE) {
	$url=route('edit_order');
	header ("Location: ".$url."?order_id=".$_SESSION["order_id"]);
} else {
    echo "Error updating record: " . $conn->error;
}

exit;
?>
