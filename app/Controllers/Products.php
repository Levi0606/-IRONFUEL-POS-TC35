<?php

namespace App\Controllers;

use App\Models\ProductModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Products extends BaseController
{
    // DISPLAY ALL PRODUCTS
    public function index()
    {
        $model = new ProductModel();

        return view('products/index', [
            'products' => $model
                ->orderBy('id', 'DESC')
                ->findAll()
        ]);
    }

    // SHOW ADD OR EDIT PRODUCT FORM
    public function form($id = null)
    {
        $model = new ProductModel();

        $product = null;

        if ($id !== null) {
            $product = $model->find($id);

            if (!$product) {
                throw PageNotFoundException::forPageNotFound(
                    'Product not found.'
                );
            }
        }

        return view('products/form', [
            'product' => $product
        ]);
    }

    // CREATE OR UPDATE PRODUCT
    public function save($id = null)
    {
        $model = new ProductModel();

        $product = null;

        if ($id !== null) {
            $product = $model->find($id);

            if (!$product) {
                throw PageNotFoundException::forPageNotFound(
                    'Product not found.'
                );
            }
        }

        // VALIDATION RULES
        $rules = [
            'name' => [
                'label' => 'Product Name',
                'rules' => 'required|max_length[100]'
            ],
            'price' => [
                'label' => 'Price',
                'rules' => 'required|decimal|greater_than_equal_to[0]'
            ],
            'stock_quantity' => [
                'label' => 'Stock Quantity',
                'rules' => 'required|integer|greater_than_equal_to[0]'
            ]
        ];

        $image = $this->request->getFile('image');

        // VALIDATE IMAGE WHEN PROVIDED
        if (
            $image !== null &&
            $image->getError() !== UPLOAD_ERR_NO_FILE
        ) {
            $rules['image'] = [
                'label' => 'Product Image',
                'rules' =>
                    'uploaded[image]|' .
                    'is_image[image]|' .
                    'mime_in[image,image/jpeg,image/png]|' .
                    'max_size[image,2048]'
            ];
        }

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // PREPARE PRODUCT DATA
        $data = [
            'name' => trim(
                $this->request->getPost('name')
            ),
            'price' => $this->request->getPost('price'),
            'stock_quantity' => (int)
                $this->request->getPost('stock_quantity')
        ];

        if ($id === null) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        $newPath = null;
        $newName = null;

        // PROCESS PRODUCT IMAGE
        if (
            $image !== null &&
            $image->isValid() &&
            !$image->hasMoved()
        ) {
            $folder = FCPATH . 'uploads/products/';

            if (!is_dir($folder)) {
                if (
                    !mkdir($folder, 0755, true) &&
                    !is_dir($folder)
                ) {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with(
                            'error',
                            'Unable to create product image folder.'
                        );
                }
            }

            // RANDOM FILENAME
            $newName = bin2hex(random_bytes(16)) . '.jpg';
            $newPath = $folder . $newName;

            try {
                // RESIZE IMAGE TO 500x500
                service('image')
                    ->withFile($image->getTempName())
                    ->fit(500, 500)
                    ->convert(IMAGETYPE_JPEG)
                    ->save($newPath, 85);

                $data['image'] = $newName;

            } catch (\Throwable $e) {
                log_message(
                    'error',
                    'Product image processing failed: ' .
                    $e->getMessage()
                );

                if (is_file($newPath)) {
                    unlink($newPath);
                }

                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Image processing failed. Check PHP GD extension.'
                    );
            }
        }

        // SAVE PRODUCT TO DATABASE
        try {
            if ($id !== null) {
                $saved = $model->update($id, $data);
            } else {
                $saved = $model->insert($data);
            }

            if ($saved === false) {
                throw new \RuntimeException(
                    'Unable to save product.'
                );
            }

        } catch (\Throwable $e) {
            log_message(
                'error',
                'Product database error: ' . $e->getMessage()
            );

            if ($newPath !== null && is_file($newPath)) {
                unlink($newPath);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to save product. Check database.'
                );
        }

        // DELETE PREVIOUS IMAGE AFTER SUCCESSFUL UPDATE
        if (
            $id !== null &&
            $newName !== null &&
            !empty($product['image'])
        ) {
            $oldPath = FCPATH .
                'uploads/products/' .
                basename($product['image']);

            if (is_file($oldPath)) {
                unlink($oldPath);
            }
        }

        return redirect()
            ->to('/products')
            ->with(
                'success',
                'Product saved successfully!'
            );
    }

    // DELETE PRODUCT
    public function delete($id)
    {
        $model = new ProductModel();

        $product = $model->find($id);

        if (!$product) {
            throw PageNotFoundException::forPageNotFound(
                'Product not found.'
            );
        }

        // CHECK IF PRODUCT HAS SALES HISTORY
        $hasSales = db_connect()
            ->table('sales')
            ->where('product_id', $id)
            ->countAllResults() > 0;

        if ($hasSales) {
            return redirect()
                ->to('/products')
                ->with(
                    'error',
                    'This product cannot be deleted because it has sales history.'
                );
        }

        // DELETE FROM DATABASE
        try {
            $deleted = $model->delete($id);

            if ($deleted === false) {
                throw new \RuntimeException(
                    'Product deletion failed.'
                );
            }

        } catch (\Throwable $e) {
            log_message(
                'error',
                'Product deletion error: ' . $e->getMessage()
            );

            return redirect()
                ->to('/products')
                ->with(
                    'error',
                    'Unable to delete product.'
                );
        }

        // DELETE PRODUCT IMAGE
        if (!empty($product['image'])) {
            $imagePath = FCPATH .
                'uploads/products/' .
                basename($product['image']);

            if (is_file($imagePath)) {
                unlink($imagePath);
            }
        }

        return redirect()
            ->to('/products')
            ->with(
                'success',
                'Product deleted successfully!'
            );
    }
}
