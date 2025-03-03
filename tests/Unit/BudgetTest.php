<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Budget;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BudgetTest extends TestCase
{
    use RefreshDatabase;

    public function test_budget_belongs_to_a_profile()
    {
        $profile = Profile::factory()->create();
        $budget = Budget::factory()->create(['profile_id' => $profile->id]);

        $this->assertEquals($profile->id, $budget->profile->id);
    }

}
