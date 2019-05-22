@extends('layouts.master')
@section('content')
<?php
session_start();
include(app_path().'/includes/get_vendors_list.php');
include(app_path().'/includes/get_order_status_list.php');
include(app_path().'/includes/connect.php');
$user_id=auth()->user()->id;
$sql = "SELECT * FROM orders_table ORDER BY order_id DESC LIMIT 1";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $i=(int) $row['order_id'];
    }
}
$_SESSION["order_number"]=  date("y").'-'.$user_id.$i;
include(app_path().'/includes/verify_order_number_for_duplicate.php');
while($is_duplicate){
    $i+=1;
    $_SESSION["order_number"]= date("y").'-'.$user_id.$i;
    include(app_path().'/includes/verify_order_number_for_duplicate.php');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	#make sure there is session variable that can interact with the process of creating a new order
	if (isset($_SESSION["order_edit"])){unset($_SESSION["order_edit"]);}
	$is_furnest=FALSE;
	
	#connect to the database
	include(app_path().'/includes/connect.php');

	#get the form value
	if (empty($_POST["order_number"])) {
		$order_number_err = "order number is required";	
	}
	else{
		$order_number=mysqli_real_escape_string($conn,$_POST["order_number"]);
		$order_number=trim($order_number," ");
		$order_number=preg_replace ( "[ ]","_",$order_number);
		$_SESSION["order_number"]=$order_number;
		include(app_path().'/includes/verify_order_number_for_duplicate.php');
	}
	if (empty($_POST["order_date"])) {
	$order_date = date('y-m-d',time()) ;
		echo "order date empty, set default date to today's date";	
	}else{
		
	$order_date=mysqli_real_escape_string($conn,$_POST["order_date"]);
		echo $order_date;
	}
	if (empty($_POST["vendor_id"])) {
		$vendor_err = "vendor name is required";	
	}else{
		$vendor=mysqli_real_escape_string($conn,$_POST["vendor_id"]);
		#check if vendor is furnest to set customer as is_furnest and register order with default status
		foreach($vendors_list as $vendor_elem){
			list($vendor_id,$vendor_name)=preg_split("[,]",$vendor_elem);
			if ($vendor_id==$vendor AND substr($vendor_name,0,3)=="Fur"){$is_furnest=TRUE;break;}
		}
	}
	if (empty($_POST["customer_name"])) {
		$name_err="customer name is required";
	}
	else{
		$name=mysqli_real_escape_string($conn,$_POST["customer_name"]);
		$name=trim($name ," ");
	}
	if (empty($_POST["customer_address"])){
		$address=mysqli_real_escape_string($conn,"");
	}
	else{
		$address=mysqli_real_escape_string($conn,$_POST["customer_address"]);
	}
	if (empty($_POST["customer_mail"])){
		$email=mysqli_real_escape_string($conn,"");
	}
	else{
		$email=mysqli_real_escape_string($conn,$_POST["customer_mail"]);
	}
	if(empty($_POST["customer_phone"])){
		$phone=mysqli_real_escape_string($conn,"");
	}else{
		$phone=mysqli_real_escape_string($conn,$_POST["customer_phone"]);
	}
	if(empty($_POST["vat_id"])){
		$vat_id=mysqli_real_escape_string($conn,"");
	}else{
		$vat_id=mysqli_real_escape_string($conn,$_POST["vat_id"]);
	}
	if(empty($_POST["customer_id"])){
		$customer_id="";
	}else{
		$customer_id=mysqli_real_escape_string($conn,$_POST["customer_id"]);
	}
	if(!empty($_POST["order_status_id"])){
	$order_status_id=mysqli_real_escape_string($conn,$_POST["order_status_id"]);
	}
	else{
		$order_status_id="";
	}
	

	//Check that no required fields were left empty	
	if (isset($order_number_err) OR isset($vendor_err) OR isset($name_err)) {
        $missing_required_fields="Check that <b>order number</b>, <b>order date</b>, <b>supplier</b> and <b>customer name</b> are filled properly before submitting the form.";
	}
	elseif ($is_duplicate){
		$edit_order = route('edit_order');
		$check_for_duplicate='Order number already exists<br> change or <a href="'.$edit_order.'?order_id='.$_SESSION["duplicate_id"].'">edit order</a> instead? ';
	}
	else{
			
		//Check if customer id is set in which case a new entry won't be created.}
		if ($customer_id==""){
			if ($is_furnest){
				$sql1 = "INSERT INTO customers (customer_name, customer_phone, customer_mail, customer_address,vat_id, is_furnest) VALUES ('$name', '$phone', '$email','$address','$vat_id','$is_furnest')";
			}
			else{
				$sql1 = "INSERT INTO customers (customer_name, customer_phone, customer_mail, customer_address,vat_id, is_furnest) VALUES ('$name', '$phone', '$email','$address','$vat_id','0')";
			}
			if ($conn->query($sql1) === TRUE) {
				$customer_id = $conn->insert_id;
				echo "New record created successfully in customers tab". $customer_id;
			} else {
				echo "Error: " . $sql1 . "<br>" . $conn->error;
			}
			$sql5 ="INSERT INTO salesteam_customers (user_id,customer_id) VALUES ('$user_id',$customer_id)";
			if ($conn->query($sql5) === TRUE) {
			    echo "New record created successfully in salesteam_customers table";
			} else {
			    echo "Error: " . $sql5 . "<br>" . $conn->error;
			}
		}
		$sql = "INSERT INTO orders_table (order_number, order_date, customer_id, vendor_id)
			VALUES ('$order_number', '$order_date', '$customer_id','$vendor')";
		if ($conn->query($sql) === TRUE) {
			echo "New record created successfully in orders tab";
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}

		$sql3 = "SELECT order_id FROM orders_table WHERE order_number='$order_number'";
		$result = mysqli_query($conn, $sql3);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				echo(string) 'result : '.$row['order_id'];
				$_SESSION["order_id"]=$row['order_id'];
				};
		}
		if ($order_status_id=="" AND $is_furnest ){
			$order_status_id=1;
		}
		elseif ($order_status_id=="" AND !$is_furnest){
			$order_status_id=2;
		}
		$order_id=$_SESSION["order_id"];
		
		$sql2 = "INSERT INTO orders_status (order_id, order_status_id) VALUES ('$order_id', '$order_status_id')";
		if ($conn->query($sql2) === TRUE) {
			echo "New record created successfully in order_status tab";
		} else {
			echo "Error: " . $sql2 . "<br>" . $conn->error;
		}
		$sql4="INSERT INTO salesteam_orders (user_id, order_id) VALUES ('$user_id','$order_id')";
		if ($conn->query($sql4) === TRUE) {
		    echo "New record created successfully in salesteam_orders table";
		} else {
		    echo "Error: " . $sql4 . "<br>" . $conn->error;
		}
		$conn->close();
		$header_location_url=route('add_item_to_order_form');
		header("Location: ".$header_location_url);
		exit;
	}
}

