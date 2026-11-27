<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Customer - Chan POS</title>
    <link rel="stylesheet" href="<?= base_url('css/chan-pos.css') ?>">
</head>
<body>

<?= view('layout/navbar') ?>

<div class="container">
    <div class="card form-card">

        <p class="eyebrow">Customer Management</p>
        <h1>Add Customer</h1>
        <p class="subtitle">Create a new customer record.</p>

        <?php if ($validation->getErrors()): ?>
            <div class="error">
                <ul>
                    <?php foreach ($validation->getErrors() as $message): ?>
                        <li><?= esc($message) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('customers/create') ?>" method="post">
            <?= csrf_field() ?>

            <label>Full Name</label>
            <input type="text"
                   name="full_name"
                   value="<?= esc(old('full_name')) ?>"
                   maxlength="100"
                   required>

            <label>Email</label>
            <input type="email"
                   name="email"
                   value="<?= esc(old('email')) ?>"
                   maxlength="100"
                   required>

            <label>Phone</label>
            <input type="text"
                   name="phone"
                   value="<?= esc(old('phone')) ?>"
                   maxlength="20">

            <div class="actions">
                <a class="btn btn-outline"
                   href="<?= site_url('customers') ?>">
                    Cancel
                </a>

                <button class="btn btn-primary" type="submit">
                    Create Customer
                </button>
            </div>
        </form>

    </div>
</div>
</body>
</html>