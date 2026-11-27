
<?= view('layout/header', [
    'title' => 'Sales History - IRONFUEL POS'
]) ?>

<style>
    .sales-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
    }

    .sales-header h1 {
        color: white;
    }

    .sales-header p {
        color: #A6A6AE;
    }

    .sales-add-btn {
        background: #E50914;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
    }

    .sales-card {
        background: #1C1C20;
        border: 1px solid #303035;
        border-radius: 15px;
        padding: 25px;
        overflow-x: auto;
    }

    .sales-table {
        width: 100%;
        border-collapse: collapse;
    }

    .sales-table th {
        background: #28282E;
        color: white;
        padding: 15px;
        text-align: left;
    }

    .sales-table td {
        padding: 15px;
        border-bottom: 1px solid #303035;
        color: #E5E5E5;
    }

    .sale-total {
        color: #FF525B;
        font-weight: bold;
    }

    .sale-id {
        color: #A6A6AE;
    }

    .walk-in {
        color: #A6A6AE;
        font-style: italic;
    }

    @media(max-width:700px) {
        .sales-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="sales-header">

    <div>
        <p style="color:#FF525B;font-weight:bold;">
            SALES MANAGEMENT
        </p>

        <h1>Sales History</h1>

        <p>
            View all recorded whey protein transactions.
        </p>
    </div>

    <a
        class="sales-add-btn"
        href="<?= site_url('sales/new') ?>"
    >
        + Record Sale
    </a>

</div>

<div class="sales-card">

    <table class="sales-table">

        <thead>
            <tr>
                <th>Sale ID</th>
                <th>Product</th>
                <th>Customer</th>
                <th>Sold By</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Date</th>
            </tr>
        </thead>

        <tbody>

            <?php if (!empty($sales)): ?>

                <?php foreach ($sales as $sale): ?>

                    <tr>
                        <td class="sale-id">
                            #<?= esc($sale['id']) ?>
                        </td>

                        <td>
                            <strong>
                                <?= esc($sale['product_name']) ?>
                            </strong>
                        </td>

                        <td>
                            <?php if (!empty($sale['customer_name'])): ?>

                                <?= esc($sale['customer_name']) ?>

                            <?php else: ?>

                                <span class="walk-in">
                                    Walk-in Customer
                                </span>

                            <?php endif; ?>
                        </td>

                        <td>
                            <?= esc($sale['staff_name']) ?>
                        </td>

                        <td>
                            <?= esc($sale['quantity']) ?>
                        </td>

                        <td>
                            <span class="sale-total">
                                ₱<?= number_format($sale['total_price'], 2) ?>
                            </span>
                        </td>

                        <td>
                            <?= esc($sale['created_at']) ?>
                        </td>
                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="7" style="text-align:center;padding:30px;">
                        No sales transactions recorded yet.
                    </td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</div>

<?= view('layout/footer') ?>
