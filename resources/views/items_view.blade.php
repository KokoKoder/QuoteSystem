@extends('layouts.master')
@section('content') 
<?php
include(app_path().'/includes/connect.php');
$sql_items="SELECT * FROM items JOIN suppliers ON suppliers.supplier_id=items.item_supplier_id";
$sql_custom_items="SELECT * FROM custom_items JOIN suppliers ON suppliers.supplier_id=custom_items.custom_supplier_id";
$result_items = mysqli_query($conn, $sql_items);
$result_custom_items = mysqli_query($conn, $sql_custom_items);
?>
<h2>Items list</h2>
<table>
@foreach($items as $item)
	<tr><td><a title="delete" onclick="return confirm('Delete?');" href="{{route('delete',$item->item_id)}}"><i class="small material-icons">delete</i></a></td><td>{{$item->item_name}}</td><td>{{$item->supplier_sku}}</td><td>{{$item->item_supplier_id}}</td><td>{{$item->item_price}}</td><td>{{$item->item_description}}</td><td>{{$item->item_weight}}</td><td>{{$item->package_weight}}</td><td><a title="editItem" href="{{route('editItem',$item->item_id)}}"><i class="small material-icons">edit</i></a></td></tr>
@endforeach
</table>

{{$items->links('vendor.pagination.materializecss')}}
<h2>Custom items list</h2>
<table>
@foreach($custom_items as $custom_item)
<tr><td><a title="delete" onclick="return confirm('Delete?');" href="{{route('deleteCustom',$custom_item->custom_item_id)}}"><i class="small material-icons">delete</i></a></td><td>{{$custom_item->item_name}}</td><td>NA</td><td>{{$custom_item->custom_supplier_id}}</td><td>{{$custom_item->custom_item_price}}</td><td>{{$custom_item->custom_item_description}}</td><td>NA</td><td>NA</td></tr>
@endforeach

</table>
{{$custom_items->links('vendor.pagination.materializecss')}}
<table>
	<tr><th>Name</th><th>SKU</th><th>Supplier</th><th>Price</th><th>Description</th><th>Dimensions</th><th>Package Dimensions</th><th>Item per package</th></tr>
<?php
if (mysqli_num_rows($result_items) > 0) {
    while($row = mysqli_fetch_assoc($result_items)) {
        echo '<tr><td>'.$row['item_name'].'</td><td>'.$row['supplier_sku'].'</td><td>'.$row['supplier_name'].'</td><td>'.$row['item_price'].'</td><td>'.$row['item_description'].'</td><td></td><td></td><td></td></tr>';

    };
}
if (mysqli_num_rows($result_custom_items) > 0) {
    while($row = mysqli_fetch_assoc($result_custom_items)) {
        echo '<tr><td>'.$row['item_name'].'</td><td>NA</td><td>'.$row['supplier_name'].'</td><td>'.$row['custom_item_price'].'</td><td>'.$row['custom_item_description'].'</td><td></td><td></td><td></td></tr>';
    };
}

?>
'</table>
@endsection('content') 