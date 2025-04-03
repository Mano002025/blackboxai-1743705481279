<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!is_logged_in()) {
    redirect('login.php');
}

$pageTitle = 'Sales Management';
require_once '../includes/header.php';

// Sample data - in real implementation this would come from database
$sales = [
    [
        'id' => 1,
        'invoice_number' => 'INV-2023-1001',
        'customer_name' => 'Acme Corporation',
        'date' => '2023-06-15',
        'total' => 1499.97,
        'status' => 'paid'
    ],
    [
        'id' => 2,
        'invoice_number' => 'INV-2023-1002',
        'customer_name' => 'Globex Corp',
        'date' => '2023-06-14',
        'total' => 899.85,
        'status' => 'pending'
    ]
];
?>

<div class="space-y-6">
    <!-- Sales Orders Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Sales Orders</h2>
            <button onclick="openNewSaleModal()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                <i class="fas fa-plus mr-2"></i>New Sale
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($sales as $sale): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="#" class="text-blue-600 hover:text-blue-800"><?= $sale['invoice_number'] ?></a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?= $sale['customer_name'] ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= $sale['date'] ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">$<?= number_format($sale['total'], 2) ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?= $sale['status'] === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                <?= ucfirst($sale['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="viewSale(<?= $sale['id'] ?>)" 
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="printInvoice(<?= $sale['id'] ?>)" 
                                    class="text-purple-600 hover:text-purple-900 mr-3">
                                <i class="fas fa-print"></i>
                            </button>
                            <button onclick="deleteSale(<?= $sale['id'] ?>)" 
                                    class="text-red-600 hover:text-red-900 confirm-delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Sales Analytics Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium mb-4">Sales This Month</h3>
            <div class="flex items-end space-x-2 h-48">
                <div class="flex-1 bg-blue-100 rounded-t flex flex-col items-center">
                    <div class="bg-blue-600 w-full rounded-t" style="height: 30%"></div>
                    <span class="text-xs mt-1">Week 1</span>
                </div>
                <div class="flex-1 bg-blue-100 rounded-t flex flex-col items-center">
                    <div class="bg-blue-600 w-full rounded-t" style="height: 60%"></div>
                    <span class="text-xs mt-1">Week 2</span>
                </div>
                <div class="flex-1 bg-blue-100 rounded-t flex flex-col items-center">
                    <div class="bg-blue-600 w-full rounded-t" style="height: 80%"></div>
                    <span class="text-xs mt-1">Week 3</span>
                </div>
                <div class="flex-1 bg-blue-100 rounded-t flex flex-col items-center">
                    <div class="bg-blue-600 w-full rounded-t" style="height: 45%"></div>
                    <span class="text-xs mt-1">Week 4</span>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium mb-4">Top Selling Products</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>Premium Widget</span>
                        <span>45 units</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-green-600 h-2.5 rounded-full" style="width: 75%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>Standard Widget</span>
                        <span>32 units</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-green-600 h-2.5 rounded-full" style="width: 55%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>Deluxe Widget</span>
                        <span>18 units</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-green-600 h-2.5 rounded-full" style="width: 30%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Sale Modal (hidden by default) -->
<div id="newSaleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-3/4 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Create New Sale</h3>
            <button onclick="closeNewSaleModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Customer Selection -->
            <div class="md:col-span-1">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-medium mb-3">Customer Information</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Select Customer</label>
                            <select class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option>Select Customer</option>
                                <option>Acme Corporation</option>
                                <option>Globex Corp</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Payment Terms</label>
                            <select class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option>Due on Receipt</option>
                                <option>Net 15</option>
                                <option>Net 30</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Product Selection -->
            <div class="md:col-span-2">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-medium mb-3">Products</h4>
                    <div class="space-y-4">
                        <div class="flex space-x-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700">Product</label>
                                <select class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option>Select Product</option>
                                    <option>Premium Widget</option>
                                    <option>Standard Widget</option>
                                </select>
                            </div>
                            <div class="w-24">
                                <label class="block text-sm font-medium text-gray-700">Qty</label>
                                <input type="number" value="1" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="w-24">
                                <label class="block text-sm font-medium text-gray-700">Price</label>
                                <input type="number" step="0.01" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="flex items-end">
                                <button class="bg-red-500 text-white p-2 rounded hover:bg-red-600">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        
                        <button class="text-blue-600 hover:text-blue-800 text-sm">
                            <i class="fas fa-plus mr-1"></i>Add Another Product
                        </button>
                    </div>
                </div>
                
                <!-- Summary -->
                <div class="mt-4 bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-medium mb-3">Order Summary</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Subtotal:</span>
                            <span>$0.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tax (10%):</span>
                            <span>$0.00</span>
                        </div>
                        <div class="flex justify-between font-medium border-t pt-2 mt-2">
                            <span>Total:</span>
                            <span>$0.00</span>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            <i class="fas fa-save mr-2"></i>Create Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Sale modal functions
    function openNewSaleModal() {
        document.getElementById('newSaleModal').classList.remove('hidden');
    }

    function closeNewSaleModal() {
        document.getElementById('newSaleModal').classList.add('hidden');
    }

    function viewSale(id) {
        // In real implementation, this would show sale details
        console.log('Viewing sale with ID:', id);
    }

    function printInvoice(id) {
        // In real implementation, this would print the invoice
        console.log('Printing invoice for sale with ID:', id);
    }

    function deleteSale(id) {
        if (confirm('Are you sure you want to delete this sale?')) {
            // In real implementation, this would send delete request to server
            console.log('Deleting sale with ID:', id);
        }
    }
</script>

<?php require_once '../includes/footer.php'; ?>