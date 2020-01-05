<?php

namespace App\Http\Controllers\Backend;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    
    public function index()
    {
        $categories = Category::all();
        return view('dashboard.categorySetup.index',compact('categories'));
    }


    public function store(Request $request)
    {
        $validator= Validator::make($request->all(),[
            // 'categoryName'  => 'required|min:3|max:15|unique:categories|regex:/^[a-zA-Z]+$/u',
            // 'categoryName'  => 'required|min:3|max:15|unique:categories|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', //must te start with Capital Word
            // 'categoryName'  => 'required|min:3|max:15|unique:categories|regex:/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/', //must te start with Capital Word
            'categoryName'  => 'required|min:3|max:15|unique:categories|regex:/^[a-zA-Z]+[a-zA-Z0-9]*$/', //must te start with Letter, not number first, don't use space & _
        ]);

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $category = new Category();
        $category->categoryName = $request->categoryName;

        $category->save();

        session()->flash('type','success');
        session()->flash('message','Category Added Successfully');
        
        return redirect()->back();
    }

    

    
    public function edit($id)
    {
        $category = Category::find($id);
        return view('dashboard.categorySetup.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator= Validator::make($request->all(),[
            'categoryName'  => 'required|min:3|max:15|unique:categories|regex:/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/', //must te start with Letter
        ]);

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $category = Category::find($id);
        $category->categoryName = $request->categoryName;

        $category->update();

        session()->flash('type','success');
        session()->flash('message','Category Updated Successfully');
        
        return redirect()->back();
    }

    
    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();

        return redirect()->back();
    }
}
