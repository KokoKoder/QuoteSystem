
<?php
session_start();
include(app_path().'/includes/connect.php');


$order_date=mysqli_real_escape_string($conn,$_POST["order_date"]);
$vendor_id=mysqli_real_escape_string($conn,$_POST["vendor_id"]);
$order_status_id=mysqli_real_escape_string($conn,$_POST["order_status_id"]);
$order_id=$_SESSION["order_id"];
echo $order_date;

$sql = "UPDATE orders_table SET   order_date='$order_date', vendor_id='$vendor_id' WHERE order_id='$order_id'";
$sql2 = "UPDATE orders_status SET   order_status_id='$order_status_id' WHERE order_id='$order_id'";
$check1=$conn->query($sql);
$check2=$conn->query($sql2);
if ($check1 === TRUE AND $check2 === TRUE) {
	$url=route('edit_order');
	header ("Location: ".$url."?order_id=".$_SESSION["order_id"]);
} else {
    echo "Error updating record: " . $conn->error;
}

exit;
?>
