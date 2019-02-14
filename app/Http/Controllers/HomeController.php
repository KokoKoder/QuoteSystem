<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
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
	public function print_confirmation(){
	    return view('print_confirmation');
	}
}
