@extends('dashboard.layouts.maintemplate')

@section('title', 'Search & View Items')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Search & View Items</h1>
        </div>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <form action="{{route('searchAndViewItems.show')}}" method="get">
                    <div class="form-group">
                        <label for="company_id">Company</label>
                        <select id="company_id" id="companyId" name="company_id" class="form-control @error('category_id') is-invalid @enderror" required >
                            <option value="">--Select--</option>
                            @foreach ($companies as $company)
                                <option value="{{$company->id}}">{{$company->companyName}}</option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select id="category_id" id="categoryId" name="category_id" class="form-control @error('category_id') is-invalid @enderror" required >
                            <option value="">--Select--</option>
                            @foreach ($categories as $category)
                                <option value="{{$category->id}}">{{$category->categoryName}}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Search</button>
                    <br><br>
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
                        <th scope="col">Item</th>
                        <th scope="col">Company</th>
                        <th scope="col">Available Quantity</th>
                        <th scope="col">Reorder Level</th>
                      </tr>
                    </thead>
                    <tbody class="table-secondary" id="tableList">
                        @if (isset($views))
                            @foreach ($views as $key=>$view)
                                <tr class="text-center">
                                    <td scope="row">{{$key+1}}</th>
                                    <td class="text-dark">{{$view->itemName}}</td>
                                    <td class="text-dark">{{$view->companyName}}</td>
                                    <td class="text-dark">{{$view->stock_in}}</td>
                                    <td class="text-dark">{{$view->reorderLevel}}</td>
                                </tr>
                            @endforeach
                        {{-- @else
                            <tr>
                                <td><h1 class="text-danger">Sorry Data Matched !</h1></td>
                            </tr> --}}
                        @endif
                    </tbody>
                  </table>
            </div>
            <div class="col-md-3"></div>
        </div>  
    </div>

  
@endsection

