<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit');
        $show_products = $request->input('show_products');
        
        if($id){
            $category = ProductCategory::with('products')->find($id);

            if($category){
                return ResponseFormatter::success(
                    $category,
                    'Data kategori produk berhasil diambil'
                );
            }else{
                return ResponseFormatter::error(
                    null,
                    'Data kategori produk tidak ditemukan',
                    404
                );
            }
        }

        $category = ProductCategory::query();


        if($name){
            $category->where('name', 'like', '%'.$name.'%');
        }
        if($show_products){
            $category->with('products');
        }

        
        return ResponseFormatter::success(
            $category->paginate($limit),
            'Data list kategori produk berhasil diambil'
        );
    }

}
