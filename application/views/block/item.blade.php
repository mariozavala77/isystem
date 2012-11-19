<table cellpadding="0" cellspacing="0" width="100%" class="tDark">
    <thead>
    <tr>
        <td>ID</td>
        <td>名称</td>
        <td>SKU</td>
        <td>数量</td>
        <td>价格</td>
        <td>邮费</td>
        <td>库存</td>
    </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
        <tr>
            <td>{{$item->entity_id}}</td>
            <td>{{$item->name}}</td>
            <td>{{$item->sku}}</td>
            <td>{{$item->quantity}}</td>
            <td>{{$item->price}}</td>
            <td>{{$item->shipping_price}}</td>
            <td>
                {{$item->stock}}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
