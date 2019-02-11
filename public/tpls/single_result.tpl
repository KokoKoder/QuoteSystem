
  <tr>
	<form>
	@csrf
	<td><a href="/quotesystem/public/delete_item?id=[+id+]">x</a></td>
    <td>[+item_name+]</td>
	<td><input type="text" value="[+item_quantity+]" name="item_quantity"></td>
    <td><input type="text" class="datepicker" value="[+Schedule_delivery_date+]" name="Schedule_delivery_date"></td> 
    <td>[+supplier_name+]</td>
	<td><input type="text" name="item_status" value="[+status_name+]"><span id="#edit_status" onclick="select_status();">edit</span></td>
	<td><input type="text" name="timestamp" value="[+timestamp+]"></td>
	<td><a href="/quotesystem/public/edit_item?id=[+id+]">confirm change</a></td>
	</form>
  </tr>
