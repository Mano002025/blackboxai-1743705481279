<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!is_logged_in()) {
    redirect('login.php');
}

$pageTitle = 'Warehouse Management';
require_once '../includes/header.php';

// Sample data - in real implementation this would come from database
$warehouses = [
    [
        'id' => 1,
        'name' => 'Main Warehouse',
        'location' => '123 Industrial Park, New York',
        'capacity' => 1000,
        'current_stock' => 750
    ],
    [
        'id' => 2,
        'name' => 'Secondary Storage',
        'location' => '456 Commerce Ave, New Jersey',
        'capacity' => 500,
        'current_stock' => 320
    ]
];
?>

<div class="space-y-6">
    <!-- Warehouse List Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Warehouse Locations</h2>
            <button onclick="openWarehouseModal()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                <i class="fas fa-plus mr-2"></i>Add Warehouse
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($warehouses as $warehouse): ?>
            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center mb-3">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-3">
                        <i class="fas fa-warehouse text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold"><?= $warehouse['name'] ?></h3>
                </div>
                <div class="space-y-2 text-sm">
                    <p class="text-gray-600"><i class="fas fa-map-marker-alt mr-2 text-blue-500"></i><?= $warehouse['location'] ?></p>
                    <div class="pt-2">
                        <div class="flex justify-between text-sm mb-1">
                            <span>Capacity Utilization</span>
                            <span><?= round(($warehouse['current_stock'] / $warehouse['capacity']) * 100) ?>%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" 
                                 style="width: <?= ($warehouse['current_stock'] / $warehouse['capacity']) * 100 ?>%"></div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex space-x-2">
                    <button onclick="editWarehouse(<?= $warehouse['id'] ?>)" 
                            class="text-blue-600 hover:text-blue-800 px-3 py-1 border border-blue-200 rounded">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>
                    <button onclick="deleteWarehouse(<?= $warehouse['id'] ?>)" 
                            class="text-red-600 hover:text-red-800 px-3 py-1 border border-red-200 rounded confirm-delete">
                        <i class="fas fa-trash mr-1"></i>Delete
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Transfer Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Inventory Transfers</h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">To</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                    <i class="fas fa-box text-purple-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">Premium Widget</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            Main Warehouse
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            Secondary Storage
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            25 units
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            2023-06-15
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Completed
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <h3 class="text-lg font-medium mb-3">New Transfer</h3>
            <form class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Product</label>
                    <select class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option>Select Product</option>
                        <option>Premium Widget</option>
                        <option>Standard Widget</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">From Warehouse</label>
                    <select class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option>Select Warehouse</option>
                        <option>Main Warehouse</option>
                        <option>Secondary Storage</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">To Warehouse</label>
                    <select class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option>Select Warehouse</option>
                        <option>Main Warehouse</option>
                        <option>Secondary Storage</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input type="number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        <i class="fas fa-exchange-alt mr-2"></i>Transfer Inventory
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Warehouse Modal (hidden by default) -->
<div id="warehouseModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold" id="warehouseModalTitle">Add New Warehouse</h3>
            <button onclick="closeWarehouseModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="warehouseForm">
            <input type="hidden" id="warehouseId">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label for="warehouseName" class="block text-sm font-medium text-gray-700">Name*</label>
                    <input type="text" id="warehouseName" name="name" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="warehouseLocation" class="block text-sm font-medium text-gray-700">Location*</label>
                    <input type="text" id="warehouseLocation" name="location" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="warehouseCapacity" class="block text-sm font-medium text-gray-700">Capacity (units)*</label>
                    <input type="number" id="warehouseCapacity" name="capacity" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeWarehouseModal()"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    Save Warehouse
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Warehouse modal functions
    function openWarehouseModal(warehouse = null) {
        const modal = document.getElementById('warehouseModal');
        const form = document.getElementById('warehouseForm');
        const modalTitle = document.getElementById('warehouseModalTitle');
        
        if (warehouse) {
            modalTitle.textContent = 'Edit Warehouse';
            document.getElementById('warehouseId').value = warehouse.id;
            document.getElementById('warehouseName').value = warehouse.name;
            document.getElementById('warehouseLocation').value = warehouse.location;
            document.getElementById('warehouseCapacity').value = warehouse.capacity;
        } else {
            modalTitle.textContent = 'Add New Warehouse';
            form.reset();
        }
        
        modal.classList.remove('hidden');
    }

    function closeWarehouseModal() {
        document.getElementById('warehouseModal').classList.add('hidden');
    }

    function editWarehouse(id) {
        // In real implementation, this would fetch warehouse data from server
        const warehouse = {
            id: id,
            name: 'Sample Warehouse',
            location: '123 Sample St',
            capacity: 1000
        };
        openWarehouseModal(warehouse);
    }

    function deleteWarehouse(id) {
        if (confirm('Are you sure you want to delete this warehouse?')) {
            // In real implementation, this would send delete request to server
            console.log('Deleting warehouse with ID:', id);
        }
    }

    // Form submission
    document.getElementById('warehouseForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // In real implementation, this would send data to server
        console.log('Warehouse form submitted:', {
            id: document.getElementById('warehouseId').value,
            name: document.getElementById('warehouseName').value,
            location: document.getElementById('warehouseLocation').value,
            capacity: document.getElementById('warehouseCapacity').value
        });
        closeWarehouseModal();
    });
</script>

<?php require_once '../includes/footer.php'; ?>