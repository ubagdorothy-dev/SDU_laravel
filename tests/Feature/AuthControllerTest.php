<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Office;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the login page is accessible.
     *
     * @return void
     */
    public function test_login_page_is_accessible()
    {
        $response = $this->get('/login');
        
        $response->assertStatus(200);
    }

    /**
     * Test that the registration page is accessible.
     *
     * @return void
     */
    public function test_registration_page_is_accessible()
    {
        // Create a sample office for the registration form
        Office::factory()->create(['code' => 'TEST', 'name' => 'Test Office']);
        
        $response = $this->get('/register');
        
        $response->assertStatus(200);
    }

    /**
     * Test user login with valid credentials.
     *
     * @return void
     */
    public function test_user_can_login_with_valid_credentials()
    {
        // Create an office for registration
        $office = Office::factory()->create(['code' => 'TEST']);
        
        // Register a new user
        $this->post('/register', [
            'full_name' => 'Test User',
            'email' => 'staff.testuser@sdu.edu.ph',
            'password' => 'password',
            'office_code' => $office->code,
        ]);
        
        // Approve the user
        $user = User::where('email', 'staff.testuser@sdu.edu.ph')->first();
        $user->is_approved = true;
        $user->save();

        $response = $this->post('/login', [
            'email' => 'staff.testuser@sdu.edu.ph',
            'password' => 'password',
        ]);

        $response->assertRedirect('/staff/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test user cannot login with invalid credentials.
     *
     * @return void
     */
    public function test_user_cannot_login_with_invalid_credentials()
    {
        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test that authenticated users cannot access login page.
     *
     * @return void
     */
    public function test_authenticated_users_cannot_access_login_page()
    {
        $user = User::factory()->create([
            'email' => 'staff.testuser@sdu.edu.ph',
            'is_approved' => true,
        ]);
        
        $response = $this->actingAs($user)->get('/login');
        
        $response->assertRedirect('/'); // Redirect to home page
    }
}