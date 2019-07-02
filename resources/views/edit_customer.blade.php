<?php
session_start();
include(app_path().'/includes/connect.php');
include(app_path().'/includes/parse.php');
include(app_path().'/includes/get_vendors_list.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $err_duplicate_custome="";
	$customer_id=$_SESSION["customer_id"];
	$customer_name=mysqli_real_escape_string($conn,$_POST["customer_name"]);
	$customer_address=mysqli_real_escape_string($conn,$_POST["customer_address"]);
	$customer_mail=mysqli_real_escape_string($conn,$_POST["customer_mail"]);
	$customer_phone=mysqli_real_escape_string($conn,$_POST["customer_phone"]);
	$vat_id=mysqli_real_escape_string($conn,$_POST["vat_id"]);
	$registration_nb=mysqli_real_escape_string($conn,$_POST["registration_nb"]);
	
	$sql_duplicate_name = "SELECT customer_name,customer_id FROM customers WHERE customer_name='$customer_name'";
	$result_duplicate_name = mysqli_query($conn, $sql_duplicate_name);
	if (mysqli_num_rows($result) > 0) {
	    while($row = mysqli_fetch_assoc($result_duplicate_name)) {
	        if($row['customer_id']!=$customer_id){
	            $err_duplicate_customer="Customer already exists";
	        };
	    }
	        
	    }
	if (empty($err_duplicate_customer)){
    	$sql_update="UPDATE customers SET customer_name='$customer_name', customer_address='$customer_address', customer_mail='$customer_mail',customer_phone='$customer_phone','registration_nb'=$registration_nb,vat_id='$vat_id' WHERE customer_id='$customer_id'";
    	echo "POST to customer ID : ".$customer_id."address".$customer_address."mail".$customer_mail."phone".$customer_phone;
    	if ($conn->query($sql_update) === TRUE) {
        echo "Record updated successfully";
    	} else {
    		echo "Error updating record: " . $conn->error;
    	}
    	if (isset($_SESSION["order_edit"])){
    		$url=route('edit_order');
    		header ("Location: ".$url."?order_id=".$_SESSION["order_id"]);
    		exit;
    	}
	}
}

if (isset($_GET["customer_id"])){
	$_SESSION["customer_id"]=mysqli_real_escape_string($conn,$_GET["customer_id"]);
	$customer_id=$_SESSION["customer_id"];
	$sql="SELECT customer_name,customer_address,customer_mail,customer_phone,registration_nb,vat_id FROM customers WHERE customer_id='$customer_id'";
	$customer_details=[];
	$result=mysqli_query($conn,$sql);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$customer_details[]=$row['customer_name'];
			$customer_details[]=$row['customer_address'];
			$customer_details[]=$row['customer_mail'];
			$customer_details[]=$row['customer_phone'];
			$customer_details[]=$row['vat_id'];
			$customer_details[]=$row['registration_nb'];
			};
	}
}else{
	$customer_id=$_SESSION["customer_id"];
	$sql="SELECT customer_name,customer_address,customer_mail,customer_phone,registration_nb,vat_id FROM customers WHERE customer_id='$customer_id'";
	$customer_details=[];
	$result=mysqli_query($conn,$sql);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$customer_details[]=$row['customer_name'];
			$customer_details[]=$row['customer_address'];
			$customer_details[]=$row['customer_mail'];
			$customer_details[]=$row['customer_phone'];
			$customer_details[]=$row['vat_id'];
			$customer_details[]=$row['registration_nb'];
			};
	}
}

?>
@extends('layouts.master')
@section('content')
    <div class="section">
		<div class="row">
			<div class="col s12">
				<h2>Customer details:</h2>
			</div>
		</div>
	</div>
    <div class="section">
  <div class="row">
    <form autocomplete="off" method="POST" action="{{route('edit_customer')}}" class="col s12">

	@csrf
	  <div class="row">
		<div class="col s12">
	  
	  	</div>
	</div>
	<div class="row">

		
		  <div class="input-field col s6">	
			<i class="material-icons prefix">account_circle</i><input name="customer_name" id="customer_name" value="<?php if (isset($customer_id)){echo $customer_details[0];} ?>" >
		  <span class="helper-text">Enter customer name</span>
		  <span style="color:red"><?php if (!empty($err_duplicate_customer)){echo $err_duplicate_customer;}?></span>
		  </div>
		  <div class="input-field col s6">
		  <i class="material-icons prefix">home</i>
			<input id="address" type="text" name="customer_address" value="<?php if (isset($customer_id)){echo $customer_details[1];} ?>">
			<span class="helper-text">Enter customer address</span>
		  </div>

	</div>
	
	<div class="row">
		  <div class="input-field col s6">
		  <i class="material-icons prefix">mail</i>
			<input id="email" name="customer_mail" type="email" class="validate" value="<?php if (isset($customer_id)){echo $customer_details[2];} ?>">
			<span class="helper-text" data-error="wrong" data-success="right">Enter customer email</span>
		  </div>
		  
		  <div class="input-field col s6">
		  <i class="material-icons prefix">phone</i>
			<input id="phone" type="tel" name="customer_phone" class="validate" value="<?php if (isset($customer_id)){echo $customer_details[3];} ?>">
			<span class="helper-text">Enter customer phone</span>
		  </div>

	</div>
	<div class="row">
		  <div class="input-field col s6">
		  <i class="material-icons prefix">V</i>
			<input id="vat_id" type="text" name="vat_id"  value="<?php if (isset($customer_id)){echo $customer_details[4];} ?>">
			<span class="helper-text">Enter VAT ID</span>
		  </div>
		  <div class="input-field col s6">
		  <i class="material-icons prefix">R</i>
			<input id="registration_nb" type="text" name="registration_nb"  value="<?php if (isset($customer_id)){echo $customer_details[5];} ?>">
			<span class="helper-text">Enter Registration number</span>
		  </div>
	</div>
	  <button class="waves-effect waves-light btn" type="submit" value="Submit">Edit customer details</button>
    </form>
  </div>
 </div>
</div> 
@endsection
@push('scripts')
	// Get list of existing Customers
	jQuery(document).ready(main); function main() {  
	jQuery('#customer_name').keyup(get_matching_customers);
	};
	function get_matching_customers(){
		var search_query =jQuery('#customer_name').val();
		if(search_query != "" && search_query .length > 0 ) {
			jQuery.get("get_customers_live", { customer_name:search_query }, write_item_suggestion_to_page);
		}
		else{
			console.log('Search term empty or too short.');} 
		};
	function write_item_suggestion_to_page(data,status, xhr) {
		if (status == "error") {
			var msg = "Sorry but there was an error: ";
			console.error(msg + xhr.status + " " + xhr.statusText);   
			}   
		else   {
				var customer_suggestions =  [];
				customer_suggestions =  data.split(',');
				autocomplete(document.getElementById("customer_name"), customer_suggestions);
				
		}		
	}	 
	function get_customer_details(){
	jQuery.get("get_customer_details", { customer_name:search_query }, fill_customer_details);
	}	
	function fill_customer_details(data,status, xhr){
		var customer_details=data.split(',');
		$("#address").val(customer_details[1]);
		$("#phone").val(customer_details[2]);
		$("#email").val(customer_details[4]);
		$("#customer_id").val(customer_details[3]);
	}
@endpush

