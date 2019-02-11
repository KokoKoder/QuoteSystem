
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Order Management System </title>

  <!-- CSS  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
</head>
<body>
  <nav class="light-blue lighten-1" role="navigation">
    <div class="nav-wrapper container"><a id="logo-container" href="#" class="brand-logo">OMS</a>
      <ul class="right hide-on-med-and-down">
        <li><a href="{{route('enter_order')}}">Enter Order</a></li>
		<li><a href="{{route('enter_supplier')}}">Enter Supplier</a></li>
		<li><a href="{{route('enter_item')}}">Enter Item</a></li>
		<li><a href="{{route('orders_view')}}">View Orders</a></li>
		<li><a href="{{route('orders_suppliers_view')}}">View Orders by suppliers</a></li>
      </ul>

      <ul id="nav-mobile" class="sidenav">
        <li><a href="{{route('enter_order')}}">Enter Order</a></li>
		<li><a href="{{route('enter_supplier')}}">Enter Supplier</a></li>
		<li><a href="{{route('enter_item')}}">Enter Item</a></li>
		<li><a href="{{route('orders_view')}}">View Orders</a></li>
		<li><a href="{{route('orders_suppliers_view')}}">View Orders by suppliers</a></li>
      </ul>
      <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
    </div>
  </nav>
<div class="container">
<div class="row">
	<form id="logout-form" action="{{ route('logout') }}" method="POST"  class="col s12 text-right">
		@csrf
		<p>Logged in as <b>{{Auth::user()->name}}</b><span style="margin-left:15px"><button class="waves-effect waves-light btn ">Logout</button></span></p>
	</form>
</div>
</div>

  <div class="container">
 @yield('content')
 </div>
  <footer class="page-footer orange">
    <div class="container">
      <div class="row">
        <div class="col l6 s12">
          <h5 class="white-text">Furnest</h5>
          <p class="grey-text text-lighten-4">Furniture selling company for private and professional customer. Restaurant, office, coffee, fastfood and bar, home and garden in Estonia and Finland. Custom made in Estonia, import and serie production.</p>
        </div>
        <div class="col l3 s12">
          <h5 class="white-text">Enter</h5>
          <ul>
            <li><a class="white-text" href="{{route('enter_order')}}">Enter Order</a></li>
            <li><a class="white-text" href="{{route('enter_supplier')}}">Enter Supplier</a></li>
            <li><a class="white-text" href="{{route('enter_item')}}">Enter Item</a></li>
			<li><a class="white-text" href="{{route('enter_order_status')}}">Enter order status</a></li>
          </ul>
        </div>
        <div class="col l3 s12">
          <h5 class="white-text">View</h5>
          <ul>
            <li><a class="white-text" href="{{route('orders_view')}}">View Orders</a></li>
            <li><a class="white-text" href="{{route('suppliers_view')}}">View Suppliers</a></li>
			<li><a class="white-text" href="{{route('vendor_details')}}">Vendor details</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="footer-copyright">
      <div class="container">
      Made for <a class="orange-text text-lighten-3" href="https://www.furnest.ee/">Furnest furniture OÜ</a>
      </div>
    </div>
  </footer>


  <!--  Scripts-->
  <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>
  <script src="js/autocomplete.js"></script>
  <script>
	@stack('scripts')
	//Date Picker
	document.addEventListener('DOMContentLoaded', function() {
	var options = {
		format: 'yyyy-mm-dd',
     };
    var elems = document.querySelectorAll('.datepicker');
    var instances = M.Datepicker.init(elems, options);
	});
	
	//Select
	$(document).ready(function(){
	$('select').formSelect();
	});
  
	// Post Materialize select 
	$('#vendor_select').on('change', function() {
		var vendor_select=document.getElementById("vendor_select").value;
		document.getElementById("vendor_hidden").value=vendor_select;
	});   
	
	
	// Add csrf token to js query
	$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
	});
  </script>
  </body>
</html>