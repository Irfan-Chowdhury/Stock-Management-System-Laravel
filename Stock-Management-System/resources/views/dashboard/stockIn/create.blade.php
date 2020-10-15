@extends('dashboard.layouts.maintemplate')

@section('title', 'Stock In')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Stock In</h1>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">

                {{-- --------- Check in Flash Message -------- --}}
                @include('dashboard.flashMessage.message')
                {{-- ---------------- X -------------------- --}}

                <form action="{{route('stockin.store')}}" method="post">
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
                            <option>--Select Item--</option>
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
                        <label for="stockIn">Stock In</label>
                        <input type="number" name="stock_in" id="stockIn" class="form-control @error('stock_in') is-invalid @enderror" placeholder="Input a Value">
                        @error('stock_in')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>                                         
                </form>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>

    <script>
        $('#companyId').change(function() 
        {
            console.log('ok');

            var companyId = $('#companyId').val();
            if (companyId) 
            {
                $.get("{{route('company-wise-item')}}",{company_id:companyId}, function (data) 
                {
                    console.log(data);
                    $('#itemId').empty().html(data);
                });
            }
            else{
                $('#itemId').empty().html('<option>--Select Item--</option>');  //Item সিলেক্ট করার Drop-Down টি
            }
        });



        $('#itemId').change(function(){
            var companyId = $('#companyId').val();
            var itemId = $(this).val();
            if (companyId && itemId) 
            {
                $.get("{{route('show-reorder-level')}}",{company_id:companyId , id:itemId}, function (data) 
                {
                    $('#reorderLevel_availableQuantity').empty().html(data); //Reorder Level & Availble Quantity তে ডাটা শো করার DIV টা । 
                });            
            }
        });
    </script>



@endsection