@extends('layouts.master')
@section('content') 
<?php
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		#connect to the database
		include(app_path().'/includes/connect.php');
		if (isset($_POST['order_status_name'])){
			echo $_POST['order_status_name'];
			$order_status_name=mysqli_real_escape_string($conn,$_POST['order_status_name']);
		}else{
			$err_missing_status="Status name is mandatory - enter name";
		}
		if (!isset($err_missing_status)){
			$order_status_description=mysqli_real_escape_string($conn,$_POST['order_status_description']);
			$is_duplicate_status=FALSE;
			$duplicate_check_sql = "SELECT order_status_name FROM order_status_list WHERE order_status_name  = '$order_status_name '";
			$check_results = mysqli_query($conn, $duplicate_check_sql);
			if (mysqli_num_rows($check_results) > 0) {
				$err_duplicate_status="Status already exists - change name";
			}
			else{
				$sql="INSERT INTO order_status_list(order_status_name,order_status_description) VALUES ('$order_status_name','$order_status_description') ";
				if ($conn->query($sql) === TRUE) {
					$success= "New record created successfully in orders status tab";
				} else {
					$err_connection= "Error: " . $sql . "<br>" . $conn->error;
				}
			}
		}
	}
?>
<div class="section">
	<div class="row">
		<div class="col s12">@php if(isset($err_missing_status)){echo $err_missing_status;}elseif(isset($err_duplicate_status)){echo $err_duplicate_status;}elseif(isset($success)){echo $success;}elseif(isset($err_connection)){echo $err_connection;}@endphp
		</div>
	</div>
	<div class="row">
		<form method="POST" action="{{route('enter_order_status')}}" class="col s12">
		@csrf
			<input class="col s6" type="text" name="order_status_name" placeholder="Order status name">
			
			<input class="col s6" type="text" name="order_status_description" placeholder="Order status description">
			<input class="btn" type="submit">
		</form>
		
	</div>
</div>
@endsection('content')