<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BudgetControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_budget_data_returns_correct_structure()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $category = Category::factory()->create(['type' => 'income']);

        Transaction::factory()->create([
            'profile_id' => $profile->id,
            'category_id' => $category->id,
            'type' => 'income',
            'amount' => 1000
        ]);

        $this->actingAs($user);

        // Use get() instead of getJson() because the controller returns a view
        $response = $this->get(route('budget'));

        $response->assertStatus(200);

        // Extract data from the view
        $data = $response->getOriginalContent()->getData();

        // Assert that the returned view data has the expected structure
        $this->assertArrayHasKey('budgetDistribution', $data);
        $this->assertArrayHasKey('totalIncome', $data);
        $this->assertArrayHasKey('incomeCategories', $data);
        $this->assertArrayHasKey('expenseCategories', $data);
        $this->assertArrayHasKey('expensesByCategory', $data);
        $this->assertArrayHasKey('totalSpent', $data);
        $this->assertArrayHasKey('totalMonthlyIncome', $data);
        $this->assertArrayHasKey('monthlyBudget', $data);
        $this->assertArrayHasKey('remainingBudget', $data);
        $this->assertArrayHasKey('monthlySpending', $data);

        // Check the substructure of budgetDistribution and monthlyBudget
        $this->assertArrayHasKey('needs', $data['budgetDistribution']);
        $this->assertArrayHasKey('wants', $data['budgetDistribution']);
        $this->assertArrayHasKey('savings', $data['budgetDistribution']);

        $this->assertArrayHasKey('needs', $data['monthlyBudget']);
        $this->assertArrayHasKey('wants', $data['monthlyBudget']);
        $this->assertArrayHasKey('savings', $data['monthlyBudget']);
    }

    public function test_export_csv_downloads_successfully()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('budget.export.csv'));

        $response->assertStatus(200);
        $this->assertTrue($response->headers->has('Content-Disposition'));
        $header = $response->headers->get('Content-Disposition');
        $this->assertStringContainsString('attachment; filename=budget-export', $header);
    }

    public function test_index_route_returns_budget_view()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('budget'));

        $response->assertStatus(200);
        $response->assertViewIs('budget');
    }
}
