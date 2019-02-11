@extends('layouts.master')
@section('content') 
<?php
	session_start();
	include(app_path().'/includes/parse.php');
	include(app_path().'/includes/connect.php');
	include(app_path().'/includes/get_status_list.php');
	include(app_path().'/includes/get_suppliers_list.php');
	$url=route('edit_order');
?>
	<div class="section no-pad-bot" id="index-banner">
		<div class="container">
		  <br><br>
		  <h1 class="header center orange-text">Orders by suppliers</h1>
		  <br><br>

		</div>
	</div>
	<section>
		<div class="row">
			<div class="col s12">
			<form method="POST" action="{{route('orders_suppliers_view')}}" >
				@csrf
				<select id="status_id" class="col s6 browser-default" style="display:block" name="status_id" >
				<?php 
					$item_status_filter="";
					foreach ($options as $option){
						list($option_status_id,$option_status_name)=preg_split("[,]",$option);
						 $item_status_filter.='<option value="'.$option_status_id.'">'.$option_status_name.'</option>';
					}
					echo '<option selected disabled value="">status filter</option><option value="0">Not completed</option>'.$item_status_filter;
				?>
				</select>
				<select id="supplier_id" class="col s6 browser-default" style="display:block" name="supplier_id" >
				<?php 
					$item_supplier_filter="";
					foreach ($suppliers_list as $supplier){
						list($option_supplier_id,$option_supplier_name)=preg_split("[,]",$supplier);
						 $item_supplier_filter.='<option value="'.$option_supplier_id.'">'.$option_supplier_name.'</option>';
					}
					echo '<option selected disabled value="">supplier filter</option><option  value="0">All suppliers</option>'.$item_supplier_filter;
				?>
				</select>
				<div class="col s12">
				<br><br>
					<!--button class="btn" type="submit">Filter</button-->
				</div>
			</form>
			</div>
		</div>
	</section>
	<table >

	
	</table>
	<table id="supplier_filter_results"></table>
@endsection('content')
@push('scripts')
	$( "#supplier_id" ).change(function() {
		var search_query=document.getElementById("supplier_id").value;
		var search_query_2=document.getElementById("status_id").value;
		if(search_query != "" ) {
			jQuery.get("filter_by_suppliers", { supplier_id:search_query , status_id:search_query_2 }, display_results_to_page);
			console.log(search_query+' '+search_query_2);
		}
		else{console.log('suppliers id  not set.');} 
	});
	$( "#status_id" ).change(function() {
		var search_query=document.getElementById("status_id").value;
		var search_query_2=document.getElementById("supplier_id").value;
		if(search_query != "" ) {
			jQuery.get("filter_by_suppliers", { status_id:search_query, supplier_id:search_query_2}, display_results_to_page);
			console.log(search_query+' '+search_query_2);
		}
		else{console.log('query for status');} 
	});
	function display_results_to_page(data,status, xhr) {
		if (status == "error") {
			var msg = "Sorry but there was an error: ";
			console.error(msg + xhr.status + " " + xhr.statusText);   
			}   
		else   {
				var supplier_filter_results=data;
				console.log(supplier_filter_results);
				document.getElementById("supplier_filter_results").innerHTML= supplier_filter_results;
				
		}		
	}	
@endpush