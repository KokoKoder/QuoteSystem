@extends('layouts.master')
@section('content') 

 <style>
  input[type=text]:not(.browser-default){
  border:none;
  }
  </style>
  <div class="section no-pad-bot" id="index-banner">
    <div class="container">
      <br><br>
      <h1 class="header center orange-text">Edit Order</h1>
      <div class="row center">
        <h5 class="header col s12 light">Edit an existing order </h5>
      </div>
      <br><br>
    </div>
  </div>
<div class="section">
	<div class="row">
		<form autocomplete="off" method="POST" action="{{route('validate_order_edit')}}" class="col s12">
		@csrf
		<?php
		session_start();
		include(app_path().'/includes/connect.php');
		include(app_path().'/includes/parse.php');
		include(app_path().'/includes/get_vendors_list.php');
		include(app_path().'/includes/get_order_status_list.php');
		$order_id=mysqli_real_escape_string($conn,$_GET["order_id"]);
		$_SESSION["order_id"]=$order_id;
		$_SESSION["order_edit"]=TRUE;

		$sql="SELECT orders_table.order_id, order_number, customer_name, customer_address,customer_phone,customer_mail, order_date,orders_table.customer_id, orders_table.vendor_id, vendor.vendor_name,order_status_list.order_status_name 
		FROM orders_table 
		JOIN customers ON orders_table.customer_id=customers.customer_id
		JOIN vendor  ON orders_table.vendor_id=vendor.vendor_id 
		JOIN orders_status ON orders_status.order_id='$order_id'
		JOIN order_status_list ON orders_status.order_status_id=order_status_list.order_status_id
		WHERE orders_table.order_id='$order_id'";
		$result = mysqli_query($conn, $sql);
		$container = array('content'=>''); 
		$single_tpl = file_get_contents( 'tpls/edit_order.tpl');  	
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				/*echo $container['content'] .= parse($single_tpl, $row );*/
				$order_number=$row["order_number"];
				$order_date=$row["order_date"];
				$customer_name=$row["customer_name"];
				$customer_address=$row["customer_address"];
				$customer_phone=$row["customer_phone"];
				$customer_mail=$row["customer_mail"];
				$customer_id=$row["customer_id"];
				$vendor_set_name=$row["vendor_name"];
				$vendor_set_id=$row["vendor_id"];
				$order_status_set_name=$row["order_status_name"];
				};
		}
		else{
			$customer_details="No order entered";
		}	

        switch ($vendor_set_name){
        	case "Sisustusmööbel":
        		$lang="ee";
        		break;
        	case ("Sisustuskaluste"):
        		$lang="fi";
        		break;
        	case ("Furnest FI"):
        			$lang="fi";
        		break;
        	case ("Furnest EE"):
        			$lang="ee";
        		break;
        }
?>
		 <div class="row">
		  <input type="hidden" value="'.$row["order_id"].'" name="order_id">
			<div class="input-field col s3">
				<p><b>Order number</b><br>
				@php 
					echo $order_number;
				@endphp
				</p>
			</div>
			<div class="input-field col s3">
				<p ><a onclick="print_confirmation()">Print order confirmation</a><br></p>
				@php
				if ($vendor_set_name=="Furnest FI"){
				echo '<p ><a onclick="print_invoice()">Print invoice</a><br></p>';
				}
				else{
				echo '<p ><a onclick="print_invoice()">Print invoice 1</a><br></p>
						<p ><a onclick="print_invoice_2()">Print invoice 2</a><br></p>
						<p ><a onclick="print_full_invoice()">Print full invoice </a><br></p>';
				}

				 @endphp
			</div>
			<div class="input-field col s6">
				<table>
					<tr>
					<th>Customer Name</th>
					<th>address</th>
					<th>phone</th>
					<th>mail</th>
					</tr>
					<tr>
						<td>
						@php 
							echo htmlspecialchars($customer_name);
						@endphp
						</td>
						<td>
						@php
							echo htmlspecialchars($customer_address);
						@endphp
						</td>
						<td>
						@php 
							echo htmlspecialchars($customer_phone);
						@endphp
						</td>
						<td>
						@php 
							echo htmlspecialchars($customer_mail);
						@endphp</td>
					</tr>
				</table>
				@php $url=route('edit_customer');@endphp
				<a href="@php echo $url.'?customer_id='.$customer_id;@endphp"><i class="material-icons">edit</i></a>
			</div>
		  </div>
		  <div class="row">
			<div name="vendor" id="vendor" class="input-field col s4">
				<b>Vendor</b>
				<select id="vendor_select">
				<option value="" disabled selected>Change vendor</option>
				@php
				 foreach ($vendors_list as $vendor){
					 list($vendor_id,$vendors_name)=preg_split("[,]",$vendor);
					 if ($vendor_set_name==$vendors_name){
						 $selected="selected";
					 }
					 else{
						 $selected="";
					 }
					 echo '<option '.$selected.' value="'.$vendor_id.'">'.htmlspecialchars($vendors_name).'</option>';
				 }
				@endphp
					  
				</select>
				<input type="hidden" name="vendor_id" id="vendor_hidden" value="@php echo(htmlspecialchars($vendor_id));@endphp" />
			</div>
			<div class="input-field col s4">
				<b>Order Status</b>
				<select id="order_status_select" name="order_status_id">
				@php
				 foreach ($order_status_list as $order_status_elem){
					 list($order_status_id,$order_status_name)=preg_split("[,]",$order_status_elem);
					 if ($order_status_set_name==$order_status_name){
						 $selected="selected";
					 }
					 else{
						 $selected="";
					 }
					 echo '<option '.$selected.' value="'.$order_status_id.'">'.htmlspecialchars($order_status_name).'</option>';
				 }
				@endphp
				</select>
			</div>
			<div class="input-field col s4">
			<b>Order date</b>
			<input id="order_date" type="text" class="datepicker"  name="order_date" value="@php echo htmlspecialchars($order_date);@endphp">
			</div>
		  </div>
		  <button class="btn" type="submit"><i class="material-icons">done</i></button>
		</form>
	</div>
