<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function index()
    {
        $faqs = [
            [
                'question' => 'How do I add a new product?',
                'answer' => 'Go to Products > Add Product, fill in the required information including name, price, description, and upload images.',
                'category' => 'Products'
            ],
            [
                'question' => 'How do I process an order?',
                'answer' => 'Navigate to Orders, select the order, verify payment proof if needed, and update the status to Processing, Shipped, or Delivered.',
                'category' => 'Orders'
            ],
            [
                'question' => 'How do I manage user accounts?',
                'answer' => 'Go to Users section to view all customer accounts, their order history, and manage their access.',
                'category' => 'Users'
            ],
            [
                'question' => 'How do I view sales reports?',
                'answer' => 'Access Reports section for detailed analytics including revenue, order statistics, and payment method breakdowns.',
                'category' => 'Reports'
            ],
            [
                'question' => 'How do I update my admin password?',
                'answer' => 'Go to Settings > Security and use the Change Password form to update your credentials.',
                'category' => 'Security'
            ]
        ];

        $quickLinks = [
            ['title' => 'Add New Product', 'url' => route('admin.products.create'), 'icon' => 'plus'],
            ['title' => 'View Orders', 'url' => route('admin.orders.index'), 'icon' => 'shopping-bag'],
            ['title' => 'User Management', 'url' => route('admin.users.index'), 'icon' => 'users'],
            ['title' => 'Sales Reports', 'url' => route('admin.reports'), 'icon' => 'chart-bar'],
            ['title' => 'System Settings', 'url' => route('admin.settings'), 'icon' => 'cog']
        ];

        return view('admin.help.index', compact('faqs', 'quickLinks'));
    }
}