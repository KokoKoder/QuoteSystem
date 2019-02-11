
######NEED To be rewritten NOT READY ####
###FILE TO DELETE - UNUSED #####
<?php
include(app_path().'/includes/connect.php');
$customer_name=$_POST["customer_name"];
$cust_dup_sql = "SELECT customer_name customer_id FROM customers WHERE customer_name LIKE '$customer_name'";
$cust_dup_result = mysqli_query($conn, $cust_dup_sql);
if (mysqli_num_rows($cust_dup_result) > 0) {
	while($row = mysqli_fetch_assoc($cust_dup_result)) {
		echo(string) 'Customer already exists<br> change name and create a new customer <br><button>Create new customer</button><br> or <a href="/order_management/edit_customer.php?order_id='.$row['customer_id'].'">edit existing customer</a> instead. All orders associated with this customer will be edited likewise ';
		$_SESSION["customer_id"]=$row['customer_id'];

}
}
else{
	echo (string) "customer not found";
}
?>