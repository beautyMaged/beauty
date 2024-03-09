<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact us message</title>
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
    <h2>contact message notification</h2>
    
    <p>You have received a new contact message:</p>

    <table class="table table-striped">
      <thead>
        <tr>
          <th>item</th>
          <th>detailes</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Name</td>
          <td>{{ $data->name }}</td>
        </tr>
        <tr>
          <td>Email</td>
          <td>{{$data->email }}</td>
        </tr>
        <tr>
          <td>Phone</td>
          <td>{{ $data->Phone }}</td>
        </tr>
        <tr>
          <td>Type</td>
          <td>{{ $data->type }}</td>
        </tr>
        <tr>
          <td>Title</td>
          <td>{{ $data->title }}</td>
        </tr>
        <tr>
          <td>message</td>
          <td>{{ $data->message }}</td>
        </tr>

      </tbody>
    </table>

    <p>Click the button below to view and process the message:</p>
    
    @component('mail::button', ['url' => url('/app/contact-messages')])
    View message
    @endcomponent

  </div>
</body>
</html>
