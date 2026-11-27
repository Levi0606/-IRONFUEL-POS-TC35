<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff Login - IRONFUEL POS</title>
    <link rel="stylesheet"
          href="<?= base_url('css/ironfuel.css') ?>">
</head>
<body>

<div class="login-screen">
    <div class="card login-box">

        <h2>IRONFUEL <span class="accent">POS</span></h2>
        <p>WHEY PROTEIN MANAGEMENT</p>

        <h1>Staff Login</h1>
        <p>Sign in to manage products, inventory, and sales.</p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('login') ?>" method="post">
            <?= csrf_field() ?>

            <label>Username</label>
            <input type="text" name="username"
                   value="<?= esc(old('username')) ?>"
                   autocomplete="username" required>

            <label>Password</label>
            <input type="password" name="password"
                   autocomplete="current-password" required>

            <button class="btn btn-red"
                    style="width:100%;margin-top:25px;"
                    type="submit">
                Sign In
            </button>
        </form>

    </div>
</div>

</body>
</html>