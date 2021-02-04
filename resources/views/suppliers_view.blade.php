
@extends('layouts.master')
@section('content')
	<div class="section no-pad-bot" id="index-banner">
		<div class="container">
		  <h1 class="header center orange-text">Suppliers</h1>
		</div>
	</div>
	<div class="section">
		<div class="row">
			<div class="col s12">
				<p><a href="{{route('enter_supplier')}}">Add a supplier</a></p>
			</div>
		</div>
		<div class="row">
			<div class="col s12">
			<?php
			include(app_path().'/includes/connect.php');
			include(app_path().'/includes/get_suppliers_list.php');
			$url = route('edit_supplier');
			/*foreach($suppliers_list as $supplier){
			 list($supplier_id,$supplier_name)=preg_split("[,]", $supplier );
			 echo '<div class="row"><div class="col s1"><p>'.$supplier_id.'</p></div><div class="col s3"><p><b>'.$supplier_name.'</b></p></div><div class="col s1"><p><a href="'.$url.'?supplier_id='.$supplier_id.'">edit</a></p></div></div><hr>';
			}*/
			foreach($suppliers_details as $supplier){
			    list($supplier_id,$supplier_name,$supplier_address,$supplier_phone1,$supplier_mail)=preg_split("[,]", $supplier );
			    echo '<div class="row">
                        <div class="col s1"><p>'.htmlspecialchars($supplier_id).'</p></div>
                        <div class="col s2"><p><b>'.htmlspecialchars($supplier_name).'</b></p></div>
                        <div class="col s2"><p>'.htmlspecialchars($supplier_mail).'</p></div>
                        <div class="col s2"><p>'.htmlspecialchars($supplier_phone1).'</p></div>
                        <div class="col s3"><p>'.htmlspecialchars($supplier_address).'</p></div>
                        <div class="col s2">
							<p>
								<a href="'.htmlspecialchars($url).'?supplier_id='.htmlspecialchars($supplier_id).'" ><i class="small material-icons">edit</i></a> 
								<a href="deleteSupplier?supplier_id='.htmlspecialchars($supplier_id).'" onclick="return confirm(\'Delete?\');" style="margin-left: 40px;"><i class="small material-icons">delete</i></a></a>
							</p>	
						</div>
						<hr>
                    </div>';
			}
			?>
			</div>
		</div>
	</div>
@endsection