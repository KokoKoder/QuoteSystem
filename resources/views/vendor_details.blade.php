<?php
session_start();
include(app_path().'/includes/connect.php');
include(app_path().'/includes/parse.php');
include(app_path().'/includes/get_vendors_list.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$vendor_id=mysqli_real_escape_string($conn,$_POST["vendor_id"]);
	$_SESSION['vendor_id']=$vendor_id;
	$company_name=mysqli_real_escape_string($conn,$_POST["company_name"]);
	$address=mysqli_real_escape_string($conn,$_POST["address"]);
	$email=mysqli_real_escape_string($conn,$_POST["email"]);
	$phone=mysqli_real_escape_string($conn,$_POST["phone"]);
	$rg_kood=mysqli_real_escape_string($conn,$_POST["rg_kood"]);
	$konto=mysqli_real_escape_string($conn,$_POST["konto"]);
	$vat_nb=mysqli_real_escape_string($conn,$_POST["vat_nb"]);

	$check_duplicate="SELECT vendor_id FROM vendor_address WHERE vendor_id='$vendor_id'";
	$check_result=mysqli_query($conn, $check_duplicate);
	if (mysqli_num_rows($check_result) > 0) {
		echo "duplicate found";
		header("location: ".route('edit_vendor_details').'?vendor_id='.$vendor_id);
		exit;
	}
	else{
		$sql="INSERT INTO vendor_address (vendor_id,company_name, address, email, phone, rg_kood, konto, eu_vat_nb) VALUES ($vendor_id,'$company_name','$address','$email','$phone','$rg_kood', '$konto','$vat_nb') ";
		if ($conn->query($sql) === TRUE) {
		echo "Record updated successfully";
		} else {
			echo "Error updating record: " . $conn->error;
		}
	}
}


?>
@extends('layouts.master')
@section('content')
    <div class="section">
		<div class="row">
			<div class="col s12">
				<h2>Create Vendor details:</h2>
			</div>
		</div>
	</div>
    <div class="section">
  <div class="row">
    <form autocomplete="off" method="POST" action="{{route('vendor_details')}}" class="col s12">

	@csrf
	  <div class="row">
		<div class="col s12">
	  
	  	</div>
	</div>
	<div class="row">

			<div class="col s12">
				<select id="vendor_selection" name="vendor_id" class="browser-default">
				<option disabled selected value="">Select Vendor</option>
				@php
					foreach ($vendors_list as $vendor ){
						var_dump($vendors_list);
						list($select_vendor_id,$vendor_name)=preg_split("[,]", $vendor );
						echo '<option value="'.$select_vendor_id.'">'.$vendor_name.'</option>';
					}
				@endphp
				</select>
			</div>
		  <div class="input-field col s12">
		  <i class="material-icons prefix">account_circle</i>
			<input id="company_name" type="text" name="company_name" >	
		  </div>
		  <div class="input-field col s12 m6">
		  <i class="material-icons prefix">home</i>
			<input id="address" type="text" name="address" >
			<span class="helper-text">Enter address</span>
		  </div>
		  <div class="input-field col s12 m6">
		  <i class="material-icons prefix"></i>
			<input id="vat_nb" name="vat_nb" type="text" class="validate" >
			<span class="helper-text" >VAT Number</span>
		  </div>

	</div>
	
	<div class="row">
		  <div class="input-field col s12 m6">
		  <i class="material-icons prefix">mail</i>
			<input id="email" name="email" type="email" class="validate">
			<span class="helper-text" data-error="wrong" data-success="right">Enter vendor email</span>
		  </div>
		  
		  <div class="input-field col s12 m6">
		  <i class="material-icons prefix">phone</i>
			<input id="phone" type="tel" name="phone" class="validate">
			<span class="helper-text">Enter vendor phone</span>
		  </div>
	</div>
	<div class="row">
		  <div class="input-field col s12 m6">
		  <i class="material-icons prefix"></i>
			<input id="rg_kood" name="rg_kood" type="text" class="validate" >
			<span class="helper-text" >Company registration code</span>
		  </div>
		  
		  <div class="input-field col s12 m6">
		  <i class="material-icons prefix">B</i>
			<input id="konto" type="text" name="konto" class="validate" >
			<span class="helper-text">Bank account</span>
		  </div>
	</div>
	<div class="row">

	</div>
	  <button class="waves-effect waves-light btn" type="submit" value="Submit">Enter vendor details</button>
    </form>
  </div>
 </div>
</div> 
@endsection
@push('scripts')
	jQuery('#vendor_selection').change(get_vendor_details);
	function get_vendor_details(){
	var	search_query=jQuery("#vendor_selection").val();
	jQuery.get("get_vendor_details", { vendor_id:search_query }, fill_vendor_details);
	}	
	function fill_vendor_details(data,status, xhr){
		var vendor_details=data.split('¤');
		$("#address").val(vendor_details[1]);
		$("#phone").val(vendor_details[3]);
		$("#email").val(vendor_details[2]);
		$("#rg_kood").val(vendor_details[4]);
		$("#konto").val(vendor_details[5]);
		$("#vat_nb").val(vendor_details[6]);
		$("#company_name").val(vendor_details[7]);
		console.log(vendor_details);
	}
@endpush

