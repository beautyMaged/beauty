<!DOCTYPE html>
<html>
<head>

  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>{{\App\CPU\translate('Order Placed')}}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style type="text/css">
  /**
   * Google webfonts. Recommended to include the .woff version for cross-client compatibility.
   */

  @import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');
  /**
   * Avoid browser level font resizing.
   * 1. Windows Mobile
   * 2. iOS / OSX
   */
   body{
    font-family: 'Roboto', sans-serif;
   }
  body,
  table,
  td,
  a {
    -ms-text-size-adjust: 100%; /* 1 */
    -webkit-text-size-adjust: 100%; /* 2 */
  }

  /**
   * Remove extra space added to tables and cells in Outlook.
   */
  table,
  td {
    mso-table-rspace: 0pt;
    mso-table-lspace: 0pt;

  }

  /**
   * Better fluid images in Internet Explorer.
   */
  img {
    -ms-interpolation-mode: bicubic;
  }

  /**
   * Remove blue links for iOS devices.
   */
  a[x-apple-data-detectors] {
    font-family: inherit !important;
    font-size: inherit !important;
    font-weight: inherit !important;
    line-height: inherit !important;
    color: inherit !important;
    text-decoration: none !important;
  }

  /**
   * Fix centering issues in Android 4.4.
   */
  /* div[style*="margin: 16px 0;"] {
    margin: 0 !important;
  } */

  

  /**
   * Collapse table borders to avoid space between cells.
   */
  table {
    border-collapse: collapse !important;
  }

  a {
    color: #1a82e2;
  }

  img {
    height: auto;
    line-height: 100%;
    text-decoration: none;
    border: 0;
    outline: none;
  }
  
  </style>

</head>
<body style="background-color: #ececec;margin:0;padding:0">
  <?php 
    use App\Model\BusinessSetting;
    $company_phone =BusinessSetting::where('type', 'company_phone')->first()->value;
    $company_email =BusinessSetting::where('type', 'company_email')->first()->value;
    $company_name =BusinessSetting::where('type', 'company_name')->first()->value;
    $company_web_logo =BusinessSetting::where('type', 'company_web_logo')->first()->value;
    $company_mobile_logo =BusinessSetting::where('type', 'company_mobile_logo')->first()->value;
?>
<div style="width:650px;margin:auto; background-color:#ececec;height:50px;">

