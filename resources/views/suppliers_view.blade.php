
@extends('layouts.master')
@section('content')
	<div class="section no-pad-bot" id="index-banner">
		<div class="container">
		  <br><br>
		  <h1 class="header center orange-text">Suppliers</h1>
		  <br><br>

		</div>
	</div>
	<div class="section">
		<div class="row">
			<div class="col s12">
				<p><a href="{{route('enter_supplier')}}">Add a supplier</a></p>
			</div>
		</div>
		<div class="row">
			<?php
			include(app_path().'/includes/connect.php');
			include(app_path().'/includes/get_suppliers_list.php');
			$url=route('edit_supplier');
			foreach($suppliers_list as $supplier){
			 list($supplier_id,$supplier_name)=preg_split("[,]", $supplier );
			 echo '<div class="row"><div class="col s1"><p>'.$supplier_id.'</p></div><div class="col s3"><p><b>'.$supplier_name.'</b></p></div><div class="col s1"><p><a href="'.$url.'?supplier_id='.$supplier_id.'">edit</a></p></div></div>';
			}
			?>
		</div>
	</div>
@endsection