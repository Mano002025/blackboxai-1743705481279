<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!is_logged_in()) {
    redirect('login.php');
}

$pageTitle = 'Product Management';
require_once '../includes/header.php';

// Sample data - in real implementation this would come from database
$products = [
    [
        'id' => 1,
        'name' => 'Premium Widget',
        'description' => 'High-quality widget for professional use',
        'price' => 49.99,
        'cost' => 25.00,
        'stock' => 42,
        'warehouse' => 'Main Warehouse'
    ],
    [
        'id' => 2,
        'name' => 'Standard Widget',
        'description' => 'Basic widget for everyday use',
        'price' => 29.99,
        'cost' => 15.00,
        'stock' => 125,
        'warehouse' => 'Secondary Storage'
    ]
];
?>

<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Product Management</h2>
        <button onclick="openProductModal()" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            <i class="fas fa-plus mr-2"></i>Add Product
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Warehouse</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($products as $product): ?>
                <tr>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                <i class="fas fa-box text-purple-600"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900"><?= $product['name'] ?></div>
                                <div class="text-sm text-gray-500"><?= $product['description'] ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">$<?= number_format($product['price'], 2) ?></div>
                        <div class="text-sm text-gray-500">Cost: $<?= number_format($product['cost'], 2) ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?= $product['stock'] < 10 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' ?>">
                            <?= $product['stock'] ?> units
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= $product['warehouse'] ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="editProduct(<?= $product['id'] ?>)" 
                                class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteProduct(<?= $product['id'] ?>)" 
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

<!-- Product Modal (hidden by default) -->
<div id="productModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold" id="productModalTitle">Add New Product</h3>
            <button onclick="closeProductModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="productForm">
            <input type="hidden" id="productId">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label for="productName" class="block text-sm font-medium text-gray-700">Name*</label>
                    <input type="text" id="productName" name="name" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="productDescription" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="productDescription" name="description" rows="3"
                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="productPrice" class="block text-sm font-medium text-gray-700">Price*</label>
                        <input type="number" step="0.01" id="productPrice" name="price" required
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="productCost" class="block text-sm font-medium text-gray-700">Cost*</label>
                        <input type="number" step="0.01" id="productCost" name="cost" required
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="productStock" class="block text-sm font-medium text-gray-700">Initial Stock*</label>
                        <input type="number" id="productStock" name="stock" required
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="productWarehouse" class="block text-sm font-medium text-gray-700">Warehouse*</label>
                        <select id="productWarehouse" name="warehouse" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Warehouse</option>
                            <option value="1">Main Warehouse</option>
                            <option value="2">Secondary Storage</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeProductModal()"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    Save Product
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Product modal functions
    function openProductModal(product = null) {
        const modal = document.getElementById('productModal');
        const form = document.getElementById('productForm');
        const modalTitle = document.getElementById('productModalTitle');
        
        if (product) {
            modalTitle.textContent = 'Edit Product';
            document.getElementById('productId').value = product.id;
            document.getElementById('productName').value = product.name;
            document.getElementById('productDescription').value = product.description;
            document.getElementById('productPrice').value = product.price;
            document.getElementById('productCost').value = product.cost;
            document.getElementById('productStock').value = product.stock;
            document.getElementById('productWarehouse').value = product.warehouse_id;
        } else {
            modalTitle.textContent = 'Add New Product';
            form.reset();
        }
        
        modal.classList.remove('hidden');
    }

    function closeProductModal() {
        document.getElementById('productModal').classList.add('hidden');
    }

    function editProduct(id) {
        // In real implementation, this would fetch product data from server
        const product = {
            id: id,
            name: 'Sample Product',
            description: 'Sample description',
            price: 19.99,
            cost: 10.00,
            stock: 50,
            warehouse_id: 1
        };
        openProductModal(product);
    }

    function deleteProduct(id) {
        if (confirm('Are you sure you want to delete this product?')) {
            // In real implementation, this would send delete request to server
            console.log('Deleting product with ID:', id);
        }
    }

    // Form submission
    document.getElementById('productForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // In real implementation, this would send data to server
        console.log('Product form submitted:', {
            id: document.getElementById('productId').value,
            name: document.getElementById('productName').value,
            description: document.getElementById('productDescription').value,
            price: document.getElementById('productPrice').value,
            cost: document.getElementById('productCost').value,
            stock: document.getElementById('productStock').value,
            warehouse: document.getElementById('productWarehouse').value
        });
        closeProductModal();
    });
</script>

<?php require_once '../includes/footer.php'; ?>