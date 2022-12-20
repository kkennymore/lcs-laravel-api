<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lox Corporate Services | Admin section</title>
</head>
<body>
    <section class="container">
        <center>
            <h1>Orders</h1>
            <a href="{{ url('/admin') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Home</a>
            <a href="{{ url('/admin/cart') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Cart</a>
            <a href="{{ url('/admin/orders') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Orders</a>
            <a href="{{ url('/admin/products') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Products</a>
        </center>
    </section>
</body>
</html>
