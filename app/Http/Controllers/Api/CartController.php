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
			
			DB::table('pos')->where('product_id', $id)->increment('product_quantity');

			$product = DB::table('pos')->where('product_id', $id)->first();
			$sub_total = $product->product_price * $product->product_quantity;
			DB::table('pos')->where('product_id', $id)->update(['sub_total' => $sub_total]);

			// $product = Product::find($id);
			// $product->product_quantity -= 1;
			// $product->save();
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

	public function increment($id)
	{
		$quantity = DB::table('pos')->where('id', $id)->increment('product_quantity');

		$product = DB::table('pos')->where('id', $id)->first();
		$sub_total = $product->product_price * $product->product_quantity;
		DB::table('pos')->where('id', $id)->update(['sub_total' => $sub_total]);
	}

	public function decrement($id)
	{
		$quantity = DB::table('pos')->where('id', $id)->decrement('product_quantity');

		$product = DB::table('pos')->where('id', $id)->first();
		$sub_total = $product->product_price * $product->product_quantity;
		DB::table('pos')->where('id', $id)->update(['sub_total' => $sub_total]);
	}

	public function vat()
	{
		$vat = DB::table('extra')->first();
		return response()->json($vat);
	}
}
