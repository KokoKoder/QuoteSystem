<?php

namespace App\Http\Controllers;
use PDF;

class HomeController extends Controller
{
    function generate_pdf() {
        $data = [
            'foo' => 'bar'
        ];
        $pdf = PDF::loadView('print_invoice', $data);
        return $pdf->stream('document.pdf');
    }
}