<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'dark-bg': '#121212',
                        'dark-surface': '#1E1E1E',
                        'dark-border': '#333333',
                        'income': '#10B981',
                        'expense': '#EF4444',
                    }
                }
            }
        }
    </script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-dark-bg text-gray-200 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-dark-surface border-b border-dark-border px-6 py-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Dashboard</h1>
            <div class="flex gap-4">

                <a href="{{ route('saving-goals') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-md transition-colors">
                    Saving Goals
                </a>
                <a href=" /budget" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-md transition-colors">
                    Budget
                </a>
                <form action="/logout" method="post">
                    @csrf
                    <button type="submit" class="cursor-pointer rounded-md px-3 py-2 text-sm font-medium text-gray-500 hover:bg-gray-700 hover:text-white">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    
    <div class="container mx-auto px-4 py-8">
        <!-- Charts Section -->
        <section class="mb-12">
            <h2 class="text-xl font-semibold mb-6">Financial Overview</h2>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Income Chart -->
                <div class="bg-dark-surface p-6 rounded border border-dark-border">
                    <h3 class="text-lg font-medium mb-4">Income by Category</h3>
                    <div class="h-64">
                        <canvas id="incomeChart"></canvas>
                    </div>
                </div>

                <!-- Account Balance Card -->
                <div class="bg-gradient-to-r from-income to-expense p-6 rounded border border-dark-border flex flex-col items-center justify-center">
                    <p class="text-lg font-medium mb-2 text-white">Account Balance</p>
                    <h2 class="text-4xl font-bold text-white">${{ number_format($accountBalance, 2) }}</h2>
                </div>
                
                <!-- Expense Chart -->
                <div class="bg-dark-surface p-6 rounded border border-dark-border">
                    <h3 class="text-lg font-medium mb-4">Expenses by Category</h3>
                    <div class="h-64">
                        <canvas id="expenseChart"></canvas>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Categories Section -->
        <section class="mb-12">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">Categories</h2>
                <button id="openAddCategoryModal" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-md transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add Category
                </button>
            </div>
            
            <div class="bg-dark-surface rounded border border-dark-border p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($categories as $category)
                        <div class="flex items-center justify-between p-3 bg-gray-800 rounded-md">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $category->color }}"></div>
                                <span>{{ $category->name }}</span>
                            </div>
                            <div class="flex space-x-2">
                                <button class="text-blue-400 hover:text-blue-300 transition-colors edit-category" 
                                        data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}"
                                        data-color="{{ $category->color }}"
                                        data-type="{{ $category->type }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-400 hover:text-red-300 transition-colors delete-category"
                                        data-id="{{ $category->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-gray-400 text-center py-4">
                            No categories found. Create your first category!
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
        
        <!-- Transactions Section -->
        <section>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">Recent Transactions</h2>
                <button id="openAddTransactionModal" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-md transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add Transaction
                </button>
            </div>
            
            <div class="bg-dark-surface rounded border border-dark-border overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-800 text-left">
                                <th class="px-6 py-3">Date</th>
                                <th class="px-6 py-3">Profile</th>
                                <th class="px-6 py-3">Description</th>
                                <th class="px-6 py-3">Category</th>
                                <th class="px-6 py-3 text-right">Amount</th>
                                <th class="px-6 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-dark-border">
                            @forelse($transactions as $transaction)
                                <tr class="hover:bg-gray-800 transition-colors">
                                    <td class="px-6 py-4">{{ $transaction->date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4">{{ $transaction->profile->name }}</td>
                                    <td class="px-6 py-4">{{ $transaction->description }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $transaction->category->color }}"></div>
                                            {{ $transaction->category->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right font-medium {{ strtolower($transaction->type) === 'income' ? 'text-income' : 'text-expense' }}">
                                        {{ strtolower($transaction->type) === 'income' ? '+' : '-' }} ${{ number_format($transaction->amount, 2) }}
                                    </td>                                    
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center space-x-3">
                                            <button class="text-blue-400 hover:text-blue-300 transition-colors edit-transaction" 
                                                    data-id="{{ $transaction->id }}"
                                                    data-date="{{ $transaction->date->format('Y-m-d') }}"
                                                    data-amount="{{ $transaction->amount }}"
                                                    data-description="{{ $transaction->description }}"
                                                    data-category-id="{{ $transaction->category_id }}"
                                                    data-profile-id="{{ $transaction->profile_id }}"
                                                    data-type="{{ $transaction->type }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="text-red-400 hover:text-red-300 transition-colors delete-transaction"
                                                    data-id="{{ $transaction->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                        No transactions found. Add your first transaction!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($transactions->count() > 0)
                    <div class="px-6 py-3 bg-gray-800 border-t border-dark-border">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
    
    <!-- Add/Edit Transaction Modal -->
    <div id="transactionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-dark-surface rounded w-full max-w-md p-6 border border-dark-border">
            <div class="flex justify-between items-center mb-6">
                <h3 id="transactionModalTitle" class="text-xl font-semibold">Add Transaction</h3>
                <button id="closeTransactionModal" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="transactionForm" action="{{ route('transactions.store') }}" method="POST">
                @csrf
                <input type="hidden" id="transactionMethod" name="_method" value="POST">
                <div class="space-y-4">
                    <div>
                        <label for="date" class="block text-sm font-medium mb-1">Date</label>
                        <input type="date" id="date" name="date" required
                               class="w-full bg-gray-800 border border-dark-border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    
                    <div>
                        <label for="amount" class="block text-sm font-medium mb-1">Amount</label>
                        <input type="number" id="amount" name="amount" step="0.01" min="0.01" required
                               class="w-full bg-gray-800 border border-dark-border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium mb-1">Description</label>
                        <input type="text" id="description" name="description" required
                               class="w-full bg-gray-800 border border-dark-border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    
                    <div>
                        <label for="category_id" class="block text-sm font-medium mb-1">Category</label>
                        <select id="category_id" name="category_id" required
                                class="w-full bg-gray-800 border border-dark-border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <input type="hidden" name="profile_id" value="{{$profileId}}">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1">Transaction Type</label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="type" value="income" class="text-indigo-600" checked>
                                <span class="ml-2">Income</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="type" value="expense" class="text-indigo-600">
                                <span class="ml-2">Expense</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button type="button" id="cancelTransaction" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-md mr-2">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-md">
                        Save Transaction
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Add/Edit Category Modal -->
    <div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-dark-surface rounded w-full max-w-md p-6 border border-dark-border">
            <div class="flex justify-between items-center mb-6">
                <h3 id="categoryModalTitle" class="text-xl font-semibold">Add Category</h3>
                <button id="closeCategoryModal" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="categoryForm" action="{{ route('categories.store') }}" method="POST">
                @csrf
                <input type="hidden" id="categoryMethod" name="_method" value="POST">
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium mb-1">Category Name</label>
                        <input type="text" id="name" name="name" required
                               class="w-full bg-gray-800 border border-dark-border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    
                    <div>
                        <label for="color" class="block text-sm font-medium mb-1">Color</label>
                        <input type="color" id="color" name="color" required
                               class="w-full h-10 bg-gray-800 border border-dark-border rounded-md px-1 py-1 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1">Category Type</label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="type" value="income" class="text-indigo-600">
                                <span class="ml-2">Income</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="type" value="expense" class="text-indigo-600">
                                <span class="ml-2">Expense</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button type="button" id="cancelCategory" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-md mr-2">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-md">
                        Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Delete Transaction Confirmation Modal -->
    <div id="deleteTransactionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-dark-surface rounded w-full max-w-md p-6 border border-dark-border">
            <h3 class="text-xl font-semibold mb-4">Confirm Deletion</h3>
            <p class="mb-6">Are you sure you want to delete this transaction? This action cannot be undone.</p>
            
            <form id="deleteTransactionForm" action="" method="POST">
                @csrf
                @method('DELETE')
                
                <div class="flex justify-end">
                    <button type="button" id="cancelDeleteTransaction" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-md mr-2">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-md">
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Delete Category Confirmation Modal -->
    <div id="deleteCategoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-dark-surface rounded w-full max-w-md p-6 border border-dark-border">
            <h3 class="text-xl font-semibold mb-4">Confirm Deletion</h3>
            <p class="mb-6">Are you sure you want to delete this category? This action cannot be undone and may affect related transactions.</p>
            
            <form id="deleteCategoryForm" action="" method="POST">
                @csrf
                @method('DELETE')
                
                <div class="flex justify-end">
                    <button type="button" id="cancelDeleteCategory" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-md mr-2">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-md">
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
    
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const allCategories = {!! json_encode($categories) !!};

        // Function to update the category dropdown based on the selected type
        function updateCategoryDropdown(selectedType) {
            const categorySelect = document.getElementById('category_id');
            // Clear current options
            categorySelect.innerHTML = '<option value="">Select a category</option>';
            
            // Filter the categories by the selected type
            const filteredCategories = allCategories.filter(category => category.type === selectedType);
            
            // Append filtered categories as options
            filteredCategories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.text = category.name;
                categorySelect.appendChild(option);
            });
        }

        // Listen for changes on the transaction type radio buttons
        const typeRadios = document.querySelectorAll('#transactionForm input[name="type"]');
        typeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                updateCategoryDropdown(this.value);
            });
        });
        
        // Initialize the dropdown with the default selected type (assuming 'income' is default)
        const defaultType = document.querySelector('#transactionForm input[name="type"]:checked');
        if (defaultType) {
            updateCategoryDropdown(defaultType.value);
        }
        
        // Chart initialization
        initCharts();
        
        // Modal controls
        setupModals();
        
        // Transaction actions
        setupTransactionActions();
        
        // Category actions
        setupCategoryActions();
    });
    
    function initCharts() {
        // Income Chart
        const incomeCtx = document.getElementById('incomeChart').getContext('2d');
        const incomeChart = new Chart(incomeCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($incomeCategories) !!},
                datasets: [{
                    data: {!! json_encode($incomeData) !!},
                    backgroundColor: [
                        '#10B981', '#3B82F6', '#8B5CF6', '#EC4899', '#F59E0B', '#06B6D4'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: '#E5E7EB'
                        }
                    }
                }
            }
        });
        
        // Expense Chart
        const expenseCtx = document.getElementById('expenseChart').getContext('2d');
        const expenseChart = new Chart(expenseCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($expenseCategories) !!},
                datasets: [{
                    data: {!! json_encode($expenseData) !!},
                    backgroundColor: [
                        '#EF4444', '#F59E0B', '#8B5CF6', '#EC4899', '#3B82F6', '#06B6D4'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: '#E5E7EB'
                        }
                    }
                }
            }
        });
    }
    
    function setupModals() {
        // Transaction Modal
        const transactionModal = document.getElementById('transactionModal');
        const openAddTransactionModal = document.getElementById('openAddTransactionModal');
        const closeTransactionModal = document.getElementById('closeTransactionModal');
        const cancelTransaction = document.getElementById('cancelTransaction');
        
        openAddTransactionModal.addEventListener('click', () => {
            // Reset the form
            document.getElementById('transactionForm').reset();
            document.getElementById('transactionForm').action = "{{ route('transactions.store') }}";
            document.getElementById('transactionMethod').value = 'POST';
            document.getElementById('transactionModalTitle').textContent = 'Add Transaction';
            
            // Set default date to today
            document.getElementById('date').value = new Date().toISOString().split('T')[0];
            
            // Initialize category dropdown based on the default selected type
            const defaultType = document.querySelector('#transactionForm input[name="type"]:checked').value;
            updateCategoryDropdown(defaultType);
            
            transactionModal.classList.remove('hidden');
        });
        
        closeTransactionModal.addEventListener('click', () => {
            transactionModal.classList.add('hidden');
        });
        
        cancelTransaction.addEventListener('click', () => {
            transactionModal.classList.add('hidden');
        });
        
        // Category Modal
        const categoryModal = document.getElementById('categoryModal');
        const openAddCategoryModal = document.getElementById('openAddCategoryModal');
        const closeCategoryModal = document.getElementById('closeCategoryModal');
        const cancelCategory = document.getElementById('cancelCategory');
        
        openAddCategoryModal.addEventListener('click', () => {
            // Reset the form
            document.getElementById('categoryForm').reset();
            document.getElementById('categoryForm').action = "{{ route('categories.store') }}";
            document.getElementById('categoryMethod').value = 'POST';
            document.getElementById('categoryModalTitle').textContent = 'Add Category';
            
            categoryModal.classList.remove('hidden');
        });
        
        closeCategoryModal.addEventListener('click', () => {
            categoryModal.classList.add('hidden');
        });
        
        cancelCategory.addEventListener('click', () => {
            categoryModal.classList.add('hidden');
        });
        
        // Delete Transaction Modal
        const deleteTransactionModal = document.getElementById('deleteTransactionModal');
        const cancelDeleteTransaction = document.getElementById('cancelDeleteTransaction');
        
        cancelDeleteTransaction.addEventListener('click', () => {
            deleteTransactionModal.classList.add('hidden');
        });
        
        // Delete Category Modal
        const deleteCategoryModal = document.getElementById('deleteCategoryModal');
        const cancelDeleteCategory = document.getElementById('cancelDeleteCategory');
        
        cancelDeleteCategory.addEventListener('click', () => {
            deleteCategoryModal.classList.add('hidden');
        });
    }
    
    function setupTransactionActions() {
        // Edit Transaction
        const editButtons = document.querySelectorAll('.edit-transaction');
        editButtons.forEach(button => {
            button.addEventListener('click', () => {
                const transactionId = button.getAttribute('data-id');
                const date = button.getAttribute('data-date');
                const amount = button.getAttribute('data-amount');
                const description = button.getAttribute('data-description');
                const categoryId = button.getAttribute('data-category-id');
                const profileId = button.getAttribute('data-profile-id');
                const type = button.getAttribute('data-type');
                
                // Update the form
                const form = document.getElementById('transactionForm');
                form.action = `/transactions/${transactionId}`;
                document.getElementById('transactionMethod').value = 'PUT';
                document.getElementById('transactionModalTitle').textContent = 'Edit Transaction';
                
                // Fill the form fields
                document.getElementById('date').value = date;
                document.getElementById('amount').value = amount;
                document.getElementById('description').value = description;
                
                // Select the correct type radio button
                const typeRadios = form.querySelectorAll('input[name="type"]');
                typeRadios.forEach(radio => {
                    if (radio.value === type.toLowerCase()) {
                        radio.checked = true;
                    }
                });
                
                // Update category dropdown based on the transaction type
                updateCategoryDropdown(type.toLowerCase());
                
                // Select the correct category and profile
                setTimeout(() => {
                    document.getElementById('category_id').value = categoryId;
                    document.getElementById('profile_id').value = profileId;
                }, 100);
                
                // Show the modal
                document.getElementById('transactionModal').classList.remove('hidden');
            });
        });
        
        // Delete Transaction
        const deleteButtons = document.querySelectorAll('.delete-transaction');
        const deleteForm = document.getElementById('deleteTransactionForm');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', () => {
                const transactionId = button.getAttribute('data-id');
                deleteForm.action = `/transactions/${transactionId}`;
                document.getElementById('deleteTransactionModal').classList.remove('hidden');
            });
        });
    }
    
    function setupCategoryActions() {
        // Edit Category
        const editButtons = document.querySelectorAll('.edit-category');
        editButtons.forEach(button => {
            button.addEventListener('click', () => {
                const categoryId = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const color = button.getAttribute('data-color');
                const type = button.getAttribute('data-type');
                
                // Update the form
                const form = document.getElementById('categoryForm');
                form.action = `/categories/${categoryId}`;
                document.getElementById('categoryMethod').value = 'PUT';
                document.getElementById('categoryModalTitle').textContent = 'Edit Category';
                
                // Fill the form fields
                document.getElementById('name').value = name;
                document.getElementById('color').value = color;
                
                // Select the correct type radio button
                const typeRadios = form.querySelectorAll('input[name="type"]');
                typeRadios.forEach(radio => {
                    if (radio.value === type.toLowerCase()) {
                        radio.checked = true;
                    }
                });
                
                // Show the modal
                document.getElementById('categoryModal').classList.remove('hidden');
            });
        });
        
        // Delete Category
        const deleteButtons = document.querySelectorAll('.delete-category');
        const deleteForm = document.getElementById('deleteCategoryForm');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', () => {
                const categoryId = button.getAttribute('data-id');
                deleteForm.action = `/categories/${categoryId}`;
                document.getElementById('deleteCategoryModal').classList.remove('hidden');
            });
        });
    }
    
    function updateCategoryDropdown(selectedType) {
        const categorySelect = document.getElementById('category_id');
        const allCategories = {!! json_encode($categories) !!};
        
        // Clear current options
        categorySelect.innerHTML = '<option value="">Select a category</option>';
        
        // Filter the categories by the selected type
        const filteredCategories = allCategories.filter(category => 
            category.type.toLowerCase() === selectedType.toLowerCase()
        );
        
        // Append filtered categories as options
        filteredCategories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.text = category.name;
            categorySelect.appendChild(option);
        });
    }
</script>
</body>
</html>