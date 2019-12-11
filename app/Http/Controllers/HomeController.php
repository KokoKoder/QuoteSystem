<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Item;
use PDF;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('index');
    }
	public function enter_order()
    {
        return view('enter_order');
    }
	public function get_customers_live()
    {
        return view('get_customers_live');
    }
	public function verify_order_number_for_duplicate()
    {
        return view('verify_order_number_for_duplicate');
    }
	public function add_item_to_order_form(){
		return view('add_item_to_order_form');
	}
	public function add_item_to_order(){
		return view('add_item_to_order');
	}
	public function get_items_live(){
		return view('get_items_live');
	}
	public function get_customer_details(){
		return view('get_customer_details');
	}
	public function enter_item(){
	    if(Auth::user()->is_admin){
		  return view('enter_item');
	    }
	    else{
	        return view('home');
	    }
	}
	public function enter_supplier(){
	    if(Auth::user()->is_admin){
	        return view('enter_supplier');
	    }
	    else{
	        return view('home');
	    }
	}
	public function orders_suppliers_view(){
	    if(Auth::user()->is_admin){
	        return view('orders_suppliers_view');
	    }
	    else{
	        return view('home');
	    }
	}
	public function orders_view(Request $request){
	    $search_term = $request->input('search_term');
		if (substr($search_term,0,1)!='%'){
			$search_term='%'.$search_term.'%';
		}
	    if (empty($request->input('start_date'))){
	        $start_date = date('y-m-d',0);
	    }else{
	        $start_date = $request->input('start_date');
	    }
	    if(empty($request->input('end_date'))){
	        $end_date=date('y-m-d',time());
	    }else{
	        $end_date = $request->input('end_date');
	    } 
	    if(Auth::user()->is_admin){
			$orders = DB::table('orders_table')
			->join('customers', 'customers.customer_id', '=', 'orders_table.customer_id')
			->join('vendor', 'vendor.vendor_id', '=', 'orders_table.vendor_id')
			->join('orders_status', 'orders_status.order_id', '=', 'orders_table.order_id')
			->join('order_status_list', 'order_status_list.order_status_id', '=', 'orders_status.order_status_id')
	        ->where('order_date', '>', $start_date)
			->where('order_date', '<', $end_date)
			->where( function ($query) use ($search_term) {
				$query->where('customer_name', 'like', $search_term)
					  ->orWhere('customer_mail',  'like', $search_term)
					  ->orWhere('customer_phone',  'like', $search_term)
					  ->orWhere('order_number',  'like', $search_term);
				 })

			->paginate(10);
	    }
	    else{
	        $orders = DB::table('orders_table')
	        ->join('customers', 'customers.customer_id', '=', 'orders_table.customer_id')
	        ->join('vendor', 'vendor.vendor_id', '=', 'orders_table.vendor_id')
	        ->join('orders_status', 'orders_status.order_id', '=', 'orders_table.order_id')
	        ->join('order_status_list', 'order_status_list.order_status_id', '=', 'orders_status.order_status_id')
	        ->join('salesteam_orders', function($join){$join->on('salesteam_orders.order_id','=','orders_table.order_id')->where('salesteam_orders.user_id', '=', Auth::user()->id);})
	        ->where(function ($query) use ($search_term) {
				$query->where('customer_name', 'like', $search_term)
					  ->orWhere('customer_mail',  'like', $search_term)
					  ->orWhere('customer_phone',  'like', $search_term)
					  ->orWhere('order_number',  'like', $search_term);
				 })
			->where('order_date', '>', $start_date)
			->where('order_date', '<', $end_date)
	        ->paginate(10);
	    }
		return view('orders_view',compact('orders','search_term','start_date','end_date'));
	}
	public function edit_order(){
		return view('edit_order');
	}
	public function validate_order_edit(){
		return view('validate_order_edit');
	}
	public function edit_customer(){
		return view('edit_customer');
	}
	public function get_order_items(){
		return view('get_order_items');
	}
	public function edit_order_item(){
		return view('edit_order_item');
	}
	public function delete_order_item(){
		return view('delete_order_item');
	}
	public function filter_by_suppliers(){
		return view('filter_by_suppliers');
	}
	public function suppliers_view()
	{	    
        if(Auth::user()->is_admin){
            return view('suppliers_view');
    	}
    	else{
    	    return view('home');
    	}
	}
	public function edit_supplier(){
	    if(Auth::user()->is_admin){
	        return view('edit_supplier');
	    }
	    else{
	        return view('home');
	    }
	}
	public function add_custom_item_to_order(){
	    if(Auth::user()->is_admin){
	        return view('add_custom_item_to_order');
	    }
	    else{
	        return view('home');
	    }
	}
	public function enter_order_status(){
		return view('enter_order_status');
	}
	public function vendor_details(){
	    if(Auth::user()->is_admin){
	        return view('vendor_details');
	    }
	    else{
	        return view('home');
	    }
	}
	public function print_invoice(){
		return view('print_invoice');
	}
	public function print_creditinvoice(){
	    return view('print_creditinvoice');
	}
	public function delete_order_custom_item(){
	    if(Auth::user()->is_admin){
		  return view('delete_order_custom_item');
	    }
	    else{
	        return view('home');
	    }
	}
	public function get_vendor_details(){
		return view('get_vendor_details');
	}
	public function edit_vendor_details(){
		return view('edit_vendor_details');
	}
	public function edit_custom_order_item(){
		return view('edit_custom_order_item');
	}
	public function print_invoice_2(){
	    return view('print_invoice_2');
	}
	public function print_full_invoice(){
	    return view('print_full_invoice');
	}
	public function print_confirmation(){
	    return view('print_confirmation');
	}
	public function items_view(Request $request){
	    $search_term = $request->input('search_term');
		if (substr($search_term,0,1)!='%'){
			$search_term='%'.$search_term.'%';
		}
	    $items = DB::table('items')
	       ->where('item_name', 'like', $search_term)
	       ->orWhere('supplier_sku',  'like', $search_term)
	    ->paginate(10);
	    $custom_items = DB::table('custom_items')->where('custom_items.item_name', 'like', $search_term)->paginate(10);
	    return view('items_view',compact('items','custom_items','search_term'));
	}
	public function delete($item_id){
	    if(Auth::user()->is_admin){
    	    $item=DB::table('items')->where('item_id','=',$item_id);
    	    $item->delete();
    	    return redirect()->back();
	    }
	    else{
	        return view('home');
	    }
	}
	public function edit_item($item_id){
	    if(Auth::user()->is_admin){
	       $item=DB::table('items')->where('item_id','=',$item_id)->first();
	       return view('edit_item',compact('item'));
	    }
	    else{
	       return view('home');
	    }
	}
	public function update($item_id, Request $request){
	    $pattern = '[,]';
	    $replacement = '.';
		$item_price=preg_replace($pattern,$replacement,$request->input('item_price'));
	    $package_weight=preg_replace($pattern,$replacement,$request->input('package_weight'));
		$item_length=preg_replace($pattern,$replacement,$request->input('item_length'));
		if(empty($item_length)){$item_length=000;}
	    if (empty($package_weight)){$package_weight=0.00;}
	    $item_weight=preg_replace($pattern,$replacement,$request->input('item_weight'));
	    if (empty($item_weight)){$item_weight=0.00;}
		$item_width=preg_replace($pattern,$replacement,$request->input('item_width'));
		if (empty($item_width)){$item_width=0;}
		$item_height=preg_replace($pattern,$replacement,$request->input('item_height'));
		if (empty($item_height)){$item_height=0;}
		$package_length=preg_replace($pattern,$replacement,$request->input('package_length'));
		if (empty($package_length)){$package_length=0;}
		$package_width=preg_replace($pattern,$replacement,$request->input('package_width'));
		if (empty($package_width)){$package_width=0;}
		$package_height=preg_replace($pattern,$replacement,$request->input('package_height'));
		if (empty($package_height)){$package_height=0;}
	    if (empty($request->input('item_name')) or empty($request->input('supplier_sku')) or empty($request->input('item_price')) or empty($request->input('supplier_id')) )
	       {
	       $missing_fields="Make sure to provide product name, price, sku and provider";  
	       return redirect()->back()->withErrors([$missing_fields, $missing_fields]);
	       }
	    else{
	    DB::table('items')
	       ->where('item_id','=',$item_id)
	       ->update(['item_name'=>$request->input('item_name'),
	           'supplier_sku'=>$request->input('supplier_sku'),
	           'item_supplier_id'=>$request->input('supplier_id'),
	           'item_price'=>$item_price,
	           'item_weight'=>$item_weight,
	           'item_length'=>$item_length,
	           'item_width'=>$item_width,
	           'item_height'=>$item_height,
	           'package_weight'=>$package_weight,
	           'package_length'=>$package_length,
	           'package_width'=>$package_width,
	           'package_height'=>$package_height,
	           'item_per_pack'=>$request->input('item_per_pack')]);
	           return redirect()->back()->with('success', 'item succesfully edited');
	    }    
	}
	public function generate_pdf(){
	    $is_invoice_2=$_GET['invoice_2'];
	    $is_proforma=$_GET['proforma'];
	    $is_full=$_GET['pay_full'];
	    $filename=$_GET['order_number'];
	    $is_credit=$_GET['is_credit'];
	    $data = [
	        'foo' => 'bar'
	    ];
	    if ($is_invoice_2=='is_invoice_2'){
	        $pdf = PDF::loadView('print_invoice_2', $data);
	        if(file_exists('../app/files/invoice/'.$filename.'-2.pdf')){
	            $i=1;
	            while(file_exists('../app/files/invoice/'.$filename.'-2'.$i.'.pdf')){
	                $i+=1;
	            }
	            $pdf->save('../app/files/invoice/'.$filename.'-2'.$i.'.pdf');
	        }
	        else{
	            $pdf->save('../app/files/invoice/'.$filename.'-2.pdf');
	        }
	    }
	    elseif($is_proforma=='is_proforma'){
	        $pdf = PDF::loadView('print_confirmation', $data);
	        if(file_exists('../app/files/confirmation/'.$filename.'.pdf')){
	            $i=1;
	            while(file_exists('../app/files/confirmation/'.$filename.$i.'.pdf')){
	                $i+=1;
	            }
	            $pdf->save('../app/files/confirmation/'.$filename.$i.'.pdf');
	        }
	        else{
	            $pdf->save('../app/files/confirmation/'.$filename.'.pdf');
	        }
	    }
	    elseif($is_full=="full"){
	        $pdf = PDF::loadView('print_full_invoice', $data);
	        if(file_exists('../app/files/invoice/'.$filename.'.pdf')){
	            $i=1;
	            while(file_exists('../app/files/invoice/'.$filename.$i.'.pdf')){
	                $i+=1;
	            }
	            $pdf->save('../app/files/invoice/'.$filename.$i.'.pdf');
	        }
	        else{
	            $pdf->save('../app/files/invoice/'.$filename.'.pdf');
	        }
	    }
	    elseif($is_credit=="is_credit"){
	        $pdf = PDF::loadView('print_creditinvoice', $data);
	        $filename='credit_'.$filename;
	        if(file_exists('../app/files/invoice/'.$filename.'.pdf')){
	            $i=1;
	            while(file_exists('../app/files/invoice/'.$filename.$i.'.pdf')){
	                $i+=1;
	            }
	            $pdf->save('../app/files/invoice/'.$filename.$i.'.pdf');
	        }
	        else{
	            $pdf->save('../app/files/invoice/'.$filename.'.pdf');
	        }
	    }
	    else{
	        $pdf = PDF::loadView('print_invoice', $data);
	        if(file_exists('../app/files/invoice/'.$filename.'.pdf')){
	            $i=1;
	            while(file_exists('../app/files/invoice/'.$filename.$i.'.pdf')){
	                $i+=1;
	            }
	            $pdf->save('../app/files/invoice/'.$filename.$i.'.pdf');
	        }
	        else{
	            $pdf->save('../app/files/invoice/'.$filename.'.pdf');
	        }
	    }
	    return $pdf->stream('document.pdf');
	    
	}
	public function getDownload($filename){
	    return response()->download("../app/files/invoice/".$filename);
	}
	public function getConfirmation($filename){
	    return response()->download("../app/files/confirmation/".$filename);
	}
	public function most_sold(Request $request){
		$vendor_id=$request->input('vendor_id');
		if(Auth::user()->is_admin){
			if ($vendor_id!='0'){
				$items = DB::table('order_items')
				->join('orders_table','orders_table.order_id','=','order_items.order_id')
				->where('vendor_id','=',$vendor_id)
				->select(DB::raw('item_name,count(*) as item_count'))
				->groupBy('item_name')
				->orderBy('item_count','desc')
				->get();
			}
			else{
				$items = DB::table('order_items')
				->select(DB::raw('item_name,count(*) as item_count'))
				->groupBy('item_name')
				->orderBy('item_count','desc')
				->get();
				return view('most_sold',compact('items'));	
			}
			return view('most_sold',compact('items'));
	    }
	}
}
?>