
<?php
session_start();
#connect to the database
include(app_path().'/includes/connect.php');
include(app_path().'/includes/get_status_list.php');
include (app_path().'/includes/parse.php');
$today = date ('Y-m-d',time());
$today = strtotime($today);
$delete_url=route('delete_order_item');
$delete_custom_url=route('delete_order_custom_item');
$edit_order_item_url=route('edit_order_item');
$edit_custom_order_item_url=route('edit_custom_order_item');
if (isset($_SESSION["order_id"])){
		$order_id=$_SESSION["order_id"];
		#get standard items
		$sql="
		SELECT order_items.item_quantity, order_items.Schedule_delivery_date,items.item_name, suppliers.supplier_name, order_items.id, order_items.status_id, order_items.timestamp, status.status_name, items.item_supplier_id
		FROM order_items 
		JOIN items ON items.item_id=order_items.item_id
		JOIN suppliers  ON suppliers.supplier_id=items.item_supplier_id
		JOIN status ON order_items.status_id=status.status_id
		WHERE order_items.order_id='$order_id' 
		";
		$result = mysqli_query($conn, $sql);
		$line=$lines=""; 
		$single_tpl = file_get_contents( 'tpls/single_result.tpl');  
		$token = csrf_token();

		#get custom items
		
		$sql2="
		SELECT *
		FROM order_custom_items 
		JOIN custom_items ON custom_items.custom_item_id=order_custom_items.custom_item_id
		JOIN suppliers  ON suppliers.supplier_id=custom_items.custom_supplier_id
		JOIN status ON order_custom_items.status_id=status.status_id
		WHERE order_custom_items.order_id='$order_id'
		";
		$result2 = mysqli_query($conn, $sql2);
		
		#prepare standard items result for display
		
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$option_status="";
				$state="";
				$Schedule_delivery_date=$row["Schedule_delivery_date"];
				
				if ($row['status_id']<4){
					if ($today > strtotime("$Schedule_delivery_date -1 week")){
						$alert_level= 'style="background-color:#f98181"';
						}
					elseif ($today > strtotime("$Schedule_delivery_date -2 weeks")){
						$alert_level= 'style="background-color:#f9a181"';
						}
					else {
						$alert_level='style="background-color:#4286f4"';
					}
				}
				else{
					$alert_level='style="background-color:white"';
				}
			
				foreach ($options as $option ){
					list($option_id,$option_name)=preg_split("[,]",$option);
					if ($row['status_id']==$option_id){
						$state="selected ";
					}
					else{
						$state="";
						}
						
					$option_status.="<option ".$state." value='".$option_id."' >".$option_name.'</option>';
				} 
				
				$status_selection_html='<select class="browser-default" name="item_status" style="display:block">'.$option_status.'</select>';
			    $line='<form method="POST" action="'.$edit_order_item_url.'" class="col s12"><input type="hidden" name="_token" id="csrf-token" value="'.$token.'">
				  <div class="row" '.$alert_level.'>
						<div class="col s1"><p><a class="incline"  href="'.$delete_url.'?id='.$row['id'].'">x</a></p></div>
						<div class="col s2"><input  type="hidden" name="id" value="'.$row['id'].'"><p>'.$row['item_name'].'</p></div>
						<div class="col s1"><input  type="text" value="'.$row['item_quantity'].'" name="item_quantity"></div>
						<div class="col s2"><input   type="text" class="datepicker" value="'.$row['Schedule_delivery_date'].'" name="Schedule_delivery_date"></div>
						<div class="col s1"><p>'.$row['supplier_name'].'</p></div>
						<div class="col s2">'.$status_selection_html.'</div>
						<div class="col s1"><button class="btn inline" type="submit" value="submit"><i class="material-icons">done</i></button></div>
					</div>
				</form>';
				$lines .= $line;
			}
		}
		
		#prepare the custom items result for display
		
		$lines2="";
		if (mysqli_num_rows($result2) > 0) {
			while($row = mysqli_fetch_assoc($result2)) {
				$option_status="";
				$state="";
				$Schedule_delivery_date=$row["Schedule_delivery_date"];

				if ($row['status_id']<4){
					if ($today > strtotime("$Schedule_delivery_date -1 week")){
						$alert_level= 'style="background-color:#f98181"';
						}
					elseif ($today > strtotime("$Schedule_delivery_date -2 weeks")){
						$alert_level= 'style="background-color:#f9a181"';
						}
					else {
						$alert_level='style="background-color:#4286f4"';
					}
				}
				else{
					$alert_level='style="background-color:white"';
				}
				foreach ($options as $option ){
					list($option_id,$option_name)=preg_split("[,]",$option);
					if ($row['status_id']==$option_id){
						$state="selected ";
					}
					else{
						$state="";
						}
					$option_status.="<option ".$state." value='".$option_id."' >".$option_name.'</option>';
				} 
				$status_selection_html='<select class="browser-default" name="item_status" style="display:block">'.$option_status.'</select>';
			    $line='<form method="POST" action="'.$edit_custom_order_item_url.'" class="col s12"><input type="hidden" name="_token" id="csrf-token" value="'.$token.'">
				  <div class="row" '.$alert_level.'>
						<div class="col s1"><p><a class="incline"  href="'.$delete_custom_url.'?id='.$row['id'].'">x</a></p></div>
						<div class="col s2"><input  type="hidden" name="id" value="'.$row['id'].'"><p>'.$row['item_name'].'</p></div>
						<div class="col s1"><input  type="text" value="'.$row['item_quantity'].'" name="item_quantity"></div>
						<div class="col s2"><input   type="text" class="datepicker" value="'.$row['Schedule_delivery_date'].'" name="Schedule_delivery_date"></div>
						<div class="col s1"><p>'.$row['supplier_name'].'</p></div>
						<div class="col s2">'.$status_selection_html.'</div>
						<div class="col s1"><button class="btn inline" type="submit" value="submit"><i class="material-icons">done</i></button></div>
					</div>
				</form>';
				$lines2 .= $line;
			}
		}

	
#Print the all results to the page
print   '<style>
  input[type=text]:not(.browser-default){
  border:none;
  }
  </style> 
  <div class="row">
		<div class="col s1"><p></p></div>
		<div class="col s2"><p><b>Product name</b></p></div>
		<div class="col s1"><p><b>Quantity</b></p></div>
		<div class="col s2"><p><b>Schedule delivery date</b></p></div>
		<div class="col s1"><p><b>Supplier name</b></p></div>
		<div class="col s2"><p><b>Status</b></p></div>
		<div class="col s1"></div>
  
  </div>
  <div class="row">'.$lines.$lines2.'</div>';
	}
else{
	echo "Order id is not set";
}

?>

