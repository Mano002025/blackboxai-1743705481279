<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!is_logged_in()) {
    redirect('login.php');
}

$pageTitle = 'Dashboard';
require_once '../includes/header.php';
?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Customers Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500">Total Customers</p>
                <h3 class="text-2xl font-bold">1,234</h3>
            </div>
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-users text-xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <span class="text-green-500 text-sm font-medium">+12.5% from last month</span>
        </div>
    </div>

    <!-- Total Sales Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500">Total Sales</p>
                <h3 class="text-2xl font-bold">$56,789</h3>
            </div>
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-shopping-cart text-xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <span class="text-green-500 text-sm font-medium">+8.2% from last month</span>
        </div>
    </div>

    <!-- Inventory Value Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500">Inventory Value</p>
                <h3 class="text-2xl font-bold">$234,567</h3>
            </div>
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <i class="fas fa-boxes text-xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <span class="text-red-500 text-sm font-medium">-3.1% from last month</span>
        </div>
    </div>

    <!-- Treasury Balance Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500">Treasury Balance</p>
                <h3 class="text-2xl font-bold">$89,123</h3>
            </div>
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <i class="fas fa-wallet text-xl"></i>
            </div>
        </div>
        <div class="mt-4">
            <span class="text-green-500 text-sm font-medium">+5.7% from last month</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Sales -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Recent Sales</h2>
            <a href="sales.php" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">INV-2023-001</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Acme Corp</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">$1,234.00</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2 hours ago</td>
                    </tr>
                    <!-- More rows would be dynamically generated from database -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Low Stock Items -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Low Stock Items</h2>
            <a href="products.php" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
        </div>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-red-100 rounded-full text-red-600">
                        <i class="fas fa-box"></i>
                    </div>
                    <div>
                        <h4 class="font-medium">Premium Widget</h4>
                        <p class="text-sm text-gray-500">Warehouse A</p>
                    </div>
                </div>
                <span class="text-red-600 font-medium">3 left</span>
            </div>
            <!-- More low stock items would be dynamically generated from database -->
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>