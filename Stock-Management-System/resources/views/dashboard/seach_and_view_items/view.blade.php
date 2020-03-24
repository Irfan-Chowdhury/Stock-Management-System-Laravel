@foreach ($views as $key=>$view)
    <tr class="text-center">
        <td scope="row">{{$key+1}}</th>
        <td class="text-dark">{{$view->itemName}}</td>
        <td class="text-dark">{{$view->companyName}}</td>
        <td class="text-dark">{{$view->stock_in}}</td>
        <td class="text-dark">{{$view->reorderLevel}}</td>
    </tr>
@endforeach