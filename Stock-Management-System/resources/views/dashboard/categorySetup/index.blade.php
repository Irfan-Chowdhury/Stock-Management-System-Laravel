@extends('dashboard.layouts.maintemplate')

@section('title', 'Category Setup')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Category Setup</h1>
        </div>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <form action="{{route('category.store')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="categoryName">Category Name</label>
                        <input type="text" name="categoryName" class="form-control"  >
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Submit</button>
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

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <table class="table">
                    <thead class="thead-dark">
                      <tr class="text-center">
                        <th scope="col">SL</th>
                        <th scope="col">Category Name</th>
                        <th scope="col" colspan="2">Action</th>
                      </tr>
                    </thead>
                    <tbody class="table-secondary">
                    @foreach ($categories as $key=>$category)
                        <tr class="text-center">
                            <td scope="row">{{$key+1}}</th>
                            <td>{{$category->categoryName}}</td>
                            <td><a href="{{route('category.edit',$category->id)}}" class="btn btn-success">Edit</a></td>
                            <td>
                                <form action="{{route('category.delete',$category->id)}}" method="post" method="post" onsubmit="return confirm('Are You Sure to delete ?')">
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

