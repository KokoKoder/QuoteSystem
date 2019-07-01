@extends('layouts.master')
@section('content') 
<?php
include(app_path().'/includes/connect.php');
if ($_SERVER["REQUEST_METHOD"] == "GET") {
$sql_items="SELECT * FROM items JOIN suppliers ON suppliers.supplier_id=items.item_supplier_id";
$sql_custom_items="SELECT * FROM custom_items JOIN suppliers ON suppliers.supplier_id=custom_items.custom_supplier_id";
$result_items = mysqli_query($conn, $sql_items);
$result_custom_items = mysqli_query($conn, $sql_custom_items);
}else{
    $search_term=mysqli_real_escape_string($conn,$_POST["search_item"]);
    $search_term=$search_term.'%';
    $sql_items="SELECT * FROM items JOIN suppliers ON suppliers.supplier_id=items.item_supplier_id WHERE items.item_name=search_term";
    $sql_custom_items="SELECT * FROM custom_items JOIN suppliers ON suppliers.supplier_id=custom_items.custom_supplier_id WHERE custom_items.item_name=search_term";
}
?>
<h2>Items list</h2>
<form action="<?php $_SERVER["PHP_SELF"] ?>">
<div class="input-field col s6">
	<input type="text" name="search_term" >
</div>
<button type="submit" class="waves-effect waves-light btn">Search</button>
</form>
@php
if (isset($search_term)){echo $search_term;}
@endphp
<table>
<tr><th></th><th>Name</th><th>SKU</th>@if (Auth::user()->is_admin)<th>Supplier</th>@endif<th>Price</th><th>Description</th>@if (Auth::user()->is_admin)<th></th>@endif</tr>
@foreach($items as $item)
	<tr><td><!--  a title="delete" onclick="return confirm('Delete?');" href="{{route('delete',$item->item_id)}}"><i class="small material-icons">delete</i></a--></td><td>{{$item->item_name}}</td><td>{{$item->supplier_sku}}</td>@if (Auth::user()->is_admin)<td>{{$item->item_supplier_id}}</td>@endif<td>{{$item->item_price}}</td><td>{{$item->item_description}}</td>@if (Auth::user()->is_admin)<td><a title="editItem" href="{{route('edit_item',$item->item_id)}}"><i class="small material-icons">edit</i></a></td>@endif</tr>
@endforeach
</table>
{{$items->links('vendor.pagination.materializecss')}}


@endsection('content') 