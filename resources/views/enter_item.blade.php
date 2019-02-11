@extends('layouts.master')
@section('content')  
<?php
#connect to the database
include(app_path().'/includes/connect.php');
include(app_path().'/includes/get_suppliers_list.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	session_start();
	#get the form value
	$item_name=mysqli_real_escape_string($conn,$_POST["item_name"]);
	$item_price=mysqli_real_escape_string($conn,$_POST["item_price"]);
	$item_supplier_id=mysqli_real_escape_string($conn,$_POST["supplier_id"]);
	if ($_POST["supplier_sku"]!=''){
	$supplier_sku=mysqli_real_escape_string($conn,$_POST["supplier_sku"]);
	}
	else{
	$supplier_sku="";
	}
	if ($_POST["item_height"]!=""){
		$item_height=mysqli_real_escape_string($conn,$_POST["item_height"]);
	}
	else{
		$item_height='0';
	}
	if ($_POST["item_weight"]!=""){
		$item_weight=mysqli_real_escape_string($conn,$_POST["item_weight"]);
	}
	else{
		$item_weight='0';
	}
	
	$duplicate_check_sql = "SELECT item_name FROM items WHERE item_name = '$item_name'";
	$check_results = mysqli_query($conn, $duplicate_check_sql);
	if (mysqli_num_rows($check_results) > 0) {
		$err_duplicate_item="Item already exists - change name";
	}

	else{
		$sql = "INSERT INTO items(item_name,supplier_sku,item_supplier_id,item_price,item_height,item_weight)
		VALUES ('$item_name','$supplier_sku','$item_supplier_id','$item_price', '$item_height','$item_weight')";
		if ($conn->query($sql) === TRUE) {
			echo "New record created successfully in orders tab";
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}


		$conn->close();

		if (isset($_SESSION["order_id"])){
		$_SESSION['product_name']=$item_name;
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
			  <input placeholder="Product name" id="item_name" name="item_name" type="text" class="validate">
			  <span>@php if (isset($err_duplicate_item)){echo $err_duplicate_item;}@endphp</span>
			</div>
			<div class="input-field col s6">
			  <input id="item_price" name="item_price" type="text" class="validate">
			  <label for="item_price">Item price in € - Bulk price without taxes. Ex: 25.68</label>
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
					echo '<option selected disabled value="">supplier filter</option>'.$item_supplier_filter;
				?>
				</select>
			 </div>
			 <div class="input-field col s6">
			  <input id="supplier_sku" type="text" name="supplier_sku" class="validate" >
			  <label for="supplier_sku">supplier_sku</label>
			 </div>
		</div>
		<div class="row">
			<div class="input-field col s6">
			  <input id="item_height" type="text" name="item_height" class="validate" >
			  <label for="item_height">Size in m. Ex: 1.25</label>
			</div>

			<div class="input-field col s6">
			  <input id="item_weight" name="item_weight"  type="text" class="validate" >
			  <label for="item_weight">Weight in kg. Ex: 0.5</label>
			</div>
		</div>
		<button type="submit" class="btn">Enter Product</button>
    </form>
  </div>
 </div>
</div> 

@endsection('content')