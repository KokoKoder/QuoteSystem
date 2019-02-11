@extends('layouts.master')
@section('content')
<?php
include(app_path().'/includes/connect.php');
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		#get the form value
		$supplier_id=mysqli_real_escape_string($conn,$_POST["supplier_id"]);
		$supplier_url=mysqli_real_escape_string($conn,$_POST["supplier_url"]);
		$supplier_mail=mysqli_real_escape_string($conn,$_POST["supplier_mail"]);
		$pickup_address=mysqli_real_escape_string($conn,$_POST["pickup_address"]);
		$commercial_contract=mysqli_real_escape_string($conn,$_POST["commercial_contract"]);
		$standard_delivery_time=mysqli_real_escape_string($conn,$_POST["standard_delivery_time"]);

		$sql_update = "UPDATE suppliers SET supplier_url='$supplier_url', supplier_mail='$supplier_mail',standard_delivery_time='$standard_delivery_time', pickup_address='$pickup_address',commercial_contract='$commercial_contract' WHERE supplier_id='$supplier_id'";
		if ($conn->query($sql_update) === TRUE) {
			echo "Supplier details successfully edited ";
		} else {
			echo "Error: " . $sql_update . "<br>" . $conn->error;
		}
		$sql="SELECT * FROM suppliers WHERE supplier_id='$supplier_id'";	
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {		
			$form_fields='
			<div class="input-field col s6">
				<p>Name</p>
				<input id="supplier_id" type="hidden" name="supplier_id" value="'.$supplier_id.'">
				<input id="supplier_name" name="supplier_name"  value="'.$row["supplier_name"].'">
			</div>
			<div class="input-field col s6">
				<p>URL</p>
				<input id="supplier_url" name="supplier_url" value="'.$row["supplier_url"].'">
			</div>
			<div class="input-field col s6">
				<p>Mail</p>
				<input id="supplier_mail" name="supplier_mail" value="'.$row["supplier_mail"].'">
			</div>
			<div class="input-field col s6">
				<p>Address</p>
				<input name="pickup_address" value="'.$row["pickup_address"].'" class="materialize-textarea">
			</div>
			<div class="input-field col s6">
				<p>Commercial contract</p>
				<input name="commercial_contract" value="'.$row["commercial_contract"].'" >
			</div>
			<div class="input-field col s6">
				<p>standard delivery time in weeks</p>
				<input id="standard_delivery_time" name="standard_delivery_time" value="'.$row["standard_delivery_time"].'" >
			</div>
			<br><br>';
			}
		}
		else{
			echo "<tr><td>No results returned</td></tr>";
		}
	}
	else{
		$supplier_id=mysqli_real_escape_string($conn,$_GET["supplier_id"]);
		$sql="SELECT * FROM suppliers WHERE supplier_id='$supplier_id'";	
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {		
			$form_fields='<input id="supplier_id" type="hidden" name="supplier_id" value="'.$supplier_id.'">
			<div class="input-field col s6">
				<p>Name</p>
				<input id="supplier_name" name="supplier_name"  value="'.$row["supplier_name"].'" placeholder=" ">
			</div>
			<div class="input-field col s6">
				<p>URL</p>
				<input id="supplier_url" name="supplier_url" value="'.$row["supplier_url"].'">
			</div>
			<div class="input-field col s6">
				<p>email</p><input id="supplier_mail" name="supplier_mail" value="'.$row["supplier_mail"].'">
			</div>
			<div class="input-field col s6">
				<p>Address</p><input name="pickup_address" value="'.$row["pickup_address"].'" class="materialize-textarea">
			</div>
			<div class="input-field col s6">
				<p>commercial contract</p><input name="commercial_contract" value="'.$row["commercial_contract"].'" class="materialize-textarea">
			</div>
			<div class="input-field col s6"><p>standard delivery time in weeks</p><input id="standard_delivery_time" name="standard_delivery_time" value="'.$row["standard_delivery_time"].'">
			</div><br><br>';
			}
		}
		else{
			echo "<tr><td>No results returned</td></tr>";
		}
		
	}
?>
<div class="row">
	<div class="col s12">
	 <p><a href="{{route('suppliers_view')}}">Back to suppliers view</a></p>
	</div>
</div>
<div class="row">
<form method="post" action="{{route('edit_supplier')}}" class="col s12">
@csrf
<?php echo $form_fields;?>
<div class="input-field col s12"><button class="btn"><i class="material-icons">done</i></button></div>
<br><br>
</form>
</div>
@endsection
<?php

?>