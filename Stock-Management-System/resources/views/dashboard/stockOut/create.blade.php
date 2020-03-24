@extends('dashboard.layouts.maintemplate')

@section('title', 'Stock Out')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Stock Out</h1>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">

                {{-- --------- Check in Flash Message -------- --}}
                @include('dashboard.flashMessage.message')
                {{-- ---------------- X -------------------- --}}

                {{-- <form action="{{route('stockout.show')}}" method="get"> --}}
                <form action="{{route('stockout.add')}}" method="post">
                   @csrf
                    <div class="form-group">
                        <label for="company_id">Company</label>
                        <select id="companyId" name="company_id" class="form-control @error('company_id') is-invalid @enderror">
                                <option value="">--Select Company--</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->companyName }}</option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                                       
                    <div class="form-group">
                        <label for="item_id">Item </label>
                        <select id="itemId" name="item_id" class="form-control @error('item_id') is-invalid @enderror">
                            <option value="">--Select Item--</option>
                        </select>
                        @error('item_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="reorderLevel_availableQuantity"> <!--reorderLevel_availableQuantity- Ajax-->
                        <div class="form-group">
                            <label for="reorderLevel">Reorder Level</label>
                            <input type="number" class="form-control" value="0" readonly>
                        </div>

                        <div class="form-group">
                            <label for="availableQuantity">Available Quantity</label>
                            <input type="number" value="0" class="form-control" readonly>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="stockOut">Stock Out</label>
                        <input type="number" name="add_quantity" id="addQuantity" class="form-control @error('add_quantity') is-invalid @enderror" placeholder="Input a Value">
                        @error('add_quantity')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                    </div>
                    <button type="submit" name="add" class="btn btn-primary">ADD</button>                                         
                </form>
            </div>
            <div class="col-md-3"></div>
        </div>

        <!--Table Start -->
        <table class="mt-3 table table-bordered">
            <thead class="thead-dark text-center">
                <tr>
                    <th scope="col">#SL</th>
                    <th scope="col">Item</th>
                    <th scope="col">Company</th>
                    <th scope="col">Quantity</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($stock_add_quantities))
                    @foreach ($stock_add_quantities as $key=> $item)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$item->itemName}}</td>
                            <td>{{$item->companyName}}</td>
                            <td>{{$item->add_quantity}}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <form action="{{route('stockout.sell')}}" method="post">
                    @csrf
                    <button type="submit" name="sell" class="m-2 btn btn-success">Sell</button>
                </form>
                <form action="{{route('stockout.damage')}}" method="post">
                    @csrf
                    <button type="submit" name="damage" class="m-2 btn btn-danger">Damage</button>
                </form>
                <form action="{{route('stockout.lost')}}" method="post">
                    @csrf
                    <button type="submit" name="lost" class="m-2 btn btn-warning">Lost</button>
                </form>
            </div>
        </div>

        <!--Table End-->
    </div>

    <script>
        $('#companyId').change(function() 
        {
            var companyId = $('#companyId').val();
            if (companyId) 
            {
                // $.get("{{route('company-wise-item')}}",{company_id:companyId}, function (data) 
                $.get("{{route('company-wise-item-stockout')}}",{company_id:companyId}, function (data) 
                {
                    console.log(data);
                    $('#itemId').empty().html(data);
                });
            }
            else{
                $('#itemId').empty().html('<option>--Select Item--</option>');
            }
        });



        $('#itemId').change(function(){
            var companyId = $('#companyId').val();
            var itemId = $(this).val();
            if (companyId && itemId) 
            {
                // $.get("{{route('show-reorder-level')}}",{company_id:companyId , id:itemId}, function (data) 
                $.get("{{route('show-reorder-level-stockout')}}",{company_id:companyId , id:itemId}, function (data) 
                {
                    $('#reorderLevel_availableQuantity').empty().html(data);
                });            
            }
        });

        //test
        // $(document).ready(function(){
            
        // });
        // $('button').click(function() {
        //         var i = 1;
        //         var x = 0;
        //             // test[i] = parseInt($('#stockOut').val());
        //         while (i<=2) {
        //             x += parseInt($('#stockOut').val());
        //             i++;
        //         }
        //         // var x = 0;
        //         //     x += parseInt($('#stockOut').val());
        //         // var stockOut = x + y;
        //         // $('#result').val(stockOut)
        //         $('#result').val(x)
        //     });
        
        


    </script>



@endsection