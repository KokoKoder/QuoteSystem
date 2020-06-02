@extends('layouts.master')
@php
include(app_path().'/includes/get_vendors_list.php');
include(app_path().'/includes/connect.php');
@endphp  
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit product</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('products.index') }}"> Back</a>
            </div>
        </div>
    </div>
   
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
  
    <form action="{{ route('products.update',$product->id) }}" method="POST">
        @csrf
        @method('PUT')
   
         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" value="{{ $product->name }}" class="form-control" placeholder="Name">
                </div>
            </div>
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="form-group">
					<strong>URL:</strong>
					<input type="text" name="URL" class="form-control" value="{{ $product->URL }}">
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="form-group">
					<strong>Description:</strong>
					<input type="text" name="description" class="form-control" value="{{ $product->description }}">
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="form-group">
					<strong>Pictures:</strong>
					<input type="text" name="pictures" class="form-control" value="{{ $product->pictures }}">
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="form-group">
					<strong>Meta description:</strong>
					<input type="text" name="metaDesc" class="form-control" value="{{ $product->metaDesc }}">
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="form-group">
					<strong>Meta title:</strong>
					<input type="text" name="metaTitle" class="form-control" value="{{ $product->metaTitle }}">
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="form-group">
					<strong>Language:</strong>
					<input type="text" name="lang" class="form-control" value="{{ $product->lang }}">
				</div>
			</div>
			<div class="input-field col s12">
				<b>Vendor:</b>
				<select id="vendor_select" name="vendor" class="browser-default">
					<option disabled selected>Select vendor</option>
					@php 
						echo $product->vendor;
						foreach($vendors_list as $vendor){
							list($vendor_id,$vendor_name)=preg_split("[,]",$vendor);
							if ($product->vendor==$vendor_id){
								$selected='selected';
							}
							else{
								$selected='';
							}
							echo ('<option '.$selected.' value="'.$vendor_id.'">'.$vendor_name.'</option>');
						}
					@endphp
				</select>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="form-group">
					<strong>Parent product:</strong>
					
					<select id="parentproduct" name="parentproduct" class="browser-default">
					<option value="" disabled selected>Select parent product</option>
					@foreach($products as $parentproduct)
						@if ($product->parentproduct==$parentproduct->id)
							@php
								$selected='selected';
							@endphp
						@else
							@php
								$selected='';
							@endphp
						@endif
						<option {{$selected}} value="{{$parentproduct->id}}">{{$parentproduct->name}}</option>
					@endforeach
					</select>
				</div>
			</div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
   
    </form>
@endsection