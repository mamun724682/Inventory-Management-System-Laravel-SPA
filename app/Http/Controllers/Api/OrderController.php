<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function todayOrder()
    {
    	$orders = DB::table('orders')
    				->join('customers', 'orders.customer_id', 'customers.id')
    				->where('orders.order_date', date('d/m/Y'))
    				->select('customers.name', 'orders.*')
    				->orderBy('orders.id', 'desc')
    				->get();
    	return response()->json($orders);
    }

    public function orders($id)
    {
    	$orders = DB::table('orders')
    				->join('customers', 'orders.customer_id', 'customers.id')
    				->where('orders.id', $id)
    				->select('customers.name', 'customers.phone', 'customers.address', 'orders.*')
    				->first();

    	return response()->json($orders);
    }

    public function orderDetails($id)
    {
    	$details = DB::table('order_details')
    				->join('products', 'order_details.product_id', 'products.id')
    				->where('order_details.order_id', $id)
    				->select('products.product_name', 'products.product_code', 'products.image', 'order_details.*')
    				->get();
    	return response()->json($details);
    }
}
