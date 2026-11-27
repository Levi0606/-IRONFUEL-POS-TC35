
<?= view('layout/header', [
    'title' => $product ? 'Edit Product' : 'Add Product'
]) ?>

<style>
    .product-form-card {
        max-width: 700px;
        margin: 40px auto;
        padding: 35px;
        background: #1C1C20;
        border: 1px solid #303035;
        border-radius: 16px;
    }

    .product-form-card h1 {
        color: white;
    }

    .product-form-card p {
        color: #A6A6AE;
    }

    .product-form-card label {
        display: block;
        color: white;
        font-weight: bold;
        margin-top: 20px;
        margin-bottom: 8px;
    }

    .product-form-card input {
        width: 100%;
        padding: 13px;
        background: #29292F;
        color: white;
        border: 1px solid #44444C;
        border-radius: 9px;
    }

    .form-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
        gap: 12px;
    }

    .save-product-btn {
        background: #E50914;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
    }

    .cancel-product-btn {
        background: #35353C;
        color: white;
        text-decoration: none;
        padding: 12px 20px;
        border-radius: 8px;
    }

    .current-product-image {
        width: 140px;
        height: 140px;
        object-fit: cover;
        border-radius: 12px;
        margin-top: 15px;
    }
</style>

<div class="product-form-card">

    <h1>
        <?= $product ? 'Edit Product' : 'Add New Product' ?>
    </h1>

    <p>
        Enter whey protein product information.
    </p>

    <form
        action="<?= site_url('products/save' . ($product ? '/' . $product['id'] : '')) ?>"
        method="post"
        enctype="multipart/form-data"
    >

        <?= csrf_field() ?>

        <label>Product Name</label>

        <input
            type="text"
            name="name"
            value="<?= esc(old('name', $product['name'] ?? '')) ?>"
            maxlength="100"
            required
        >

        <label>Price (PHP)</label>

        <input
            type="number"
            name="price"
            min="0"
            step="0.01"
            value="<?= esc(old('price', $product['price'] ?? '')) ?>"
            required
        >

        <label>Stock Quantity</label>

        <input
            type="number"
            name="stock_quantity"
            min="0"
            step="1"
            value="<?= esc(old('stock_quantity', $product['stock_quantity'] ?? 0)) ?>"
            required
        >

        <label>Product Image</label>

        <input
            type="file"
            name="image"
            accept=".jpg,.jpeg,.png"
        >

        <p>JPG or PNG only. Maximum 2MB.</p>

        <?php if (!empty($product['image'])): ?>

            <p>Current Product Image:</p>

            <img
                class="current-product-image"
                src="<?= base_url('uploads/products/' . rawurlencode($product['image'])) ?>"
                alt="Current product image"
            >

        <?php endif; ?>

        <div class="form-buttons">

            <a class="cancel-product-btn"
               href="<?= site_url('products') ?>">
                Cancel
            </a>

            <button class="save-product-btn" type="submit">
                Save Product
            </button>

        </div>

    </form>

</div>

<?= view('layout/footer') ?>
