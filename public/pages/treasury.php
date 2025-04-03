<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!is_logged_in()) {
    redirect('login.php');
}

$pageTitle = 'Treasury Management';
require_once '../includes/header.php';

// Sample data - in real implementation this would come from database
$treasuries = [
    [
        'id' => 1,
        'name' => 'Main Cash Account',
        'balance' => 125000.50,
        'currency' => 'USD'
    ],
    [
        'id' => 2,
        'name' => 'Petty Cash',
        'balance' => 2500.00,
        'currency' => 'USD'
    ]
];

$transactions = [
    [
        'id' => 1,
        'type' => 'deposit',
        'from' => '',
        'to' => 'Main Cash Account',
        'amount' => 5000.00,
        'date' => '2023-06-15',
        'description' => 'Customer payment - INV-1001'
    ],
    [
        'id' => 2,
        'type' => 'transfer',
        'from' => 'Main Cash Account',
        'to' => 'Petty Cash',
        'amount' => 1000.00,
        'date' => '2023-06-14',
        'description' => 'Weekly petty cash replenishment'
    ]
];
?>

<div class="space-y-6">
    <!-- Treasury Accounts Overview -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-6">Treasury Accounts</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php foreach ($treasuries as $treasury): ?>
            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center mb-3">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 mr-3">
                        <i class="fas fa-wallet text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold"><?= $treasury['name'] ?></h3>
                </div>
                <div class="space-y-2 text-sm">
                    <p class="text-2xl font-bold text-gray-800">
                        <?= $treasury['currency'] ?> <?= number_format($treasury['balance'], 2) ?>
                    </p>
                    <div class="flex space-x-2 pt-2">
                        <button onclick="openDepositModal(<?= $treasury['id'] ?>)" 
                                class="text-sm bg-blue-100 text-blue-600 px-3 py-1 rounded hover:bg-blue-200">
                            <i class="fas fa-plus mr-1"></i>Deposit
                        </button>
                        <button onclick="openWithdrawalModal(<?= $treasury['id'] ?>)" 
                                class="text-sm bg-yellow-100 text-yellow-600 px-3 py-1 rounded hover:bg-yellow-200">
                            <i class="fas fa-minus mr-1"></i>Withdraw
                        </button>
                        <button onclick="openTransferModal(<?= $treasury['id'] ?>)" 
                                class="text-sm bg-purple-100 text-purple-600 px-3 py-1 rounded hover:bg-purple-200">
                            <i class="fas fa-exchange-alt mr-1"></i>Transfer
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <!-- Add New Account Card -->
            <div class="border-2 border-dashed rounded-lg p-4 flex items-center justify-center hover:bg-gray-50 cursor-pointer"
                 onclick="openNewAccountModal()">
                <div class="text-center">
                    <div class="mx-auto p-3 rounded-full bg-gray-100 text-gray-600 w-12 h-12 flex items-center justify-center mb-2">
                        <i class="fas fa-plus"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-600">Add New Account</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Transaction History</h2>
            <div class="flex space-x-2">
                <select class="border border-gray-300 rounded-md shadow-sm py-1 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option>All Accounts</option>
                    <option>Main Cash Account</option>
                    <option>Petty Cash</option>
                </select>
                <select class="border border-gray-300 rounded-md shadow-sm py-1 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option>Last 30 Days</option>
                    <option>This Month</option>
                    <option>Last Month</option>
                    <option>Custom Range</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From/To</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= $transaction['date'] ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?= $transaction['type'] === 'deposit' ? 'bg-green-100 text-green-800' : 
                                   ($transaction['type'] === 'withdrawal' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') ?>">
                                <?= ucfirst($transaction['type']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php if ($transaction['type'] === 'transfer'): ?>
                                <div class="flex items-center">
                                    <span class="text-red-500 mr-1"><?= $transaction['from'] ?></span>
                                    <i class="fas fa-arrow-right text-xs mx-1 text-gray-400"></i>
                                    <span class="text-green-500 ml-1"><?= $transaction['to'] ?></span>
                                </div>
                            <?php elseif ($transaction['type'] === 'deposit'): ?>
                                <span class="text-green-500"><?= $transaction['to'] ?></span>
                            <?php else: ?>
                                <span class="text-red-500"><?= $transaction['from'] ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium 
                            <?= $transaction['type'] === 'deposit' ? 'text-green-600' : 'text-red-600' ?>">
                            <?= ($transaction['type'] === 'deposit' ? '+' : '-') ?> $<?= number_format($transaction['amount'], 2) ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <?= $transaction['description'] ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="viewTransaction(<?= $transaction['id'] ?>)" 
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="printReceipt(<?= $transaction['id'] ?>)" 
                                    class="text-purple-600 hover:text-purple-900 mr-3">
                                <i class="fas fa-print"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- New Account Modal -->
<div id="newAccountModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-1/3 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Add New Treasury Account</h3>
            <button onclick="closeNewAccountModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="accountForm">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Account Name*</label>
                    <input type="text" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Initial Balance*</label>
                    <input type="number" step="0.01" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Currency*</label>
                    <select required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="USD">US Dollar (USD)</option>
                        <option value="EUR">Euro (EUR)</option>
                        <option value="GBP">British Pound (GBP)</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeNewAccountModal()"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    Create Account
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Transaction Modals would be similar but are omitted for brevity -->

<script>
    // Treasury functions
    function openNewAccountModal() {
        document.getElementById('newAccountModal').classList.remove('hidden');
    }

    function closeNewAccountModal() {
        document.getElementById('newAccountModal').classList.add('hidden');
    }

    function openDepositModal(accountId) {
        // In real implementation, this would open deposit modal
        console.log('Deposit to account ID:', accountId);
    }

    function openWithdrawalModal(accountId) {
        // In real implementation, this would open withdrawal modal
        console.log('Withdraw from account ID:', accountId);
    }

    function openTransferModal(accountId) {
        // In real implementation, this would open transfer modal
        console.log('Transfer from account ID:', accountId);
    }

    function viewTransaction(id) {
        // In real implementation, this would show transaction details
        console.log('Viewing transaction with ID:', id);
    }

    function printReceipt(id) {
        // In real implementation, this would print the receipt
        console.log('Printing receipt for transaction with ID:', id);
    }

    // Form submission
    document.getElementById('accountForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // In real implementation, this would create new account
        console.log('Creating new treasury account');
        closeNewAccountModal();
    });
</script>

<?php require_once '../includes/footer.php'; ?>