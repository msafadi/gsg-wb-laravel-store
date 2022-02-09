<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="http://style.css">
</head>
<body>
    <h2 style="color:darkcyan;">Hi {{ $notifiable->name }}</h2>
    <p style="margin-top: 15px;">A new order of ({{ $order->total }}) has been placed on your store.</p>
    <p><a style="display: inline-block; padding: 5px 10px; background: darkgreen; color: #fff;" href="">Click to view your order</a></p>
</body>
</html>