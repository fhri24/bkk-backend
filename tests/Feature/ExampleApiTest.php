<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test getting all items
     */
    public function test_get_all_items(): void
    {
        User::factory(5)->create();

        $response = $this->getJson('/api/items');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'name', 'email', 'created_at', 'updated_at'],
                ],
                'pagination' => [
                    'total',
                    'per_page',
                    'current_page',
                    'last_page',
                ],
            ])
            ->assertJson(['success' => true]);
    }

    /**
     * Test creating an item
     */
    public function test_create_item(): void
    {
        $itemData = [
            'name' => 'Item Name',
            'email' => 'item@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/items', $itemData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'name', 'email', 'created_at', 'updated_at'],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Item created successfully',
                'data' => [
                    'name' => 'Item Name',
                    'email' => 'item@example.com',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'item@example.com',
        ]);
    }

    /**
     * Test creating item with invalid data
     */
    public function test_create_item_validation_fails(): void
    {
        $response = $this->postJson('/api/items', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    /**
     * Test getting a single item
     */
    public function test_get_single_item(): void
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/items/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'name', 'email', 'created_at', 'updated_at'],
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ]);
    }

    /**
     * Test getting non-existent item
     */
    public function test_get_non_existent_item(): void
    {
        $response = $this->getJson('/api/items/99999');

        $response->assertStatus(404);
    }

    /**
     * Test updating an item
     */
    public function test_update_item(): void
    {
        $user = User::factory()->create();

        $updateData = [
            'name' => 'Updated Item',
            'email' => 'updated@example.com',
        ];

        $response = $this->putJson("/api/items/{$user->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'name', 'email', 'created_at', 'updated_at'],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Item updated successfully',
                'data' => [
                    'id' => $user->id,
                    'name' => 'Updated Item',
                    'email' => 'updated@example.com',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'updated@example.com',
        ]);
    }

    /**
     * Test deleting an item
     */
    public function test_delete_item(): void
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/items/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Item deleted successfully',
            ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    /**
     * Test searching items
     */
    public function test_search_items(): void
    {
        User::factory()->create(['name' => 'Item One', 'email' => 'item1@example.com']);
        User::factory()->create(['name' => 'Item Two', 'email' => 'item2@example.com']);
        User::factory()->create(['name' => 'Other Item', 'email' => 'other@example.com']);

        $response = $this->getJson('/api/items/search/item');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'query',
                'results' => [
                    '*' => ['id', 'name', 'email'],
                ],
                'count',
            ])
            ->assertJson([
                'success' => true,
                'query' => 'item',
            ]);

        // Should return 3 results (all contain "item")
        $this->assertEquals(3, $response['count']);
    }

    /**
     * Test health check endpoint
     */
    public function test_health_check(): void
    {
        $response = $this->getJson('/api/health');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'timestamp',
            ])
            ->assertJson([
                'status' => 'healthy',
            ]);
    }
}
