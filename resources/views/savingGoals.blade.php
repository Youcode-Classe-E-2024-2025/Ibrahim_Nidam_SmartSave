<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saving Goals</title>
    
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
                        'success': '#10B981',
                        'warning': '#F59E0B',
                        'danger': '#EF4444',
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-dark-bg text-gray-200 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-dark-surface border-b border-dark-border px-6 py-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <a href="/dashboard" class="text-gray-400 hover:text-white">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold">Saving Goals</h1>
            </div>
            <div class="flex gap-4">
                <button id="openNewGoalModal" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-md transition-colors">
                    <i class="fas fa-plus mr-2"></i>New Goal
                </button>
                <form action="/logout" method="post">
                    @csrf
                    <button type="submit" class="cursor-pointer rounded-md px-3 py-2 text-sm font-medium text-gray-500 hover:bg-gray-700 hover:text-white">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    
    <div class="container mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-800 text-white p-4 rounded-md mb-6">
                {{ session('success') }}
            </div>
        @endif
        
        <!-- Goals Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($savingGoals as $savingGoal)
                <!-- Goal Card -->
                <div class="bg-dark-surface rounded-lg border border-dark-border p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-semibold mb-1">{{ $savingGoal->title }}</h3>
                            <p class="text-sm text-gray-400">Created by {{ $savingGoal->profile->name }}</p>
                        </div>
                        <div>
                            <form action="{{ route('saving-goals.destroy', $savingGoal) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-400" 
                                        onclick="return confirm('Are you sure you want to delete this goal?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-400">Progress</span>
                                <span class="font-medium">${{ number_format($savingGoal->current_amount, 2) }} / ${{ number_format($savingGoal->target_amount, 2) }}</span>
                            </div>
                            @php
                                $percentage = ($savingGoal->target_amount > 0) ? ($savingGoal->current_amount / $savingGoal->target_amount * 100) : 0;
                                $colorClass = $percentage >= 80 ? 'bg-success' : ($percentage >= 50 ? 'bg-warning' : 'bg-danger');
                            @endphp
                            <div class="h-2 bg-gray-800 rounded-full overflow-hidden">
                                <div class="h-full {{ $colorClass }}" style="width: {{ min($percentage, 100) }}%"></div>
                            </div>
                        </div>
                        
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">Deadline</span>
                            <span>{{ $savingGoal->deadline ? $savingGoal->deadline->format('F j, Y') : 'No deadline' }}</span>
                        </div>
                        
                        <div class="pt-4 border-t border-dark-border">
                            @if($savingGoal->current_amount >= $savingGoal->target_amount)
                                <button class="create-transaction-btn w-full px-4 py-2 bg-success hover:bg-green-600 rounded-md transition-colors" 
                                        data-goal-id="{{ $savingGoal->id }}" 
                                        data-amount="{{ $savingGoal->target_amount }}">
                                    <i class="fas fa-exchange-alt mr-2"></i>Create Transaction
                                </button>
                            @else
                                <button class="add-funds-btn w-full px-4 py-2 {{ $colorClass }} hover:{{ str_replace('bg-', 'bg-', $colorClass) }}-600 rounded-md transition-colors" 
                                        data-goal-id="{{ $savingGoal->id }}">
                                    <i class="fas fa-plus mr-2"></i>Add Funds
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-400 text-lg">No saving goals yet. Click the "New Goal" button to create one.</p>
                </div>
            @endforelse
        </div>
    </div>
    
    <!-- New Goal Modal -->
    <div id="newGoalModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-dark-surface rounded-lg w-full max-w-md p-6 border border-dark-border">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold">Create New Goal</h3>
                <button id="closeNewGoalModal" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="newGoalForm" action="{{ route('saving-goals.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="goalTitle" class="block text-sm font-medium mb-1">Goal Title</label>
                    <input type="text" id="goalTitle" name="title" required
                           class="w-full bg-gray-800 border border-dark-border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           placeholder="e.g., New Car">
                </div>
                
                <div>
                    <label for="targetAmount" class="block text-sm font-medium mb-1">Target Amount</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-400">$</span>
                        <input type="number" id="targetAmount" name="target_amount" required min="1" step="0.01"
                               class="w-full bg-gray-800 border border-dark-border rounded-md pl-8 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                               placeholder="0.00">
                    </div>
                </div>
                
                <div>
                    <input type="hidden" name="profile_id" value="{{$profileId}}">
                </div>
                
                <div>
                    <label for="deadline" class="block text-sm font-medium mb-1">Deadline (Optional)</label>
                    <input type="date" id="deadline" name="deadline"
                           class="w-full bg-gray-800 border border-dark-border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium mb-1">Description (Optional)</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full bg-gray-800 border border-dark-border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                              placeholder="Add some details about your goal..."></textarea>
                </div>
                
                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" id="cancelNewGoal"
                            class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-md transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-md transition-colors">
                        Create Goal
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Add Funds Modal -->
    <div id="addFundsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-dark-surface rounded-lg w-full max-w-md p-6 border border-dark-border">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold">Add Funds to Goal</h3>
                <button id="closeAddFundsModal" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="addFundsForm" action="" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" id="goalId" name="goal_id" value="">
                
                <div>
                    <label for="amount" class="block text-sm font-medium mb-1">Amount</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-400">$</span>
                        <input type="number" id="amount" name="amount" required min="1" step="0.01"
                               class="w-full bg-gray-800 border border-dark-border rounded-md pl-8 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                               placeholder="0.00">
                    </div>
                </div>
                
                <div>
                    <label for="source" class="block text-sm font-medium mb-1">Source</label>
                    <select id="source" name="source" required
                            class="w-full bg-gray-800 border border-dark-border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select source</option>
                        <option value="savings">Savings Account</option>
                        <option value="checking">Checking Account</option>
                        <option value="cash">Cash</option>
                    </select>
                </div>
                
                <div>
                    <label for="notes" class="block text-sm font-medium mb-1">Notes (Optional)</label>
                    <textarea id="notes" name="notes" rows="2"
                              class="w-full bg-gray-800 border border-dark-border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                              placeholder="Add any notes about this contribution..."></textarea>
                </div>
                
                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" id="cancelAddFunds"
                            class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-md transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-success hover:bg-green-600 rounded-md transition-colors">
                        Add Funds
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Create Transaction Modal -->
    <div id="createTransactionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-dark-surface rounded-lg w-full max-w-md p-6 border border-dark-border">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold">Create Transaction</h3>
                <button id="closeTransactionModal" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="createTransactionForm" action="" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" id="transactionGoalId" name="goal_id" value="">
                <input type="hidden" id="transactionAmount" name="amount" value="">
                
                <div>
                    <p class="text-sm mb-4">You are about to create an expense transaction for the goal amount.</p>
                </div>
                
                <div>
                    <label for="transaction_category" class="block text-sm font-medium mb-1">Category</label>
                    <select id="transaction_category" name="category" required
                            class="w-full bg-gray-800 border border-dark-border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select category</option>
                        <option value="shopping">Shopping</option>
                        <option value="bills">Bills & Utilities</option>
                        <option value="entertainment">Entertainment</option>
                        <option value="travel">Travel</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div>
                    <label for="account" class="block text-sm font-medium mb-1">From Account</label>
                    <select id="account" name="account" required
                            class="w-full bg-gray-800 border border-dark-border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select account</option>
                        <option value="savings">Savings Account</option>
                        <option value="checking">Checking Account</option>
                        <option value="cash">Cash</option>
                    </select>
                </div>
                
                <div>
                    <label for="transaction_notes" class="block text-sm font-medium mb-1">Notes (Optional)</label>
                    <textarea id="transaction_notes" name="notes" rows="2"
                              class="w-full bg-gray-800 border border-dark-border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                              placeholder="Add any notes about this transaction..."></textarea>
                </div>
                
                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" id="cancelTransaction"
                            class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-md transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-md transition-colors">
                        Create Transaction
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // New Goal Modal
            const newGoalModal = document.getElementById('newGoalModal');
            const openNewGoalModal = document.getElementById('openNewGoalModal');
            const closeNewGoalModal = document.getElementById('closeNewGoalModal');
            const cancelNewGoal = document.getElementById('cancelNewGoal');
            
            openNewGoalModal.addEventListener('click', () => {
                newGoalModal.classList.remove('hidden');
            });
            
            [closeNewGoalModal, cancelNewGoal].forEach(element => {
                element.addEventListener('click', () => {
                    newGoalModal.classList.add('hidden');
                });
            });
            
            // Add Funds Modal
            const addFundsModal = document.getElementById('addFundsModal');
            const addFundsButtons = document.querySelectorAll('.add-funds-btn');
            const closeAddFundsModal = document.getElementById('closeAddFundsModal');
            const cancelAddFunds = document.getElementById('cancelAddFunds');
            const addFundsForm = document.getElementById('addFundsForm');
            const goalIdInput = document.getElementById('goalId');
            
            addFundsButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const goalId = button.getAttribute('data-goal-id');
                    goalIdInput.value = goalId;
                    addFundsForm.action = `/saving-goals/${goalId}/add-funds`;
                    addFundsModal.classList.remove('hidden');
                });
            });
            
            [closeAddFundsModal, cancelAddFunds].forEach(element => {
                element.addEventListener('click', () => {
                    addFundsModal.classList.add('hidden');
                });
            });
            
            // Create Transaction Modal
            const createTransactionModal = document.getElementById('createTransactionModal');
            const createTransactionButtons = document.querySelectorAll('.create-transaction-btn');
            const closeTransactionModal = document.getElementById('closeTransactionModal');
            const cancelTransaction = document.getElementById('cancelTransaction');
            const createTransactionForm = document.getElementById('createTransactionForm');
            const transactionGoalIdInput = document.getElementById('transactionGoalId');
            const transactionAmountInput = document.getElementById('transactionAmount');
            
            createTransactionButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const goalId = button.getAttribute('data-goal-id');
                    const amount = button.getAttribute('data-amount');
                    transactionGoalIdInput.value = goalId;
                    transactionAmountInput.value = amount;
                    createTransactionForm.action = `/saving-goals/${goalId}/create-transaction`;
                    createTransactionModal.classList.remove('hidden');
                });
            });
            
            [closeTransactionModal, cancelTransaction].forEach(element => {
                element.addEventListener('click', () => {
                    createTransactionModal.classList.add('hidden');
                });
            });
        });
    </script>
</body>
</html>