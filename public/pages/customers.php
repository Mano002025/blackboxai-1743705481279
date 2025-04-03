<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!is_logged_in()) {
    redirect('login.php');
}

$pageTitle = 'Customer Management';
require_once '../includes/header.php';

// Sample data - in real implementation this would come from database
$customers = [
    [
        'id' => 1,
        'name' => 'Acme Corporation',
        'email' => 'contact@acme.com',
        'phone' => '(123) 456-7890',
        'address' => '123 Business St, New York',
        'balance' => 1250.50
    ],
    [
        'id' => 2,
        'name' => 'Globex Corp',
        'email' => 'sales@globex.com',
        'phone' => '(987) 654-3210',
        'address' => '456 Industry Ave, Chicago',
        'balance' => 875.25
    ]
];
?>

<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Customer Management</h2>
        <button onclick="openCustomerModal()" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            <i class="fas fa-plus mr-2"></i>Add Customer
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($customers as $customer): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900"><?= $customer['name'] ?></div>
                                <div class="text-sm text-gray-500"><?= $customer['email'] ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900"><?= $customer['phone'] ?></div>
                        <div class="text-sm text-gray-500"><?= $customer['address'] ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?= $customer['balance'] > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' ?>">
                            $<?= number_format($customer['balance'], 2) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="editCustomer(<?= $customer['id'] ?>)" 
                                class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteCustomer(<?= $customer['id'] ?>)" 
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

<!-- Customer Modal (hidden by default) -->
<div id="customerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold" id="modalTitle">Add New Customer</h3>
            <button onclick="closeCustomerModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="customerForm">
            <input type="hidden" id="customerId">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name*</label>
                    <input type="text" id="name" name="name" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone*</label>
                    <input type="tel" id="phone" name="phone" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" id="address" name="address"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeCustomerModal()"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    Save Customer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Customer modal functions
    function openCustomerModal(customer = null) {
        const modal = document.getElementById('customerModal');
        const form = document.getElementById('customerForm');
        const modalTitle = document.getElementById('modalTitle');
        
        if (customer) {
            modalTitle.textContent = 'Edit Customer';
            document.getElementById('customerId').value = customer.id;
            document.getElementById('name').value = customer.name;
            document.getElementById('email').value = customer.email;
            document.getElementById('phone').value = customer.phone;
            document.getElementById('address').value = customer.address;
        } else {
            modalTitle.textContent = 'Add New Customer';
            form.reset();
        }
        
        modal.classList.remove('hidden');
    }

    function closeCustomerModal() {
        document.getElementById('customerModal').classList.add('hidden');
    }

    function editCustomer(id) {
        // In real implementation, this would fetch customer data from server
        const customer = {
            id: id,
            name: 'Sample Customer',
            email: 'sample@example.com',
            phone: '(123) 456-7890',
            address: '123 Sample St'
        };
        openCustomerModal(customer);
    }

    function deleteCustomer(id) {
        if (confirm('Are you sure you want to delete this customer?')) {
            // In real implementation, this would send delete request to server
            console.log('Deleting customer with ID:', id);
        }
    }

    // Form submission
    document.getElementById('customerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // In real implementation, this would send data to server
        console.log('Form submitted:', {
            id: document.getElementById('customerId').value,
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            address: document.getElementById('address').value
        });
        closeCustomerModal();
    });
</script>

<?php require_once '../includes/footer.php'; ?>