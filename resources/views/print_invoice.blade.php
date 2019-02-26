<?php
include(app_path().'/includes/connect.php');

if (!empty($_GET["lang"])){
    $lang=$_GET["lang"];
    switch ($lang){
        case "ee":
            include(app_path().'/localization/ee_EE.php');
            break;
        case "fi":
            include(app_path().'/localization/fi_FI.php');
            if (include(app_path().'/localization/fi_FI.php')){break;}
        default:
            include(app_path().'/localization/en_EN.php');
    }
    
}
$total="0";
$order_id=94;
$coeff=1;
$VAT_rate=0.2;
$today=date("d.m.y");
function price($price,$coeff){return round($coeff*$price,2);}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <title>Integrated Order Management Tool </title>

  <!-- CSS  -->

  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection,print"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection,print"/>
   <style>
    .price_align{
    text-align:right
    }
	.vert-bottom-block{
		height:120px; position: relative;
	}
	.vert-align-bottom{
		margin: 0;
	position: absolute;
	  top: 100%;
	  -ms-transform: translateY(-100%);
	  transform: translateY(-100%);
	}
	tr{border:0px;}
	.item_list_header{border-bottom: 2px solid black;}
	.item_list{border-bottom: 1px solid black;}
	.container{width:1024px}
	table.cst_details *{
		padding:0px;
	}
	#footer_container{
		  position: relative;
	min-height: 150px;
	}
#footer {
  position: absolute;
  bottom: 0;
  left: 0;
}
  </style>
