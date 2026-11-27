
<?= view('layout/header', [
    'title' => $user ? 'Edit Staff' : 'Add Staff'
]) ?>

<style>
    .staff-form-card {
        max-width: 700px;
        margin: 35px auto;
        padding: 35px;
        background: #1C1C20;
        border: 1px solid #303035;
        border-radius: 16px;
    }

    .staff-form-card h1 {
        color: white;
    }

    .staff-form-card p {
        color: #A6A6AE;
    }

    .staff-form-card label {
        display: block;
        color: white;
        margin: 20px 0 8px;
        font-weight: bold;
    }

    .staff-form-card input {
        width: 100%;
        padding: 13px;
        background: #29292F;
        color: white;
        border: 1px solid #44444C;
        border-radius: 9px;
    }

    .staff-form-card input:focus {
        outline: none;
        border-color: #E50914;
    }

    .staff-form-buttons {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        margin-top: 30px;
    }

    .staff-save-btn {
        background: #E50914;
        color: white;
        border: none;
        padding: 13px 22px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
    }

    .staff-cancel-btn {
        background: #35353C;
        color: white;
        text-decoration: none;
        padding: 13px 22px;
        border-radius: 8px;
    }

    .current-avatar {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #E50914;
        margin-top: 15px;
    }
</style>

<div class="staff-form-card">

    <p style="color:#FF525B;font-weight:bold;">
        STAFF MANAGEMENT
    </p>

    <h1>
        <?= $user ? 'Edit Staff Account' : 'Add New Staff' ?>
    </h1>

    <p>
        <?= $user
            ? 'Update staff information and profile picture.'
            : 'Create a new staff account.' ?>
    </p>

    <form
        action="<?= site_url('users/save' . ($user ? '/' . $user['id'] : '')) ?>"
        method="post"
        enctype="multipart/form-data"
    >

        <?= csrf_field() ?>

        <label>Username</label>

        <input
            type="text"
            name="username"
            value="<?= esc(old('username', $user['username'] ?? '')) ?>"
            maxlength="50"
            required
        >

        <label>Full Name</label>

        <input
            type="text"
            name="full_name"
            value="<?= esc(old('full_name', $user['full_name'] ?? '')) ?>"
            maxlength="100"
            required
        >

        <label>Email</label>

        <input
            type="email"
            name="email"
            value="<?= esc(old('email', $user['email'] ?? '')) ?>"
            maxlength="100"
        >

        <label>Password</label>

        <input
            type="password"
            name="password"
            minlength="8"
            autocomplete="new-password"
            <?= $user ? '' : 'required' ?>
        >

        <p>
            <?= $user
                ? 'Leave blank to keep the existing password.'
                : 'Password must have at least 8 characters.' ?>
        </p>

        <label>Profile Picture</label>

        <input
            type="file"
            name="avatar"
            accept=".jpg,.jpeg,.png"
        >

        <p>
            JPG or PNG only. Maximum file size: 2MB.
        </p>

        <?php if (!empty($user['avatar'])): ?>

            <p>Current Profile Picture:</p>

            <img
                class="current-avatar"
                src="<?= base_url('uploads/avatars/' . rawurlencode($user['avatar'])) ?>"
                alt="Current Staff Avatar"
            >

        <?php endif; ?>

        <div class="staff-form-buttons">

            <a
                class="staff-cancel-btn"
                href="<?= site_url('users') ?>"
            >
                Cancel
            </a>

            <button
                class="staff-save-btn"
                type="submit"
            >
                <?= $user ? 'Update Staff' : 'Create Staff' ?>
            </button>

        </div>

    </form>

</div>

<?= view('layout/footer') ?>
