@extends('dashboard.layouts.maintemplate')

@section('title', 'Category Setup')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Category Edit</h1>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <form action="{{route('category.update',$category->id)}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="categoryName">Category Name</label>
                        <input type="text" name="categoryName" class="form-control" value="{{$category->categoryName}}">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update</button>
                    <br><br>
                    @error('categoryName')
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