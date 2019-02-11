  <style>
  input[type=text]:not(.browser-default){
  border:none;
  }
  </style>
  <div class="section no-pad-bot" id="index-banner">
    <div class="container">
      <br><br>
      <h1 class="header center orange-text">Edit Order</h1>
      <div class="row center">
        <h5 class="header col s12 light">Edit an existing order </h5>
      </div>

      <br><br>

    </div>
  </div>
<form autocomplete="off" method="POST" action="/quotesystem/public/validate_order_edit" class="col s12">
  <div class="row">
  <input type="hidden" value="[+order_id+]" name="order_id">
	<div class="input-field col s6">
		<b>Order number</b>
		<input type="text" value="[+order_number+]" name="order_number">
	</div>
	<div class="input-field col s6">
		<table>
			<tr>
			<th>Customer Name</th>
			<th>address</th>
			<th>phone</th>
			<th>mail</th>
			</tr>
			<tr>
				<td>[+customer_name+]</td>
				<td>[+customer_address+]</td>
				<td>[+customer_phone+]</td>
				<td>[+customer_mail+]</td>
			</tr>
		</table>
		<a href="//quotesystem/public/edit_customer?customer_id=[+customer_id+]">edit customer details</a>
	</div>
  </div>
  <div class="row">
	<div name="vendor" id="vendor" class="input-field col s6">
		<b>[+vendor_name+]</b>
		<select id="vendor_select">
			  <option value="" disabled selected>Change vendor</option>
			  <option value="14">Furnest</option>
			  <option value="15">Sisustuskaluste</option>
			  <option value="16">Sisustusmööbel</option>
		</select>
		<input type="hidden" name="vendor_id" id="vendor_hidden" value="[+vendor_id+]" />
	</div>
	<div class="input-field col s6">
	<b>Order date</b>
    <input type="text" class="datepicker" value="[+order_date+]" name="order_date">
	</div>
  </div>
  <button class="btn" type="submit">validate</button>
</form>


	
