
<?= view('layout/header', [
    'title' => 'Staff Management - IRONFUEL POS'
]) ?>

<style>
    .staff-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
    }

    .staff-header h1 {
        color: white;
    }

    .staff-card {
        background: #1C1C20;
        border: 1px solid #303035;
        border-radius: 15px;
        padding: 25px;
        overflow-x: auto;
    }

    .staff-table {
        width: 100%;
        border-collapse: collapse;
    }

    .staff-table th {
        background: #28282E;
        padding: 15px;
        color: white;
        text-align: left;
    }

    .staff-table td {
        padding: 15px;
        border-bottom: 1px solid #303035;
        color: #E5E5E5;
    }

    .staff-avatar {
        width: 65px;
        height: 65px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #E50914;
    }

    .staff-placeholder {
        width: 65px;
        height: 65px;
        background: #35353C;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #AAA;
        font-size: 12px;
    }

    .staff-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .staff-actions form {
        margin: 0;
    }

    .staff-add-btn {
        background: #E50914;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
    }

    .staff-edit-btn {
        background: #35353C;
        color: white;
        padding: 9px 15px;
        border-radius: 8px;
        text-decoration: none;
    }

    .staff-delete-btn {
        background: #76272D;
        color: white;
        border: none;
        padding: 9px 15px;
        border-radius: 8px;
        cursor: pointer;
    }

    @media(max-width:700px) {
        .staff-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="staff-header">

    <div>
        <p style="color:#FF525B;font-weight:bold;">
            STAFF MANAGEMENT
        </p>

        <h1>Staff Accounts</h1>

        <p>
            Manage staff accounts and profile pictures.
        </p>
    </div>

    <a class="staff-add-btn"
       href="<?= site_url('users/new') ?>">
        + Add Staff
    </a>

</div>

<div class="staff-card">

    <table class="staff-table">

        <thead>
            <tr>
                <th>Avatar</th>
                <th>ID</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

            <?php if (!empty($users)): ?>

                <?php foreach ($users as $user): ?>

                    <tr>
                        <td>
                            <?php if (!empty($user['avatar'])): ?>

                                <img
                                    class="staff-avatar"
                                    src="<?= base_url('uploads/avatars/' . rawurlencode($user['avatar'])) ?>"
                                    alt="Staff Avatar"
                                >

                            <?php else: ?>

                                <div class="staff-placeholder">
                                    Admin
                                </div>

                            <?php endif; ?>
                        </td>

                        <td><?= esc($user['id']) ?></td>

                        <td>
                            <strong>
                                <?= esc($user['username']) ?>
                            </strong>
                        </td>

                        <td><?= esc($user['full_name']) ?></td>

                        <td><?= esc($user['email'] ?? '') ?></td>

                        <td><?= esc($user['created_at']) ?></td>

                        <td>
                            <div class="staff-actions">

                                <a
                                    class="staff-edit-btn"
                                    href="<?= site_url('users/edit/' . $user['id']) ?>"
                                >
                                    Edit
                                </a>

                                <?php if (
                                    (int) session()->get('user_id') !==
                                    (int) $user['id']
                                ): ?>

                                    <form
                                        action="<?= site_url('users/delete/' . $user['id']) ?>"
                                        method="post"
                                        onsubmit="return confirm('Delete this staff account?')"
                                    >
                                        <?= csrf_field() ?>

                                        <button
                                            class="staff-delete-btn"
                                            type="submit"
                                        >
                                            Delete
                                        </button>
                                    </form>

                                <?php endif; ?>

                            </div>
                        </td>
                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="7" style="text-align:center;">
                        No staff accounts found.
                    </td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</div>

<?= view('layout/footer') ?>
