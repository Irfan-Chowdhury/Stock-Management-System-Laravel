@extends('dashboard.layouts.maintemplate')

@section('title', 'Company Setup')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Company Edit</h1>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <form action="{{route('company.update',$company->id)}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="companyName">Company Name</label>
                        <input type="text" name="companyName" class="form-control" value="{{$company->companyName}}">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update</button>
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

@endsection