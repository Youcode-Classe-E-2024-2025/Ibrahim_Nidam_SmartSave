<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that storing a category creates a record and redirects.
     */
    public function test_store_category_creates_record_and_redirects()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $postData = [
            'name'  => 'Test Category',
            'color' => '#ff0000',
            'type'  => 'income'
        ];

        $response = $this->post(route('categories.store'), $postData);

        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('success', 'Category added successfully.');

        $this->assertDatabaseHas('categories', [
            'name'    => 'Test Category',
            'color'   => '#ff0000',
            'type'    => 'income',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test that updating a category modifies the record and redirects.
     */
    public function test_update_category_updates_record_and_redirects()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create an initial category for this user
        $category = Category::factory()->create([
            'user_id' => $user->id,
            'name'    => 'Old Category',
            'color'   => '#00ff00',
            'type'    => 'expense'
        ]);

        $updateData = [
            'name'  => 'Updated Category',
            'color' => '#0000ff',
            'type'  => 'expense'
        ];

        $response = $this->put(route('categories.update', $category), $updateData);

        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('success', 'Category updated successfully.');

        $this->assertDatabaseHas('categories', [
            'id'      => $category->id,
            'name'    => 'Updated Category',
            'color'   => '#0000ff',
            'type'    => 'expense',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test that deleting a category removes the record and redirects.
     */
    public function test_destroy_category_deletes_record_and_redirects()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a category to delete
        $category = Category::factory()->create([
            'user_id' => $user->id,
            'name'    => 'Category to Delete',
            'color'   => '#123456',
            'type'    => 'expense'
        ]);

        $response = $this->delete(route('categories.destroy', $category));

        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('success', 'Category deleted successfully.');

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}
