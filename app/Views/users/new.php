<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add User - Chan POS</title>
    <link rel="stylesheet" href="<?= base_url('css/chan-pos.css') ?>">
</head>
<body>

<?= view('layout/navbar') ?>

<div class="container">
    <div class="card form-card">

        <p class="eyebrow">Staff Management</p>
        <h1>Add User</h1>
        <p class="subtitle">Create a new staff account.</p>

        <?php if ($validation->getErrors()): ?>
            <div class="error">
                <ul>
                    <?php foreach ($validation->getErrors() as $message): ?>
                        <li><?= esc($message) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('users/create') ?>" method="post">

            <?= csrf_field() ?>

            <label>Username</label>
            <input type="text"
                   name="username"
                   value="<?= esc(old('username')) ?>"
                   maxlength="50"
                   required>

            <label>Full Name</label>
            <input type="text"
                   name="full_name"
                   value="<?= esc(old('full_name')) ?>"
                   maxlength="100"
                   required>

            <label>Email</label>
            <input type="email"
                   name="email"
                   value="<?= esc(old('email')) ?>">

            <label>Password</label>
            <input type="password"
                   name="password"
                   minlength="8"
                   autocomplete="new-password"
                   required>

            <p class="subtitle">
                Password must contain at least 8 characters.
            </p>

            <div class="actions">
                <a class="btn btn-outline"
                   href="<?= site_url('users') ?>">
                    Cancel
                </a>

                <button class="btn btn-primary" type="submit">
                    Create User →
                </button>
            </div>
        </form>

    </div>
</div>
</body>
</html>