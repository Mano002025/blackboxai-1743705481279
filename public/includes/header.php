<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            transition: all 0.3s;
        }
        .sidebar.collapsed {
            width: 80px;
        }
        .sidebar.collapsed .nav-text {
            display: none;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar bg-blue-800 text-white w-64 flex flex-col">
            <div class="p-4 flex items-center space-x-2 border-b border-blue-700">
                <i class="fas fa-store text-2xl"></i>
                <span class="nav-text text-xl font-bold">BMS</span>
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <a href="dashboard.php" class="flex items-center space-x-2 p-2 rounded hover:bg-blue-700">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
                <a href="customers.php" class="flex items-center space-x-2 p-2 rounded hover:bg-blue-700">
                    <i class="fas fa-users"></i>
                    <span class="nav-text">Customers</span>
                </a>
                <a href="suppliers.php" class="flex items-center space-x-2 p-2 rounded hover:bg-blue-700">
                    <i class="fas fa-truck"></i>
                    <span class="nav-text">Suppliers</span>
                </a>
                <a href="products.php" class="flex items-center space-x-2 p-2 rounded hover:bg-blue-700">
                    <i class="fas fa-boxes"></i>
                    <span class="nav-text">Products</span>
                </a>
                <a href="warehouses.php" class="flex items-center space-x-2 p-2 rounded hover:bg-blue-700">
                    <i class="fas fa-warehouse"></i>
                    <span class="nav-text">Warehouses</span>
                </a>
                <a href="sales.php" class="flex items-center space-x-2 p-2 rounded hover:bg-blue-700">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="nav-text">Sales</span>
                </a>
                <a href="purchases.php" class="flex items-center space-x-2 p-2 rounded hover:bg-blue-700">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="nav-text">Purchases</span>
                </a>
                <a href="treasury.php" class="flex items-center space-x-2 p-2 rounded hover:bg-blue-700">
                    <i class="fas fa-money-bill-wave"></i>
                    <span class="nav-text">Treasury</span>
                </a>
            </nav>
            <div class="p-4 border-t border-blue-700">
                <button class="flex items-center space-x-2 text-sm text-blue-200 hover:text-white">
                    <i class="fas fa-cog"></i>
                    <span class="nav-text">Settings</span>
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="flex justify-between items-center p-4">
                    <div class="flex items-center space-x-4">
                        <button id="sidebarToggle" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h1 class="text-xl font-semibold text-gray-800">
                            <?php echo isset($pageTitle) ? $pageTitle : 'Dashboard'; ?>
                        </h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <i class="fas fa-bell text-gray-500 hover:text-blue-600 cursor-pointer"></i>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">3</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <img src="https://images.pexels.com/photos/220453/pexels-photo-220453.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" 
                                 alt="User" 
                                 class="w-8 h-8 rounded-full">
                            <span class="text-sm font-medium">Admin</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>