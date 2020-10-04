<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PosController extends Controller
{
	public function categoryProducts($id)
	{
		$categoryProducts = DB::table('products')->where('category_id', $id)->get();

		return response()->json($categoryProducts);
	}

	public function order(Request $request)
	{
		$request->validate([
			'customer_id' => 'required',
			'payBy' => 'required'
		]);

		$data = [];
		$data['customer_id'] = $request->customer_id;
		$data['qty'] = $request->qty;
		$data['sub_total'] = $request->sub_total;
		$data['vat'] = $request->vat;
		$data['total'] = $request->total;
		$data['pay'] = $request->pay;
		$data['due'] = $request->due;
		$data['payBy'] = $request->payBy;
		$data['order_date'] = date('d/m/Y');
		$data['order_month'] = date('F');
		$data['order_year'] = date('Y');
		$order_id = DB::table('orders')->insertGetId($data);

		$cartContents = DB::table('pos')->get();

		$cartData = [];
		foreach ($cartContents as $content) {
			$cartData['order_id'] = $order_id;
			$cartData['product_id'] = $content->product_id;
			$cartData['product_quantity'] = $content->product_quantity;
			$cartData['product_price'] = $content->product_price;
			$cartData['sub_total'] = $content->sub_total;
			DB::table('order_details')->insert($cartData);

			DB::table('products')
					->where('id', $content->product_id)
					->update(['product_quantity' => DB::raw('product_quantity - '.$content->product_quantity)]);
		}

		DB::table('pos')->delete();

		return response()->json('Done');
	}
}
