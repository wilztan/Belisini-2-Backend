<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return product::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $product = Product::create([
          'name'=>$request->name,
          'price'=>$request->price,
          'stock'=>$request->stock,
          'description'=>$request->description,
          'owner_id'=>request()->user()->id,
        ]);
        // Validating Photos
        if ($request->file('image')->isValid()) {
            $img_url=$request->file('image')->move('img/item/',$product->id.".tmp");
            $product->image = $img_url;
            $product->save();
        }

        $status = 0;
        if(Product::where('id','=',$product->id)->exists())
        $status = 1;

        $data = [
            'product'   => $product,
            'status'   => $status,
        ];
        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function showProduct($name,$id)
    {
        return Product::where('id','=',$id)->first();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //search API
    public function searchName($name)
    {
        return Product::where('name','LIKE','%'.$name.'%')->get();
    }

    public function getMyProduct()
    {
      $user = request()->user()->id;
      $product = Product::where('owner_id','=',$user)->get();
      $data=[
        'product' => $product,
        'user' => $user,
      ];
      return $data;
    }
}
