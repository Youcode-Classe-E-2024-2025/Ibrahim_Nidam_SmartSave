<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Profile;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Temporarily define the route for selectProfile so it can be referenced by name.
        Route::middleware('web')->get('/profiles/select/{profile}', [\App\Http\Controllers\ProfileController::class, 'selectProfile'])
            ->name('profiles.select');
    }

    /**
     * Test that the index route returns the profiles view with data.
     */
    public function test_index_returns_profiles_view()
    {
        $user = User::factory()->create();
        // Create a couple of profiles for this user.
        $profiles = Profile::factory()->count(2)->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $response = $this->get(route('profiles.index'));
        $response->assertStatus(200);
        $response->assertViewIs('profiles.index');
        $response->assertViewHas('profiles', function ($viewProfiles) use ($profiles) {
            return $viewProfiles->count() === $profiles->count();
        });
    }

    /**
     * Test that storing a profile creates a record and returns a JSON response.
     */
    public function test_store_creates_profile_and_returns_json()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $postData = [
            'name'  => 'Test Profile',
            'pin'   => '1234',
            'color' => '#abcdef',
        ];

        $response = $this->post(route('profiles.store'), $postData);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('profiles', [
            'name'    => 'Test Profile',
            'color'   => '#abcdef',
            'role'    => 'member',
            'user_id' => $user->id,
        ]);

        // Verify that the stored PIN is hashed.
        $profile = Profile::where('name', 'Test Profile')->first();
        $this->assertTrue(Hash::check('1234', $profile->profile_pin));
    }

    /**
     * Test that verifyPin returns success when the correct PIN is provided.
     */
    public function test_verify_pin_success_returns_json_success_true()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a profile with a known PIN.
        $profile = Profile::factory()->create([
            'user_id'     => $user->id,
            'profile_pin' => Hash::make('1234'),
        ]);

        $postData = [
            'profile_id' => $profile->id,
            'pin'        => '1234',
        ];

        $response = $this->post(route('profiles.verifyPin'), $postData);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Assert that the profile id is stored in session.
        $this->assertEquals($profile->id, session('selected_profile_id'));
    }

    /**
     * Test that verifyPin returns an error when an incorrect PIN is provided.
     */
    public function test_verify_pin_failure_returns_json_failure_with_status_422()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a profile with a known PIN.
        $profile = Profile::factory()->create([
            'user_id'     => $user->id,
            'profile_pin' => Hash::make('1234'),
        ]);

        $postData = [
            'profile_id' => $profile->id,
            'pin'        => '0000', // incorrect PIN
        ];

        $response = $this->post(route('profiles.verifyPin'), $postData);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Incorrect PIN.',
        ]);

        // Ensure the session does not store the profile.
        $this->assertNull(session('selected_profile_id'));
    }

    /**
     * Test that selecting a profile stores it in the session and redirects to /dashboard.
     */
    public function test_select_profile_redirects_to_dashboard()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $response = $this->get(route('profiles.select', $profile->id));
        $response->assertRedirect('/dashboard');
        $this->assertEquals($profile->id, session('selected_profile_id'));
    }
}
