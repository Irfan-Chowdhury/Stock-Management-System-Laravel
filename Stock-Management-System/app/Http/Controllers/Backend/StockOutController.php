<?php

namespace App\Http\Controllers\Backend;

use DB;
use App\Models\Item;
use App\Models\Company;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class StockOutController extends Controller
{
    public function create()
    {
        $companies = Company::all();

        return view('dashboard.stockOut.create',compact('companies'));
    }

    // ---- Cascading & Reorder_AvlQuantity Start ----- 
    public function companyWiseItem(Request $request)
    {
        $items = Item::where('company_id',$request->company_id)
                                    ->get();
        return view('dashboard.stockOut.company_wise_item',compact('items'));
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
        
        return view('dashboard.stockOut.item_wise_reorderLevel',compact('item','stockIn'));
    }
    // ---- Cascading & Reorder_AvlQuantity End ----- 


    // ==========================  A D D  =========================

    // Add into list
    public function add(Request $request)  // Add এ ক্লিক করলে ডাটা সব Database এর add_quantity টেবিলে স্টোর হবে, নির্দিষ্ট action_no সহ । এরপর action_no এর নির্দিষ্ট নাম্বারটা অনুসারে বাকিগুলার ডাটা টেবিলে দেখাবে .   
    {
        //Validation start
        $validator= Validator::make($request->all(),[
            'company_id'  => 'required|exists:companies,id',   //columnName =>'exists:tableName,columnName (of tableName)'         
            'item_id'     => 'required|exists:items,id',   //columnName =>'exists:tableName,columnName (of tableName)'         
            'add_quantity'=> 'required|numeric',            
        ]);

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        //Validation end


        //First check that available quantity is sufficient or not
        $stockIn = StockIn::select('stock_in')
                            ->where([
                                'company_id' => $request->company_id,
                                'item_id'    => $request->item_id,
                            ])->first();

        if ($request->add_quantity > $stockIn->stock_in ) //Check
        {
            session()->flash('type','danger');
            session()->flash('message','Available Quantity is not sufficient');

            return redirect()->back();
        }                                               //Check end
        else
        {
            $data_check = StockOut::where([ // যদি ডাটাবেজে থাকে 
                                'company_id' => $request->company_id, 
                                'item_id'    => $request->item_id,
                                'action_no'  => 1, 
                                'status'     => NULL, 
                            ])
                            ->first();

            if ($data_check) 
            {
                $stockout  = StockOut::Find($data_check->id);

                $data_check->add_quantity = $data_check->add_quantity + $request->add_quantity;
                $data_check->update();
            }
            else 
            {
                $stockout               = new StockOut();
                $stockout->company_id   = $request->company_id; 
                $stockout->item_id      = $request->item_id; 
                $stockout->add_quantity = $request->add_quantity;
                $stockout->action_no    = 1 ;

                $stockout->save();
            }
        }

        $companies = Company::all();
        $stock_add_quantities = DB::table('stock_outs')
                    ->join('companies','companies.id','=','stock_outs.company_id')
                    ->join('items','items.id','=','stock_outs.item_id')
                    ->select('items.itemName','companies.companyName','stock_outs.add_quantity')
                    ->where([
                        'stock_outs.action_no' => 1,
                ])->get();

        return view('dashboard.stockOut.create',compact('companies','stock_add_quantities'));
    }


    // ==========================  S E L L  =========================

    
    public function sell(Request $request) // Sell এ ক্লিক করলে add_quantity ডাটাগুলা sell_quantity তে ট্রান্সফার হবে |&| (stock_ins টেবিলের stock_in) থেকে (stock_outs এর sell_quantity'র) ডাটা বিয়োগ হবে মানে [stock_in - sell_quantity]  
    {

        $data = DB::table('stock_outs')
                        ->where('action_no',1)
                        ->get();

        foreach ($data as $item) // প্রতি লুপে তাদের প্রত্যেকের Row আইডেন্টিফাই করা হবে । পরবর্তী একই আইটেম ভিন্ন কোম্পানি হতে পারে 
        {
            $stock_out  = StockOut::find($item->id); //stock_out's- id      
            $stock_out->sell_quantity = $item->add_quantity;  //actually the sell_quantity does not need .
            $stock_out->action_no = 0;
            $stock_out->status = "SOLD";
            $stock_out->update();
                                

            $stockIn_id  = StockIn::select('id') //stock_in এর স্পেসিফিক রো এর id সিলেক্ট করা হচ্ছে । 
                                ->where('company_id',$item->company_id)
                                ->where('item_id',$item->item_id)
                                ->first(); 

            $stockIn  = StockIn::find($stockIn_id->id); // স্পেসিফিক রো এর আইডিটা ধরলো । 

            $stockIn->stock_in = $stockIn->stock_in - $stock_out->sell_quantity; //stock_in থেকে ডাটা ডিলিট হয়ে গেলো ।  //or follow next line line
            // $stockIn->stock_in = $stockIn->stock_in - $item->add_quantity; 
            $stockIn->update();

        }

        session()->flash('type','success');
        session()->flash('message','Items Sold Successfully');

        return redirect()->route('stockout.create');                        
    }


    // ==========================  D A M A G E  =========================

    //I did not setup of reduce quantity from "Stock In" after clicking the "Damage"
    public function damage(Request $request) // damage এ ক্লিক করলে add_quantity ডাটাগুলা damage_quantity তে ট্রান্সফার হবে 
    {
        $data = DB::table('stock_outs')
                    ->where('action_no', 1)
                    ->get();


        foreach ($data as $item) // প্রতি লুপে তাদের প্রত্যেকের Row আইডেন্টিফাই করা হবে । পরবর্তী একই আইটেম ভিন্ন কোম্পানি হতে পারে 
        {
            $stock_out  = StockOut::find($item->id); //stock_out's- id 
            $stock_out->damage_quantity = $item->add_quantity;
            $stock_out->action_no = 0;
            $stock_out->status = "DAMAGE";
            $stock_out->update();
        }

        session()->flash('type','success');
        session()->flash('message','Items Damaged Successfully');

        return redirect()->route('stockout.create');                        
    }


    // ==========================  L O S T  =========================

    //I did not setup of reduce quantity from "Stock In" after clicking the "Lost"
    public function lost(Request $request) // lost এ ক্লিক করলে add_quantity ডাটাগুলা lost_quantity তে ট্রান্সফার হবে 
    {
        $data = DB::table('stock_outs')
                    ->where('action_no', 1)
                    ->get();
        

        foreach ($data as $item)  // প্রতি লুপে তাদের প্রত্যেকের Row আইডেন্টিফাই করা হবে । পরবর্তী একই আইটেম ভিন্ন কোম্পানি হতে পারে 
        {
            $stock_out  = StockOut::find($item->id); //stock_out's- id
                                        
            $stock_out->lost_quantity = $item->add_quantity;
            $stock_out->action_no = 0;
            $stock_out->status = "LOST";
            $stock_out->update();
        }

        session()->flash('type','success');
        session()->flash('message','Items Lost Successfully');

        return redirect()->route('stockout.create');                        
    }
}
