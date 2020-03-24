<?php

namespace App\Http\Controllers\Backend;

use DB;
use App\Models\Item;
use App\Models\Company;
use App\Models\Category;
use App\Models\StockIn;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class searchAndViewItemsController extends Controller
{
    public function index(Request $request)
    {
        $companies = Company::all();
        $categories = Category::all();

        return view('dashboard.seach_and_view_items.index',compact('companies','categories')); 
    }
    
    public function show(Request $request)
    {
        // $validator= Validator::make($request->all(),[
        //     'category_id' => 'required', 
        //     'company_id'  => 'required',            
        // ]);
        // if($validator->fails())
        // {
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }
       
        $companies = Company::all();
        $categories = Category::all();

        if ($request->company_id) 
        {
            $views = DB::table('stock_ins')
                ->join('companies','companies.id','=','stock_ins.company_id')
                ->join('items','items.id','=','stock_ins.item_id')
                ->select('items.itemName','companies.companyName','stock_ins.stock_in','items.reorderLevel')
                ->where([
                    'stock_ins.company_id'=>$request->company_id,
                ])->get();
                
            return view('dashboard.seach_and_view_items.index',compact('companies','categories','views'));
        }
        elseif ($request->category_id) 
        {
            $views = DB::table('stock_ins')
                ->join('companies','companies.id','=','stock_ins.company_id')
                ->join('items','items.id','=','stock_ins.item_id')
                ->select('items.itemName','companies.companyName','stock_ins.stock_in','items.reorderLevel')
                ->where([
                    'stock_ins.category_id'=>$request->category_id,
                ])->get();
                
            return view('dashboard.seach_and_view_items.index',compact('companies','categories','views'));
        }
        elseif (isset($request->company_id) && isset($request->category_id)) 
        {
            $views = DB::table('stock_ins')
                    ->join('companies','companies.id','=','stock_ins.company_id')
                    ->join('items','items.id','=','stock_ins.item_id')
                    ->select('items.itemName','companies.companyName','stock_ins.stock_in','items.reorderLevel')
                    ->where([
                        'stock_ins.company_id'=>$request->company_id,
                        'stock_ins.category_id'=>$request->category_id,
                    ])->get();  

            return view('dashboard.seach_and_view_items.index',compact('companies','categories','views'));
        }
    }

}
