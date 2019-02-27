<?php

namespace App\Http\Controllers;
use PDF;

class PHPController extends Controller
{
    function print_invoice() {
        $invoice_nb=$_GET['order_nb'];
        $data = [
            'foo' => 'bar'
        ];
        $filename="../".$invoice_nb.".pdf";
        $pdf = PDF::loadView('generate_invoice', $data);
        $pdf->save($filename);
        return $pdf->stream('document.pdf');
    }
    function generate_invoice(){
        return view('generate_invoice');
    }
}