<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ \App\CPU\translate("product") }} {{ \App\CPU\translate("barcode") }}</title>
    <link rel="stylesheet" href="{{ asset('public/assets/back-end') }}/css/bootstrap.css" />
    <style>
        body {
            line-height: 1.2;
        }
        .text-capitalize {
            text-transform: uppercase;
        }
        .text-bold {
            font-weight: bold;
        }
        .currency {
            font-family: DejaVuSans;
        }
    </style>
</head>

<body>
    @if ($quantity)
        <div class="container">
            <div class="row">
                @for ($i = 0; $i < $quantity; $i++)
                    @if ($i % 3 == 0 && $i != 0)
            </div>
            <div class="row">
    @endif
    <div align="center" class="col-xs-4" style="border: 1px dotted #CCC; margin: 5px; width: 27%;">
        <span
            class="text-capitalize text-bold">{{ \App\Model\BusinessSetting::where('type', 'company_name')->first()->value }}</span>
        <span class="product-name" style="display: block">{{ Str::limit($product->name, 30) }}</span>
        <span class="currency">
            {{ $product['selling_price'] . ' ' . \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($product->unit_price)) }}</span>
        <br>
        <div class="bar-code" style="margin-left: 10px !important; font-weight:bold">{!! DNS1D::getBarcodeHTML($product->code, 'C128') !!}</div>
        <p class="">{{ \App\CPU\translate('code') }} :
            {{ $product->code }}</p>
    </div>
    @endfor
    </div>
    </div>
    @endif
</body>

</html>
