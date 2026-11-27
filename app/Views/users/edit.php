<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit User - Chan POS</title>
    <link rel="stylesheet" href="<?= base_url('css/chan-pos.css') ?>">
</head>
<body>

<?= view('layout/navbar') ?>

<div class="container">
    <div class="card form-card">

        <p class="eyebrow">Staff Management</p>
        <h1>Edit User</h1>
        <p class="subtitle">
            Update staff details and profile picture.
        </p>

        <?php $error = session()->getFlashdata('error'); ?>

        <?php if ($error): ?>
            <div class="error"><?= esc($error) ?></div>
        <?php endif; ?>

        <?php if ($validation->getErrors()): ?>
            <div class="error">
                <ul>
                    <?php foreach ($validation->getErrors() as $message): ?>
                        <li><?= esc($message) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('users/update/' . $user['id']) ?>"
              method="post"
              enctype="multipart/form-data">

            <?= csrf_field() ?>

            <label>Username</label>
            <input type="text"
                   name="username"
                   value="<?= esc(old('username', $user['username'])) ?>"
                   required>

            <label>Full Name</label>
            <input type="text"
                   name="full_name"
                   value="<?= esc(old('full_name', $user['full_name'])) ?>"
                   required>

            <label>Email</label>
            <input type="email"
                   name="email"
                   value="<?= esc(old('email', $user['email'])) ?>">

            <label>Profile Picture</label>
            <input type="file"
                   name="avatar"
                   accept=".jpg,.jpeg,.png">

            <p class="subtitle">
                JPG or PNG only. Maximum size: 2 MB.
            </p>

            <?php if (!empty($user['avatar'])): ?>
                <p><strong>Current Avatar</strong></p>

                <img class="avatar"
                     src="<?= base_url('uploads/avatars/' . rawurlencode($user['avatar'])) ?>"
                     alt="Current avatar"
                     style="width:110px;height:110px;max-width:110px;">
            <?php endif; ?>

            <div class="actions">
                <a class="btn btn-outline"
                   href="<?= site_url('users') ?>">
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