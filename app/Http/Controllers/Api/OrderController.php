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
    				->select('customers.name', 'orders.*')
    				->where('orders.order_date', date('d/m/Y'))
    				->orderBy('orders.id', 'desc')
    				->get();
    	return response()->json($orders);
    }
}