</div>
<div class="section">
	<div class="row">
	<div class="col s12" id="order_items">
	</div>
	</div>
	<div class="row">
	<p><a  href="{{route('add_item_to_order_form')}}">add item</a></p>
	</div>
</div>
<script src="js/FileSaver.js"></script>
@endsection('content') 
@push('scripts')

//Get order items
		$(document).ready(function(){
			var order_id=<?php echo  $_SESSION["order_id"]?>;
			console.log(order_id);
			jQuery.get("get_order_items", write_results_to_page);
		});
		
		function write_results_to_page(data,status, xhr) {
			if (status == "error") {
				var msg = "Sorry but there was an error: ";
				console.error(msg + xhr.status + " " + xhr.statusText);   
				}   
			else   {
					jQuery('#order_items').html(data);  
				var options = {
						format: 'yyyy-mm-dd',
				};
				var elems = document.querySelectorAll('.datepicker');
				var instances = M.Datepicker.init(elems, options)			
				} 
			
		};

			function print_invoice(){
				var print_url='@php echo route('generate_pdf').'?order_id='.$order_id.'&lang='.$lang.'&order_number='.htmlspecialchars($order_number).'&invoice_2=FALSE&proforma=FALSE&pay_full=false';@endphp';
				if(!$('#printLinkIframe')[0]) {
					console.log(print_url);
					var iframe = '<iframe id="printLinkIframe" name="printLinkIframe" src=' + print_url + ' style="position:absolute;top:-9999px;left:-9999px;border:0px;overfow:none; z-index:-1"></iframe>';
					$('body').append(iframe);
					$('#printLinkIframe').on('load',function() {  
						frames['printLinkIframe'].focus();
						frames['printLinkIframe'].print();
					});
				}else{
					console.log('iframe already exists'); 
					console.log(print_url);
					$('#printLinkIframe').attr('src', print_url);
					frames['printLinkIframe'].focus();
				}
			}
			
			function print_invoice_2(){
				var print_url='@php echo route('generate_pdf').'?order_id='.$order_id.'&lang='.$lang.'&order_number='.htmlspecialchars($order_number).'&invoice_2=is_invoice_2&proforma=FALSE&pay_full=false';@endphp';
				if(!$('#printLinkIframe')[0]) {
					console.log(print_url);
					var iframe = '<iframe id="printLinkIframe" name="printLinkIframe" src=' + print_url + ' style="position:absolute;top:-9999px;left:-9999px;border:0px;overfow:none; z-index:-1"></iframe>';
					$('body').append(iframe);
					$('#printLinkIframe').on('load',function() {  
					frames['printLinkIframe'].focus();
					frames['printLinkIframe'].print();
					});
				}else{
					console.log(print_url);
					console.log('iframe already exists');
					$('#printLinkIframe').attr('src', print_url); ;
					frames['printLinkIframe'].focus();
				}
			}
			function print_full_invoice(){
				var print_url='@php echo route('generate_pdf').'?order_id='.$order_id.'&lang='.$lang.'&order_number='.htmlspecialchars($order_number).'&invoice_2=FALSE&proforma=FALSE&pay_full=full';@endphp';
				if(!$('#printLinkIframe')[0]) {
					console.log(print_url);
					var iframe = '<iframe id="printLinkIframe" name="printLinkIframe" src=' + print_url + ' style="position:absolute;top:-9999px;left:-9999px;border:0px;overfow:none; z-index:-1"></iframe>';
					$('body').append(iframe);
					$('#printLinkIframe').on('load',function() {  
						frames['printLinkIframe'].focus();
						frames['printLinkIframe'].print();
					});
				}else{
					console.log('iframe already exists'); 
					console.log(print_url);
					$('#printLinkIframe').attr('src', print_url);
					frames['printLinkIframe'].focus();
				}
			}
			function print_confirmation(){
				var print_url='@php echo route('generate_pdf').'?order_id='.$order_id.'&lang='.$lang.'&order_number='.htmlspecialchars($order_number).'&invoice_2=FALSE&proforma=is_proforma&pay_full=false';@endphp';
				if(!$('#printLinkIframe')[0]) {
					console.log(print_url);
					var iframe = '<iframe id="printLinkIframe" name="printLinkIframe" src=' + print_url + ' style="position:absolute;top:-9999px;left:-9999px;border:0px;overfow:none; z-index:-1"></iframe>';
					$('body').append(iframe);
					$('#printLinkIframe').on('load',function() {  
					frames['printLinkIframe'].focus();
					frames['printLinkIframe'].print();
					});
				}else{
				console.log(print_url);
					$('#printLinkIframe').attr('src', print_url);
					console.log('iframe already exists'); 
					frames['printLinkIframe'].focus();
				}
			}

@endpush