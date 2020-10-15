<div class="form-group">
    <label for="reorderLevel">Reorder Level</label>
    <input type="number" class="form-control" value="{{$item->reorderLevel}}" readonly>
</div>


<div class="form-group">
    <label for="availableQuantity">Available Quantity</label>
    @if (isset($stockIn))
        {{-- <input type="number" value="{{$stockIn->stock_in}}" class="form-control" readonly> --}} <!-- অথবা নিচেরটা -->
        <input type="number" value="{{$stockIn}}" class="form-control" readonly>
    @else
        <input type="number" value="0" class="form-control" readonly>
    @endif
    {{-- <input type="number" value="{{$stockIn->stock_in}}" class="form-control" readonly> --}}
</div>



{{-- <input type="text"  name="item_id" class="form-control" value="{{$item->reorderLevel}}" readonly>
<input type="number" value="{{$stockIn->stock_in}}" class="form-control" readonly> --}}
