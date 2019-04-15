@extends('layouts.master')
<?php

	session_start();
	include(app_path().'/includes/parse.php');
	include(app_path().'/includes/connect.php');
	include(app_path().'/includes/get_status_list.php');
	include(app_path().'/includes/get_suppliers_list.php');
	include(app_path().'/includes/get_order_status_list.php');
	$user_id=auth()->user()->id;
echo "something";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$status_id="";
	$order_status_id="";
	if (Auth::user()->is_admin){
	    echo "is admin" ;
    	#get the form value
    	if (!empty($_POST["status_id"]) AND empty($_POST["order_status_id"])){
    		$status_id=mysqli_real_escape_string($conn,$_POST["status_id"]);
    		$sql="SELECT DISTINCT orders_table.order_id, order_number, customer_name, order_date, vendor.vendor_name, status.status_name, Schedule_delivery_date, order_status_name  FROM orders_table 
    			JOIN customers ON orders_table.customer_id= customers.customer_id
    			JOIN vendor ON orders_table.vendor_id=vendor.vendor_id
    			JOIN orders_status ON orders_status.order_id=orders_table.order_id
    			JOIN order_status_list ON order_status_list.order_status_id=orders_status.order_status_id
    			JOIN order_items ON order_items.order_id=orders_table.order_id
    			JOIN status  ON status.status_id=order_items.status_id
    			WHERE order_items.status_id='$status_id'";  
    	}
    	elseif (!empty($_POST["order_status_id"]) AND empty($_POST["status_id"])){
    		$order_status_id=mysqli_real_escape_string($conn,$_POST["order_status_id"]);
    		$sql="SELECT DISTINCT  orders_table.order_id, order_number, customer_name, order_date, vendor.vendor_name, order_status_name
    		FROM orders_table 
    		JOIN customers ON orders_table.customer_id= customers.customer_id
    		JOIN vendor ON orders_table.vendor_id=vendor.vendor_id
    		JOIN orders_status ON orders_status.order_status_id='$order_status_id'
    		JOIN order_status_list ON order_status_list.order_status_id=orders_status.order_status_id
    		WHERE orders_status.order_id=orders_table.order_id";  
    	}
    	elseif(!empty($_POST["status_id"]) AND !empty($_POST["order_status_id"])){
    		$status_id=mysqli_real_escape_string($conn,$_POST["status_id"]);
    		$order_status_id=mysqli_real_escape_string($conn,$_POST["order_status_id"]);
    		$sql="SELECT DISTINCT  orders_table.order_id, order_number, customer_name, order_date, vendor.vendor_name, order_status_name
    		FROM orders_table 
    		JOIN order_items ON order_items.status_id='$status_id'
    		JOIN customers ON orders_table.customer_id= customers.customer_id
    		JOIN vendor ON orders_table.vendor_id=vendor.vendor_id
    		JOIN orders_status ON orders_status.order_status_id='$order_status_id'
    		JOIN order_status_list ON order_status_list.order_status_id=orders_status.order_status_id
    		WHERE orders_status.order_id=orders_table.order_id ";  		
    	}
    	else{
    		$sql="SELECT DISTINCT  orders_table.order_id, order_number, customer_name, order_date, vendor.vendor_name, order_status_name
    		FROM orders_table 
    		JOIN customers ON orders_table.customer_id= customers.customer_id 
    		JOIN vendor ON orders_table.vendor_id=vendor.vendor_id
    		JOIN orders_status ON orders_status.order_id=orders_table.order_id AND orders_status.order_status_id<>'5' AND orders_status.order_status_id<>'6'
    		JOIN order_status_list ON orders_status.order_status_id=order_status_list.order_status_id";
    
    	}
	}
	else{
	    echo "Not admin - 1 - ";
	    if (!empty($_POST["status_id"]) AND empty($_POST["order_status_id"])){
	        echo "Not Admin status selected";
	        $status_id=mysqli_real_escape_string($conn,$_POST["status_id"]);
	        $sql="SELECT DISTINCT salesteam_orders.order_id, salesteam_orders.user_id, orders_table.order_id, order_number, customer_name, order_date, vendor.vendor_name, status.status_name, Schedule_delivery_date, order_status_name  
                FROM orders_table
    			JOIN customers ON orders_table.customer_id= customers.customer_id
    			JOIN vendor ON orders_table.vendor_id=vendor.vendor_id
    			JOIN orders_status ON orders_status.order_id=orders_table.order_id
    			JOIN order_status_list ON order_status_list.order_status_id=orders_status.order_status_id
    			JOIN order_items ON order_items.order_id=orders_table.order_id
    			JOIN status  ON status.status_id=order_items.status_id
                JOIN salesteam_orders ON salesteam_orders.user_id='$user_id'
    			WHERE order_items.status_id='$status_id' AND orders_table.order_id=salesteam_orders.order_id";
	    }
	    elseif (!empty($_POST["order_status_id"]) AND empty($_POST["status_id"])){
	        echo "Not Admin orders status selected";
	        $order_status_id=mysqli_real_escape_string($conn,$_POST["order_status_id"]);
	        $sql="SELECT DISTINCT  salesteam_orders.order_id, salesteam_orders.user_id, orders_table.order_id, order_number, customer_name, order_date, vendor.vendor_name, order_status_name
    		FROM orders_table
    		JOIN customers ON orders_table.customer_id= customers.customer_id
    		JOIN vendor ON orders_table.vendor_id=vendor.vendor_id
    		JOIN orders_status ON orders_status.order_status_id='$order_status_id'
    		JOIN order_status_list ON order_status_list.order_status_id=orders_status.order_status_id
            JOIN salesteam_orders ON salesteam_orders.user_id='$user_id'
    		WHERE orders_status.order_id=orders_table.order_id AND orders_table.order_id=salesteam_orders.order_id";
	    }
	    elseif(!empty($_POST["status_id"]) AND !empty($_POST["order_status_id"])){
	        echo "Not Admin both filters selected";
	        $status_id=mysqli_real_escape_string($conn,$_POST["status_id"]);
	        $order_status_id=mysqli_real_escape_string($conn,$_POST["order_status_id"]);
	        $sql="SELECT DISTINCT  salesteam_orders.order_id, salesteam_orders.user_id, orders_table.order_id, order_number, customer_name, order_date, vendor.vendor_name, order_status_name
    		FROM orders_table
    		JOIN order_items ON order_items.status_id='$status_id'
    		JOIN customers ON orders_table.customer_id= customers.customer_id
    		JOIN vendor ON orders_table.vendor_id=vendor.vendor_id
    		JOIN orders_status ON orders_status.order_status_id='$order_status_id'
    		JOIN order_status_list ON order_status_list.order_status_id=orders_status.order_status_id
            JOIN salesteam_orders ON salesteam_orders.user_id='$user_id'
    		WHERE orders_status.order_id=orders_table.order_id AND orders_table.order_id=salesteam_orders.order_id";
	    }
	    else{
	        echo "Not Admin no filter selected";
	        $sql="SELECT DISTINCT  salesteam_orders.order_id, salesteam_orders.user_id, orders_table.order_id, order_number, customer_name, order_date, vendor.vendor_name, order_status_name
    		FROM orders_table
    		JOIN customers ON orders_table.customer_id= customers.customer_id
    		JOIN vendor ON orders_table.vendor_id=vendor.vendor_id
    		JOIN orders_status ON orders_status.order_id=orders_table.order_id AND orders_status.order_status_id<>'5' AND orders_status.order_status_id<>'6'
    		JOIN order_status_list ON orders_status.order_status_id=order_status_list.order_status_id
	        JOIN salesteam_orders ON salesteam_orders.user_id='$user_id'
	        WHERE orders_table.order_id=salesteam_orders.order_id";
	    }
	}
}