</div>
<div style="width:650px;margin:auto; background-color:white;margin-top:100px; 
            padding-top:40px;padding-bottom:40px;border-radius: 3px;">
    <table style="background-color: rgb(255, 255, 255);width: 90%;margin:auto;height:72px; border-bottom:1px ridge;">
        <tbody>
            <tr>
                <td>
                    <h2 >{{\App\CPU\translate('thanks_for_the_order')}}</h2>
                    <h3 style="color:green;">{{\App\CPU\translate('Your_order_ID')}} : {{$id}}</h3>
                </td>
                <td>
                    <div style="text-align: right; margin-right:15px;">
                        @php($logo=\App\Model\BusinessSetting::where(['type'=>'company_web_logo'])->first()->value)
                        <img style="max-width:250px;border:0;" src="{{asset('/storage/app/public/company/'.$logo)}}" title=""
                            class="sitelogo" width="60%"  alt=""/>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    @php($order = \App\Model\Order::find($id))
    <?php 
    if($order->seller_is == 'seller')
    {
        $seller = \App\Model\Seller::find($order->seller_id);
        $shop = \App\Model\Shop::find($seller->id);
    }
    ?>
    <table style="background-color: rgb(255, 255, 255);width: 90%;margin:auto; padding-bottom:20px;">
        <tbody >
            <tr style="width: 100%;">
                <td style="width:50%;vertical-align: top; margin-top:5px;">
                    
                    <div style="text-align:left;margin-top:10px;">
                      <span style="color: #130505 !important;text-transform: capitalize;font-weight: bold;">{{\App\CPU\translate('seller_details')}}  </span><br>
                      
                      @if ($order->seller_is == 'seller')
                    
                        <div style="display:flex; align-items:center;margin-top:10px;">
                            
                            <img style="border:0;border-radius:50%;" src="{{asset('/storage/app/public/shop/'.$shop->image)}}" title=""
                                    class="sitelogo" width="20%"  alt=""/>
                        
                            <span style="padding-left: 5px;">{{$seller->f_name . ' ' . $seller->l_name}}</span>
                        </div>
                    
                    @else
                    <div style="display:flex; align-items:center;margin-top:10px;">
                        <span>
                            {{\App\CPU\translate('inhouse_products')}}
                        </span>
                    </div>
                    @endif
                  </div>
    
                </td>
                <td style="width:50%;vertical-align: top;">
                    <div style="text-align:right;margin-top:10px;">
                        <span style="color: #130505 !important;text-transform: capitalize;font-weight: bold;">{{\App\CPU\translate('payment_details')}}  </span><br>
                        <div style="margin-top: 10px;">
                          <span style="color: #414141 !important ; text-transform: capitalize;">{{ str_replace('_',' ',$order->payment_method) }}</span><br> 
                        <span style="color: {{$order->payment_status=='paid'?'green':'red'}};">
                          {{$order->payment_status}}
                        </span><br>
                        <span style="color: #414141 !important ; text-transform: capitalize;">
                          {{date('d-m-y H:i:s',strtotime($order['created_at']))}}
                        </span>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
        
    </table>
    
    
    <?php
        $subtotal=0;
        $total=0;
        $sub_total=0;
        $total_tax=0;
        $total_shipping_cost=0;
        $total_discount_on_product=0;
        $extra_discount=0;
    ?>
    <div style="background-color: rgb(248, 248, 248); width: 90%;margin:auto;margin-top:30px;">
        <div style="padding:20px;">
            <table style="width: 100%; ">
                <tbody style="">
                    {{-- <div style="margin-top:100px;"> --}}
                        <tr style="border-bottom: 1px ridge;text-transform: capitalize;">
                            <th style="padding-bottom: 8px;width:10%;">{{\App\CPU\translate('SL')}}</th>
                            <th style="padding-bottom: 8px;width:40%;">{{\App\CPU\translate('Ordered_Items')}}</th>
                            <th style="padding-bottom: 8px;width:15%">{{\App\CPU\translate('Unit_price')}}</th>
                            <th style="padding-bottom: 8px;width:15%;">{{\App\CPU\translate('QTY')}}</th>
                            <th style="padding-bottom: 8px;width:20%;">{{\App\CPU\translate('Total')}}</th>
                        </tr>
                        @foreach ($order->details as $key=>$details)
                        <?php $subtotal=($details['price'])*$details->qty; ?>
                            <tr style="text-align: center;">
                                
                                <td style="padding:5px;">{{$key+1}}</td>
                                <td style="padding:5px;">
                                  <span style="font-size: 14px;">
                                    {{$details['product']?Str::limit($details['product']->name,55):''}}
                                  </span>
                                    
                                <br>
                                @if ($details['variant']!=null)
                                  <span style="font-size: 12px;">
                                    {{\App\CPU\translate('variation')}} : {{$details['variant']}}
                                  </span>
                                @endif
                               
                                </td>
                                <td style="padding:5px;">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($details['price']))}}</td>
                                <td style="padding:5px;">{{$details->qty}}</td>
                                <td style="padding:5px;">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($subtotal))}}</td>
                            </tr>
                            <?php 
                                $sub_total+=$details['price']*$details['qty'];
                                $total_tax+=$details['tax'];
                                $total_shipping_cost+=$details->shipping ? $details->shipping->cost :0;
                                $total_discount_on_product+=$details['discount'];
                                $total+=$subtotal;
                            ?>
                        @endforeach
                        
                    {{-- </div> --}}
                </tbody>
            </table>
        </div>
    </div>
    <?php
        if ($order['extra_discount_type'] == 'percent') {
            $extra_discount = ($sub_total / 100) * $order['extra_discount'];
        } else {
            $extra_discount = $order['extra_discount'];
        }
        $shipping=$order['shipping_cost'];
    ?>
    
        <table style="background-color: rgb(255, 255, 255);width: 90%;margin:auto;margin-top:30px;">
            <tr>
                <th style="text-align: left; vertical-align: auto;">
                    
                </th>
    
                <td style="text-align: right">
                    <table style="width: 46%;margin-left:41%; display: inline;text-transform: capitalize; ">
                        <tbody>
    
                        <tr>
                            <th  ><b>{{\App\CPU\translate('sub_total')}} : </b></th>
                            <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($sub_total))}}</td>
    
                        </tr>
                        <tr>
                            <td>{{\App\CPU\translate('tax')}}  : </td>
                            <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total_tax))}}</td>
                        </tr>
                        @if($order->order_type == 'default_type')
                        <tr>
                            <td  >{{\App\CPU\translate('shipping')}} : </td>
                            <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($shipping))}}</td>
                        </tr>
                        @endif
                        <tr>
                            <td  >{{\App\CPU\translate('coupon_discount')}} : </td>
                            <td>
                                - {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->discount_amount))}}</td>
                        </tr>
                        <tr class="border-bottom">
                            <td  >{{\App\CPU\translate('discount_on_product')}} : </td>
                            <td>
                                - {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total_discount_on_product))}}</td>
                        </tr>
                        @if ($order->order_type != 'default_type')
                        <tr class="border-bottom">
                            <th  >{{\App\CPU\translate('extra_discount')}} : </th>
                            <td>
                                - {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($extra_discount))}}</td>
                        </tr>
                        @endif
                        <tr class="bg-primary">
                            <th class="text-left"><b class="text-white">{{\App\CPU\translate('total')}} : </b></th>
                            <td class="text-white">
                                {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->order_amount))}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
   
    <table style="background-color: rgb(255, 255, 255);width: 90%;margin:auto;margin-top:30px;">
        <tbody style="">
            
                <tr style="">
                    <td>{{\App\CPU\translate('You_can_track_your_order_by_clicking_the_below_button')}}</td>
                </tr>
                <tr>
                    <td>
                      <?php
                        $user_phone = \App\User::find($order->customer_id)->phone;
                      ?>
                    
                          <div style="margin-top: 50px; margin-bottom:30px">
                            <a href="{{route('track-order.result',['order_id'=>$order->id,'phone_number'=>$user_phone])}}" style="background-color: #1a82e2; padding:20px;border:none;
                              margin-top:20px;color:aliceblue;border-radius: 3px; font-size:18px;text-decoration: none; text-transform: capitalize;">
                              {{\App\CPU\translate('track_your_order')}}
                            </a>
                          </div>
                    </td>
                </tr>
            
        </tbody>
    </table>
    
