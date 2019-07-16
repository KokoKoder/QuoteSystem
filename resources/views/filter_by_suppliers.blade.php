	<?php
	session_start();
	include(app_path().'/includes/parse.php');
	include(app_path().'/includes/connect.php');
	include(app_path().'/includes/get_status_list.php');
	include(app_path().'/includes/get_suppliers_list.php');
	$today = date ('Y-m-d',time());
	$today = strtotime($today);
	$table_header='<tr><th>Product name</th><th>Quantity</th><th>Status</th><th>Schedule delivery time</th><th>Suppplier name</th><th>Order number</th><td></td></tr>';
	$table_content='';
	$supplier_id="";
	$status_id="";
	$edit_order=route('edit_order');

	#get the form value
	if (!empty($_GET["supplier_id"]) ){
		$supplier_id=mysqli_real_escape_string($conn,$_GET["supplier_id"]);
	}
	if (!empty($_GET["status_id"])){
		$status_id=mysqli_real_escape_string($conn,$_GET["status_id"]);
	}
	
	if (!empty($supplier_id) AND empty($status_id)){
		$sql="SELECT DISTINCT orders_table.order_id, orders_table.order_number, supplier_name, order_items.item_name, item_quantity, status_name, Schedule_delivery_date
			FROM suppliers 
			JOIN items ON suppliers.supplier_id=items.item_supplier_id
            JOIN order_items ON items.item_id=order_items.item_id
			JOIN orders_table ON orders_table.order_id=order_items.order_id
			JOIN status ON status.status_id=order_items.status_id
			JOIN orders_status ON orders_table.order_id=orders_status.order_id
			JOIN order_status_list ON orders_status.order_status_id=order_status_list.order_status_id
			WHERE suppliers.supplier_id='$supplier_id' AND status_name<>'delivered' AND status_name<>'shipped' AND orders_status.order_status_id<>1";
		$test_result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($test_result) > 0) {
			while($row = mysqli_fetch_assoc($test_result)) {
				$Schedule_delivery_date=$row["Schedule_delivery_date"];
				$check_status=$row["status_name"];
				if($check_status!="completed" AND $check_status !="shipped"){
					if ($today > strtotime("$Schedule_delivery_date -1 week")){
							$alert_level= 'style="background-color:#f98181"';
							}
						elseif ($today > strtotime("$Schedule_delivery_date -2 weeks")){
							$alert_level= 'style="background-color:#f9a181"';
							}
						else {
							$alert_level='style="background-color:#4286f4"';
						}
				}else{
					$alert_level='style="background-color:white"';
				}
				$table_content.='<tr '.$alert_level.' ><td>'.$row["item_name"].'</td><td>'.$row["item_quantity"].'</td><td>'.$row["status_name"].'</td><td>'.$row["Schedule_delivery_date"].'</td><td>'.$row["supplier_name"].'</td><td>'.$row["order_number"].'</td><td><a class="btn" href="'.$edit_order.'?order_id='.$row["order_id"].'" >edit</a></td></tr>';
			}
			
		}
		else{"<tr><td>No results</td></tr>";}
		$sql2="
		SELECT *
		FROM suppliers
		JOIN custom_items ON  custom_items.custom_supplier_id=suppliers.supplier_id
		JOIN order_custom_items ON custom_items.custom_item_id=order_custom_items.custom_item_id
		JOIN orders_table ON orders_table.order_id= order_custom_items.order_id
		JOIN status ON status.status_id=order_custom_items.status_id
        JOIN orders_status ON orders_table.order_id=orders_status.order_id
		WHERE suppliers.supplier_id='$supplier_id' AND status_name<>'delivered' AND status_name<>'shipped' AND orders_status.order_status_id<>1";	
		$custom_result = mysqli_query($conn, $sql2);
		if (mysqli_num_rows($custom_result) > 0) {	
			$Schedule_delivery_date=$row["Schedule_delivery_date"];
			$check_status=$row["status_name"];		
			if($check_status!="completed" AND $check_status !="shipped"){
				if ($today > strtotime("$Schedule_delivery_date -1 week")){
						$alert_level= 'style="background-color:#f98181"';
						}
					elseif ($today > strtotime("$Schedule_delivery_date -2 weeks")){
						$alert_level= 'style="background-color:#f9a181"';
						}
					else {
						$alert_level='style="background-color:#4286f4"';
					}
			}else{
				$alert_level='style="background-color:white"';
				
			}
			while($row = mysqli_fetch_assoc($custom_result)) {
				$table_content.='<tr '.$alert_level.' ><td>'.$row["item_name"].'</td><td>'.$row["item_quantity"].'</td><td>'.$row["status_name"].'</td><td>'.$row["Schedule_delivery_date"].'</td><td>'.$row["supplier_name"].'</td><td>'.$row["order_number"].'</td><td><a class="btn" href="'.$edit_order.'?order_id='.$row["order_id"].'" >edit</a></td></tr>';
			}
		}
		else{"<tr><td>No results</td></tr>";}
		echo $table_header.$table_content;
	}
	elseif (!empty($supplier_id) AND !empty($status_id)){
		$status_sql="
		SELECT DISTINCT orders_table.order_id, orders_table.order_number, supplier_name, order_items.item_name,order_items.item_id, item_quantity, status_name, Schedule_delivery_date
		FROM suppliers 
		JOIN items ON suppliers.supplier_id=items.item_supplier_id
		JOIN order_items ON items.item_id=order_items.item_id
		JOIN orders_table ON orders_table.order_id=order_items.order_id
		JOIN status ON status.status_id=order_items.status_id
		WHERE order_items.status_id='$status_id' AND suppliers.supplier_id='$supplier_id'
		";
		$status_result = mysqli_query($conn, $status_sql);
		if (mysqli_num_rows($status_result) > 0) {
			while($row = mysqli_fetch_assoc($status_result)) {
				$Schedule_delivery_date=$row["Schedule_delivery_date"];
				$check_status=$row["status_name"];
				if($check_status!="completed" AND $check_status !="shipped"){
					if ($today > strtotime("$Schedule_delivery_date -1 week")){
							$alert_level= 'style="background-color:#f98181"';
							}
						elseif ($today > strtotime("$Schedule_delivery_date -2 weeks")){
							$alert_level= 'style="background-color:#f9a181"';
							}
						else {
							$alert_level='style="background-color:#4286f4"';
						}
					
				}else{
					$alert_level='style="background-color:white"';
				}
				$table_content.='<tr '.$alert_level.' ><td>'.$row["item_name"].'</td><td>'.$row["item_quantity"].'</td><td>'.$row["status_name"].'</td><td>'.$row["Schedule_delivery_date"].'</td><td>'.$row["supplier_name"].'</td><td>'.$row["order_number"].'</td><td><a class="btn" href="'.$edit_order.'?order_id='.$row["order_id"].'" >edit</a></td></tr>';
			}
			
		}
		else{"<tr><td>No results</td></tr>";}
		$sql2="
		SELECT *
		FROM suppliers
		JOIN custom_items ON  custom_items.custom_supplier_id=suppliers.supplier_id
		JOIN order_custom_items ON custom_items.custom_item_id=order_custom_items.custom_item_id
		JOIN orders_table ON orders_table.order_id= order_custom_items.order_id
		JOIN status ON status.status_id=order_custom_items.status_id
		WHERE suppliers.supplier_id='$supplier_id' AND order_custom_items.status_id='$status_id'";	
		$custom_result = mysqli_query($conn, $sql2);
		if (mysqli_num_rows($custom_result) > 0) {	
			$Schedule_delivery_date=$row["Schedule_delivery_date"];
			$check_status=$row["status_name"];		
			if($check_status!="completed" AND $check_status !="shipped"){
				if ($today > strtotime("$Schedule_delivery_date -1 week")){
						$alert_level= 'style="background-color:#f98181"';
						}
					elseif ($today > strtotime("$Schedule_delivery_date -2 weeks")){
						$alert_level= 'style="background-color:#f9a181"';
						}
					else {
						$alert_level='style="background-color:#4286f4"';
					}
			}else{
				$alert_level='style="background-color:white"';
				
			}
			while($row = mysqli_fetch_assoc($custom_result)) {
				$table_content.='<tr '.$alert_level.' ><td>'.$row["item_name"].'</td><td>'.$row["item_quantity"].'</td><td>'.$row["status_name"].'</td><td>'.$row["Schedule_delivery_date"].'</td><td>'.$row["supplier_name"].'</td><td>'.$row["order_number"].'</td><td><a class="btn" href="'.$edit_order.'?order_id='.$row["order_id"].'" >edit</a></td></tr>';
			}
		}
		else{"<tr><td>No results</td></tr>";}
		echo $table_header.$table_content;

	}
	elseif (empty($supplier_id) AND !empty($status_id) ){
		$status_sql="
		SELECT DISTINCT orders_table.order_id, orders_table.order_number, supplier_name, order_items.item_name, item_quantity, status_name, Schedule_delivery_date
		FROM suppliers 
		JOIN items ON suppliers.supplier_id=items.item_supplier_id
		JOIN order_items ON items.item_id=order_items.item_id
		JOIN orders_table ON orders_table.order_id=order_items.order_id
		JOIN status ON status.status_id=order_items.status_id
		WHERE order_items.status_id='$status_id' 
		";		
		$status_result = mysqli_query($conn, $status_sql);
		if (mysqli_num_rows($status_result) > 0) {
			while($row = mysqli_fetch_assoc($status_result)) {
				$Schedule_delivery_date=$row["Schedule_delivery_date"];
				$check_status=$row["status_name"];
				if($check_status!="completed" AND $check_status !="shipped"){
					if ($today > strtotime("$Schedule_delivery_date -1 week")){
							$alert_level= 'style="background-color:#f98181"';
							}
						elseif ($today > strtotime("$Schedule_delivery_date -2 weeks")){
							$alert_level= 'style="background-color:#f9a181"';
							}
						else {
							$alert_level='style="background-color:#4286f4"';
						}
					
				}else{
					$alert_level='style="background-color:white"';
				}
				$table_content.='<tr '.$alert_level.' ><td>'.$row["item_name"].'</td><td>'.$row["item_quantity"].'</td><td>'.$row["status_name"].'</td><td>'.$row["Schedule_delivery_date"].'</td><td>'.$row["supplier_name"].'</td><td>'.$row["order_number"].'</td><td><a class="btn" href="'.$edit_order.'?order_id='.$row["order_id"].'" >edit</a></td></tr>';
			}
		}
		else{"<tr><td>No results</td></tr>";}
		$sql2="
		SELECT *
		FROM suppliers
		JOIN custom_items ON  custom_items.custom_supplier_id=suppliers.supplier_id
		JOIN order_custom_items ON custom_items.custom_item_id=order_custom_items.custom_item_id
		JOIN orders_table ON orders_table.order_id= order_custom_items.order_id
		JOIN status ON status.status_id=order_custom_items.status_id
		WHERE  order_custom_items.status_id='$status_id'";	
		$custom_result = mysqli_query($conn, $sql2);
		if (mysqli_num_rows($custom_result) > 0) {	
			$Schedule_delivery_date=$row["Schedule_delivery_date"];
			$check_status=$row["status_name"];		
			if($check_status!="completed" AND $check_status !="shipped"){
				if ($today > strtotime("$Schedule_delivery_date -1 week")){
						$alert_level= 'style="background-color:#f98181"';
						}
					elseif ($today > strtotime("$Schedule_delivery_date -2 weeks")){
						$alert_level= 'style="background-color:#f9a181"';
						}
					else {
						$alert_level='style="background-color:#4286f4"';
					}
			}else{
				$alert_level='style="background-color:white"';
				
			}
			while($row = mysqli_fetch_assoc($custom_result)) {
				$table_content.='<tr '.$alert_level.' ><td>'.$row["item_name"].'</td><td>'.$row["item_quantity"].'</td><td>'.$row["status_name"].'</td><td>'.$row["Schedule_delivery_date"].'</td><td>'.$row["supplier_name"].'</td><td>'.$row["order_number"].'</td><td><a class="btn" href="'.$edit_order.'?order_id='.$row["order_id"].'" >edit</a></td></tr>';
			}
		}
		else{"<tr><td>No results</td></tr>";}
		echo $table_header.$table_content;
	}
	else{
		echo ('All order selected');
		$status_sql="SELECT DISTINCT orders_table.order_id, orders_table.order_number, supplier_name, order_items.item_name, item_quantity, status_name, Schedule_delivery_date
			FROM suppliers 
			JOIN items ON suppliers.supplier_id=items.item_supplier_id
			JOIN order_items ON items.item_id=order_items.item_id
			JOIN orders_table ON orders_table.order_id=order_items.order_id
			JOIN status ON status.status_id=order_items.status_id
			JOIN orders_status ON orders_table.order_id=orders_status.order_id
			JOIN order_status_list ON orders_status.order_status_id=order_status_list.order_status_id
			WHERE status_name<>'delivered' AND orders_status.order_status_id <> 1	";		
		$status_result = mysqli_query($conn, $status_sql);
		if (mysqli_num_rows($status_result) > 0) {
			while($row = mysqli_fetch_assoc($status_result)) {
				$Schedule_delivery_date=$row["Schedule_delivery_date"];
				$check_status=$row["status_name"];
				if($check_status!="completed" AND $check_status !="shipped"){
					if ($today > strtotime("$Schedule_delivery_date -1 week")){
							$alert_level= 'style="background-color:#f98181"';
							}
						elseif ($today > strtotime("$Schedule_delivery_date -2 weeks")){
							$alert_level= 'style="background-color:#f9a181"';
							}
						else {
							$alert_level='style="background-color:#4286f4"';
						}
					
				}else{
					$alert_level='style="background-color:white"';
				}
				$table_content.='<tr '.$alert_level.' ><td>'.$row["item_name"].'</td><td>'.$row["item_quantity"].'</td><td>'.$row["status_name"].'</td><td>'.$row["Schedule_delivery_date"].'</td><td>'.$row["supplier_name"].'</td><td>'.$row["order_number"].'</td><td><a class="btn" href="'.$edit_order.'?order_id='.$row["order_id"].'" >edit</a></td></tr>';
			}
		}
		else{"<tr><td>No results</td></tr>";}
		$sql2="
		SELECT *
		FROM suppliers
		JOIN custom_items ON  custom_items.custom_supplier_id=suppliers.supplier_id
		JOIN order_custom_items ON custom_items.custom_item_id=order_custom_items.custom_item_id
		JOIN orders_table ON orders_table.order_id= order_custom_items.order_id
		JOIN status ON status.status_id=order_custom_items.status_id
		WHERE status_name<>'delivered'";	
		$custom_result = mysqli_query($conn, $sql2);
		if (mysqli_num_rows($custom_result) > 0) {	
			$Schedule_delivery_date=$row["Schedule_delivery_date"];
			$check_status=$row["status_name"];		
			if($check_status!="completed" AND $check_status !="shipped"){
				if ($today > strtotime("$Schedule_delivery_date -1 week")){
						$alert_level= 'style="background-color:#f98181"';
						}
					elseif ($today > strtotime("$Schedule_delivery_date -2 weeks")){
						$alert_level= 'style="background-color:#f9a181"';
						}
					else {
						$alert_level='style="background-color:#4286f4"';
					}
			}else{
				$alert_level='style="background-color:white"';
				
			}
			while($row = mysqli_fetch_assoc($custom_result)) {
				$table_content.='<tr '.$alert_level.' ><td>'.$row["item_name"].'</td><td>'.$row["item_quantity"].'</td><td>'.$row["status_name"].'</td><td>'.$row["Schedule_delivery_date"].'</td><td>'.$row["supplier_name"].'</td><td>'.$row["order_number"].'</td><td><a class="btn" href="'.$edit_order.'?order_id='.$row["order_id"].'" >edit</a></td></tr>';
			}
		}
		else{"<tr><td>No results</td></tr>";}
		echo $table_header.$table_content;
	}

	?>