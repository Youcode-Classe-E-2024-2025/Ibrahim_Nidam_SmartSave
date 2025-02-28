<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Profile;
use App\Models\SavingGoal;
use App\Models\Transaction;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create 10 users
        $users = User::factory(10)->create();

        // Create 10 profiles, each linked to one of the already created users.
        $profiles = $users->map(function($user) {
            return Profile::factory()->for($user)->create();
        });

        // Create 10 categories
        $categories = Category::factory(10)->create();

        // Create 10 saving goals, each linked to a random existing profile.
        $savingGoals = $profiles->map(function($profile) {
            return SavingGoal::factory()->for($profile)->create();
        });

        // Create 10 budgets, each linked to a random existing profile.
        $budgets = $profiles->map(function($profile) {
            return Budget::factory()->for($profile)->create();
        });

        // Create 10 transactions, linking to an existing profile and category.
        // For the 'type', you might want to decide if it should come from the chosen category or be determined some other way.
        $transactions = $profiles->map(function($profile) use ($categories) {
            $category = $categories->random();
            return Transaction::factory()->create([
                'profile_id' => $profile->id,
                'category_id' => $category->id,
                'type' => $category->type,
            ]);
        });
    }
}

