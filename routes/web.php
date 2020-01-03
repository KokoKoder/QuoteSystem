<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes();

Route::middleware(['auth'])->group(function () {
	Route::get('/', function () {return view('index');});
	Route::get('/index', 'HomeController@index')->name('index');
	Route::get('/home', 'HomeController@home');
	Route::any('/enter_order', 'HomeController@enter_order')->name('enter_order');
	Route::post('/enter_order_script', 'HomeController@enter_order_script')->name('enter_order_script');
	Route::any('/enter_item', 'HomeController@enter_item')->name('enter_item');
	Route::any('/enter_supplier', 'HomeController@enter_supplier')->name('enter_supplier');
	Route::any('/enter_order_status','HomeController@enter_order_status')->name('enter_order_status');
	Route::get('/get_customers_live', 'HomeController@get_customers_live');
	Route::get('/get_customer_details', 'HomeController@get_customer_details');
	Route::get('/verify_order_number_for_duplicate', 'HomeController@verify_order_number_for_duplicate');
	Route::get('/add_item_to_order_form', 'HomeController@add_item_to_order_form')->name('add_item_to_order_form');
	Route::post('/add_item_to_order', 'HomeController@add_item_to_order')->name('add_item_to_order');
	Route::get('/get_items_live', 'HomeController@get_items_live');
	Route::any('/orders_suppliers_view', 'HomeController@orders_suppliers_view')->name('orders_suppliers_view');
	Route::any('/orders_view', 'HomeController@orders_view')->name('orders_view');
	Route::any('/edit_order', 'HomeController@edit_order')->name('edit_order');
	Route::post('/validate_order_edit', 'HomeController@validate_order_edit')->name('validate_order_edit');
	Route::any('/edit_customer','HomeController@edit_customer')->name('edit_customer');
	Route::get('/get_order_items','HomeController@get_order_items')->name('get_order_items');
	Route::post('/edit_order_item','HomeController@edit_order_item')->name('edit_order_item');
	Route::get('/delete_order_item','HomeController@delete_order_item')->name('delete_order_item');
	Route::get('/delete_order_custom_item','HomeController@delete_order_custom_item')->name('delete_order_custom_item');
	Route::get('/filter_by_suppliers','HomeController@filter_by_suppliers');
	Route::get('/suppliers_view','HomeController@suppliers_view')->name('suppliers_view');
	Route::any('/edit_supplier','HomeController@edit_supplier')->name('edit_supplier');
	Route::any('/add_custom_item_to_order','HomeController@add_custom_item_to_order')->name('add_custom_item_to_order');
	Route::any('/vendor_details','HomeController@vendor_details')->name('vendor_details');
	Route::get('/get_vendor_details','HomeController@get_vendor_details')->name('get_vendor_details');
	Route::any('/edit_vendor_details','HomeController@edit_vendor_details')->name('edit_vendor_details');
	Route::get('/print_invoice','HomeController@print_invoice')->name('print_invoice');
	Route::get('/print_creditinvoice','HomeController@print_creditinvoice')->name('print_creditinvoice');
	Route::get('/print_invoice_2','HomeController@print_invoice_2')->name('print_invoice_2');
	Route::get('/print_full_invoice','HomeController@print_full_invoice')->name('print_full_invoice');
	Route::get('/generate_pdf','HomeController@generate_pdf')->name('generate_pdf');
	Route::any('/edit_custom_order_item','HomeController@edit_custom_order_item')->name('edit_custom_order_item');
	Route::any('/print_confirmation','HomeController@print_confirmation')->name('print_confirmation');
	Route::any('/items_view','HomeController@items_view')->name('items_view');
	Route::get('/delete/{item_id}','HomeController@delete')->name('delete');
	Route::get('/edit_item/{item_id}','HomeController@edit_item')->name('edit_item');
	Route::post('/update/{item_id}','HomeController@update')->name('update');
	Route::get('/getDownload/{filename}','HomeController@getDownload')->name('getDownload');
	Route::get('/getConfirmation/{filename}','HomeController@getConfirmation')->name('getConfirmation');
	Route::any('/most_sold/','HomeController@most_sold')->name('most_sold');
	Route::resource('categories','CategoryController');
});
    Route::get('/home', 'HomeController@index')->name('home');
