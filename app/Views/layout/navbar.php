<nav>
    <span class="logo">Chan POS</span>

    <a href="<?= site_url('overview') ?>">Overview</a>
    <a href="<?= site_url('customers') ?>">Customers</a>
    <a href="<?= site_url('users') ?>">Users</a>
    <a href="<?= site_url('about') ?>">About</a>

    <?php if (session()->get('isLoggedIn') === true): ?>

        <form class="logout-form"
              action="<?= site_url('logout') ?>"
              method="post">

            <?= csrf_field() ?>

            <button class="logout-button" type="submit">
                Logout →
            </button>
        </form>

    <?php else: ?>

        <a href="<?= site_url('login') ?>">Login</a>

    <?php endif; ?>
</nav>