<?php
session_start();
include(app_path().'/includes/connect.php');

// Required field names
$required = array('product_name', 'product_quantity');
// Loop over field names, make sure each one exists and is not empty
$error = false;
foreach($required as $field) {
  if (empty($_POST[$field])) {
    $error = true;
  }
}

if ($error) {
	echo 'error is detecte';
	$_SESSION["Err_missing_data"] = "Name and quantity required";
	$url = route('add_item_to_order_form');
	header("Location: ".$url);
	die;
}


#get the form value
$product_name = mysqli_real_escape_string($conn,$_POST["product_name"]);
$product_quantity = mysqli_real_escape_string($conn,$_POST["product_quantity"]);
if (isset($_POST["schedule_date"])){
	$Schedule_delivery_date = mysqli_real_escape_string($conn,$_POST["schedule_date"]);
}
else{
	$Schedule_delivery_date="";
}
if (isset($_SESSION['product_name'])){unset($_SESSION['product_name']);}
$order_id = mysqli_real_escape_string($conn,$_SESSION["order_id"]);
$status_id = "2";

#get item id
$sql2 = "SELECT item_id,item_price FROM items WHERE item_name = '$product_name'";
$result = mysqli_query($conn, $sql2);
if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$item_id = $row['item_id'];
		$item_price = $row['item_price'];
		};
}
else{
	$url = route('enter_item');
	header("Location: ".$url);
	die;
}
#get standard delivery time if it was not set in the form and set the schedule delivery date
if ($Schedule_delivery_date == ""){
	#get the order date to calculate the schedule delivery date by adding the standard delivery time for the supplier
	$order_date_sql = "SELECT order_date  FROM orders_table  WHERE order_id='$order_id='";
	$order_date_result = mysqli_query($conn, $order_date_sql);
	if (mysqli_num_rows($order_date_result) > 0) {
		while($row = mysqli_fetch_assoc($order_date_result)) {
			$order_date = $row['order_date'];
			}
	}
	#get the standard delivery time for the supplier 
	$standard_delivery_time_sql = "SELECT standard_delivery_time FROM suppliers JOIN items ON suppliers.supplier_id=items.item_supplier_id WHERE item_id = '$item_id'";
	$standard_delivery_time_result = mysqli_query($conn, $standard_delivery_time_sql);
	if (mysqli_num_rows($standard_delivery_time_result) > 0) {
		while($row = mysqli_fetch_assoc($standard_delivery_time_result)) {
			$standard_delivery_time = $row['standard_delivery_time'];
			};
	}
	#calculate the Schedule delivery date
	$Schedule_delivery_date = date('Y-m-d',strtotime("$order_date +".$standard_delivery_time." weeks"));
	$latest_order_date = date('Y-m-d',strtotime("$Schedule_delivery_date -1 week"));	
	echo "Schedule delivery date : ".$Schedule_delivery_date."latest order date: ".$latest_order_date;
}

$sql = "INSERT INTO order_items (item_id,item_name,item_price,item_quantity,Schedule_delivery_date, order_id,status_id)
VALUES ('$item_id','$product_name','$item_price','$product_quantity','$Schedule_delivery_date','$order_id','$status_id')";

if ($conn->query($sql) === TRUE) {
	if (isset($_SESSION["order_edit"])){
		echo  "This is a edit order session : ".$_SESSION["order_edit"];
		echo "for the order : ".$_SESSION["order_id"];
		$url = route('edit_order');
		header("Location: ".$url.'?order_id='.$_SESSION["order_id"]);
	}
	else{
		echo "This is a new order session";
		$url = route('add_item_to_order_form');
		header("Location: ".$url);
		exit;
	}	
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
exit;
?>
