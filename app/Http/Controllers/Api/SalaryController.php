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
    	if ($check) {
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

    public function allSalary()
    {
    	$salaries = DB::table('salaries')->select('salary_month')->groupBy('salary_month')->get();
    	return response()->json($salaries);
    }

    public function salaryByMonth($month)
    {
    	$salaries = DB::table('salaries')
    					->where('salary_month', $month)
    					->join('employees', 'salaries.employee_id', 'employees.id')
    					->select('salaries.*', 'employees.name')
    					->get();

    	return response()->json($salaries);
    }

    public function edit($id)
    {
    	$salary = DB::table('salaries')
    					->join('employees', 'salaries.employee_id', 'employees.id')
    					->select('salaries.*', 'employees.name', 'employees.email')
    					->where('salaries.id', $id)
    					->first();

    	return response()->json($salary);
    }

    public function update(Request $request, $id)
    {
    	$data = [];
    	$data['employee_id'] = $request->employee_id;
    	$data['amount'] = $request->amount;
    	$data['salary_month'] = $request->salary_month;

    	DB::table('salaries')->where('id', $id)->update($data);
    }
}
