<?php

namespace App\Http\Controllers\Backend;

use App\Models\Item;
use App\Models\Company;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        $companies  = Company::all();
        return view('dashboard.itemSetup.create',compact('categories','companies'));
    }

    public function store(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'itemName'    => 'required|min:3|max:15|unique:items|regex:/^[a-zA-Z]+[a-zA-Z0-9]*$/', //must te start with Letter, not number first, don't use space & _
            'category_id' => 'exists:categories,id', //columnName =>'exists:tableName,columnName (of tableName)'
            'company_id' => 'exists:companies,id',            
            'reorderLevel' => 'numeric',            
        ]);

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $item               = new Item();
        $item->category_id  = $request->category_id;
        $item->company_id   = $request->company_id;
        $item->itemName     = $request->itemName;
        $item->reorderLevel = $request->reorderLevel;

        $item->save();

        session()->flash('type','success');
        session()->flash('message','Item Added Successfully');
        
        return redirect()->back();
    }
}
