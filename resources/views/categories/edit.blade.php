@extends('layouts.master')
@php
include(app_path().'/includes/get_vendors_list.php');
include(app_path().'/includes/connect.php');
@endphp  
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Category</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('categories.index') }}"> Back</a>
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
  
    <form action="{{ route('categories.update',$category->id) }}" method="POST">
        @csrf
        @method('PUT')
   
         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" value="{{ $category->name }}" class="form-control" placeholder="Name">
                </div>
            </div>
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="form-group">
					<strong>URL:</strong>
					<input type="text" name="URL" class="form-control" value="{{ $category->URL }}">
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="form-group">
					<strong>Description:</strong>
					<input type="text" name="description" class="form-control" value="{{ $category->description }}">
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="form-group">
					<strong>Pictures:</strong>
					<input type="text" name="pictures" class="form-control" value="{{ $category->pictures }}">
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="form-group">
					<strong>Meta description:</strong>
					<input type="text" name="metaDesc" class="form-control" value="{{ $category->metaDesc }}">
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="form-group">
					<strong>Meta title:</strong>
					<input type="text" name="metaTitle" class="form-control" value="{{ $category->metaTitle }}">
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="form-group">
					<strong>Language:</strong>
					<input type="text" name="lang" class="form-control" value="{{ $category->lang }}">
				</div>
			</div>
			<div class="input-field col s12">
				<b>Vendor:</b>
				<select id="vendor_select" name="vendor" class="browser-default">
					<option disabled selected>Vendor</option>
					@php 
						echo $category->vendor;
						foreach($vendors_list as $vendor){
							list($vendor_id,$vendor_name)=preg_split("[,]",$vendor);
							if ($category->vendor==$vendor_id){
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
					<strong>Parent category:</strong>
					<textarea  type="text"  style="height:150px" name="parentCategory" value="{{ $category->parentCategory }}"></textarea>
				</div>
			</div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
   
    </form>
@endsection