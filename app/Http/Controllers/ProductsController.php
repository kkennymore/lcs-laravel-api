<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Orders;
use App\Models\Products;
use App\Models\ProductsImages;

class ProductsController extends Controller
{
    //

    public function getProducts(Request $request, $products = ''){
        if(!empty($products) && is_numeric($products)){
            $productData = Products::where('id', $products)->first();
            if(!empty($productData)){
                 /**find the email in the user table */
              $productData->images = Products::find($productData->id)->getProductImages;
              return  $productData;
            }
        }
        $productData = Products::all();
        $productResponse = [];
        if(!empty($productData)){
            foreach($productData as $product){
              $images = ProductsImages::where('product_id', $product->id)->first();
              if($images['product_id'] == $product['id']){
                $imagesData = ProductsImages::where('product_id', $product->id)->get();
                $productResponse[] = [
                "id" =>  $product->id,
                "user_id" => $product->user_id,
                "image_url" => $product->image_url,
                "images" => $imagesData,
                "product_name"=> $product->product_name,
                "product_price" => $product->product_price,
                "product_category" => $product->product_category,
                "discount" => $product->discount,
                "instock" => $product->instock,
                "created_at"=> $product->created_at,
                "updated_at" => $product->updated_at
                ];
              }
            }

        }
        return [...$productResponse];
    }
}
