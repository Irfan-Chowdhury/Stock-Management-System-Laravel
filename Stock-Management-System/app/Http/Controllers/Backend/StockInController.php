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


    public function companyWiseItem(Request $request)
    {
        $items = Item::where('company_id',$request->company_id)
                                    ->get();
        return view('dashboard.stockIn.company_wise_item',compact('items'));
    }

    public function showReorderLevel(Request $request)
    {
        $item = Item::where([
                    'company_id' => $request->company_id,
                    'id'         => $request->id,
                ])->first();
        
        $stockIn = StockIn::where([
                    'company_id' => $request->company_id,
                    'item_id'    => $request->id,
                ])->first();
        
        return view('dashboard.stockIn.item_wise_reorderLevel',compact('item','stockIn'));

       
        // $states = DB::table("states")->where("countries_id",$id)->pluck("name","id");
        // return json_encode($states);
    }



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

        $previous_StockIn = StockIn::select('stock_in')
                        ->where([
                            'company_id' => $request->company_id,
                            'item_id'    => $request->item_id,
                        ])->first();

        if (isset($previous_StockIn)) 
        {
            $stockIn   = StockIn::where([
                            'company_id' => $request->company_id,
                            'item_id'    => $request->item_id,
                            ])->first();

            $stockIn->stock_in   = $stockIn->stock_in + $request->stock_in; //addition with previuos
            $stockIn->update();

        }
        else {
            $stock_in             = new StockIn();
            $stock_in->company_id = $request->company_id;
            $stock_in->item_id    = $request->item_id;
            $stock_in->stock_in   = $request->stock_in;
            $stock_in->save();
        }

        session()->flash('type','success');
        session()->flash('message','Stock In Successfully');
        
        return redirect()->back();
    }
}
