<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Item;
use PDF;

class HomeController extends Controller
{

    public function index()
    {
        return view('home');
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
		return view('enter_item');
	}
	public function enter_supplier(){
		return view('enter_supplier');
	}
	public function orders_suppliers_view(){
		return view('orders_suppliers_view');
	}
	public function orders_view(){
		return view('orders_view');
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
	public function suppliers_view(){
		return view('suppliers_view');
	}
	public function edit_supplier(){
		return view('edit_supplier');
	}
	public function add_custom_item_to_order(){
		return view('add_custom_item_to_order');
	}
	public function enter_order_status(){
		return view('enter_order_status');
	}
	public function vendor_details(){
		return view('vendor_details');
	}
	public function print_invoice(){
		return view('print_invoice');
	}
	public function print_creditinvoice(){
	    return view('print_creditinvoice');
	}
	public function delete_order_custom_item(){
		return view('delete_order_custom_item');
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
	public function items_view(){
	    $items = DB::table('items')->paginate(10);
	    $custom_items = DB::table('custom_items')->paginate(10);
	    return view('items_view',compact('items','custom_items'));
	}
	public function delete($item_id){
	    $item=DB::table('items')->where('item_id','=',$item_id);
	    $item->delete();
	    return redirect()->back();
	}
	public function edit_item($item_id){
	    $item=DB::table('items')->where('item_id','=',$item_id)->first();
	    return view('edit_item',compact('item'));
	}
	public function update($item_id, Request $request){
	    $item=DB::table('items')
	       ->where('item_id','=',$item_id)
	       ->update(['item_name'=>$request->input('item_name'),
	           'supplier_sku'=>$request->input('supplier_sku'),
	           'item_supplier_id'=>$request->input('supplier_id'),
	           'item_price'=>$request->input('item_price'),
	           'item_weight'=>$request->input('item_weight'),
	           'item_length'=>$request->input('item_length'),
	           'item_width'=>$request->input('item_width'),
	           'item_height'=>$request->input('item_height'),
	           'package_weight'=>$request->input('package_weight'),
	           'package_length'=>$request->input('package_length'),
	           'package_width'=>$request->input('package_width'),
	           'package_height'=>$request->input('package_height'),
	           'item_per_pack'=>$request->input('item_per_pack')]);
        return redirect()->back();
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
	    return response()->download("../app/files/invoice/".$filename);
	}
}
?>