<?php

namespace Tests\Feature\Admin;

use App\Models\UserInfo;
use App\Models\Product;
use App\Models\Budget;
use App\Models\Expense;
use App\Models\Income;
use App\Models\MarketplaceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = UserInfo::factory()->create([
            'email' => 'adminb@gmail.com'
        ]);

        // Create regular user
        $this->user = UserInfo::factory()->create();

        // Create category
        $this->category = MarketplaceCategory::create([
            'name' => 'Electronics',
            'description' => 'Electronic items'
        ]);
    }

    public function test_admin_can_view_dashboard()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.index');
    }

    public function test_non_admin_cannot_view_dashboard()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('admin.index'));

        $response->assertRedirect('/home');
        $response->assertSessionHas('error', 'Unauthorized access. Admin privileges required.');
    }

    public function test_admin_can_delete_product()
    {
        $this->actingAs($this->admin);

        $product = Product::factory()->create([
            'seller_id' => $this->user->id,
            'category' => 'Electronics'
        ]);

        $response = $this->delete(route('admin.deleteProduct', $product->id));

        $response->assertRedirect(route('admin.index'));
        $response->assertSessionHas('success', 'Product deleted successfully');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_admin_can_delete_user()
    {
        $this->actingAs($this->admin);

        $userToDelete = UserInfo::factory()->create();
        $product = Product::factory()->create([
            'seller_id' => $userToDelete->id,
            'category' => 'Electronics'
        ]);

        $response = $this->delete(route('admin.deleteUser', $userToDelete->id));

        $response->assertRedirect(route('admin.index'));
        $response->assertSessionHas('success', 'User and all their products deleted successfully');
        $this->assertDatabaseMissing('users_info', ['id' => $userToDelete->id]);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_admin_cannot_delete_admin_user()
    {
        $this->actingAs($this->admin);

        $response = $this->delete(route('admin.deleteUser', $this->admin->id));

        $response->assertRedirect(route('admin.index'));
        $response->assertSessionHas('error', 'Cannot delete admin user');
        $this->assertDatabaseHas('users_info', ['id' => $this->admin->id]);
    }

    public function test_admin_can_view_user_details()
    {
        $this->actingAs($this->admin);

        // Create some user data
        $userToView = UserInfo::factory()->create();
        
        Product::factory()->create([
            'seller_id' => $userToView->id,
            'category' => 'Electronics'
        ]);

        Budget::factory()->create([
            'user_id' => $userToView->id,
            'amount' => 1000
        ]);

        Expense::factory()->create([
            'user_id' => $userToView->id,
            'amount' => 500
        ]);

        Income::factory()->create([
            'user_id' => $userToView->id,
            'amount' => 2000
        ]);

        $response = $this->get(route('admin.viewUser', $userToView->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.user-profile');
        $response->assertViewHas('user');
        $response->assertViewHas('totalProducts', 1);
        $response->assertViewHas('totalExpenses', 500);
        $response->assertViewHas('totalIncome', 2000);
        $response->assertViewHas('totalBudgets', 1);
    }

    public function test_admin_can_search_products()
    {
        $this->actingAs($this->admin);

        $product1 = Product::factory()->create([
            'title' => 'iPhone 12',
            'category' => 'Electronics'
        ]);

        $product2 = Product::factory()->create([
            'title' => 'Samsung TV',
            'category' => 'Electronics'
        ]);

        $response = $this->get(route('admin.index', ['search' => 'iPhone']));

        $response->assertStatus(200);
        $response->assertViewHas('products');
        $response->assertSee('iPhone 12');
        $response->assertDontSee('Samsung TV');
    }

    public function test_admin_can_search_users()
    {
        $this->actingAs($this->admin);

        $user1 = UserInfo::factory()->create([
            'username' => 'john_doe'
        ]);

        $user2 = UserInfo::factory()->create([
            'username' => 'jane_smith'
        ]);

        $response = $this->get(route('admin.index', ['user_search' => 'john']));

        $response->assertStatus(200);
        $response->assertViewHas('users');
        $response->assertSee('john_doe');
        $response->assertDontSee('jane_smith');
    }
}
