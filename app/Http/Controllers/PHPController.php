<?php

namespace App\Http\Controllers;
use PDF;

class PHPController extends Controller
{
    function print_invoice() {
        $data = [
            'foo' => 'bar'
        ];
        $filename="../mpdf_invoice.pdf";
        $pdf = PDF::loadView('print_invoice', $data);
        $pdf->save($filename);
        return $pdf->stream('document.pdf');
    }
}