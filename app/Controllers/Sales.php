<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CustomerModel;
use App\Models\SaleModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Sales extends BaseController
{
    // DISPLAY SALES HISTORY
    public function index()
    {
        $db = db_connect();

        $sales = $db->table('sales s')
            ->select('
                s.id,
                s.quantity,
                s.total_price,
                s.created_at,
                p.name AS product_name,
                c.full_name AS customer_name,
                u.full_name AS staff_name
            ')
            ->join(
                'products p',
                'p.id = s.product_id'
            )
            ->join(
                'customers c',
                'c.id = s.customer_id',
                'left'
            )
            ->join(
                'users u',
                'u.id = s.sold_by'
            )
            ->orderBy('s.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return view('sales/index', [
            'sales' => $sales
        ]);
    }

    // SHOW RECORD SALE FORM
    public function newSale()
    {
        $productModel = new ProductModel();
        $customerModel = new CustomerModel();

        return view('sales/new', [
            'products' => $productModel
                ->orderBy('name', 'ASC')
                ->findAll(),

            'customers' => $customerModel
                ->orderBy('full_name', 'ASC')
                ->findAll()
        ]);
    }

    // RECORD A SALE AND UPDATE STOCK
    public function record()
    {
        $rules = [
            'product_id' => 'required|integer|greater_than[0]',
            'customer_id' => 'permit_empty|integer|greater_than[0]',
            'quantity' => 'required|integer|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->to('/sales/new')
                ->withInput()
                ->with(
                    'errors',
                    $this->validator->getErrors()
                );
        }

        $productId = (int) $this->request->getPost('product_id');
        $quantity = (int) $this->request->getPost('quantity');

        $customerInput = $this->request->getPost('customer_id');

        $customerId = (
            $customerInput === null ||
            $customerInput === ''
        )
            ? null
            : (int) $customerInput;

        $staffId = (int) session()->get('user_id');

        $db = db_connect();

        // VERIFY STAFF SESSION
        if ($staffId <= 0) {
            return redirect()->to('/login');
        }

        // VERIFY CUSTOMER IF SELECTED
        if ($customerId !== null) {
            $customerExists = $db->table('customers')
                ->where('id', $customerId)
                ->countAllResults();

            if (!$customerExists) {
                return redirect()
                    ->to('/sales/new')
                    ->withInput()
                    ->with(
                        'error',
                        'Selected customer does not exist.'
                    );
            }
        }

        // START DATABASE TRANSACTION
        $db->transBegin();

        try {
            // LOCK PRODUCT ROW UNTIL TRANSACTION ENDS
            $product = $db->query(
                'SELECT id, name, price, stock_quantity
                 FROM products
                 WHERE id = ?
                 FOR UPDATE',
                [$productId]
            )->getRowArray();

            if (!$product) {
                $db->transRollback();

                return redirect()
                    ->to('/sales/new')
                    ->withInput()
                    ->with(
                        'error',
                        'Selected product does not exist.'
                    );
            }

            // CHECK AVAILABLE STOCK
            if ((int) $product['stock_quantity'] < $quantity) {
                $db->transRollback();

                return redirect()
                    ->to('/sales/new')
                    ->withInput()
                    ->with(
                        'error',
                        'Insufficient stock! Only ' .
                        $product['stock_quantity'] .
                        ' unit(s) available.'
                    );
            }

            // CALCULATE TOTAL PRICE
            $unitPriceCents = (int) round(
                ((float) $product['price']) * 100
            );

            $totalCents = $unitPriceCents * $quantity;

            $totalPrice = number_format(
                $totalCents / 100,
                2,
                '.',
                ''
            );

            // DEDUCT PRODUCT STOCK
            $db->query(
                'UPDATE products
                 SET stock_quantity = stock_quantity - ?
                 WHERE id = ?
                   AND stock_quantity >= ?',
                [$quantity, $productId, $quantity]
            );

            if ($db->affectedRows() !== 1) {
                throw new \RuntimeException(
                    'Stock could not be updated.'
                );
            }

            // SAVE SALES TRANSACTION
            $saleModel = new SaleModel();

            $saleId = $saleModel->insert([
                'product_id' => $productId,
                'customer_id' => $customerId,
                'sold_by' => $staffId,
                'quantity' => $quantity,
                'total_price' => $totalPrice,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            if (
                $saleId === false ||
                $db->transStatus() === false
            ) {
                throw new \RuntimeException(
                    'Sale could not be saved.'
                );
            }

            // COMMIT TRANSACTION
            if (!$db->transCommit()) {
                throw new \RuntimeException(
                    'Transaction commit failed.'
                );
            }

            return redirect()
                ->to('/sales')
                ->with(
                    'success',
                    'Sale recorded successfully! Stock updated.'
                );

        } catch (\Throwable $e) {
            $db->transRollback();

            log_message(
                'error',
                'Sale error: ' . $e->getMessage()
            );

            return redirect()
                ->to('/sales/new')
                ->withInput()
                ->with(
                    'error',
                    'Unable to complete sale. Please try again.'
                );
        }
    }
}
