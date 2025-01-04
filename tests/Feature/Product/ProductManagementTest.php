<?php

namespace Tests\Feature\Product;

use App\Models\UserInfo;
use App\Models\Product;
use App\Models\ProductReport;
use App\Models\MarketplaceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = UserInfo::factory()->create(['is_verified_seller' => true]);
        
        // Create a test category
        $this->category = MarketplaceCategory::create([
            'name' => 'Electronics',
            'description' => 'Electronic items'
        ]);
    }

    public function test_verified_seller_can_create_product()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('products.store'), [
            'title' => 'Test Product',
            'description' => 'Test Description',
            'price' => 100,
            'category' => 'Electronics',
            'condition_status' => 'New',
            'location' => 'Test Location'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', [
            'seller_id' => $this->user->id,
            'title' => 'Test Product',
            'price' => 100,
            'category' => 'Electronics'
        ]);
    }

    public function test_user_can_view_product_details()
    {
        $this->actingAs($this->user);

        $product = Product::factory()->create([
            'seller_id' => $this->user->id,
            'title' => 'Test Product',
            'category' => 'Electronics'
        ]);

        $response = $this->get(route('products.show', $product));

        $response->assertStatus(200);
        $response->assertSee('Test Product');
    }

    public function test_user_can_report_product()
    {
        $this->actingAs($this->user);

        $product = Product::factory()->create([
            'category' => 'Electronics'
        ]);

        $response = $this->post(route('product.report'), [
            'product_id' => $product->id,
            'reason' => 'Inappropriate content',
            'description' => 'This product contains inappropriate content'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('product_reports', [
            'product_id' => $product->id,
            'user_id' => $this->user->id,
            'reason' => 'Inappropriate content'
        ]);
    }

    public function test_seller_can_edit_own_product()
    {
        $this->actingAs($this->user);

        $product = Product::factory()->create([
            'seller_id' => $this->user->id,
            'category' => 'Electronics'
        ]);

        $response = $this->put(route('products.update', $product), [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'price' => 150,
            'category' => 'Electronics',
            'condition_status' => 'New',
            'location' => 'Updated Location'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'title' => 'Updated Title',
            'price' => 150
        ]);
    }

    public function test_seller_can_delete_own_product()
    {
        $this->actingAs($this->user);

        $product = Product::factory()->create([
            'seller_id' => $this->user->id,
            'category' => 'Electronics'
        ]);

        $response = $this->delete(route('products.destroy', $product));

        $response->assertRedirect();
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
