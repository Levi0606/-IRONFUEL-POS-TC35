<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Customer - Chan POS</title>
    <link rel="stylesheet" href="<?= base_url('css/chan-pos.css') ?>">
</head>
<body>

<?= view('layout/navbar') ?>

<div class="container">
    <div class="card form-card">

        <p class="eyebrow">Customer Management</p>
        <h1>Edit Customer</h1>
        <p class="subtitle">Update customer information.</p>

        <?php if ($validation->getErrors()): ?>
            <div class="error">
                <ul>
                    <?php foreach ($validation->getErrors() as $message): ?>
                        <li><?= esc($message) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('customers/update/' . $customer['id']) ?>"
              method="post">

            <?= csrf_field() ?>

            <label>Full Name</label>
            <input type="text"
                   name="full_name"
                   value="<?= esc(old('full_name', $customer['full_name'])) ?>"
                   required>

            <label>Email</label>
            <input type="email"
                   name="email"
                   value="<?= esc(old('email', $customer['email'])) ?>"
                   required>

            <label>Phone</label>
            <input type="text"
                   name="phone"
                   value="<?= esc(old('phone', $customer['phone'])) ?>">

            <div class="actions">
                <a class="btn btn-outline"
                   href="<?= site_url('customers') ?>">
                    Cancel
                </a>

                <button class="btn btn-primary" type="submit">
                    Save Changes →
                </button>
            </div>
        </form>

    </div>
</div>
</body>
</html>