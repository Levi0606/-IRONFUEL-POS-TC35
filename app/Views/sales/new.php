
<?= view('layout/header', [
    'title' => 'Record Sale - IRONFUEL POS'
]) ?>

<style>
    .sale-form-card {
        max-width: 700px;
        margin: 30px auto;
        background: #1C1C20;
        border: 1px solid #303035;
        border-radius: 16px;
        padding: 35px;
    }

    .sale-form-card h1 {
        color: white;
    }

    .sale-form-card p {
        color: #A6A6AE;
    }

    .sale-form-card label {
        display: block;
        margin: 20px 0 8px;
        color: white;
        font-weight: bold;
    }

    .sale-form-card select,
    .sale-form-card input {
        width: 100%;
        padding: 13px;
        background: #29292F;
        color: white;
        border: 1px solid #44444C;
        border-radius: 9px;
        font-size: 14px;
    }

    .sale-form-card select:focus,
    .sale-form-card input:focus {
        outline: none;
        border-color: #E50914;
    }

    .sale-buttons {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        margin-top: 30px;
    }

    .sale-submit {
        background: #E50914;
        color: white;
        padding: 13px 22px;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
    }

    .sale-cancel {
        background: #35353C;
        color: white;
        padding: 13px 22px;
        border-radius: 8px;
        text-decoration: none;
    }

    .sale-info {
        background: #29292F;
        border-left: 4px solid #E50914;
        padding: 15px;
        border-radius: 8px;
        margin-top: 25px;
        color: #DDD;
    }
</style>

<div class="sale-form-card">

    <p style="color:#FF525B;font-weight:bold;">
        SALES MANAGEMENT
    </p>

    <h1>Record New Sale</h1>

    <p>
        Select a whey protein product, choose a customer,
        and enter the quantity to complete a transaction.
    </p>

    <form
        action="<?= site_url('sales/record') ?>"
        method="post"
    >

        <?= csrf_field() ?>

        <label for="product_id">Select Product</label>

        <select id="product_id" name="product_id" required>

            <option value="">-- Select Whey Protein --</option>

            <?php foreach ($products as $product): ?>

                <option
                    value="<?= esc($product['id']) ?>"
                    <?= (string) old('product_id') === (string) $product['id'] ? 'selected' : '' ?>
                    <?= (int) $product['stock_quantity'] <= 0 ? 'disabled' : '' ?>
                >
                    <?= esc($product['name']) ?>
                    - ₱<?= number_format($product['price'], 2) ?>
                    (<?= esc($product['stock_quantity']) ?> in stock)
                </option>

            <?php endforeach; ?>

        </select>

        <label for="customer_id">Customer (Optional)</label>

        <select id="customer_id" name="customer_id">

            <option value="">Walk-in Customer</option>

            <?php foreach ($customers as $customer): ?>

                <option
                    value="<?= esc($customer['id']) ?>"
                    <?= (string) old('customer_id') === (string) $customer['id'] ? 'selected' : '' ?>
                >
                    <?= esc($customer['full_name']) ?>
                </option>

            <?php endforeach; ?>

        </select>

        <label for="quantity">Quantity</label>

        <input
            type="number"
            id="quantity"
            name="quantity"
            min="1"
            step="1"
            value="<?= esc(old('quantity', 1)) ?>"
            required
        >

        <div class="sale-info">
            <strong>Inventory Protection</strong>
            <p>
                The system automatically checks available stock,
                calculates the total price, and updates inventory
                after a successful sale.
            </p>
        </div>

        <div class="sale-buttons">

            <a
                class="sale-cancel"
                href="<?= site_url('dashboard') ?>"
            >
                Cancel
            </a>

            <button
                class="sale-submit"
                type="submit"
            >
                Confirm Sale
            </button>

        </div>

    </form>

</div>

<?= view('layout/footer') ?>