?>
<div class="section">
<?php if (isset($missing_required_fields)){echo $missing_required_fields; }
if (isset($is_duplicate) AND isset($check_for_duplicate)){echo $check_for_duplicate;}
?>
  <div class="row">
    <form autocomplete="off" method="POST" action="{{route('enter_order')}}" class="col s12">
	@csrf
      <div class="row">
        <div class="input-field col s6">
          <input  id="order_number" name="order_number" type="text" value="<?php echo $_SESSION["order_number"]; ?>">
          <label id="order_number_label" for="order_number">Order Number</label>
		  <span id="order_number_alert"></span>
        </div>
        <div class="input-field col s6">
			<input id="order_date" type="text" name="order_date" class="datepicker">
			<label for="order_date">Order date</label>
        </div>
      </div>
      <div class="row">
		<div name="vendor" id="vendor" class="input-field col s6">
			@if (Auth::user()->is_admin)
			<select id="vendor_select" name="vendor_id" class="browser-default">
				<option value="" disabled selected>Vendor</option>
				<?php 
					foreach($vendors_list as $vendor){
						list($vendor_id,$vendor_name)=preg_split("[,]",$vendor);
						echo ('<option value="'.$vendor_id.'">'.$vendor_name.'</option>');
					}
				?>
			</select>
			@else
			<input hidden id="vendor_select" name="vendor_id" type="text"  value="14">
			@endif
		 </div>
		 <div  class="input-field col s6">
			<select id="order_status_select" name="order_status_id" class="browser-default">
				<option value="" disabled selected>Order status</option>
				<?php 
					foreach($order_status_list as $order_status_elem){
						list($order_satus_id,$order_satus_name)=preg_split("[,]",$order_status_elem);
						echo ('<option value="'.$order_satus_id.'">'.$order_satus_name.'</option>');
					}
				?>
			</select>
		 </div>
      </div>
	  <div class="row">
		<div class="col s12">
	  Customer details:
	  	</div>
	</div>
	<div class="row">
		<div class="col s12">
		  <div class="input-field col s6">
		  <i class="material-icons prefix">account_circle</i>
			<input id="customer_name" type="text" name="customer_name">
			<span class="helper-text" >Enter customer name</span>
			<input id="customer_id" type="hidden" name="customer_id" >
		  </div>
		  <div class="input-field col s6">
		  <i class="material-icons prefix">home</i>
			<input id="address" type="text" name="customer_address" >
			<span class="helper-text">Enter customer address</span>
		  </div>
		</div >
	</div>
	
	<div class="row">
		<div class="col s12">
		  <div class="input-field col s6">
		  <i class="material-icons prefix">mail</i>
			<input id="email" name="customer_mail" type="email" class="validate">
			<span class="helper-text" data-error="wrong" data-success="right">Enter customer email</span>
		  </div>
		  
		  <div class="input-field col s6">
		  <i class="material-icons prefix">phone</i>
			<input id="phone" type="tel" name="customer_phone" class="validate">
			<span class="helper-text">Enter customer phone</span>
		  </div>
		</div>
	</div>
	<div class="row">
		<div class="input-field col s6">
		<i class="material-icons prefix">V</i>
		<input id="vat_id" type="text" name="vat_id">
		<span class="helper-text">Enter customer VAT ID</span>
		</div>
		<div class="col 6"><p><a href="http://ec.europa.eu/taxation_customs/vies/" target="_blank">Check VAT ID</a></p></div>
	</div>
	  <button id="submit_form" class="waves-effect waves-light btn" type="submit" value="Submit">Enter order</button>
    </form>
  </div>
 </div>

 @push('scripts')
 //ALERT for duplicate order
	jQuery('#order_number').keyup(verify_order_for_duplicate);
	function verify_order_for_duplicate(){
		var search_query =$.trim(jQuery('#order_number').val());
		if(search_query != "" && search_query .length > 2 ) {
			jQuery.get("verify_order_number_for_duplicate", { order_number:search_query }, send_alert_to_page);
		}
		else{
			console.log('Search term empty or too short.');} 
	};
	function send_alert_to_page(data){
		
		if (data!="order not found"){
			$('#submit_form').css('display', 'none');
			$( "#order_number_alert" ).html(data);
			console.log(data);
		}
		else{
		$('#submit_form').css('display', 'block');
		$( "#order_number_alert" ).html('');
		}
	}
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
	/*function get_customer_details(){
	jQuery.get("get_customer_details", { customer_name:search_query }, fill_customer_details);
	}*/	
	function fill_customer_details(data,status, xhr){
		var customer_details=data.split(';');
		$("#address").val(customer_details[1]);
		$("#phone").val(customer_details[2]);
		$("#email").val(customer_details[4]);
		$("#customer_id").val(customer_details[3]);
		console.log(customer_details,' : ',customer_details[4]);
	}
	
		//AUTOCOMPLETE
			function autocomplete(inp, arr) {
			  /*the autocomplete function takes two arguments,
			  the text field element and an array of possible autocompleted values:*/
			  var currentFocus;
			  /*execute a function when someone writes in the text field:*/
			  inp.addEventListener("input", function(e) {
				  var a, b, i, val = this.value;
				  /*close any already open lists of autocompleted values*/
				  closeAllLists();
				  if (!val) { return false;}
				  currentFocus = -1;
				  /*create a DIV element that will contain the items (values):*/
				  a = document.createElement("DIV");
				  a.setAttribute("id", this.id + "autocomplete-list");
				  a.setAttribute("class", "autocomplete-items");
				  /*append the DIV element as a child of the autocomplete container:*/
				  this.parentNode.appendChild(a);
				  /*for each item in the array...*/
				  for (i = 0; i < arr.length; i++) {
					/*check if the item starts with the same letters as the text field value:*/
					if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
					  /*create a DIV element for each matching element:*/
					  b = document.createElement("DIV");
					  /*make the matching letters bold:*/
					  b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
					  b.innerHTML += arr[i].substr(val.length);
					  /*insert a input field that will hold the current array item's value:*/
					  b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
					  /*execute a function when someone clicks on the item value (DIV element):*/
					  b.addEventListener("click", function(e) {
						  /*insert the value for the autocomplete text field:*/
						  inp.value = this.getElementsByTagName("input")[0].value;
						  /*fill in the customer details for the selected customer name*/
						  var search_query=inp.value;
						  jQuery.get("get_customer_details", { customer_name:search_query }, fill_customer_details);
						  /*close the list of autocompleted values,
						  (or any other open lists of autocompleted values:*/
						  closeAllLists();
					  });
					  a.appendChild(b);
					}
				  }
			  });
			  /*execute a function presses a key on the keyboard:*/
			  inp.addEventListener("keydown", function(e) {
				  var x = document.getElementById(this.id + "autocomplete-list");
				  if (x) x = x.getElementsByTagName("div");
				  if (e.keyCode == 40) {
					/*If the arrow DOWN key is pressed,
					increase the currentFocus variable:*/
					currentFocus++;
					/*and and make the current item more visible:*/
					addActive(x);
				  } else if (e.keyCode == 38) { //up
					/*If the arrow UP key is pressed,
					decrease the currentFocus variable:*/
					currentFocus--;
					/*and and make the current item more visible:*/
					addActive(x);
				  } else if (e.keyCode == 13) {
					/*If the ENTER key is pressed, prevent the form from being submitted,*/
					e.preventDefault();
					if (currentFocus > -1) {
					  /*and simulate a click on the "active" item:*/
					  if (x) x[currentFocus].click();
					}
				  }
			  });
			  function addActive(x) {
				/*a function to classify an item as "active":*/
				if (!x) return false;
				/*start by removing the "active" class on all items:*/
				removeActive(x);
				if (currentFocus >= x.length) currentFocus = 0;
				if (currentFocus < 0) currentFocus = (x.length - 1);
				/*add class "autocomplete-active":*/
				x[currentFocus].classList.add("autocomplete-active");
			  }
			  function removeActive(x) {
				/*a function to remove the "active" class from all autocomplete items:*/
				for (var i = 0; i < x.length; i++) {
				  x[i].classList.remove("autocomplete-active");
				}
			  }
			  function closeAllLists(elmnt) {
				/*close all autocomplete lists in the document,
				except the one passed as an argument:*/
				var x = document.getElementsByClassName("autocomplete-items");
				for (var i = 0; i < x.length; i++) {
				  if (elmnt != x[i] && elmnt != inp) {
					x[i].parentNode.removeChild(x[i]);
				  }
				}
			  }
			  /*execute a function when someone clicks in the document:*/
			  document.addEventListener("click", function (e) {
				  closeAllLists(e.target);
			  });
			}
	@endpush
@endsection('content')