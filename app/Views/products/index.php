
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Products - IRONFUEL POS</title>

    <link rel="stylesheet"
          href="<?= base_url('css/ironfuel.css') ?>">

    <style>
        body {
            background: #0D0D0F;
            color: white;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .product-container {
            max-width: 1250px;
            width: 92%;
            margin: 40px auto;
        }

        .product-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
        }

        .product-card {
            background: #1C1C20;
            border: 1px solid #303035;
            border-radius: 15px;
            padding: 25px;
            overflow-x: auto;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
        }

        .product-table th {
            background: #28282E;
            color: white;
            padding: 15px;
            text-align: left;
        }

        .product-table td {
            padding: 15px;
            border-bottom: 1px solid #303035;
        }

        .product-image {
            width: 75px;
            height: 75px;
            object-fit: cover;
            border-radius: 10px;
            background: #303035;
        }

        .product-btn {
            display: inline-block;
            padding: 10px 17px;
            background: #E50914;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        .edit-btn {
            background: #35353C;
        }

        .delete-btn {
            background: #76272D;
        }

        .product-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .product-actions form {
            margin: 0;
        }

        .stock {
            color: #25B981;
            font-weight: bold;
        }

        .out-stock {
            color: #FF525B;
            font-weight: bold;
        }

        @media(max-width:700px) {
            .product-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

<?= view('layout/header', ['title' => 'Products']) ?>

<div class="product-container">

    <div class="product-header">
        <div>
            <h1>Whey Protein Products</h1>
            <p>Manage your supplements and inventory.</p>
        </div>

        <a class="product-btn"
           href="<?= site_url('products/new') ?>">
            + Add Product
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <p style="color:#25B981;">
            <?= esc(session()->getFlashdata('success')) ?>
        </p>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <p style="color:#FF525B;">
            <?= esc(session()->getFlashdata('error')) ?>
        </p>
    <?php endif; ?>

    <div class="product-card">

        <table class="product-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($products as $product): ?>

                    <tr>
                        <td>
                            <?php if (!empty($product['image'])): ?>

                                <img
                                    class="product-image"
                                    src="<?= base_url('uploads/products/' . rawurlencode($product['image'])) ?>"
                                    alt="Product image"
                                >

                            <?php else: ?>

                                <div style="
                                    width:75px;
                                    height:75px;
                                    background:#303035;
                                    border-radius:10px;
                                    display:flex;
                                    align-items:center;
                                    justify-content:center;
                                    font-size:11px;
                                ">
                                    No Image
                                </div>

                            <?php endif; ?>
                        </td>

                        <td>
                            <strong>
                                <?= esc($product['name']) ?>
                            </strong>
                        </td>

                        <td>
                            ₱<?= number_format($product['price'], 2) ?>
                        </td>

                        <td>
                            <?php if ($product['stock_quantity'] > 0): ?>
                                <span class="stock">
                                    <?= esc($product['stock_quantity']) ?>
                                </span>
                            <?php else: ?>
                                <span class="out-stock">
                                    Out of Stock
                                </span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <div class="product-actions">

                                <a class="product-btn edit-btn"
                                   href="<?= site_url('products/edit/' . $product['id']) ?>">
                                    Edit
                                </a>

                                <form
                                    action="<?= site_url('products/delete/' . $product['id']) ?>"
                                    method="post"
                                    onsubmit="return confirm('Delete this product?')"
                                >
                                    <?= csrf_field() ?>

                                    <button
                                        class="product-btn delete-btn"
                                        type="submit">
                                        Delete
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>

                <?php endforeach; ?>

                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="5" style="text-align:center;">
                            No products available.
                        </td>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>

    </div>

</div>

<?= view('layout/footer') ?>

</body>
</html>
