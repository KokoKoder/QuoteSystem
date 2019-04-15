@extends('layouts.master')
@section('content')
	<?php
	session_start();
	include(app_path().'/includes/connect.php');
	?>  
	<div class="section no-pad-bot" id="index-banner">
	<div class="container">
	  <br><br>
	  <h1 class="header center orange-text">Add item to order</h1>
	  <br><br>
	  @isAdmin
	  <a href="{{route('add_custom_item_to_order')}}">Add custom item</a>
	  @endisAdmin
	</div>
	</div>
    <div class="section">
		<div class="row">
		<span><?php if(isset($_SESSION["Err_missing_data"])){echo $_SESSION["Err_missing_data"];} 
		if(isset($_SESSION["err_message"])){echo $_SESSION["err_message"];}?></span>
			<form autocomplete="off" method="POST" action="{{route('add_item_to_order')}}" class="col s12">
			@csrf
			  <div class="row">
			  	<div class="col s2">
				<p><span id="enter_product"></span></p>
				</div>
				<div class="input-field col s3">
				  <input placeholder="Product Name" id="product_name" name="product_name" type="text" class="product_name typeahead" value="@php if (isset($_SESSION['product_name'])){echo $_SESSION['product_name'];} @endphp">
				  <span id="items_suggestions"></span>
				</div>
				<div class="input-field col s1">
					<input placeholder="Quantity" id="product_quantity" type="text" name="product_quantity" class="validate">
				</div>
				<div class="input-field col s3">
					<input placeholder="Schedule delivery date" class="datepicker" type="text" name="schedule_date" > 
					<span class="helper-text">Default to supplier delivery time if left empty</span>
				</div>
				<div class=" col s3">
					<p><button class="waves-effect waves-light btn" type="submit" value="Submit"> <i class="material-icons">done</i></button></p>
				</div>
			  </div>  
			</form>
		</div>
	</div>
	<div class="section">
		<div class="row">
		<div class="col s12" id="order_items">

		</div>
		</div>
	</div>
	<br><br>
	<?php 
		if (isset($_SESSION["order_edit"])){
		$url=route('edit_order');
		echo '<a href="'.$url.'?order_id='.$_SESSION["order_id"].'" class="waves-effect waves-light btn">Back to Order editing</a>';
	}
	else{
		
		echo '<a href="/index" class="waves-effect waves-light btn">Confirm Order</a>';
	}
	?>
	<br><br>
	<!--  Scripts-->
@endsection('content') 
@push('scripts')
		//Get order items
		$(document).ready(function(){
			jQuery.get("get_order_items", write_results_to_page);
		});
		function write_results_to_page(data,status, xhr) {
			if (status == "error") {
				var msg = "Sorry but there was an error: ";
				console.error(msg + xhr.status + " " + xhr.statusText);   
				}   
			else   {
					jQuery('#order_items').html(data);   
				} 
		}
		

		
		// get list of existing products
		jQuery(document).ready(main); function main() {  
		jQuery('#product_name').keyup(get_matching_products);
		};
		function get_matching_products(){
			var search_query =jQuery('#product_name').val();
			if(search_query != "" && search_query .length >1 ) {
				
				jQuery.get("get_items_live", { product_name:search_query }, write_item_suggestion_to_page);
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
				if (data=="product not found"){
				jQuery('#enter_product').html("<a href='{{route("enter_item")}}' class='btn' >Enter new product</button>");
				}
				else{
					var products_suggestions =  [];
					
					var products_suggestions =  data.split(',');
					console.log(products_suggestions);
					autocomplete(document.getElementById("product_name"), products_suggestions);
				}
			}		
		}
		function display_status_options(){
			var status_selection_html='';
		};
@endpush
