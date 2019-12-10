@extends('layouts.master')
<?php
include(app_path().'/includes/get_vendors_list.php');
?>
@section('content')
<form action="{{route('most_sold')}}" class="row">
@csrf
	<select id="vendor_id" class="col s6 browser-default" style="display:block" name="vendor_id" >
	<?php 
		$item_vendor_filter="";
		foreach ($vendors_list as $vendor){
			list($option_vendor_id,$option_vendor_name)=preg_split("[,]",$vendor);
			 $item_vendor_filter.='<option value="'.$option_vendor_id.'">'.$option_vendor_name.'</option>';
		}
		echo '<option selected disabled value="">vendor filter</option><option  value="0">All vendors</option>'.$item_vendor_filter;
	?>
	</select>
	<button class="btn" type="submit">Filter</button>
</form>
<table>
<tbody>
<tr><th>Item name</th><th>Nb of order with this item</th></tr>

@foreach($items as $item)
<tr><td>{{$item->item_name}}</td><td>{{$item->item_count}}</td></tr>
@endforeach
</tbody>
</table>
@endsection('content')
