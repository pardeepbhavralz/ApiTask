<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::get();

        if ($products->count() > 0) {
            return ProductResource::collection($products);
        } else {
            return response()->json(['messgae' => 'No record available'], 200);
        };
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                '' => $validator->errors(),
                'message' => 'All Fields are mandetory',

            ], 422);
        }
      

        $productsAdded = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,

        ]);
        // $token = $productsAdded->createToken('my-app-token')->planeText;

        return response()->json([
            'message' => 'Product data has been added',
            'data' => new ProductResource($productsAdded)
        ], 200);
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function update(Request $request, Product $product)
    {
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return response()->json([
            'message' => 'Product updated successfully.',
            'data' => $product
        ], 200);
    }


    //     public function update(Request $request ,  Product $product){
    //         $validator = Validator::make($request->all(), [

    //             'name'=> 'required|string|max:255',
    //     'description'=> 'required|string',
    //     'price'=> 'required|integer',
    // ]);

    // if($validator->fails()){
    //     return response()->json([''=> $validator->errors(),
    // 'message' => 'All Fields are mandetory'
    // ],422);
    // }

    // $product->update([
    // 'name'=> $request->name,
    // 'description'=> $request->description,
    // 'price'=> $request->price,

    // ]);

    // return response()->json(['message'=> 'Product Updated successfully',
    // 'data' => new ProductResource($product)
    // ],200);
    //     }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product Delete successfully'
        ], 200);
    }
}
