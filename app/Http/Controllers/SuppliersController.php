<?php
namespace App\Http\Controllers;

use App\Suppliers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SuppliersController extends Controller
{
	
	function deleteSupplier(Request $request)
	{
		if (Auth::user()->is_admin)
		{
			$supplier_id = $request->input('supplier_id');
			$supplier = DB::table('suppliers')->where('supplier_id', '=', $supplier_id);
			$supplier->delete();
			return redirect('suppliers_view');
		}
	}
	
}

//EOF
