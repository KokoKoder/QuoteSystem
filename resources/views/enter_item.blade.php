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
	#get the form value
	if ($_POST["item_name"]!=""){
	   $item_name=mysqli_real_escape_string($conn,$_POST["item_name"]);
	}
	else{
	    $err_empty_name="Name is mandatory - enter a name";
	}
	$item_price=mysqli_real_escape_string($conn,preg_replace($pattern,$replacement,$_POST["item_price"]));
	if ($_POST["supplier_id"]!=""){
	   $item_supplier_id=mysqli_real_escape_string($conn,$_POST["supplier_id"]);
	}
	else{
	    $err_empty_supplier="supplier name is mandatory - choose a supplier from the list<br><a href='https://orders.furnest.ee/enter_supplier'>enter supplier</a>";
	}
	if ($_POST["supplier_sku"]!=''){
	$supplier_sku=mysqli_real_escape_string($conn,$_POST["supplier_sku"]);
	}
	else{
	$supplier_sku="";
	}
	if ($_POST["item_height"]!=""){
	    $item_height=mysqli_real_escape_string($conn,preg_replace($pattern,$replacement,$_POST["item_height"]));
	}
	else{
		$item_height='0';
	}
	if ($_POST["item_weight"]!=""){
	    $item_weight=mysqli_real_escape_string($conn,preg_replace($pattern,$replacement,$_POST["item_weight"]));
	}
	else{
		$item_weight='0';
	}
	if ($_POST["item_length"]!=""){
	    $item_length=mysqli_real_escape_string($conn,preg_replace($pattern,$replacement,$_POST["item_length"]));
	}
	else{
	    $item_length='0';
	}
	if ($_POST["item_width"]!=""){
	    $item_width=mysqli_real_escape_string($conn,preg_replace($pattern,$replacement,$_POST["item_width"]));
	}
	else{
	    $item_width='0';
	}
	if ($_POST["item_description"]!=""){
	    $item_description=mysqli_real_escape_string($conn,preg_replace($pattern,$replacement,$_POST["item_description"]));
	}
	else{
	    $item_description='0';
	}
	if ($_POST["package_length"]!=""){
	    $package_length=mysqli_real_escape_string($conn,preg_replace($pattern,$replacement,$_POST["package_length"]));
	}
	else{
	    $package_length='0';
	}
	if ($_POST["package_width"]!=""){
	    $package_width=mysqli_real_escape_string($conn,preg_replace($pattern,$replacement,$_POST["package_width"]));
	}
	else{
	    $package_width='0';
	}
	if ($_POST["package_height"]!=""){
	    $package_height=mysqli_real_escape_string($conn,preg_replace($pattern,$replacement,$_POST["package_height"]));
	}
	else{
	    $package_height='0';
	}
	if ($_POST["package_weight"]!=""){
	    $package_weight=mysqli_real_escape_string($conn,preg_replace($pattern,$replacement,$_POST["package_weight"]));
	}
	else{
	    $package_weight='0';
	}
	if ($_POST["item_per_pack"]!=""){
	$item_per_pack=mysqli_real_escape_string($conn,$_POST["item_per_pack"]);
	}
	else{
	    $item_per_pack='0';
	}
	$duplicate_check_sql = "SELECT item_name FROM items WHERE item_name = '$item_name'";
	$check_results = mysqli_query($conn, $duplicate_check_sql);
	
	if (mysqli_num_rows($check_results) > 0) {
		$err_duplicate_item="Item already exists - change name";
	}
	elseif(isset($err_empty_name) OR isset($err_empty_supplier) ){
	    #do not proceed with sql query
	}
	else{
		$sql = "INSERT INTO items(item_name,supplier_sku,item_supplier_id,item_price,item_length,item_width,item_height,item_weight,item_description,package_length,package_width,package_height,package_weight,item_per_pack)
		VALUES ('$item_name','$supplier_sku','$item_supplier_id','$item_price', '$item_length','$item_width','$item_height','$item_weight','$item_description','$package_length','$package_width','$package_height','$package_weight','$item_per_pack')";
		if ($conn->query($sql) === TRUE) {
			echo "New record created successfully in items tab";
		} else {
		    $_SESSION['err_message']="Error: " . $sql . "<br>" . $conn->error;
		    echo  $_SESSION['err_message'];
		}
		
		$conn->close();
		
		if (isset($_SESSION["order_id"]) ){
		    if (!isset($_SESSION['err_message'])){$_SESSION['product_name']=$item_name;}
    		$url=route('add_item_to_order_form');
    		header("Location: ".$url);
    		exit;
		}
		else{
			$url=route('enter_item');
			header("Location: ".$url);
		}
	}
}
?>
  <div class="section no-pad-bot" id="index-banner">
    <div class="container">
      <br><br>
      <h1 class="header center orange-text">Enter product</h1>
      <br><br>
    </div>
  </div>
