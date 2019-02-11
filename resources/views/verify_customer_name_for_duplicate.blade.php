<?php
include(app_path().'/includes/connect.php');
$customer_name=$_GET["customer_name"];
$sql = "SELECT customer_name customer_id FROM customers WHERE customer_name LIKE '$customer_name'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		echo(string) 'Customer already exists<br> change name and create a new customer or <a href="/order_management/edit_customer.php?order_id='.$row['customer_id'].'">edit existing customer</a> instead. All orders associated with this customer will be edited likewise ';
		$_SESSION["customer_id"]=$row['customer_id'];

}
}
else{
	echo (string) "customer not found";
}
?>