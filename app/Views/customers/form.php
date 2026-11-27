
<?= view('layout/header', [
    'title' => $customer ? 'Edit Customer' : 'Add Customer'
]) ?>

<div class="card form-card">

    <p class="accent">CUSTOMER MANAGEMENT</p>

    <h1>
        <?= $customer ? 'Edit Customer' : 'Add New Customer' ?>
    </h1>

    <p>
        <?= $customer
            ? 'Update the selected customer information.'
            : 'Enter the information for a new customer.' ?>
    </p>

    <form
        action="<?= site_url('customers/save' . ($customer ? '/' . $customer['id'] : '')) ?>"
        method="post"
    >

        <?= csrf_field() ?>

        <label for="full_name">Full Name</label>

        <input
            type="text"
            id="full_name"
            name="full_name"
            value="<?= esc(old('full_name', $customer['full_name'] ?? '')) ?>"
            placeholder="Enter customer full name"
            maxlength="100"
            required
        >

        <label for="email">Email Address</label>

        <input
            type="email"
            id="email"
            name="email"
            value="<?= esc(old('email', $customer['email'] ?? '')) ?>"
            placeholder="Enter customer email"
            maxlength="100"
            required
        >

        <label for="phone">Phone Number</label>

        <input
            type="text"
            id="phone"
            name="phone"
            value="<?= esc(old('phone', $customer['phone'] ?? '')) ?>"
            placeholder="Enter phone number"
            maxlength="20"
        >

        <div class="form-actions">

            <a
                href="<?= site_url('customers') ?>"
                class="btn btn-outline"
            >
                Cancel
            </a>

            <button
                class="btn btn-red"
                type="submit"
            >
                <?= $customer ? 'Update Customer' : 'Save Customer' ?>
            </button>

        </div>

    </form>

</div>

<?= view('layout/footer') ?>
