<?= view('layout/header', ['title' => 'Dashboard']) ?>

<section class="hero">
    <p>Welcome back, <?= esc(session('full_name')) ?>!</p>

    <h1>
        Fuel the <span class="accent">grind.</span>
    </h1>

    <p>Manage your whey protein business in one place.</p>

    <a href="<?= site_url('sales/new') ?>"
       class="btn btn-red">
        + Record New Sale
    </a>
</section>

<div class="stats-grid">
    <div class="stat">
        <div class="stat-value"><?= esc($productCount) ?></div>
        <div class="stat-label">Products</div>
    </div>

    <div class="stat">
        <div class="stat-value"><?= esc($customerCount) ?></div>
        <div class="stat-label">Customers</div>
    </div>

    <div class="stat">
        <div class="stat-value"><?= esc($userCount) ?></div>
        <div class="stat-label">Staff Accounts</div>
    </div>

    <div class="stat">
        <div class="stat-value"><?= esc($stockCount) ?></div>
        <div class="stat-label">Total Units in Stock</div>
    </div>
</div>

<div class="card">
    <h2>Total Revenue</h2>
    <h1 class="accent">
        ₱<?= number_format((float)$revenue, 2) ?>
    </h1>

    <p><?= esc($saleCount) ?> recorded transactions</p>

    <a href="<?= site_url('sales') ?>"
       class="btn btn-dark">
        View Sales History
    </a>
</div>

<div class="card">
    <h2>Recent Products</h2>

    <div class="table-wrap">
        <table>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Stock</th>
            </tr>

            <?php foreach ($recentProducts as $product): ?>
                <tr>
                    <td><?= esc($product['name']) ?></td>
                    <td>₱<?= number_format($product['price'], 2) ?></td>
                    <td><?= esc($product['stock_quantity']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<?= view('layout/footer') ?>