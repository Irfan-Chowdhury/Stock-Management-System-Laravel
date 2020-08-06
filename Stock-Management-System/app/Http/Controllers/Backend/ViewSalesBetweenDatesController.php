<?php

namespace App\Http\Controllers\Backend;

use DB;
use App\Models\StockOut;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class ViewSalesBetweenDatesController extends Controller
{
    public function index()
    {
        return view('dashboard.view_sales_between_dates.index'); 
    }


    public function show(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'from_date'  => 'required',            
            'to_date'    => 'required',               
        ]);

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $from = date($request->from_date);
        $to   = date($request->to_date);

        if ($from > $to) {
            $error = "From-date is greater than To-date";
            return view('dashboard.view_sales_between_dates.index',compact('error')); 
        }

        $data = StockOut::select('item_id','company_id', DB::raw('SUM(sell_quantity) as quantity'))    
                ->where('status','SOLD')
                ->whereBetween('created_at', [$from." 00:00:00", $to." 23:59:59"])
                ->groupBy('item_id','company_id')
                ->get();

        return view('dashboard.view_sales_between_dates.index',compact('data')); 
    }
}






