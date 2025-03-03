<?php
namespace Tests\Feature;

use App\Models\SavingGoal;
use App\Models\Profile;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class SavingGoalControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_displays_saving_goals_with_account_balance()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        // Create a profile and a saving goal
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $savingGoal = SavingGoal::factory()->create(['profile_id' => $profile->id]);
        
        // Using URL directly instead of route() helper to avoid the route name issue
        $response = $this->get('/saving-goals');
       
        $response->assertStatus(200);
        $response->assertViewIs('savingGoals');
        $response->assertViewHas('savingGoals');
        $response->assertViewHas('accountBalance');
    }

    public function test_it_stores_a_saving_goal()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        // Create a profile
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        
        $data = [
            'title' => 'New Goal',
            'target_amount' => 1000,
            'profile_id' => $profile->id,
            'description' => 'Saving for a new laptop',
            'deadline' => '2025-12-31',
        ];
        
        // Using URL directly instead of route() helper
        $response = $this->post('/saving-goals', $data);
       
        $response->assertRedirect('/saving-goals');
        $this->assertDatabaseHas('saving_goals', $data);
    }

    public function test_it_deletes_a_saving_goal()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        // Fixed: Create saving goal with profile_id instead of user_id
        $savingGoal = SavingGoal::factory()->create(['profile_id' => $profile->id]);
        
        // Using URL directly instead of route() helper
        $response = $this->delete('/saving-goals/' . $savingGoal->id);
        
        $response->assertRedirect('/saving-goals');
        $this->assertDatabaseMissing('saving_goals', ['id' => $savingGoal->id]);
    }

    public function test_it_creates_a_transaction_from_saving_goal()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        // Create a category with ID 1 to match controller's hardcoded value
        $category = Category::factory()->create([
            'id' => 1, // Force this category to have ID 1
            'user_id' => $user->id
        ]);

        $savingGoal = SavingGoal::factory()->create([
            'profile_id' => $profile->id,
            'target_amount' => 500
        ]);

        // Set the selected profile in the session
        session(['selected_profile_id' => $profile->id]);

        // Add the required category_id to the transaction
        Transaction::factory()->create([
            'profile_id' => $profile->id,
            'category_id' => $category->id, // Now uses ID 1
            'type' => 'income',
            'amount' => 1000
        ]);

        $response = $this->post('/saving-goals/' . $savingGoal->id . '/create-transaction', [
            'category_id' => $category->id
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('transactions', [
            'amount' => 500,
            'type' => 'expense',
            'profile_id' => $profile->id,
            'category_id' => 1 // Matches the hardcoded value
        ]);
        $this->assertDatabaseMissing('saving_goals', ['id' => $savingGoal->id]);
    }
}