<div class="container">
    <div class="section">
  <div class="row">
    <form method="post" action="{{route('enter_item')}}" class="col s12">
	@csrf
		<div class="row">
			<div class="input-field col s6">
			  <input placeholder="Product name*" id="item_name" name="item_name" type="text" class="validate">
			  <span>@php if (isset($err_duplicate_item)){echo $err_duplicate_item;}@endphp</span>
			</div>
			<div class="input-field col s6">
			  <input id="item_price" name="item_price" type="text" class="validate">
			  <label for="item_price">Item price* in € - Bulk price without taxes. Ex: 25.68</label>
			</div>
		</div>
		<div class="row">
			<div class="input-field col s6">
				<select id="supplier_id" class="col s12 browser-default" style="display:block" name="supplier_id" >
				<?php 
					$item_supplier_filter="";
					foreach ($suppliers_list as $supplier){
						list($option_supplier_id,$option_supplier_name)=preg_split("[,]",$supplier);
						 $item_supplier_filter.='<option value="'.$option_supplier_id.'">'.$option_supplier_name.'</option>';
					}
					echo '<option selected disabled value="">supplier filter*</option>'.$item_supplier_filter;
				?>
				</select>
			 </div>
			 <div class="input-field col s6">
			  <input id="supplier_sku" type="text" name="supplier_sku" class="validate" >
			  <label for="supplier_sku">supplier_sku*</label>
			 </div>
		</div>
		<div class="row">
			<div class="input-field col s6 m3">
			  <input id="item_width" type="text" name="item_width" class="validate" >
			  <label for="item_width">Product width in mm Ex: 1250</label>
			</div>

			<div class="input-field col s6 m3">
			  <input id="item_length" name="item_length"  type="text" class="validate" >
			  <label for="item_length">Product length in mm</label>
			</div>

			<div class="input-field col s6 m3">
			  <input id="item_height" type="text" name="item_height" class="validate" >
			  <label for="item_height">Product height in mm</label>
			</div>
			<div class="input-field col s6 m3">
			  <input id="item_weight" type="text" name="item_weight" class="validate" >
			  <label for="item_weight">Product weight in kg - Ex: 2.5</label>
			</div>
		</div>
		<div class="row">
        	<div class="input-field col s12">
          		<textarea id="item_description" class="materialize-textarea" name="item_description" class="validate"></textarea>
          		<label for="item_description">Product description</label>
        	</div>
      	</div>
		<div class="row">
			<div class="input-field col s6 m3">
			  <input id="package_width" type="text" name="package_width" class="validate" >
			  <label for="package_width">Package width in mm</label>
			</div>
			<div class="input-field col s6 m3">
			  <input id="package_length" name="package_length"  type="text" class="validate" >
			  <label for="package_length">Package length</label>
			</div>

			<div class="input-field col s6 m3">
			  <input id="package_height" type="text" name="package_height" class="validate" >
			  <label for="package_height">Package height</label>
			</div>
			<div class="input-field col s6 m3">
			  <input id="package_weight" name="package_weight"  type="text" class="validate" >
			  <label for="package_weight">Package weight in kg - Ex: 2.5</label>
			</div>
		</div>
		<div class="row">
			<div class="input-field col s6 m3">
			  <input id="item_per_pack" type="text" name="item_per_pack" class="validate" >
			  <label for="item_per_pack">Item per package </label>
			  <span class="helper-text">2 for 2 items per pack <br> 1/2 for 2 packs per item <br> 1/3 for item coming in 3 packs</span>
			</div>
		</div>
		<button type="submit" class="btn">Enter Product</button>
    </form>
  </div>
 </div>
</div> 

@endsection('content')