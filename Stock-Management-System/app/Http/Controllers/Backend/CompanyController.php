<?php

namespace App\Http\Controllers\Backend;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::all();
        return view('dashboard.companySetup.index',compact('companies'));
    }

    public function store(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'companyName'  => 'required|min:3|max:15|unique:companies|regex:/^[a-zA-Z]+[a-zA-Z0-9]*$/', //must te start with Letter, not number first, don't use space & _
        ]);

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $company = new Company();
        $company->companyName = $request->companyName;

        $company->save();

        session()->flash('type','success');
        session()->flash('message','Company Added Successfully');
        
        return redirect()->back();
    }

    public function edit($id)
    {
        $company = Company::find($id);
        return view('dashboard.companySetup.edit',compact('company'));
    }

    public function update(Request $request, $id)
    {
        $validator= Validator::make($request->all(),[
            'companyName'  => 'required|min:3|max:15|unique:companies|regex:/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/', //must te start with Letter
        ]);

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $company = Company::find($id);
        $company->companyName = $request->companyName;

        $company->update();

        session()->flash('type','success');
        session()->flash('message','Company Updated Successfully');
        
        return redirect()->back();
    }

    public function destroy($id)
    {
        $company = Company::find($id);
        $company->delete();

        return redirect()->back();
    }
}
