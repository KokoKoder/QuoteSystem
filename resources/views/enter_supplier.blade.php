@extends('layouts.master')
@section('content')
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	session_start();
	#connect to the database
	include(app_path().'/includes/connect.php');
	#get the form value
	$err_name="";
	if (empty($_POST["supplier_name"])){
		$err_name="supplier name is mandatory";
	}
	else{
		$supplier_name=mysqli_real_escape_string($conn,$_POST["supplier_name"]);
		$supplier_name=trim($supplier_name," ");
	}
	
	if (empty($_POST["supplier_url"])){
		$supplier_url="";
	}
	else{
		$supplier_url=mysqli_real_escape_string($conn,$_POST["supplier_url"]);
	}

	if (empty($_POST["supplier_mail"])){
		$supplier_mail="";
	}
	else{
		$supplier_mail=mysqli_real_escape_string($conn,$_POST["supplier_mail"]);
	}
	
	if (empty($_POST["supplier_phone"])){
		$supplier_phone="";
	}
	else{
		$supplier_phone=mysqli_real_escape_string($conn,$_POST["supplier_phone"]);
	}
	if (empty($_POST["pickup_address"])){
		$pickup_address="";
	}
	else{
		$pickup_address=mysqli_real_escape_string($conn,$_POST["pickup_address"]);
	}

	if (empty($_POST["commercial_contract"])){
		$commercial_contract="";
	}
	else{
		$commercial_contract=mysqli_real_escape_string($conn,$_POST["commercial_contract"]);
	}
	if (empty($_POST["standard_delivery_time"])){
		$standard_delivery_time="3";
	}
	else{
		$standard_delivery_time=mysqli_real_escape_string($conn,$_POST["standard_delivery_time"]);
	}
	if ($err_name!=""){
		$url=route('enter_supplier');
		header("Location: ".$url);
	}
	else
	{
		$sql = "INSERT INTO suppliers (supplier_name,supplier_url, supplier_mail, supplier_phone1, pickup_address, commercial_contract,standard_delivery_time	)
		VALUES ('$supplier_name', '$supplier_url', '$supplier_mail','$supplier_phone','$pickup_address','$commercial_contract',$standard_delivery_time)";
		if ($conn->query($sql) === TRUE) {
			$success= "New record addess successfully in suppliers tab";
		} else {
			$err_name="Error: " . $sql . "<br>" . $conn->error;
		}
		$conn->close();
		$url=route('enter_supplier');
		header("Location: ".$url);
	}

}
?>
  <div class="section no-pad-bot" id="index-banner">
    <div class="container">
      <br><br>
      <h1 class="header center orange-text">Enter Supplier</h1>
      <br><br>
    </div>
  </div>
	<div class="container">
	<div class="section">
		<div class="row">
			<div class="col s12">
			@php if (!empty($err_name)){echo $err_name;}elseif(isset($success)){echo $success;} @endphp
			</div>
			<div class="col s12">
			<p><a href="{{route('suppliers_view')}}">Back to suppliers view</a></p>
			</div>
		</div>
	  <div class="row">
		<form method="POST" action="{{ route('enter_supplier') }}" class="col s12">
		@csrf
		  <div class="row">
			<div class="input-field col s6">
			  <input placeholder="Supplier Name" id="supplier_name" name="supplier_name" type="text" class="validate">
			  <label for="supplier_name">Supplier Name</label>
			</div>
			<div class="input-field col s6">
				<input id="supplier_url" type="text" name="supplier_url" class="validate">
				<label for="supplier_url">Supplier URL</label>
			</div>
		  </div>
		  <div class="row">
			<div class="input-field col s6">
				<i class="material-icons prefix">mail</i>
			  <input placeholder="Supplier email" id="supplier_mail" name="supplier_mail" type="text" class="validate">
			  <label for="supplier_name">Supplier email</label>
			</div>
			<div class="input-field col s6">
			<i class="material-icons prefix">phone</i>
				<input id="supplier_phone" type="text" name="supplier_phone" class="validate">
				<label for="supplier_phone">Supplier Phone</label>
			</div>
		  </div>
		  <div class="row">
			<div class="input-field col s4">
				<input id="standard_delivery_time" type="text" name="standard_delivery_time" class="validate">
				<label for="standard_delivery_time">Standard delivery time (in weeks)</label>
			</div>
			<div class="input-field col s4">
				<i class="material-icons prefix">home</i>
			  <input placeholder="Supplier email" id="pickup_address" name="pickup_address" type="text" class="validate">
			  <label for="pickup_address">Pickup address</label>
			</div>
			<div class="input-field col s4">
				<input id="commercial_contract" type="text" name="commercial_contract" class="validate">
				<label for="commercial_contract">Commercial contract</label>
			</div>
		  </div>
		  <button class="waves-effect waves-light btn" type="submit" value="Submit">Enter Supplier</button>
		</form>
	  </div>
	 </div>
	</div> 
@endsection('content')