</div>

<div style="padding:5px;width:650px;margin:auto;margin-top:5px; margin-bottom:50px;">
    
    <table style="margin:auto;width:90%; color:#777777;">
        <tbody>
            <tr>
                <th style="text-align: left;">
                    <h1>
                        {{$company_name = \App\Model\BusinessSetting::where('type', 'company_name')->first()->value}}
                    </h1>
                </th>
            </tr>
            <tr>
                <th style="text-align: left;">
                    <div> {{\App\CPU\translate('phone')}}
                        : {{\App\Model\BusinessSetting::where('type','company_phone')->first()->value}}</div>
                    <div> {{\App\CPU\translate('website')}}
                        : {{url('/')}}</div>
                    <div > {{\App\CPU\translate('email')}}
                        : {{$company_email}}</div>
                </th>
                
            </tr>
            <tr>
                @php($social_media = \App\Model\SocialMedia::where('active_status', 1)->get())
                
                @if(isset($social_media))
                    <th style="text-align: left; padding-top:20px;">
                        <div style="width: 100%;display: flex;
                        justify-content: flex-start;">
                          @foreach ($social_media as $item)
                        
                            <div class="" >
                              <a href="{{$item->link}}" target=”_blank”>
                              <img src="{{asset('public/assets/back-end/img/'.$item->name.'.png')}}" alt="" style="height: 50px; width:50px; margin:10px;">
                              </a>
                            </div>
                            
                          @endforeach
                        </div>
                    </th>
                @endif
            </tr>
        </tbody>
    </table>
</div>

</body>
</html>