@extends('layouts.master')
@section('content')  
<?php
#connect to the database
include(app_path().'/includes/connect.php');
include(app_path().'/includes/get_suppliers_list.php');
$pattern = '[,]';
$replacement = '.';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	session_start();
	$order_id=$_SESSION["order_id"];
	#get the form value
	if(empty($_POST["custom_item_name"])){
		$err_name="name is required";
	}
	else{
		$custom_item_name=mysqli_real_escape_string($conn,$_POST["custom_item_name"]);
		echo $custom_item_name;
	}
	if (empty($_POST["custom_item_price"])){
		$err_price="price is required";
	}
	else{
	    $custom_item_price=mysqli_real_escape_string($conn,preg_replace($pattern,$replacement,$_POST["custom_item_price"]));
	}
	if (empty($_POST["custom_item_description"])){
		$custom_item_description="";
	}
	else{
		$custom_item_description=mysqli_real_escape_string($conn,$_POST["custom_item_description"]);
	}
	if (empty($_POST["custom_supplier_id"])){
		$err_supplier="Supplier is required";
	}
	else{
		$custom_supplier_id=mysqli_real_escape_string($conn,$_POST["custom_supplier_id"]);
	}
	if (empty($_POST["custom_item_quantity"])){
		$err_quantity="Quantity is required";
	}
	else{
		$custom_item_quantity=mysqli_real_escape_string($conn,$_POST["custom_item_quantity"]);
	}
	
	if(empty($_POST["custom_schedule_date"])){
		$custom_schedule_date="";
	}
	else{
		$custom_schedule_date=mysqli_real_escape_string($conn,$_POST["custom_schedule_date"]);
	}
	$is_custom="1";
	$status_id="2";
	
	// Required field names
	$required = array('custom_item_name', 'custom_item_price','custom_item_quantity','custom_supplier_id');
	// Loop over field names, make sure each one exists and is not empty
	$error = false;
	foreach($required as $field) {
	  if (empty($_POST[$field])) {
		$error = true;
	  }
	}

	if ($error) {
	  $err_message= "Name, price, quantity ,supplier required"; 
	  if(isset($err_name)){$err_message .= $err_name;}
	  if(isset($err_price)){$err_message .= $err_price;}
	  if(isset($err_supplier)){$err_message .= $err_supplier;}
	  if(isset($err_quantity)){$err_message .= $err_quantity;}
	} 
	else {
		if($custom_schedule_date==""){
			#get the order date to calculate the schedule delivery date by adding the standard delivery time for the supplier
			$order_date_sql="SELECT order_date  FROM orders_table  WHERE order_id='$order_id='";
			$order_date_result = mysqli_query($conn, $order_date_sql);
			if (mysqli_num_rows($order_date_result) > 0) {
				while($row = mysqli_fetch_assoc($order_date_result)) {
					$order_date=$row['order_date'];
					};
			}
			#get the standard delivery time for the supplier 
			$standard_delivery_time_sql="SELECT standard_delivery_time FROM suppliers WHERE supplier_id='$custom_supplier_id'";
			$standard_delivery_time_result = mysqli_query($conn, $standard_delivery_time_sql);
			if (mysqli_num_rows($standard_delivery_time_result) > 0) {
				while($row = mysqli_fetch_assoc($standard_delivery_time_result)) {
					$standard_delivery_time=$row['standard_delivery_time'];
					};
			}
			#calculate the Schedule delivery date
			$custom_schedule_date = date('Y-m-d',strtotime("$order_date +".$standard_delivery_time." weeks"));
			$latest_order_date = date('Y-m-d',strtotime("$custom_schedule_date -1 week"));	
			echo "Schedule delivery date : ".$custom_schedule_date."latest order date: ".$latest_order_date;
		}
		
		$sql = "INSERT INTO custom_items(item_name,custom_item_description,custom_supplier_id,custom_item_price)
		VALUES ('$custom_item_name', '$custom_item_description','$custom_supplier_id','$custom_item_price')";
		if ($conn->query($sql) === TRUE) {
			$item_id = $conn->insert_id;
			echo "New record created successfully in orders tab<br> Item id: ".$item_id ;
			$item_to_order_sql = "INSERT INTO order_custom_items(custom_item_id,order_id,Schedule_delivery_date,item_quantity,status_id)
			VALUES ('$item_id','$order_id','$custom_schedule_date','$custom_item_quantity','$status_id')";
			if ($conn->query($item_to_order_sql) === TRUE) {
				echo "New record created successfully in order items tab";
			} else {
				echo "Error: " . $item_to_order_sql . "<br>" . $conn->error;
				exit;
			}
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}

		$conn->close();
		if (isset($_SESSION["order_id"])){
			header("Location: ". route('add_item_to_order_form'));
		exit;
		}
		else{
			header("Location:". route('enter_item'));
		}
	}
}
?>
  <div class="section no-pad-bot" id="index-banner">
    <div class="container">
      <br><br>
      <h1 class="header center orange-text">Add custom product</h1>

      <br><br>

    </div>
  </div>
<div class="container">
    <div class="section">
  <div class="row">
    <form id="this_form" method="post" action="{{route('add_custom_item_to_order')}}" class="col s12">
	@csrf
	@php if (isset($error)) {echo $err_message;} @endphp
      <div class="row">
        <div class="input-field col s6">
			<p><b>Product name</b></p>
			<input id="custom_item_name" name="custom_item_name" type="text" class="validate">
        </div>
        <div class="input-field col s6">
		<p><b>Item price in €</b></p>
          <input placeholder="Net sales price (without tax). Ex: 25.68" id="custom_item_price" name="custom_item_price" type="text" class="validate">
        </div>
      </div>
	  <div class="row">
		<div class="input-field col s6">
			<p><b>Supplier</b></p>
			<select id="custom_supplier_id" class="col s12 browser-default" style="display:block" name="custom_supplier_id" >
			<?php 
				$item_supplier_filter="";
				foreach ($suppliers_list as $supplier){
					list($option_supplier_id,$option_supplier_name)=preg_split("[,]",$supplier);
					 $item_supplier_filter.='<option value="'.$option_supplier_id.'">'.$option_supplier_name.'</option>';
				}
				echo '<option selected disabled value="">Select supplier </option>'.$item_supplier_filter;
			?>
			</select>
		 </div>
		 <div class="input-field col s6">
			<p><b>Quantity</b></p>
			<input id="custom_item_quantity" name="custom_item_quantity" type="text" class="validate">
		 </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
			<p><b>Product specification</b></p>
			<input placeholder="Enter product specification" id="custom_item_description" type="text" class="materialize-textarea" name="custom_item_description" class="validate" >
        </div>
		<div class="input-field col s6">
			<p><b>Schedule delivery date</b></p>
			<input   type="text" class="datepicker" name="Schedule_delivery_date">
			<span class="helper-text">Default to supplier delivery time if left empty</span>
		</div>
      </div>
	<button type="submit" class="btn">Enter Custom Product</button>
    </form>
  </div>
 </div>
</div> 

@endsection('content')
@push('script')
	document.getElementById("this_form").onkeypress = function(e) {
	  var key = e.charCode || e.keyCode || 0;     
	  if (key == 13) {
		alert("I told you not to, why did you do it?");
		e.preventDefault();
	  }
	}
@endpush