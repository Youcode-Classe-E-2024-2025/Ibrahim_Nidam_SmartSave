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
          <button type="submit" class="cursor-pointer rounded-md px-3 py-2 text-sm font-medium text-gray-500 hover:bg-gray-700 hover:text-white">
            Logout
          </button>
        </form>
      </div>
    </div>
  </nav>
  
  <div class="container mx-auto px-4 py-8">
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
              <!-- Updated Delete Button -->
              <button type="button" class="text-gray-400 hover:text-red-400 delete-goal" data-id="{{ $savingGoal->id }}">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          </div>
          
          <div class="space-y-4">
            <div>
              <div class="flex justify-between text-sm mb-1">
                <span class="text-gray-400">Progress</span>
                <span class="font-medium">${{ number_format($accountBalance, 2) }} / ${{ number_format($savingGoal->target_amount, 2) }}</span>
              </div>
              @php
                $percentage = ($savingGoal->target_amount > 0) ? (min($accountBalance / $savingGoal->target_amount * 100, 100)) : 0;
                $colorClass = $percentage >= 80 ? 'bg-success' : ($percentage >= 50 ? 'bg-warning' : 'bg-danger');
              @endphp
              <div class="h-2 bg-gray-800 rounded-full overflow-hidden">
                <div class="h-full {{ $colorClass }}" style="width: {{ $percentage }}%"></div>
              </div>
            </div>
            
            <div class="flex justify-between text-sm">
              <span class="text-gray-400">Deadline</span>
              <span>{{ $savingGoal->deadline ? $savingGoal->deadline->format('F j, Y') : 'No deadline' }}</span>
            </div>
            
            <div class="pt-4 border-t border-dark-border">
              @if($accountBalance >= $savingGoal->target_amount)
                <form action="{{ route('saving-goals.create-transaction', $savingGoal) }}" method="POST">
                  @csrf
                  <button type="submit" class="w-full px-4 py-2 bg-success hover:bg-green-600 rounded-md transition-colors">
                    <i class="fas fa-exchange-alt mr-2"></i>Create Transaction
                  </button>
                </form>
              @else
                <button disabled class="w-full px-4 py-2 bg-gray-700 text-gray-400 rounded-md transition-colors">
                  <i class="fas fa-wallet mr-2"></i>{{ round($percentage, 2) }}% Funded 
                  (${{ number_format($savingGoal->target_amount - $accountBalance, 2) }} to go)
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
  
  <!-- Delete Goal Modal -->
  <div id="deleteGoalModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-dark-surface rounded w-full max-w-md p-6 border border-dark-border">
      <h3 class="text-xl font-semibold mb-4">Confirm Goal Deletion</h3>
      <p class="mb-6">Are you sure you want to delete this goal? This action cannot be undone.</p>
      
      <form id="deleteGoalForm" action="" method="POST">
        @csrf
        @method('DELETE')
        <div class="flex justify-end">
          <button type="button" id="cancelDeleteGoal" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-md mr-2">
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
      
      // Setup delete goal modal functionality
      setupGoalDeletion();
    });
    
    function setupGoalDeletion() {
      const deleteGoalButtons = document.querySelectorAll('.delete-goal');
      const deleteGoalModal = document.getElementById('deleteGoalModal');
      const deleteGoalForm = document.getElementById('deleteGoalForm');
      const cancelDeleteGoal = document.getElementById('cancelDeleteGoal');
      
      deleteGoalButtons.forEach(button => {
        button.addEventListener('click', () => {
          const goalId = button.getAttribute('data-id');
          // Update form action with the correct route, e.g., /saving-goals/{goalId}
          deleteGoalForm.action = `/saving-goals/${goalId}`;
          deleteGoalModal.classList.remove('hidden');
        });
      });
      
      cancelDeleteGoal.addEventListener('click', () => {
        deleteGoalModal.classList.add('hidden');
      });
    }
  </script>
</body>
</html>
