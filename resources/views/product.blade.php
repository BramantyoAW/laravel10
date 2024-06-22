<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopify Products</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container mt-5">
        <h1>Shopify Products</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <h2>Create New Product</h2>
        <form action="{{ url('/create-product') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Product Title</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="body_html">Product Description</label>
                <textarea name="body_html" id="body_html" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Product Price</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="image">Product Image URL</label>
                <input type="url" name="image" id="image" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Product</button>
        </form>

        <h2 class="mt-5">Product List</h2>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Price</th>
                    <th scope="col">Image</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>	
                <?php 
                    foreach ($shopProduct as $product) {
                        foreach ($product as $key => $value) {
                            $image = count($value['images']) > 0 ? $value['images'][0]['src'] : '';
                            ?>
                            <tr>	
                                <td><img width="35" height="35" src="<?php echo $image; ?>"></td>
                                <td>
                                    <form action="" method="post" class="row side-elements">
                                        <input type="hidden" name="update_id" value="<?php echo $value['id']; ?>">
                                        <input type="text" name="update_name" value="<?php echo $value['title']; ?>">
                                        <input type="hidden" name="action_type" value="update">
                                        <button type="submit" class="secondary icon-checkmark"></button>
                                    </form>								
                                <td><?php echo $value['status'] ?></td>
                                <td>
                                    <form action="" method="POST">	
                                        <input type="hidden" name="delete_id" value="<?php echo $value['id']; ?>">
                                        <input type="hidden" name="action_type" value="delete">
                                        <button type="submit" class="secondary icon-trash"></button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                ?>
		    </tbody>
        </table>
    </div>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script>
        function updateProduct(id) {
            let row = document.querySelector(`tr[data-product-id="${id}"]`);
            let title = row.querySelector('input[name="title"]').value;
            let body_html = row.querySelector('textarea[name="body_html"]').value;
            let price = row.querySelector('input[name="price"]').value;

            fetch(`/update-product/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    title: title,
                    body_html: body_html,
                    price: price
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('Product updated successfully!');
                } else {
                    alert('Failed to update product.');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>