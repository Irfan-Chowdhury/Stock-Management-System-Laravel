@extends('dashboard.layouts.maintemplate')

@section('title', 'Company Setup')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Company Setup</h1>
        </div>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <form action="{{route('company.store')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="companyName">Company Name</label>
                        <input type="text" name="companyName" class="form-control"  >
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <br><br>
                    @error('companyName')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror 

                    {{-- --------- Check in Flash Message -------- --}}
                        @include('dashboard.flashMessage.message')
                    {{-- ---------------- X -------------------- --}}
                </form>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <table class="table">
                    <thead class="thead-dark">
                      <tr class="text-center">
                        <th scope="col">SL</th>
                        <th scope="col">Company Name</th>
                        <th scope="col" colspan="2">Action</th>
                      </tr>
                    </thead>
                    <tbody class="table-secondary">
                    @foreach ($companies as $key=>$company)
                        <tr class="text-center">
                            <td scope="row">{{$key+1}}</th>
                            <td class="text-dark">{{$company->companyName}}</td>
                            <td><a href="{{route('company.edit',$company->id)}}" class="btn btn-success">Edit</a></td>
                            <td>
                                <form action="{{route('company.delete',$company->id)}}" method="post" method="post" onsubmit="return confirm('Are You Sure to delete ?')">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                  </table>
            </div>
            <div class="col-md-3"></div>
        </div>  
    </div>
@endsection

