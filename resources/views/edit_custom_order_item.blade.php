
<?php
session_start();
#connect to the database
include(app_path().'/includes/connect.php');

#get the form value
$id=mysqli_real_escape_string($conn,$_POST["id"]);
$item_quantity=mysqli_real_escape_string($conn,$_POST["item_quantity"]);
$Schedule_delivery_date=mysqli_real_escape_string($conn,$_POST["Schedule_delivery_date"]);

echo $Schedule_delivery_date;
$item_status_id=mysqli_real_escape_string($conn,$_POST["item_status"]);
echo $id;
$sql = "UPDATE order_custom_items SET item_quantity='$item_quantity',Schedule_delivery_date='$Schedule_delivery_date',status_id='$item_status_id' WHERE id='$id'";
if ($conn->query($sql) === TRUE) {
	echo $item_status_id;
    echo "New record created successfully in orders tab";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
if (isset($_SESSION["order_id"]) AND !isset($_SESSION["order_edit"])){
	$url=route('add_item_to_order_form');
	header("Location: ".$url);
exit;
}
elseif (isset($_SESSION["order_id"]) AND isset($_SESSION["order_edit"])){
	$edit_order_url=route('edit_order');
	header("Location: ".$edit_order_url."?order_id=".$_SESSION["order_id"]);
	exit;
}
else{
	$enter_item_url=route('enter_item');
	header("Location: ".$enter_item_url);
	exit;
}
?>
