<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CatalogController extends Controller
{
    

    /**
     * @api
     * 
     * Return list of products
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $products = Product::orderBy('id','desc')->limit(10)->get();
        
        $_products = [];
        foreach($products as $product){
            list($image_url) = explode(';', $product->image_url);
            $_products[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image_url' => url($image_url),
            ];
        }

        $response = [
            'success' => true,
            'message' => 'Product list retrieved',
            'content' => $_products
        ];

        return response()->json($response, 200);
    }
}
