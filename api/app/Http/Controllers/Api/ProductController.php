<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductsRequest;
use App\Http\Requests\Product\UpdateProductsRequest;
use App\Models\Product;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();
        $query = Product::query();
        $perPage = config('constants.default_per_page');
        if ($request->has('q') && strlen($request->input('q')) > 0 ) {
            $query->where('name', 'LIKE', "%" . $data['q'] . "%");
        }
        $products = $query->orderBy('created_at', 'DESC')->paginate($perPage);

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $products,
        ]);
    }

    public function store(StoreProductsRequest $request)
    {
        DB::beginTransaction();
        try {
            $product = new Product();
            $product->name = $request->input('name');
            $product->description = $request->input('description');
            $product->price = $request->input('price');
            if ($request->hasFile('image')) {
                $product->image = Storage::disk('public')->putFile('products', $request->file('image'));
            }
           
            $product->save();
            DB::commit();

            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error store products', [
                'method' => __METHOD__,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }       
    }

    public function update(UpdateProductsRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            $product->name = $request->input('name');
            $product->description = $request->input('description');
            $product->price = $request->input('price');
            if ($request->hasFile('image')) {
                $product->image = Storage::disk('public')->putFile('products', $request->file('image'));
            }
            $product->save();
            DB::commit(); 

            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error update product', [
                'method' => __METHOD__,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);
    }
}
