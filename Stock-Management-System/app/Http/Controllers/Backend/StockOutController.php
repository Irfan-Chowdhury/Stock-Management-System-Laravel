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


        $last_row_action_no  =  DB::table('stock_outs')
                            // ->where('status','=','SOLD')
                            ->where('status','!=',NULL)
                            ->orderBy('id','DESC')
                            ->limit(1)
                            ->first();
        if (isset($last_row_action_no)) 
        {
            $last_action_no = (int)$last_row_action_no->action_no + 1 ; //লাস্টের সাথে ১ যোগ হবে 
        }

        //Go 'else' line if you add new Item || or follow the bellow line  (update add) if want update data 

        //(update add) কোন আইটেম সিলেক্ট করার পর আবার বৃদ্ধি করতে চাইলে ডাটাবাজের আগের আইটেমে add_quantity ঐটা সিলেক্ট করবে 
        $previous_add_quantity = StockOut::select('add_quantity')
                        ->where([
                            'company_id' => $request->company_id, //Blade ফাইল থেকে রিকুয়েস্ট আসতেছে 
                            'item_id'    => $request->item_id,
                            'action_no'  => $last_action_no, // যদি ডাটাবেজে থাকে 
                            'status'     => NULL, 
                        ])->first();

        //(update add) যদি থেকে থাকে তাইলে ঐটাই আপডেট হবে 
        if (isset($previous_add_quantity)) 
        {   
            $updateAddQuantity  = StockOut::where([
                'company_id' => $request->company_id,
                'item_id'    => $request->item_id,
                'action_no'  => $last_action_no, // যদি ডাটাবেজে থাকে
                'status'     => NULL,
                ])->first();
                      
            $updateAddQuantity->add_quantity = $updateAddQuantity->add_quantity + $request->add_quantity;
            // $updateAddQuantity->update();


            //Now First check that available quantity is sufficient or not
            $stockIn = StockIn::select('stock_in')
                                ->where([
                                    'company_id' => $request->company_id,
                                    'item_id'    => $request->item_id,
                                ])->first();

            if ($updateAddQuantity->add_quantity > $stockIn->stock_in ) //Check
            {
                session()->flash('type','danger');
                session()->flash('message','Available Quantity is not sufficient');
            }                                                           //Check end
            else{
                $updateAddQuantity->update();
            }
            
            
        }
        else{ //কোন আইটেম না থাকলে তাইলে নতুনভাবে এড হবে

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
            else{
                $stockout               = new StockOut();
                $stockout->company_id   = $request->company_id; 
                $stockout->item_id      = $request->item_id; 
                $stockout->add_quantity = $request->add_quantity;
                $stockout->action_no    = $last_action_no ;
                
                $stockout->save();
            }            
        }

        // after save (add) the Items will show in a table until the sell/damage/lost button click. 
        $companies = Company::all();
        $stock_add_quantities = DB::table('stock_outs')
                    ->join('companies','companies.id','=','stock_outs.company_id')
                    ->join('items','items.id','=','stock_outs.item_id')
                    ->select('items.itemName','companies.companyName','stock_outs.add_quantity')
                    ->where([
                        'stock_outs.action_no' => $last_action_no,
                ])->get();

        return view('dashboard.stockOut.create',compact('companies','stock_add_quantities'));
    }


    // ==========================  S E L L  =========================

    
    public function sell(Request $request) // Sell এ ক্লিক করলে add_quantity ডাটাগুলা sell_quantity তে ট্রান্সফার হবে |&| (stock_ins টেবিলের stock_in) থেকে (stock_outs এর sell_quantity'র) ডাটা বিয়োগ হবে মানে [stock_in - sell_quantity]  
    {
        // //retrive last action no
        $last_row_action_no  =  DB::table('stock_outs')
                            ->select('action_no')
                            ->orderBy('id','DESC')
                            ->limit(1)
                            ->first();
        if (isset($last_row_action_no)) 
        {
            $last_action_no = (int)$last_row_action_no->action_no;
        }


        // ধরি যাদের যাদের Action_no == 1 তাদেরকেই follow করবে, similarly 1,2,3..... 
        $data = DB::table('stock_outs')
                        ->where([
                            'action_no' => $last_action_no
                        ])->get();
        // return $data;

        foreach ($data as $item) 
        {
            $stock_out  = StockOut::find($item->id) //stock_out's- id
                                        ->where([
                                            'company_id' => $item->company_id,
                                            'item_id'    => $item->item_id,
                                            'action_no'  => $last_action_no,
                                        ])
                                        ->first(); // প্রতি লুপে তাদের প্রত্যেকের Row আইডেন্টিফাই করা হবে । পরবর্তী একই আইটেম ভিন্ন কোম্পানি হতে পারে 
            $stock_out->sell_quantity = $item->add_quantity;
            $stock_out->status = "SOLD";
            $stock_out->update();
                                

            $stockIn_id  = StockIn::select('id')
                                ->where('company_id',$item->company_id)
                                ->where('item_id',$item->item_id)
                                ->first(); 
            $stockIn  = StockIn::find($stockIn_id->id);

            $stockIn->stock_in = $stockIn->stock_in - $stock_out->sell_quantity; //or follow next line line
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
        // //retrive last action no
        $last_row_action_no  =  DB::table('stock_outs')
                            ->select('action_no')
                            ->orderBy('id','DESC')
                            ->limit(1)
                            ->first();
        if (isset($last_row_action_no)) 
        {
            $last_action_no = (int)$last_row_action_no->action_no;
        }


        // ধরি যাদের যাদের Action_no == 1 তাদেরকেই follow করবে, similarly 1,2,3 ..... 
        $data = DB::table('stock_outs')
                        ->where([
                            'action_no' => $last_action_no
                        ])->get();
        // return $data;

        foreach ($data as $item) 
        {
            $stock_out  = StockOut::find($item->id) //stock_out's- id
                                        ->where([
                                            'company_id' => $item->company_id,
                                            'item_id'    => $item->item_id,
                                            'action_no'  => $last_action_no,
                                        ])
                                        ->first(); // প্রতি লুপে তাদের প্রত্যেকের Row আইডেন্টিফাই করা হবে । পরবর্তী একই আইটেম ভিন্ন কোম্পানি হতে পারে 
            $stock_out->damage_quantity = $item->add_quantity;
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
        // //retrive last action no
        $last_row_action_no  =  DB::table('stock_outs')
                            ->select('action_no')
                            ->orderBy('id','DESC')
                            ->limit(1)
                            ->first();
        if (isset($last_row_action_no)) 
        {
            $last_action_no = (int)$last_row_action_no->action_no;
        }
        

        // ধরি যাদের যাদের Action_no == 1 তাদেরকেই follow করবে, similarly 1,2,3 ..... 
        $data = DB::table('stock_outs')
                        ->where([
                            'action_no' => $last_action_no
                        ])->get();
        // return $data;

        foreach ($data as $item) 
        {
            $stock_out  = StockOut::find($item->id) //stock_out's- id
                                        ->where([
                                            'company_id' => $item->company_id,
                                            'item_id'    => $item->item_id,
                                            'action_no'  => $last_action_no,
                                        ])
                                        ->first(); // প্রতি লুপে তাদের প্রত্যেকের Row আইডেন্টিফাই করা হবে । পরবর্তী একই আইটেম ভিন্ন কোম্পানি হতে পারে 
            $stock_out->lost_quantity = $item->add_quantity;
            $stock_out->status = "LOST";
            $stock_out->update();
        }

        session()->flash('type','success');
        session()->flash('message','Items Lost Successfully');

        return redirect()->route('stockout.create');                        
    }
}
