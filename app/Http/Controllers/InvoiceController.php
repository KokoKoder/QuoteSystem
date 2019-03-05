<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Item;
use PDF;
use Auth;

class InvoiceController extends Controller {
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getDownload($path,$filename)
    {
        $path = '../app/files/'.$path.$filename;
        $headers = [
            'Content-Type' => 'application/pdf',
        ];
        
        return response()->download( $path, $filename, $headers);
    }
    
}