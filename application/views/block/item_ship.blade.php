<table cellpadding="0" cellspacing="0" width="100%" class="tDark">
    <thead>
    <tr>
        <td>名称</td>
        <td>SKU</td>
        <td>发货数量</td>
        <td>库存</td>
        <td>物流公司</td>
        <td>发货方式</td>
        <td>跟踪号</td>
    </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
        <tr>
            <td>{{$item->name}}</td>
            <td>{{$item->sku}}</td>
            <td>
                {{$item->unship}}/<input name="order_ship[{{$item->id}}][quantity]" type="text" style="width: 35px" value="{{$item->unship}}">
                <input name="order_ship[{{$item->id}}][sold]" type="hidden" value="{{$item->quantity}}"/>
            </td>
            <td>{{$item->stock}}</td>
            <td><input name="order_ship[{{$item->id}}][company]" type="text"></td>
            <td><input name="order_ship[{{$item->id}}][method]" type="text"></td>
            <td><input name="order_ship[{{$item->id}}][tracking_no]" type="text"><input name="order_ship[{{$item->id}}][order_id]" type="hidden" value="{{$item->order_id}}"></td>
        </tr>
    @endforeach
    </tbody>
</table>
