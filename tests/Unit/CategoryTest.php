<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\User;
use App\Models\Profile;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a category belongs to a user.
     */
    public function test_category_belongs_to_user()
    {
        // Create a user and a category associated with that user
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        // Assert that the category's user is an instance of User and matches the created user
        $this->assertInstanceOf(User::class, $category->user);
        $this->assertEquals($user->id, $category->user->id);
    }

    /**
     * Test that a category has many transactions.
     */
    public function test_category_has_many_transactions()
    {
        // Create a user, a profile for the user, and a category for the user
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $category = Category::factory()->create(['user_id' => $user->id]);

        // Create several transactions associated with the category,
        // and provide valid profile_id and type to satisfy not null constraints.
        $transactions = Transaction::factory()->count(3)->create([
            'category_id' => $category->id,
            'profile_id'  => $profile->id,
            'type'        => 'expense', // or 'income' depending on your use case
        ]);

        // Refresh the category relation to pick up newly created transactions
        $category->load('transactions');

        // Assert that there are 3 transactions related to the category
        $this->assertCount(3, $category->transactions);

        // Assert that the transactions relationship returns a collection
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $category->transactions);
    }
}
