<option value="">--Select Item --</option>
@foreach ($items as $item)
    <option value="{{$item->id}}">{{$item->itemName}}</option>
@endforeach
