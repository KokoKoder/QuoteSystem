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
      <br>
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
		$sql="SELECT orders_table.order_id, order_number, customer_name, customer_address,customer_phone,customer_mail, order_date,pay_before,orders_table.customer_id, orders_table.vendor_id, vendor.vendor_name,order_status_list.order_status_name 
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
				$pay_before=$row["pay_before"];
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
				<div class="row">
					<div class="col s12">
        				<p><b>Order number</b><br>
        				@php 
        					echo htmlspecialchars($order_number);
        				@endphp
        				</p>
    				</div>
    				<div class="input-field col s12">
        				<b>Vendor:</b>
        				<select name="vendor_id" id="vendor_select">
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
    				</div>
            		<div class="input-field col s12">
        				<b>Order Status:</b>
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
        			<b>Order date:</b>
        			<input id="order_date" type="text" class="datepicker"  name="order_date" value="@php echo htmlspecialchars($order_date);@endphp">
        			</div>
				</div>
	
			</div>
			<div class="input-field col s3">
        		<b>Pay before:</b>
        		<input id="pay_before" type="text" class="datepicker"  name="pay_before" value="@php echo htmlspecialchars($pay_before);@endphp">	
				@php
				if ($order_status_set_name=="Canceled"){
					echo '<p><a onclick="print_creditinvoice()">Print credit invoice</a><br></p>';
				}
				else{
					if ($vendor_set_name=="Furnest FI"){
    					echo '<p ><a onclick="print_confirmation()">Print order confirmation</a><br></p>
    					<p><a onclick="print_invoice()">Print invoice</a><br></p>';
    				}
    				else{
    				echo '<p ><a onclick="print_confirmation()">Print order confirmation</a><br></p>
    					<p><a onclick="print_invoice()">Print invoice 1</a><br></p>
    					<p><a onclick="print_invoice_2()">Print invoice 2</a><br></p>
    					<p><a onclick="print_full_invoice()">Print full invoice </a><br></p>';
    				}
				}
				 @endphp
				  <!-- Modal Trigger -->
                      <a class="waves-effect waves-orange btn modal-trigger" href="#modal1">Available PDF</a>
                    
                      <!-- Modal Structure -->
                      <div id="modal1" class="modal">
                        <div class="modal-content">
                          <div style="display:inline-block"><h4>Available invoices</h4></div><div style="display:inline-block"><a href="#!" class="modal-close waves-effect waves-white btn-flat">Close</a></div>
                          <div>
                          @php
							if(file_exists('../app/files/invoice/'.$order_number.'.pdf')){echo '<br><b>Invoice :</b><br><a href="http://localhost/quotesystem/public/getDownload/'.$order_number.'.pdf"><i class="material-icons dp48">picture_as_pdf</i>'.$order_number.'</a><br>';}
                			$i=1;
                			while (file_exists('../app/files/invoice/'.$order_number.$i.'.pdf')){echo '<a href="http://localhost/quotesystem/public/getDownload/'.$order_number.$i.'.pdf"><i class="material-icons dp48">picture_as_pdf</i>'.$order_number.$i.'</a><br>';$i+=1;}
                			if(file_exists('../app/fil/invoice/'.$order_number.'-2.pdf')){echo '<br><b>Invoice 2 :</b><br><a href="/getDownload/'.$order_number.'-2.pdf"><i class="material-icons dp48">picture_as_pdf</i>'.$order_number.'-2</a><br>';}
                			$i=1;
                			while (file_exists('../app/files/invoice/'.$order_number.'-2'.$i.'.pdf')){echo '<a href="http://localhost/quotesystem/public/getDownload/'.$order_number.'-2'.$i.'.pdf"><i class="material-icons dp48">picture_as_pdf</i>'.$order_number.'-2'.$i.'</a><br>';$i+=1;}
                			$i=1;
							while(file_exists('../app/files/invoice/credit_'.$order_number.$i.'.pdf')){echo '<a href="http://localhost/quotesystem/public/getDownload/credit_'.$order_number.$i.'.pdf"><i class="material-icons dp48">picture_as_pdf</i>credit_'.$order_number.$i.'</a><br>';$i+=1;}
                			if(file_exists('../app/files/confirmation/'.$order_number.'.pdf')){echo '<br><b>Order confirmation :</b><br><a href="http://localhost/quotesystem/public/getConfirmation/'.$order_number.'.pdf"><i class="material-icons dp48">picture_as_pdf</i>'.$order_number.'</a><br>';}
							$i=1;
							while(file_exists('../app/files/confirmation/'.$order_number.$i.'.pdf')){echo '<a href="http://localhost/quotesystem/public/getConfirmation/'.$order_number.$i.'.pdf"><i class="material-icons dp48">picture_as_pdf</i>'.$order_number.$i.'</a><br>';$i+=1;}
                          @endphp
                          </div>
                        </div>
                        <div class="modal-footer">
                          
                        </div>
                      </div>
			</div>
			<div class="input-field col s6">
				<h5>Customer details</h5>
				@php $url=route('edit_customer');@endphp
				<a href="@php echo $url.'?customer_id='.$customer_id;@endphp"><i class="material-icons">edit</i></a>
				<table>
					<tr>
					<th>Customer Name</th>
						<td>
						@php 
							echo htmlspecialchars($customer_name);
						@endphp
						</td>
					</tr>
					<tr>
					<th>address</th>
					<td>
						@php 
							echo htmlspecialchars($customer_address);;
						@endphp
					</td>
					</tr>
					<tr>
					<th>phone</th>
					<td>
						@php 
							echo htmlspecialchars($customer_phone);
						@endphp
					</td>
					</tr>
					<tr>
					<th>mail</th>
					<td>
						@php 
							echo htmlspecialchars($customer_mail);
						@endphp
					</td>
					</tr>
				</table>
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
    //initialize modal
    $(document).ready(function(){
        $('.modal').modal();
    });
     
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
				var print_url='@php echo route('generate_pdf').'?order_id='.$order_id.'&lang='.$lang.'&invoice_2=FALSE&proforma=FALSE&is_credit=FALSE&pay_full=FALSE'.'&order_number='.htmlspecialchars($order_number);@endphp';
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
				reload();
			}
			
			function print_creditinvoice(){
				var print_url='@php echo route('generate_pdf').'?order_id='.$order_id.'&lang='.$lang.'&invoice_2=FALSE&proforma=FALSE&is_credit=is_credit&pay_full=FALSE'.'&order_number='.htmlspecialchars($order_number);@endphp';
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
				reload();
			}
			
			function print_invoice_2(){
				var print_url='@php echo route('generate_pdf').'?order_id='.$order_id.'&lang='.$lang.'&invoice_2=is_invoice_2&is_credit=FALSE&proforma=FALSE&pay_full=FALSE'.'&order_number='.htmlspecialchars($order_number);@endphp';
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
				reload();
			}
			function print_full_invoice(){
				var print_url='@php echo route('generate_pdf').'?order_id='.$order_id.'&lang='.$lang.'&invoice_2=FALSE&is_credit=FALSE&proforma=FALSE&pay_full=full'.'&order_number='.htmlspecialchars($order_number);@endphp';
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
				reload();
			}
			function print_confirmation(){
				var print_url='@php echo route('generate_pdf').'?order_id='.$order_id.'&lang='.$lang.'&invoice_2=FALSE&is_credit=FALSE&proforma=is_proforma&pay_full=FALSE'.'&order_number='.htmlspecialchars($order_number);@endphp';
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
				reload();
			}

@endpush