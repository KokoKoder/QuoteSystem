<?php
namespace App\Http\Controllers;

use App\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = product::latest()->paginate(10);
  
        return view('products.index',compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {	
		$products = \App\Product::all();
        return view('products.create',compact('products'));
    }
  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
			'vendor' => 'required',
        ]);
		if ($request->lang == ""){
			$vendor = $request->vendor;
			if ($vendor == 14 || $vendor == 15){
				$lang = 1;
			}else{$lang = 2;}
		}else{
			$lang = $request->lang;
		}
		if($request->URL == ""){
			$URL = preg_replace('/\s+/', '_', $request->product_name);
		}else{
			$URL = $request->URL;
		}
        product::create(
			['product_name'=>$request->name,
			'product_URL'=>$request->URL,
			'product_description'=>$request->desription,
			'product_pictures'=>$request->pictures,
			'product_metaDesc'=>$request->metaDesc,
			'product_metaTitle'=>$request->metaTitle,
			'lang'=>$lang,
			'vendor'=>$request->vendor,
			'product_categories'=>$request->product_categories
			]);
		
        return redirect()->route('products.index')
                        ->with('success','product created successfully.');
    }
   
    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(product $product)
    {
        return view('products.show',compact('product'));
    }
   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(product $product)
    {
		$products = \App\Product::all();
        return view('products.edit',compact('product','products'));
    }
  
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, product $product)
    {
        $request->validate([
            'name' => 'required',
			'URL' => 'required',
			'lang' => 'required',
			'vendor' => 'required',
        ]);
  
        $product->update($request->all());
  
        return redirect()->route('producs.index')
                        ->with('success','product updated successfully');
    }
  
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(product $product)
    {
        $product->delete();
  
        return redirect()->route('products.index')
                        ->with('success','product deleted successfully');
    }
}