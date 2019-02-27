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
<tr><th></th><th>Name</th><th>SKU</th><th>Supplier</th><th>Price</th><th>Description</th><th></th></tr>
@foreach($items as $item)
	<tr><td><!--  a title="delete" onclick="return confirm('Delete?');" href="{{route('delete',$item->item_id)}}"><i class="small material-icons">delete</i></a--></td><td>{{$item->item_name}}</td><td>{{$item->supplier_sku}}</td><td>{{$item->item_supplier_id}}</td><td>{{$item->item_price}}</td><td>{{$item->item_description}}</td><td><a title="editItem" href="{{route('edit_item',$item->item_id)}}"><i class="small material-icons">edit</i></a></td></tr>
@endforeach
</table>
{{$items->links('vendor.pagination.materializecss')}}


@endsection('content') 