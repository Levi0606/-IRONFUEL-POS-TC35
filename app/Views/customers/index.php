
<?= view('layout/header', ['title' => 'Customer Management']) ?>

<div class="page-header">
    <div>
        <p class="accent">CUSTOMER MANAGEMENT</p>
        <h1>Customer Accounts</h1>
        <p>Manage customer information in IRONFUEL POS.</p>
    </div>

    <a class="btn btn-red" href="<?= site_url('customers/new') ?>">
        + Add Customer
    </a>
</div>

<div class="card table-wrap">

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($customers)): ?>

                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= esc($customer['id']) ?></td>

                        <td>
                            <strong>
                                <?= esc($customer['full_name']) ?>
                            </strong>
                        </td>

                        <td><?= esc($customer['email']) ?></td>

                        <td><?= esc($customer['phone'] ?? '') ?></td>

                        <td><?= esc($customer['created_at']) ?></td>

                        <td>
                            <div class="row-actions">

                                <a
                                    class="btn btn-dark btn-small"
                                    href="<?= site_url('customers/edit/' . $customer['id']) ?>"
                                >
                                    Edit
                                </a>

                                <form
                                    action="<?= site_url('customers/delete/' . $customer['id']) ?>"
                                    method="post"
                                    onsubmit="return confirm('Are you sure you want to delete this customer?')"
                                >
                                    <?= csrf_field() ?>

                                    <button
                                        class="btn btn-danger btn-small"
                                        type="submit"
                                    >
                                        Delete
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="6" style="text-align:center;">
                        No customer records found.
                    </td>
                </tr>

            <?php endif; ?>
        </tbody>
    </table>

</div>

<?= view('layout/footer') ?>
