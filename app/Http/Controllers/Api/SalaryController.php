<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SalaryController extends Controller
{
    function paid(Request $request, $id)
    {
    	$request->validate([
    		'salary_month' => 'required'
    	]);

    	$month = $request->salary_month;
    	$check = DB::table('salaries')->where('employee_id', $id)->where('salary_month', $month)->first();
    	if (!$check) {
    		return response()->json('Already paid for this month!');
    	}

    	$data = [];
    	$data['employee_id'] = $id;
    	$data['amount'] = $request->salary;
    	$data['salary_date'] = date('d/m/Y');
    	$data['salary_month'] = $month;
    	$data['salary_year'] = date('Y');
    	
    	DB::table('salaries')->insert($data);
    }
}
