<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Customers extends BaseController
{
    // DISPLAY ALL CUSTOMERS
    public function index()
    {
        $model = new CustomerModel();

        return view('customers/index', [
            'customers' => $model->orderBy('id', 'DESC')->findAll()
        ]);
    }

    // SHOW ADD OR EDIT FORM
    public function form($id = null)
    {
        $model = new CustomerModel();

        $customer = null;

        if ($id !== null) {
            $customer = $model->find($id);

            if (!$customer) {
                throw PageNotFoundException::forPageNotFound(
                    'Customer not found.'
                );
            }
        }

        return view('customers/form', [
            'customer' => $customer
        ]);
    }

    // SAVE NEW CUSTOMER OR UPDATE EXISTING CUSTOMER
    public function save($id = null)
    {
        $model = new CustomerModel();

        if ($id !== null && !$model->find($id)) {
            throw PageNotFoundException::forPageNotFound(
                'Customer not found.'
            );
        }

        $rules = [
            'full_name' => 'required|max_length[100]',
            'email' => 'required|valid_email|max_length[100]',
            'phone' => 'permit_empty|max_length[20]'
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'full_name' => trim(
                $this->request->getPost('full_name')
            ),
            'email' => trim(
                $this->request->getPost('email')
            ),
            'phone' => trim(
                $this->request->getPost('phone') ?? ''
            )
        ];

        try {
            if ($id !== null) {
                $saved = $model->update($id, $data);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $saved = $model->insert($data);
            }

            if ($saved === false) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Unable to save customer.');
            }

        } catch (\Throwable $e) {
            log_message(
                'error',
                'Customer save error: ' . $e->getMessage()
            );

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Database error while saving customer.');
        }

        return redirect()
            ->to('/customers')
            ->with('success', 'Customer saved successfully!');
    }

    // DELETE CUSTOMER
    public function delete($id)
    {
        $model = new CustomerModel();

        $customer = $model->find($id);

        if (!$customer) {
            throw PageNotFoundException::forPageNotFound(
                'Customer not found.'
            );
        }

        try {
            // Preserve sales history by clearing customer_id
            // for transactions belonging to this customer.
            $db = db_connect();
            $db->transBegin();

            $db->table('sales')
                ->where('customer_id', $id)
                ->update(['customer_id' => null]);

            $deleted = $model->delete($id);

            if ($deleted === false || $db->transStatus() === false) {
                throw new \RuntimeException(
                    'Unable to delete customer.'
                );
            }

            $db->transCommit();

        } catch (\Throwable $e) {
            if (isset($db)) {
                $db->transRollback();
            }

            log_message(
                'error',
                'Customer delete error: ' . $e->getMessage()
            );

            return redirect()
                ->to('/customers')
                ->with('error', 'Unable to delete customer.');
        }

        return redirect()
            ->to('/customers')
            ->with('success', 'Customer deleted successfully!');
    }
}