@if(count($combinations) > 0)
<table class="table table-bordered physical_product_show">
    <thead>
    <tr>
        <td class="text-center">
            <label for="" class="title-color">{{\App\CPU\translate('Variant')}}</label>
        </td>
        <td class="text-center">
            <label for="" class="title-color">{{\App\CPU\translate('Variant Price')}}</label>
        </td>
        <td class="text-center">
            <label for="" class="title-color">{{\App\CPU\translate('SKU')}}</label>
        </td>
        <td class="text-center">
            <label for="" class="title-color">{{\App\CPU\translate('Quantity')}}</label>
        </td>
    </tr>
    </thead>
    <tbody>
    @endif
    @foreach ($combinations as $key => $combination)
        <tr>
            <td>
                <label for="" class="control-label">{{ $combination['type'] }}</label>
                <input value="{{ $combination['type'] }}" name="type[]" class="d-none">
            </td>
            <td>
                <input type="number" name="price_{{ $combination['type'] }}"
                       value="{{ \App\CPU\Convert::default($combination['price']) }}" min="0"
                       step="0.01"
                       class="form-control" required>
            </td>
            <td>
                <input type="text" name="sku_{{ $combination['type'] }}" value="{{ $combination['sku'] }}"
                       class="form-control" >
            </td>
            <td>
                <input type="number" onkeyup="update_qty()" name="qty_{{ $combination['type'] }}" value="{{ $combination['qty'] }}" min="1" max="100000" step="1"
                       class="form-control"
                       required>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