else{
    if (Auth::user()->is_admin){
    	$sql="SELECT DISTINCT  orders_table.order_id, order_number, customer_name, order_date, vendor.vendor_name, order_status_name
    	FROM orders_table 
    	JOIN customers ON orders_table.customer_id= customers.customer_id 
    	JOIN vendor ON orders_table.vendor_id=vendor.vendor_id
    	JOIN orders_status ON orders_status.order_id=orders_table.order_id AND orders_status.order_status_id<>'5' AND orders_status.order_status_id<>'6' 
    	JOIN order_status_list ON orders_status.order_status_id=order_status_list.order_status_id ";
    }
    else{
        $sql="SELECT DISTINCT  salesteam_orders.user_id, orders_table.order_id, order_number, customer_name, order_date, vendor.vendor_name, order_status_name
    	FROM orders_table
    	JOIN customers ON orders_table.customer_id= customers.customer_id
    	JOIN vendor ON orders_table.vendor_id=vendor.vendor_id
    	JOIN orders_status ON orders_status.order_id=orders_table.order_id AND orders_status.order_status_id<>'5' AND orders_status.order_status_id<>'6'
    	JOIN order_status_list ON orders_status.order_status_id=order_status_list.order_status_id 
        JOIN salesteam_orders ON salesteam_orders.user_id='$user_id'
        WHERE orders_table.order_id=salesteam_orders.order_id";
    }
}

?>

@section('content') 
			  <div class="section no-pad-bot" id="index-banner">
				<div class="container">
				  <br><br>
				  <h1 class="header center orange-text">Orders</h1>
				  <br><br>

				</div>
			  </div>
			  <section>
				<div class="row">
					<div class="col s12">
					<form method="POST" action="{{route('orders_view')}}" >
						@csrf
						<select id="status_id" class="col s6 browser-default" style="display:block" name="status_id" >
						<?php 
							$item_status_filter="";
							foreach ($options as $option){
								list($option_status_id,$option_status_name)=preg_split("[,]",$option);
								 $item_status_filter.='<option value="'.$option_status_id.'">'.$option_status_name.'</option>';
							}
							echo '<option selected disabled value="">Product status filter</option><option value="">All undelivered</option>'.$item_status_filter;
						?>
						</select>
						<select id="order_status_id" class="col s6 browser-default"  name="order_status_id" >
						<?php 
							$order_status_filter="";
							foreach ($order_status_list as $order_status_elem){
								list($order_status_id,$order_status_name)=preg_split("[,]",$order_status_elem);
								 $order_status_filter.='<option value="'.$order_status_id.'">'.$order_status_name.'</option>';
							}
							echo '<option selected disabled value="">Order status filter</option><option value="">All uncompleted</option>'.$order_status_filter;
						?>
						</select>
						<div class="col s12">
						<br><br>
						<button class="btn" type="submit">Filter</button>
						</div>
					</form>
					</div>
				</div>
			  </section>

			<?php
				$result = mysqli_query($conn, $sql);
				$container = array('content'=>''); 
				$single_tpl = file_get_contents( 'tpls/single_order.tpl');  
				if (mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_assoc($result)) {
						$container['content'] .= parse($single_tpl, $row );
						if (isset($row['status_name'])){
							$status_id=$row['status_name'];
						}
						};
					// Wrap the results 
					$orders_container_tpl = file_get_contents( 'tpls/orders_container.tpl');
					print parse($orders_container_tpl, $container);
				}
				else{
					$customer_details="No order entered";
					echo '<div class="row"><div class="col s12"><h2>no results found</h2></div></div>';
				}
?>
@endsection('content')