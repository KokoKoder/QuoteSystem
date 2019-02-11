
  <div class="row">
  <input type="hidden" value="[+order_id+]" name="order_id">
	<div class="input-field col s6">
		<p><b>Order number</b><br>
		[+order_number+]</p>
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
		<a href="/quotesystem/public/edit_customer?customer_id=[+customer_id+]"><i class="material-icons">edit</i></a>
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
  <button class="btn" type="submit"><i class="material-icons">done</i></button>

