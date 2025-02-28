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

        // Create default categories for each user
        foreach ($users as $user) {
            Category::create([
                'user_id' => $user->id,
                'name'    => 'Saving Goal',
                'color'   => '#10B981',  // e.g., green
                'type'    => 'expense',
            ]);
            Category::create([
                'user_id' => $user->id,
                'name'    => 'Other Expenses',
                'color'   => '#F59E0B',  // e.g., orange
                'type'    => 'expense',
            ]);
            Category::create([
                'user_id' => $user->id,
                'name'    => 'Other Incomes',
                'color'   => '#10B981',  // reusing a color for income if you like
                'type'    => 'income',
            ]);
        }

        // Optionally create additional random categories
        // This will create 10 random categories without a specific user_id,
        // so if you want to link them to a user, you can adjust your factory.
        $categories = Category::factory(10)->create();

        // Create 10 profiles, each linked to one of the created users.
        $profiles = $users->map(function($user) {
            return Profile::factory()->for($user)->create();
        });

        // Create additional seeding for saving goals, budgets, and transactions...
        $savingGoals = $profiles->map(function($profile) {
            return SavingGoal::factory()->for($profile)->create();
        });

        $budgets = $profiles->map(function($profile) {
            return Budget::factory()->for($profile)->create();
        });

        $transactions = $profiles->map(function($profile) use ($categories) {
            $category = $categories->random();
            return Transaction::factory()->create([
                'profile_id'  => $profile->id,
                'category_id' => $category->id,
                'type'        => $category->type,
            ]);
        });
    }
}
