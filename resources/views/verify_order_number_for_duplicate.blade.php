<?php
if (session_status() == PHP_SESSION_NONE) {
	session_start();
	include(app_path().'/includes/connect.php');
}
if (isset($_GET["order_number"])){
		$is_jquery=TRUE;
		$order_number=mysqli_real_escape_string($conn,$_GET["order_number"]);
		$sql = "SELECT order_id FROM orders_table WHERE order_number LIKE '$order_number'";

}
else{
	$is_php=TRUE;
	$order_number=$_SESSION["order_number"];
	$sql = "SELECT order_id FROM orders_table WHERE order_number LIKE '$order_number'";
}
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$_SESSION["duplicate_id"]=$row['order_id'];
		if (isset($is_jquery)){
			$edit_order = route('edit_order');
			echo(string) 'Order number already exists<br> change or <a href="'.$edit_order.'?order_id='.$_SESSION["duplicate_id"].'">edit order</a> instead? ';
		}
		else{
			$is_duplicate=TRUE;
		}	
	}
}
else{
	unset($is_jquery);
	unset($_SESSION["duplicate_id"],$_SESSION["order_number"]);
	echo (string) "order not found";
}
?>