<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Dashboard</title>
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
            <h1 class="text-2xl font-bold">Budget Dashboard</h1>
            <div class="flex gap-4">
                <a href="/dashboard" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-md transition-colors">
                    Dashboard
                </a>
                <a href="{{ route('saving-goals') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-md transition-colors">
                    Saving Goals
                </a>

                <form action="/logout" method="post">
                    @csrf
                    <button type="submit" class="cursor-pointer rounded-md px-3 py-2 text-sm font-medium text-gray-500 hover:bg-gray-700 hover:text-white">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    
    <div class="container mx-auto px-4 py-8">
        <!-- Budget Overview Section -->
        <section class="mb-12">
                <div class="flex justify-between">

                <h2 class="text-xl font-semibold mb-6">Budget Overview</h2>
                <div class="flex gap-4">
                    
                   
                    <a href="{{ route('budget.export.csv') }}" class="block px-4 py-2 text-sm hover:underline">
                        <i class="fas fa-file-csv mr-2"></i> Export CSV
                    </a>
                </div>
            </div>
            
            @if(isset($totalIncome) && $totalIncome > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Budget Allocation Card -->
                    <div class="bg-dark-surface p-6 rounded border border-dark-border">
                        <h3 class="text-lg font-medium mb-4">Budget Allocation</h3>
                        <p class="text-lg font-bold text-income mb-4">Total Income: {{ number_format($totalIncome, 2) }}</p>
                        
                        <ul class="space-y-4">
                            <li class="flex justify-between items-center border-b border-dark-border pb-3">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 rounded-full mr-3 bg-green-500"></div>
                                    <span><strong>Needs (50%):</strong></span>
                                </div>
                                <span>{{ number_format($budgetDistribution['needs'], 2) }}</span>
                            </li>
                            <li class="flex justify-between items-center border-b border-dark-border pb-3">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 rounded-full mr-3 bg-blue-500"></div>
                                    <span><strong>Wants (30%):</strong></span>
                                </div>
                                <span>{{ number_format($budgetDistribution['wants'], 2) }}</span>
                            </li>
                            <li class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 rounded-full mr-3 bg-yellow-500"></div>
                                    <span><strong>Savings (20%):</strong></span>
                                </div>
                                <span>{{ number_format($budgetDistribution['savings'], 2) }}</span>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Budget Chart -->
                    <div class="bg-dark-surface p-6 rounded border border-dark-border">
                        <h3 class="text-lg font-medium mb-4">Budget Chart</h3>
                        <div class="h-64">
                            <canvas id="budgetChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Budget Explanation Section -->
                <div class="mt-8 bg-dark-surface p-6 rounded border border-dark-border">
                    <h3 class="text-lg font-medium mb-4">About the 50/30/20 Rule</h3>
                    <p class="mb-4">The 50/30/20 budget rule is a simple way to effectively manage your finances:</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div class="bg-gray-800 p-4 rounded">
                            <h4 class="font-medium text-green-500 mb-2">50% - Needs</h4>
                            <p class="text-sm">Essential expenses like rent, food, bills, transportation, and loan payments.</p>
                        </div>
                        <div class="bg-gray-800 p-4 rounded">
                            <h4 class="font-medium text-blue-500 mb-2">30% - Wants</h4>
                            <p class="text-sm">Non-essential expenses like entertainment, travel, dining out, subscriptions, and discretionary purchases.</p>
                        </div>
                        <div class="bg-gray-800 p-4 rounded">
                            <h4 class="font-medium text-yellow-500 mb-2">20% - Savings</h4>
                            <p class="text-sm">Emergency fund, retirement savings, investments, and accelerated debt payment.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-dark-surface p-6 rounded border border-dark-border text-center">
                    <i class="fas fa-exclamation-circle text-yellow-500 text-3xl mb-3"></i>
                    <p class="text-lg">No data available. Please add income transactions to see your budget distribution.</p>
                    <a href="/" class="inline-block mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-md transition-colors">
                        Return to dashboard
                    </a>
                </div>
            @endif
        </section>
        
        <!-- Monthly Summary Section -->
        @if(isset($totalMonthlyIncome) && $totalMonthlyIncome > 0)
        <section class="mb-12">
            <h2 class="text-xl font-semibold mb-6">Monthly Summary</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Income Card -->
                <div class="bg-dark-surface p-6 rounded border border-dark-border">
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-green-500 bg-opacity-10 rounded-full mr-4">
                            <i class="fas fa-wallet text-green-500"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium">Total Income</h3>
                            <p class="text-xl font-bold text-green-500">{{ number_format($totalMonthlyIncome, 2) }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Expenses Card -->
                <div class="bg-dark-surface p-6 rounded border border-dark-border">
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-red-500 bg-opacity-10 rounded-full mr-4">
                            <i class="fas fa-credit-card text-red-500"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium">Total Expenses</h3>
                            <p class="text-xl font-bold text-red-500">{{ number_format($totalSpent, 2) }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Remaining Budget Card -->
                <div class="bg-dark-surface p-6 rounded border border-dark-border">
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-blue-500 bg-opacity-10 rounded-full mr-4">
                            <i class="fas fa-piggy-bank text-blue-500"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium">Remaining Budget</h3>
                            <p class="text-xl font-bold {{ $remainingBudget >= 0 ? 'text-blue-500' : 'text-red-500' }}">
                                {{ number_format($remainingBudget, 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            
        </section>
        
        <!-- Charts Section -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- Expense by Category Chart -->
            <div class="bg-dark-surface p-6 rounded border border-dark-border">
                <h3 class="text-lg font-medium mb-4">Expenses by Category</h3>
                <div class="h-64">
                    <canvas id="expensesChart"></canvas>
                </div>
            </div>
            
            <!-- Monthly Spending Trend Chart -->
            <div class="bg-dark-surface p-6 rounded border border-dark-border">
                <h3 class="text-lg font-medium mb-4">Spending Trend (Last 6 Months)</h3>
                <div class="h-64">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </section>
        
        <!-- Budget Insights -->
        <section class="mb-12">
            <h2 class="text-xl font-semibold mb-6">Detailed Analysis</h2>
            
            <div class="bg-dark-surface p-6 rounded border border-dark-border">
                <h3 class="text-lg font-medium mb-4">Expense Breakdown</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="text-left p-3 border-b border-dark-border">Category</th>
                                <th class="text-right p-3 border-b border-dark-border">Amount</th>
                                <th class="text-right p-3 border-b border-dark-border">% of Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expensesByCategory as $category => $amount)
                                <tr class="hover:bg-gray-800">
                                    <td class="p-3 border-b border-dark-border">{{ $category }}</td>
                                    <td class="text-right p-3 border-b border-dark-border">{{ number_format($amount, 2) }}</td>
                                    <td class="text-right p-3 border-b border-dark-border">
                                        {{ $totalSpent > 0 ? number_format(($amount / $totalSpent) * 100, 1) : 0 }}%
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="font-bold">
                                <td class="p-3 border-t border-dark-border">Total</td>
                                <td class="text-right p-3 border-t border-dark-border">{{ number_format($totalSpent, 2) }}</td>
                                <td class="text-right p-3 border-t border-dark-border">100%</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </section>
        @endif
        
        <!-- Budget Tips Section -->
        <section>
            <h2 class="text-xl font-semibold mb-6">Budget Tips</h2>
            
            <div class="bg-dark-surface rounded border border-dark-border p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-start">
                        <div class="bg-indigo-600 rounded-full p-2 mr-4">
                            <i class="fas fa-piggy-bank text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-medium mb-2">Save Automatically</h3>
                            <p class="text-gray-400">Set up automatic transfers to a savings account as soon as you get paid.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-indigo-600 rounded-full p-2 mr-4">
                            <i class="fas fa-list-check text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-medium mb-2">Track Your Spending</h3>
                            <p class="text-gray-400">Record every expense to identify areas where you could save money.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-indigo-600 rounded-full p-2 mr-4">
                            <i class="fas fa-calendar-days text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-medium mb-2">Budget by Period</h3>
                            <p class="text-gray-400">Create monthly budgets that account for seasonal expenses and special events.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-indigo-600 rounded-full p-2 mr-4">
                            <i class="fas fa-credit-card text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-medium mb-2">Limit Debt</h3>
                            <p class="text-gray-400">Avoid impulse purchases on credit and prioritize paying off high-interest debt.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Charts Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Colors for charts
        const colors = [
            '#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6', 
            '#EC4899', '#14B8A6', '#F97316', '#6366F1', '#D946EF'
        ];
        
        @if(isset($totalIncome) && $totalIncome > 0)
        // Budget Pie Chart
        const budgetCtx = document.getElementById('budgetChart').getContext('2d');
        const budgetChart = new Chart(budgetCtx, {
            type: 'doughnut',
            data: {
                labels: ['Needs (50%)', 'Wants (30%)', 'Savings (20%)'],
                datasets: [{
                    data: [
                        {{ $budgetDistribution['needs'] }},
                        {{ $budgetDistribution['wants'] }},
                        {{ $budgetDistribution['savings'] }}
                    ],
                    backgroundColor: [
                        '#10B981', // green for needs
                        '#3B82F6', // blue for wants
                        '#F59E0B'  // yellow for savings
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
        @endif
        
        @if(isset($totalMonthlyIncome) && $totalMonthlyIncome > 0)
        // Expense by Category Chart
        const expensesCtx = document.getElementById('expensesChart').getContext('2d');
        const categories = {!! json_encode(array_keys($expensesByCategory)) !!};
        const amounts = {!! json_encode(array_values($expensesByCategory)) !!};
        
        new Chart(expensesCtx, {
            type: 'pie',
            data: {
                labels: categories,
                datasets: [{
                    data: amounts,
                    backgroundColor: colors.slice(0, categories.length),
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
        
        // Monthly Spending Trend Chart
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        const monthlyData = {!! json_encode($monthlySpending) !!};
        const months = Object.keys(monthlyData);
        const spendingData = Object.values(monthlyData);
        
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Monthly Expenses',
                    data: spendingData,
                    fill: false,
                    borderColor: '#3B82F6',
                    tension: 0.1,
                    pointBackgroundColor: '#3B82F6'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#E5E7EB'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#E5E7EB'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#E5E7EB'
                        }
                    }
                }
            }
        });
        @endif
    });
    </script>
</body>
</html>