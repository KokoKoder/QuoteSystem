<?php
session_start();
include(app_path().'/includes/connect.php');
if(isset($_GET["vendor_id"])){
	$_SESSION['vendor_id']=$_GET["vendor_id"];
}
if (isset($_SESSION['vendor_id'])){
	$v_id=$_SESSION["vendor_id"];
	$sql="SELECT vendor_name,address,email,phone,rg_kood,konto,eu_vat_nb FROM vendor JOIN vendor_address ON vendor_address.vendor_id=vendor.vendor_id WHERE vendor_address.vendor_id='$v_id'";
	$vendor_details=[];
	$result=mysqli_query($conn,$sql);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$vendor_details[]=$row['vendor_name'];
			$vendor_details[]=$row['address'];
			$vendor_details[]=$row['email'];
			$vendor_details[]=$row['phone'];
			$vendor_details[]=$row['rg_kood'];
			$vendor_details[]=$row['konto'];
			$vendor_details[]=$row['eu_vat_nb'];
			};
			
	}
?>