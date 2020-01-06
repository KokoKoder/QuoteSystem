<?php
namespace App\Http\Controllers;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = category::latest()->paginate(5);
  
        return view('categories.index',compact('categories'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
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
			'URL' => 'required',
			'vendor' => 'required',
        ]);
		var_dump($request->name);
		if ($request->lang==""){
			$vendor=$request->vendor;
			if ($vendor==14 || $vendor==15){
				$lang=1;
			}else{$lang=2;}
		}else{
			$lang=$request->lang;
		}
        category::create(
			['name'=>$request->name,
			'URL'=>$request->URL,
			'description'=>$request->desription,
			'pictures'=>$request->pictures,
			'metaDesc'=>$request->metaDesc,
			'metaTitle'=>$request->metaTitle,
			'lang'=>$lang,
			'vendor'=>$request->vendor,
			'parentCategory'=>$request->parentCategory
			]);
		
        return redirect()->route('categories.index')
                        ->with('success','category created successfully.');
    }
   
    /**
     * Display the specified resource.
     *
     * @param  \App\category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(category $category)
    {
        return view('categories.show',compact('category'));
    }
   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(category $category)
    {
        return view('categories.edit',compact('category'));
    }
  
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, category $category)
    {
        $request->validate([
            'name' => 'required',
			'URL' => 'required',
			'lang' => 'required',
			'vendor' => 'required',
        ]);
  
        $category->update($request->all());
  
        return redirect()->route('categories.index')
                        ->with('success','category updated successfully');
    }
  
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(category $category)
    {
        $category->delete();
  
        return redirect()->route('categories.index')
                        ->with('success','category deleted successfully');
    }
}