<?php

namespace App\Http\Controllers\Backend;

use DB;
use App\Models\Item;
use App\Models\Company;
use App\Models\StockIn;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class StockInController extends Controller
{
    public function create()
    {
        
        $companies = Company::all();

        return view('dashboard.stockIn.create',compact('companies'));
        // return view('dashboard.stockIn.temporary',compact('companies'));
    }

    // ========== Cascading & Reorder_AvlQuantity Start =========


    public function companyWiseItem(Request $request)
    {
        $items = Item::where('company_id',$request->company_id)
                    ->get();
        return view('dashboard.stockIn.company_wise_item',compact('items'));
    }

    public function showReorderLevel_avlQuantity(Request $request)
    {
        $item = Item::where([  //for reorderLevel
                    'company_id' => $request->company_id,
                    'id'         => $request->id, //itemId
                ])->first();
        


        // $stockIn = StockIn::where([  //for avaible quantity (stock_in)
        //             'company_id' => $request->company_id,
        //             'item_id'    => $request->id, //itemId
        //         ])->first();  
        
                    //অথবা নিচেরটা

        $stockIn = StockIn::where([  //for avaible quantity (stock_in)
                    'company_id' => $request->company_id,
                    'item_id'    => $request->id, //itemId
                ])->value('stock_in');  //<--- difference here

        return view('dashboard.stockIn.item_wise_reorderLevel_and_avlQuantity',compact('item','stockIn'));
    }

    // =========== Cascading & Reorder_AvlQuantity End =======


    public function store(Request $request)
    {    
        $validator= Validator::make($request->all(),[
            'company_id' => 'required|exists:companies,id',   //columnName =>'exists:tableName,columnName (of tableName)'         
            'item_id'    => 'required|exists:items,id',   //columnName =>'exists:tableName,columnName (of tableName)'         
            'stock_in'   => 'required|numeric',            
        ]);

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        //আগের আইটেমে এভেইলেবল কোয়ান্টিটি ঐটা সিলেক্ট করবে 
        $previous_StockIn = StockIn::select('stock_in')
                        ->where([
                            'company_id' => $request->company_id,
                            'item_id'    => $request->item_id,
                        ])->first();

        //যদি থেকে থাকে তাইলে নতুনভাবে আপডেট হবে 
        if (isset($previous_StockIn)) 
        {
            $stockIn   = StockIn::where([
                            'company_id' => $request->company_id,
                            'item_id'    => $request->item_id,
                            ])->first();

            $stockIn->stock_in   = $stockIn->stock_in + $request->stock_in; //addition with previuos
            $stockIn->update();

        } 
        else {  //কোন আইটেম না থাকলে তাইলে নতুনভাবে এড হবে
            
            $categoryId = DB::table('items')
                    ->select('items.category_id')
                    ->where([
                        'company_id' => $request->company_id,
                        'id'         => $request->item_id,
                    ])->first();

            // return $categoryId->category_id;
            
            $stock_in              = new StockIn();
            $stock_in->company_id  = $request->company_id;
            $stock_in->category_id = $categoryId->category_id;
            $stock_in->item_id     = $request->item_id;
            $stock_in->stock_in    = $request->stock_in;
            $stock_in->save();
        }

        session()->flash('type','success');
        session()->flash('message','Stock In Successfully');
        
        return redirect()->back();
    }
}
