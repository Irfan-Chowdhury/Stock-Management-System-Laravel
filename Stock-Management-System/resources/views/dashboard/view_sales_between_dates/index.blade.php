@extends('dashboard.layouts.maintemplate')

@section('title', 'View Sales Between Two Dates')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">View Sales Between Two Dates</h1>
        </div>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <form action="{{route('view-sales-between-date.show')}}" method="get">
                    <div class="form-group">
                        <label for="fromDate">From Date</label>
                        <input type="date" name="from_date" class="form-control">
                        @error('from_date')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="toDate">To Date</label>
                        <input type="date" name="to_date" class="form-control">
                        @error('to_date')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Search</button>
                    <br><br>
                </form>
                @if (isset($error))
                    <h4 class="text-danger">{{$error}}</h4>
                @endif
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>

    

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <table class="table">
                    <thead class="thead-dark text-center">
                      <tr>
                        <th>SL</th>
                        <th>Item Name</th>
                        <th>Company</th>
                        <th>Quantity</th>
                      </tr>
                    </thead>

                    <tbody class="table-secondary">
                        @if (isset($data))
                            @foreach ($data as $key=>$value)
                                <tr class="text-center">
                                    <td>{{$key+1}}</td>
                                    <td>{{$value->item->itemName}}</td>
                                    <td>{{$value->company->companyName}}</td>
                                    <td>{{$value->quantity}}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>

  
@endsection

