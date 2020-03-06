@extends('dashboard.layouts.maintemplate')

@section('title', 'Item Setup')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Item Setup</h1>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <form action="{{route('item.store')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                            <option selected>--Select--</option>
                            @foreach ($categories as $category)
                                <option value="{{$category->id}}">{{$category->categoryName}}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        
                    </div>

                    <div class="form-group">
                        <label for="company_id">Company</label>
                        <select id="company_id" name="company_id" class="form-control @error('category_id') is-invalid @enderror">
                            <option selected>--Select--</option>
                            @foreach ($companies as $company)
                                <option value="{{$company->id}}">{{$company->companyName}}</option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="itemName">Item Name</label>
                        <input type="text" class="form-control @error('itemName') is-invalid @enderror" name="itemName" placeholder="Type Item Name">
                    </div>
                    @error('itemName')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="form-group">
                        <label for="reorderLevel">Reorder Level</label>
                        <input type="number" class="form-control" name="reorderLevel" value='0'>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>                     
                    <br><br>
                    {{-- --------- Check in Flash Message -------- --}}
                        @include('dashboard.flashMessage.message')
                    {{-- ---------------- X -------------------- --}}
                </form>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
@endsection