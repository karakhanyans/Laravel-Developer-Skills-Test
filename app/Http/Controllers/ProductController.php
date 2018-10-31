<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('index');
    }


    /**
     * @return ProductResource
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getProducts()
    {
        $productFileExists = Storage::disk('public')->exists('products.json');
        $allProducts = $productFileExists ? json_decode(Storage::disk('public')->get('products.json')) : [];

        return new ProductResource(collect($allProducts));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function addProduct(Request $request)
    {
        $data = $request->except('_token');

        $productFileExists = Storage::disk('public')->exists('products.json');
        $allProducts = $productFileExists ? json_decode(Storage::disk('public')->get('products.json')) : [];
        $data['created_at'] = date('Y-m-d H:i:s');

        array_push($allProducts, $data);

        Storage::disk('public')->put('products.json', json_encode($allProducts));

        return response()->json(['success'=>true]);
    }


    /**
     * @param Request $request
     * @param $productIndex
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function editProduct(Request $request, $productIndex)
    {
        $data = $request->except('_token');
        $products = json_decode(Storage::disk('public')->get('products.json'));
        $products[$productIndex] = $data;
        Storage::disk('public')->put('products.json', json_encode($products));

        return response()->json(['success'=>true]);
    }


    /**
     * @param $productIndex
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function deleteProduct($productIndex) {
        $products = json_decode(Storage::disk('public')->get('products.json'));
        $products = array_except($products, $productIndex);
        Storage::disk('public')->put('products.json', json_encode($products));

        return response()->json(['success'=>true]);
    }
}
