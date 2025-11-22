<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products List</title>
</head>
<body>
    <h1>All Products</h1>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
        </tr>
        @foreach($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->description }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->stock }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>
