<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Refund Request Notification</title>
  <style>
    @import url("https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css");
    body {
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      margin: 0;
      padding: 0;
    }
    .container {
      padding: 20px;
    }
    .table {
      border-collapse: collapse;
      width: 100%;
    }
    .table thead th, .table tbody td {
      padding: 10px;
      vertical-align: middle;
      text-align: left;
    }
    .table tbody tr:nth-child(odd) {
      background-color: #f5f5f5;
    }
    a {
      text-decoration: none;
      color: #007bff;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Refund Request Notification</h2>
    <p>Hello {{ $seller->f_name }},</p>
    
    <p>You have received a refund request for the following order:</p>

    <table class="table table-striped">
      <thead>
        <tr>
          <th>Order Details</th>
          <th>Value</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Order ID</td>
          <td>{{ $order_details->order->id }}</td>
        </tr>
        <tr>
          <td>Customer</td>
          <td>{{ $customer_name }}</td>
        </tr>
        <tr>
          <td>Product</td>
          <td>{{ $product->name }}</td>
        </tr>
        <tr>
          <td>Quantity</td>
          <td>{{ $quantity }} units</td>
        </tr>
        <tr>
          <td>Amount</td>
          <td>${{ $amount }}</td>
        </tr>
        <tr>
          <td>customer message</td>
          <td>{{ $refund_reason }}</td>
        </tr>
        <tr>
          <td>Refund Request Reason</td>
          <td>{{ $refund_request_reason }}</td>
        </tr>
      </tbody>
    </table>

    <p>Click the button below to view and process the request:</p>
    
    @component('mail::button', ['url' => url('/seller/dashboard')])
    View Request
    @endcomponent

    <p>Thanks, {{ $seller->f_name }}</p>
    <p>{{ config('app.name') }} Team</p>
  </div>
</body>
</html>
