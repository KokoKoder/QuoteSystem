@extends('layouts.master')
@section('content')  
@php

include(app_path().'/includes/get_suppliers_list.php');
@endphp
<h1>Edit Item</h1>
<form method="POST" action="{{ route('update',['id'=>$item->item_id]) }}" class="col s12">
		<div class="row">
			<div class="input-field col s6">
			  <input placeholder="Product name*" id="item_name" value="{{$item->item_name}}" name="item_name" type="text" class="validate">
			  <span>@php if (isset($err_duplicate_item)){echo $err_duplicate_item;}@endphp</span>
			</div>
			<div class="input-field col s6">
			  <input id="item_price" value="{{$item->item_price}}" name="item_price" type="text" class="validate">
			  <label for="item_price">Item price* in € - without taxes. Ex: 25.68</label>
			</div>
		</div>
		<div class="row">
			<div class="input-field col s6">
				<select id="supplier_id" class="col s12 browser-default" style="display:block" name="supplier_id" >
				<?php 
					$item_supplier_filter="";
					
					foreach ($suppliers_list as $supplier){
						list($option_supplier_id,$option_supplier_name)=preg_split("[,]",$supplier);
						$status="";
						if ($option_supplier_id==$item->item_supplier_id){$status="selected";}
						$item_supplier_filter.='<option '.$status.' value="'.$option_supplier_id.'">'.$option_supplier_name.'</option>';
					}
					echo '<option disabled value="">supplier filter*</option>'.$item_supplier_filter;
				?>
				</select>
			 </div>
			 <div class="input-field col s6">
			  <input id="supplier_sku" type="text" value="{{$item->supplier_sku}}" name="supplier_sku" class="validate" >
			  <label for="supplier_sku">supplier_sku*</label>
			 </div>
		</div>
		<div class="row">
			<div class="input-field col s6 m3">
			  <input id="item_width" type="text" value="{{$item->item_width}}" name="item_width" class="validate" >
			  <label for="item_width">Product width in mm Ex: 1250</label>
			</div>

			<div class="input-field col s6 m3">
			  <input id="item_length" name="item_length" value="{{$item->item_length}}" type="text" class="validate" >
			  <label for="item_length">Product length in mm</label>
			</div>

			<div class="input-field col s6 m3">
			  <input id="item_height" type="text" value="{{$item->item_height}}" name="item_height" class="validate" >
			  <label for="item_height">Product height in mm</label>
			</div>
			<div class="input-field col s6 m3">
			  <input id="item_weight" type="text" value="{{$item->item_weight}}" name="item_weight" class="validate" >
			  <label for="item_weight">Product weight in kg - Ex: 2.5</label>
			</div>
		</div>
		<div class="row">
        	<div class="input-field col s12">
          		<textarea id="item_description" value="{{$item->item_description}}" class="materialize-textarea" name="item_description" class="validate"></textarea>
          		<label for="item_description">Product description</label>
        	</div>
      	</div>
		<div class="row">
			<div class="input-field col s6 m3">
			  <input id="package_width" type="text" value="{{$item->package_width}}" name="package_width" class="validate" >
			  <label for="package_width">Package width in mm</label>
			</div>
			<div class="input-field col s6 m3">
			  <input id="package_length" name="package_length"  value="{{$item->package_length}}" type="text" class="validate" >
			  <label for="package_length">Package length</label>
			</div>

			<div class="input-field col s6 m3">
			  <input id="package_height" type="text" name="package_height" value="{{$item->package_height}}" class="validate" >
			  <label for="package_height">Package height</label>
			</div>
			<div class="input-field col s6 m3">
			  <input id="package_weight" name="package_weight" value="{{$item->package_weight}}" type="text" class="validate" >
			  <label for="package_weight">Package weight in kg - Ex: 2.5</label>
			</div>
		</div>
		<div class="row">
			<div class="input-field col s6 m3">
			  <input id="item_per_pack" type="text" name="item_per_pack" value="{{$item->item_per_pack}}" class="validate" >
			  <label for="item_per_pack">Item per package </label>
			  <span class="helper-text">2 for 2 items per pack <br> 1/2 for 2 packs per item <br> 1/3 for item coming in 3 packs</span>
			</div>
		</div>  
  <button type="submit" class="waves-effect waves-light btn " href="">Edit item</button>
  @csrf
  </div>
</form>

@endsection('content') 
