<?php

namespace Tests\Unit;

use App\Models\Profile;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a profile belongs to a user.
     */
    public function test_profile_belongs_to_user()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $profile->user);
        $this->assertEquals($user->id, $profile->user->id);
    }

    /**
     * Test that a profile has many transactions.
     */
    public function test_profile_has_many_transactions()
    {
        $profile = Profile::factory()->create();

        // Create a category first to avoid NULL category_id errors.
        $category = Category::factory()->create();

        // Create several transactions for this profile.
        $transactions = Transaction::factory()->count(3)->create([
            'profile_id' => $profile->id,
            'category_id' => $category->id,  // Ensure category_id is populated
        ]);

        $profile->load('transactions');

        $this->assertCount(3, $profile->transactions);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $profile->transactions);
    }
}
