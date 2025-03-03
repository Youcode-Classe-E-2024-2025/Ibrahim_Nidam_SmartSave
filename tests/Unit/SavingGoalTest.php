<?php
namespace Tests\Unit;

use App\Models\Profile;
use App\Models\SavingGoal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SavingGoalTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_a_profile_relationship()
    {
        // Create a profile first
        $profile = Profile::factory()->create();
        // Create the saving goal with the profile_id
        $savingGoal = SavingGoal::factory()->create(['profile_id' => $profile->id]);
       
        $this->assertInstanceOf(Profile::class, $savingGoal->profile);
    }

    public function test_it_has_fillable_attributes()
    {
        $fillable = ['profile_id', 'title', 'target_amount', 'current_amount', 'description', 'deadline'];
       
        $this->assertEquals($fillable, (new SavingGoal())->getFillable());
    }
}