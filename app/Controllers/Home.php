<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CustomerModel;
use App\Models\UserModel;
use App\Models\SaleModel;

class Home extends BaseController
{
    public function index()
    {
        $products = new ProductModel();
        $customers = new CustomerModel();
        $users = new UserModel();
        $sales = new SaleModel();

        return view('home/index', [
            'productCount' => $products->countAllResults(),
            'customerCount' => $customers->countAllResults(),
            'userCount' => $users->countAllResults(),
            'stockCount' => $products->selectSum('stock_quantity')
                ->first()['stock_quantity'] ?? 0,
            'revenue' => $sales->selectSum('total_price')
                ->first()['total_price'] ?? 0,
            'saleCount' => $sales->countAllResults(),
            'recentProducts' => $products
                ->orderBy('id', 'DESC')->findAll(5)
        ]);
    }
}