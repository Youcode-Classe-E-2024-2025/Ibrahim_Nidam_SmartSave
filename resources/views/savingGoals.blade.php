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
                <a href="dashboard.html" class="text-gray-400 hover:text-white">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold">Saving Goals</h1>
            </div>
            <button id="openNewGoalModal" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-md transition-colors">
                <i class="fas fa-plus mr-2"></i>New Goal
            </button>
        </div>
    </nav>
    
    <div class="container mx-auto px-4 py-8">
        <!-- Goals Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Goal Card 1 -->
            <div class="bg-dark-surface rounded-lg border border-dark-border p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-semibold mb-1">New Car</h3>
                        <p class="text-sm text-gray-400">Created by John</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="text-gray-400 hover:text-white">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-400">Progress</span>
                            <span class="font-medium">$15,000 / $30,000</span>
                        </div>
                        <div class="h-2 bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full bg-success" style="width: 50%"></div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Deadline</span>
                        <span>December 31, 2024</span>
                    </div>
                    
                    <div class="pt-4 border-t border-dark-border">
                        <button class="w-full px-4 py-2 bg-success hover:bg-green-600 rounded-md transition-colors">
                            <i class="fas fa-plus mr-2"></i>Add Funds
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Goal Card 2 -->
            <div class="bg-dark-surface rounded-lg border border-dark-border p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-semibold mb-1">Vacation Fund</h3>
                        <p class="text-sm text-gray-400">Created by Sarah</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="text-gray-400 hover:text-white">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-400">Progress</span>
                            <span class="font-medium">$2,500 / $5,000</span>
                        </div>
                        <div class="h-2 bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full bg-warning" style="width: 50%"></div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Deadline</span>
                        <span>July 1, 2024</span>
                    </div>
                    
                    <div class="pt-4 border-t border-dark-border">
                        <button class="w-full px-4 py-2 bg-warning hover:bg-amber-600 rounded-md transition-colors">
                            <i class="fas fa-plus mr-2"></i>Add Funds
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Goal Card 3 -->
            <div class="bg-dark-surface rounded-lg border border-dark-border p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-semibold mb-1">Emergency Fund</h3>
                        <p class="text-sm text-gray-400">Created by John</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="text-gray-400 hover:text-white">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-400">Progress</span>
                            <span class="font-medium">$8,000 / $10,000</span>
                        </div>
                        <div class="h-2 bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full bg-success" style="width: 80%"></div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Deadline</span>
                        <span>No deadline</span>
                    </div>
                    
                    <div class="pt-4 border-t border-dark-border">
                        <button class="w-full px-4 py-2 bg-success hover:bg-green-600 rounded-md transition-colors">
                            <i class="fas fa-plus mr-2"></i>Add Funds
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Goal Card 4 -->
            <div class="bg-dark-surface rounded-lg border border-dark-border p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-semibold mb-1">Home Down Payment</h3>
                        <p class="text-sm text-gray-400">Created by Sarah</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="text-gray-400 hover:text-white">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-400">Progress</span>
                            <span class="font-medium">$25,000 / $100,000</span>
                        </div>
                        <div class="h-2 bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full bg-danger" style="width: 25%"></div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Deadline</span>
                        <span>June 30, 2025</span>
                    </div>
                    
                    <div class="pt-4 border-t border-dark-border">
                        <button class="w-full px-4 py-2 bg-danger hover:bg-red-600 rounded-md transition-colors">
                            <i class="fas fa-plus mr-2"></i>Add Funds
                        </button>
                    </div>
                </div>
            </div>
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
            
            <form id="newGoalForm" class="space-y-4">
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
                        <input type="number" id="targetAmount" name="targetAmount" required min="1" step="0.01"
                               class="w-full bg-gray-800 border border-dark-border rounded-md pl-8 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                               placeholder="0.00">
                    </div>
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
            
            <form id="addFundsForm" class="space-y-4">
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
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // New Goal Modal
            const newGoalModal = document.getElementById('newGoalModal');
            const openNewGoalModal = document.getElementById('openNewGoalModal');
            const closeNewGoalModal = document.getElementById('closeNewGoalModal');
            const cancelNewGoal = document.getElementById('cancelNewGoal');
            const newGoalForm = document.getElementById('newGoalForm');
            
            openNewGoalModal.addEventListener('click', () => {
                newGoalModal.classList.remove('hidden');
            });
            
            [closeNewGoalModal, cancelNewGoal].forEach(element => {
                element.addEventListener('click', () => {
                    newGoalModal.classList.add('hidden');
                });
            });
            
            newGoalForm.addEventListener('submit', (e) => {
                e.preventDefault();
                // Here you would normally handle the form submission
                newGoalModal.classList.add('hidden');
                alert('Goal created successfully!');
            });
            
            // Add Funds Modal
            const addFundsModal = document.getElementById('addFundsModal');
            const addFundsButtons = document.querySelectorAll('button:has(.fa-plus)');
            const closeAddFundsModal = document.getElementById('closeAddFundsModal');
            const cancelAddFunds = document.getElementById('cancelAddFunds');
            const addFundsForm = document.getElementById('addFundsForm');
            
            addFundsButtons.forEach(button => {
                if (button.id !== 'openNewGoalModal') {
                    button.addEventListener('click', () => {
                        addFundsModal.classList.remove('hidden');
                    });
                }
            });
            
            [closeAddFundsModal, cancelAddFunds].forEach(element => {
                element.addEventListener('click', () => {
                    addFundsModal.classList.add('hidden');
                });
            });
            
            addFundsForm.addEventListener('submit', (e) => {
                e.preventDefault();
                // Here you would normally handle the form submission
                addFundsModal.classList.add('hidden');
                alert('Funds added successfully!');
            });
        });
    </script>
</body>
</html>

