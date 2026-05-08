<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('name');
        $description = $request->input('description');
        $tags = $request->input('tags');
        $product_categories_id = $request->input('product_categories_id');
        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');

        if($id){
            $product = Product::with(['category', 'galleries'])->find($id);

            if($product){
                return ResponseFormatter::success(
                    $product,
                    'Data produk berhasil diambil'
                );
            }else{
                return ResponseFormatter::error(
                    null,
                    'Data produk tidak ditemukan',
                    404
                );
            }
        }

        $product = Product::with(['category', 'galleries']);

        if($name){
            $product->where('name', 'like','%' . $name . '%');
        }
         if($description){
            $product->where('description', 'like','%' . $description . '%');
        }
         if($tags){
            $product->where('tags', 'like','%' . $tags . '%');
        }
         if($price_from){
            $product->where('price', '>=', $price_from);
        }
         if($price_to){
            $product->where('price', '<=', $price_to);
        }
        if($product_categories_id){
            $product->where('product_categories_id', $product_categories_id);
        }

        return ResponseFormatter::success(
            $product->paginate($limit),
            'Data produk berhasil diambil'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
