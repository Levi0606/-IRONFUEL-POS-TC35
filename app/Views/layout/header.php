<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'IRONFUEL POS') ?></title>
    <link rel="stylesheet"
          href="<?= base_url('css/ironfuel.css') ?>">
</head>
<body>

<nav class="navbar">
    <a class="brand" href="<?= site_url('dashboard') ?>">
        IRONFUEL <span>POS</span>
    </a>

    <a href="<?= site_url('dashboard') ?>">Dashboard</a>
    <a href="<?= site_url('products') ?>">Products</a>
    <a href="<?= site_url('customers') ?>">Customers</a>
    <a href="<?= site_url('users') ?>">Staff</a>
    <a href="<?= site_url('sales/new') ?>">Record Sale</a>
    <a href="<?= site_url('sales') ?>">Sales History</a>

    <form class="logout-form"
          action="<?= site_url('logout') ?>"
          method="post">
        <?= csrf_field() ?>
        <button class="btn btn-red btn-small" type="submit">
            Logout
        </button>
    </form>
</nav>

<main class="container">

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= esc(session()->getFlashdata('success')) ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error">
        <?= esc(session()->getFlashdata('error')) ?>
    </div>
<?php endif; ?>

<?php $errors = session()->getFlashdata('errors'); ?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <ul>
            <?php foreach ($errors as $message): ?>
                <li><?= esc($message) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>