</head>
<body>
	<div class="container" >
		<div class="section">
			<div class="row">
				<div class="col s6 ">
					<div class="row">
						<?php
						if (!empty($_GET["order_id"])){
						$order_id=mysqli_real_escape_string($conn,$_GET["order_id"]);
						}
						$vendor_sql="SELECT * 
						FROM orders_table 
						JOIN vendor ON vendor.vendor_id=orders_table.vendor_id
						JOIN vendor_address ON vendor.vendor_id=vendor_address.vendor_id
						WHERE orders_table.order_id='$order_id'";

						$vendor_result=mysqli_query($conn,$vendor_sql);
						if (mysqli_num_rows($vendor_result) > 0) {
							while($row = mysqli_fetch_assoc($vendor_result)) {
							    $vendor_name=$row['vendor_name'];
								$company_name=$row['company_name'];
								$vendor_address=$row['address'];
								$vendor_bankaccount=$row['konto'];
								$vendor_telephone=$row['phone'];
								$vendor_email=$row['email'];
								$vendor_reg_nbr=$row['rg_kood'];
								$vendor_eu_vat_nb=$row['eu_vat_nb'];
								if($vendor_name=="Furnest EE"){$index=(string)'-1';}
								else{$index='';}
								echo '<div class="col s12 " >
                                        <div >
                                            <h4>'.$invoice_str.': '. $row['order_number'].$index.'</h4>
                                            <p>'.$date_str.': '.date("d.m.y").'<br>
                                            '. $paybefore_str.': '.date("d.m.y",strtotime("$today +1 week")).'<br>
                                           '.$payment_condition_str.' '.$payment_condition.'</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col s6">
                                <div class="row">
                                    <div class="col s12">
                                        <h3 >'.$row['company_name'].'</h3>
                                        <p>'.$tel_str.' '.$row['phone'].'<br>'.$row['address'].'<br>'.$rg_kood_str.' '.$row['rg_kood'].'<br>'.$bankaccount_str.' '.$row['konto'].'</p>
                                    </div>
                                </div>
                            </div>';	
								}		
						}
						?>
			</div>
			<div class="row">
				<div class="col s6">

					<div class="col s12 ">
						<?php
						if (!empty($_GET["order_id"])){
						$order_id=mysqli_real_escape_string($conn,$_GET["order_id"]);
						}
						$customer_sql="SELECT * 
						FROM orders_table 
						JOIN customers ON customers.customer_id=orders_table.customer_id
						WHERE orders_table.order_id='$order_id'";
						$customer_result=mysqli_query($conn,$customer_sql);
						if (mysqli_num_rows($customer_result) > 0) {
							while($row = mysqli_fetch_assoc($customer_result)) {
							    if($row["vat_id"]){$has_vat_id=1;};
							    if(isset($has_vat_id) && $lang=="fi"){$has_vat_id=1;$VAT_rate=0;};
								echo '<table class="cst_details">
								<tr><td>'.$customer_str.'</td><td>'.$row["customer_name"].'</td></tr>
								<tr><td>'.$address_str.'</td><td>'.$row["customer_address"].'</td></tr>
								<tr><td>'.$tel_str.'</td><td>'.$row["customer_phone"].'</td></tr>
								<tr><td>'.$email_str.'</td><td>'.$row["customer_mail"].'</td></tr>';
								if($row["vat_id"]){ echo '<tr><td>'.$eu_vat_str.'</td><td>'.$row["vat_id"].'</td></tr>';};
								echo '</table>';
								}		
						}
						?>

					</div>				
				</div>
				<div class="col s6 ">
					<p class=""> </p>
				</div>
			</div>
		</div>
		<div class="section">
			<div class="row">
				<div class="col s12">
					<table>
						<tr class="item_list_header"><th>Nimetus</th><th>Kogus</th><th class="price_align">Hind €</th><th class="price_align">Kokku €</th></tr>
						<?php
						
						if (!empty($_GET["order_id"])){
						$order_id=mysqli_real_escape_string($conn,$_GET["order_id"]);
						}
						
						$sql="SELECT * 
						FROM orders_table 
						JOIN order_items ON orders_table.order_id=order_items.order_id
						JOIN items ON items.item_id=order_items.item_id
						WHERE orders_table.order_id='$order_id'";

						$result=mysqli_query($conn,$sql);
						if (mysqli_num_rows($result) > 0) {
							while($row = mysqli_fetch_assoc($result)) {
							    $subtotal=$row['item_quantity']*price($row['item_price'],$coeff);
							    $total+=$subtotal;
							    $subtotal=number_format($subtotal,2);
							    echo '<tr><td>'.$row['item_name'].'</td><td>'.$row['item_quantity'].'</td><td class="price_align">'.number_format(price($row['item_price'],1)).'</td><td class="price_align">'.$subtotal.'</td></tr>';	
								}		
						}
						$sql2="SELECT * 
						FROM orders_table 
						JOIN order_custom_items ON orders_table.order_id=order_custom_items.order_id
						JOIN custom_items ON custom_items.custom_item_id=order_custom_items.custom_item_id
						WHERE orders_table.order_id='$order_id'";
						$result2=mysqli_query($conn,$sql2);
						if (mysqli_num_rows($result2) > 0) {
							while($row = mysqli_fetch_assoc($result2)) {
							    $subtotal=$row["item_quantity"]*price($row["custom_item_price"],$coeff);
							    $total+=$subtotal;
							    $subtotal=number_format($subtotal,2);
							    echo '<tr><td>'.$row['item_name'].'<br>'.$row["custom_item_description"].'</td><td>'.$row['item_quantity'].'</td><td class="price_align">'.number_format(price($row['custom_item_price'],1),2).'</td><td class="price_align">'.$subtotal.'</td></tr>';
								};		
						}
						$VAT=$VAT_rate*$total;
						$kogumaksumus=$VAT+$total;
						$kogumaksumus_display=number_format($kogumaksumus,2,',',' ');
						$kogumaksumus=(float)$kogumaksumus;
						$VAT=number_format($VAT,2);
						
						echo '<tr class="item_list"><td></td><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><td><b>Tooted kokku</b></td><td class="price_align"><b>'.$total.'</b></td></tr>';
						if(!isset($has_vat_id) OR $lang=="ee"){echo '<tr class="item_list"><td></td><td></td><td><b>'.$VAT_str.'</b></td><td class="price_align">'.$VAT.'</td></tr>';}
						else{echo '<tr class="item_list"><td></td><td></td><td><b>'.$no_vat.'</b></td><td class="price_align">'.$VAT.'</td></tr>';}
						echo '<tr><td></td><td></td><td><b>Kogumaksumus käibemaksuga</b></td><td class="price_align"><b>'.$kogumaksumus_display.'</b></td></tr>';	
						if ($lang=="ee"){
						    $ettemaks=$kogumaksumus/2;
						    $ettemaks=number_format($ettemaks,2,',',' ');
						    
						    echo '<tr class="item_list"><td></td><td></td><td><b>'.$payment_condition.'</b></td><td class="price_align"><b>'.$ettemaks.'</b></td></tr>';
						}
						?>
						
					</table>
				</div>
			</div>
		</div>
	</div><!--END ROW -->
	</div><!--END SECTION -->
	<div class="section">
		<div class="row">
			<div class="col s12">
			<?php echo $legal_text;?>
			</div>
		</div>
	</div>
	
	<div class="section" id="footer_container">
	<hr>
	<div class="row" id="footer">
	
	<?php	echo $vendor_name.' '. $vendor_address.' '.$bankaccount_str.': '.$vendor_bankaccount.'<br>'.$tel_str.':'.$vendor_telephone.' '.$email_str.': '.$vendor_email.'<br>'.$rg_kood_str.': '.$vendor_reg_nbr.' '.$eu_vat_str.': '.$vendor_eu_vat_nb; ?>
	</div>
	</div>
</div><!-- END CONTAINER -->
  <!--  Scripts-->
  <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>

  </body>
</html>
