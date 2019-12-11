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
            break;
        default:
            include(app_path().'/localization/en_EN.php');
    }
    
}
$total=$totalvat="0";
$order_id="94";
$coeff=1;
if(isset($has_vat_id) && $lang=="fi"){
    $VAT_rate=0;
}else{
    $VAT_rate=0.2;
}
$today=date("d.m.y");
function price($price,$coeff){return round($coeff*$price,2);}
function eur_format($value){return number_format(round($value,2),2,',',' ');}
echo(round(13,356984,2));
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
   td{
   padding:0px;
   }
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
	td{padding:2px;}
	th{vertical-align:top;}
	.item_list_header{border-bottom: 2px solid black;}
	.item_list{border-bottom: 1px solid black;}
	.container{width:1024px}}
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
					    $vendor_name=htmlspecialchars($row['vendor_name']);
					    $company_name=htmlspecialchars($row['company_name']);
					    $vendor_address=htmlspecialchars($row['address']);
					    $vendor_bankaccount=htmlspecialchars($row['konto']);
					    $vendor_telephone=htmlspecialchars($row['phone']);
					    $vendor_email=htmlspecialchars($row['email']);
					    $vendor_reg_nbr=htmlspecialchars($row['rg_kood']);
					    $vendor_eu_vat_nb=htmlspecialchars($row['eu_vat_nb']);
					    $pay_before=date("d.m.y",strtotime($row['pay_before']));
					    $reference_nb=htmlspecialchars($row['reference_nb']);
					    if($pay_before!="01.01.70"){
					        $pay_before=date("d.m.y",strtotime($row['pay_before']));
					    }else{
					        $pay_before=date("d.m.y",strtotime ("today + 1 week" ));
					      
					    }
					    switch ($vendor_name){
					        case "Sisustusmööbel":
					            $logo="";
					            break;
					        case "Sisustuskaluste":
					            $logo="";
					            break;
					        default:
					            $logo='<img src="./pictures/furnest-logo-md.jpg" alt="">';
					    }
						if($vendor_name=="Furnest EE"){$index=(string)'-1';}
						else{$index='';}
						if($row["reference_nb"]){$reference_nb=$reference_nb_str.': '.$reference_nb;}
						else{$reference_nb='';}
						echo '<table><tr><th style="width:50%"><h5>'.$invoice_str.': <br>'. $row['order_number'].$index.'</h5></th><th>'.$logo.'</th></tr><tr><td>
                                     '.$reference_nb.'<br>
                                    '.$date_str.': '.date("d.m.y").'<br>
                                    '. $paybefore_str.': '.$pay_before.'<br>
                                   '.$payment_condition_str.' '.$payment_condition.'
                            </td>
                            <td>'.$company_name.'<br>
                                '.$tel_str.' '.$vendor_telephone.'<br>'.$vendor_address.'<br>'.$rg_kood_str.' '.$vendor_reg_nbr.'<br>'.$bankaccount_str.' '.$vendor_bankaccount.'
                            </td></tr></table>';	

					}
				}
				?>
			</div>
		</div>
		<div class="section">
			<div class="row">
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
						<tr><td style="width:20%">'.$customer_str.'</td><td>'.$row["customer_name"].'</td></tr>
						<tr><td>'.$address_str.'</td><td>'.$row["customer_address"].'</td></tr>
						<tr><td>'.$tel_str.'</td><td>'.$row["customer_phone"].'</td></tr>
						<tr><td>'.$email_str.'</td><td>'.$row["customer_mail"].'</td></tr>';
						if($row["registration_nb"]){ echo '<tr><td>'.$rg_kood_str.'</td><td>'.$row["registration_nb"].'</td></tr>';};
						if($row["vat_id"]){ echo '<tr><td>'.$eu_vat_str.'</td><td>'.$row["vat_id"].'</td></tr>';};
						echo '</table>';
						}		
				}
				?>
			</div>
		</div>
		<div class="section">
			<div class="row">
					<table>
						<tr class="item_list_header"><th>@php echo $itemname_str; @endphp</th><th>@php echo $quantity_str; @endphp</th><th class="price_align">@php echo $price_str; @endphp</th><th class="price_align">@php echo $sum_str @endphp</th></tr>
						<?php
						
						if (!empty($_GET["order_id"])){
						$order_id=mysqli_real_escape_string($conn,$_GET["order_id"]);
						}
						
						$sql="SELECT * 
						FROM orders_table 
						JOIN order_items ON orders_table.order_id=order_items.order_id
						WHERE orders_table.order_id='$order_id'";

						$result=mysqli_query($conn,$sql);
						if (mysqli_num_rows($result) > 0) {
							while($row = mysqli_fetch_assoc($result)) {
							    $subtotal=$row["item_quantity"]*price($row["item_price"],$coeff);
							    $total+=$subtotal;
								$subvat=$row["item_quantity"]*round($VAT_rate*price($row["item_price"],$coeff),2);
							    $totalvat+=$subvat;
							    $subtotal_display=number_format($subtotal,2,',',' ');
							    echo '<tr><td>'.$row['item_name'].'</td><td>'.$row['item_quantity'].'</td><td class="price_align">'.number_format(price($row['item_price'],1),2,',',' ').'</td><td class="price_align">'.$subtotal_display.'</td></tr>';	
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
							    $subvat=$row["item_quantity"]*round($VAT_rate*price($row["custom_item_price"],$coeff),2);
							    $totalvat+=$subvat;								
							    $subtotal_display=number_format($subtotal,2,',',' ');
							    echo '<tr><td>'.$row['item_name'].'<br>'.$row["custom_item_description"].'</td><td>'.$row['item_quantity'].'</td><td class="price_align">'.number_format(price($row['custom_item_price'],1),2,',',' ').'</td><td class="price_align">'.$subtotal_display.'</td></tr>';
								};		
						}
						$total_display=eur_format($total);
						$VAT=$VAT_rate*$total;
						$kogumaksumus=$VAT+$total;
						$kogumaksumus_display=eur_format($kogumaksumus);
						$kogumaksumus=(float)$kogumaksumus;
						$VAT=number_format($VAT,2,',',' ');
						echo '<tr class="item_list"><td></td><td></td><td></td><td></td></tr>
						<tr><td></td><td></td><th>'.$total_str.'</th><th class="price_align">'.$total_display.'</th></tr>';
						if(isset($has_vat_id) && $lang=="fi"){echo '<tr class="item_list"><td></td><td></td><th>'.$no_vat.'</th><td class="price_align">'.$VAT.'</td></tr>';}
						else{echo '<tr class="item_list"><td></td><td></td><td><b>'.$VAT_str.'</b></td><td class="price_align">'.$VAT.'</td></tr>';}
						echo '<tr><td></td><td></td><th>'.$totalvat_str.'</th><th class="price_align">'.number_format(round($kogumaksumus_display,2),2).'</th></tr>';	
						if ($lang=="ee"){
						    $ettemaks=$kogumaksumus/2;
						    $ettemaks=number_format($ettemaks,2,',',' ');
						    echo '<tr class="item_list"><td></td><td></td><th>'.$payment_condition.'</th><td class="price_align"><b>'.$ettemaks.'</b></td></tr>';
						}
						?>		
					</table>
			</div>
		</div>
	</div><!--END SECTION -->
	<div class="section">
		<div class="row">
			<div class="col s12">
			<p><?php if($vendor_name=="Furnest EE" or $vendor_name=="Furnest FI"){echo $legal_text;}?></p>
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
