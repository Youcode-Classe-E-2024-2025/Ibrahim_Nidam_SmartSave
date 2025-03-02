<?php

use App\Models\User;
use App\Models\Category;
use App\Models\Profile;
use App\Models\SavingGoal;
use App\Models\Transaction;
use App\Models\Budget;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create 10 users
        $users = User::factory(10)->create();

        // Create default categories (system-wide)
        $defaultCategories = [
            ['name' => 'Saving Goal', 'color' => '#4CAF50', 'type' => 'expense'],
            ['name' => 'Salary', 'color' => '#2196F3', 'type' => 'income'],
            ['name' => 'Other Expenses', 'color' => '#9E9E9E', 'type' => 'expense'],
            ['name' => 'Other Incomes', 'color' => '#607D8B', 'type' => 'income']
        ];

        foreach ($defaultCategories as $category) {
            Category::create([
                'user_id' => null,
                'name' => $category['name'],
                'color' => $category['color'],
                'type' => $category['type']
            ]);
        }

        // Get all default categories from DB
        $systemCategories = Category::whereNull('user_id')->get();

        // Create 10 profiles linked to users
        $profiles = $users->map(function($user) {
            return Profile::factory()->for($user)->create();
        });

        // Create saving goals
        $savingGoals = $profiles->map(function($profile) {
            return SavingGoal::factory()->for($profile)->create();
        });

        // Create budgets
        $budgets = $profiles->map(function($profile) {
            return Budget::factory()->for($profile)->create();
        });

        // Create transactions with system categories
        $transactions = $profiles->map(function($profile) use ($systemCategories) {
            return Transaction::factory()->create([
                'profile_id' => $profile->id,
                'category_id' => $systemCategories->random()->id,
                'type' => $systemCategories->random()->type,
            ]);
        });
    }
}