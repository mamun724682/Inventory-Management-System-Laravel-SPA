<?php

namespace App\Http\Controllers\Api;

use App\Model\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
	public function addToCart($id)
	{
		$exist_product = DB::table('pos')->where('product_id', $id)->first();

		if ($exist_product) {

			$data = [];
			$data['product_quantity'] = $exist_product->product_quantity += 1;
			$data['sub_total'] = $exist_product->sub_total += $exist_product->sub_total;
			DB::table('pos')->where('product_id', $id)->update($data);

			$product = Product::find($id);
			$product->product_quantity -= 1;
			$product->save();
		} else {
			$product = DB::table('products')->where('id', $id)->first();

			$data = [];
			$data['product_id'] = $id;
			$data['product_name'] = $product->product_name;
			$data['product_quantity'] = 1;
			$data['product_price'] = $product->selling_price;;
			$data['sub_total'] = $product->selling_price;

			DB::table('pos')->insert($data);
		}
	}

	public function cartProducts()
	{
		$products = DB::table('pos')->get();
		return response()->json($products);
	}

	public function cartDelete($id)
	{
		DB::table('pos')->where('id', $id)->delete();
		return response('Done');
	}
}
