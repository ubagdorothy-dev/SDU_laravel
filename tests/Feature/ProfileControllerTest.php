<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Office;
use Illuminate\Support\Facades\Hash;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that authenticated users can view their profile.
     *
     * @return void
     */
    public function test_authenticated_users_can_view_their_profile()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/profile');
        
        $response->assertStatus(200);
        $response->assertSee($user->full_name);
        $response->assertSee($user->email);
    }

    /**
     * Test that guest users cannot view profiles.
     *
     * @return void
     */
    public function test_guest_users_cannot_view_profiles()
    {
        $response = $this->get('/profile');
        
        $response->assertRedirect('/login');
    }

    /**
     * Test that users can edit their profile information.
     *
     * @return void
     */
    public function test_users_can_edit_their_profile_information()
    {
        $office = Office::factory()->create(['code' => 'TEST', 'name' => 'Test Office']);
        $user = User::factory()->create(['office_code' => $office->code]);
        
        $response = $this->actingAs($user)->put('/profile', [
            'full_name' => 'Updated Name',
            'email' => 'updated@example.com',
            'office_code' => $office->code,
        ]);
        
        $response->assertRedirect('/profile');
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('users', [
            'user_id' => $user->user_id,
            'full_name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    /**
     * Test that users can change their password.
     *
     * @return void
     */
    public function test_users_can_change_their_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);
        
        $response = $this->actingAs($user)->post('/profile/change-password', [
            'current_password' => 'oldpassword',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'newpassword',
        ]);
        
        $response->assertRedirect('/profile');
        $response->assertSessionHas('success');
        
        // Check that the password was actually changed
        $this->assertTrue(Hash::check('newpassword', $user->fresh()->password));
    }

    /**
     * Test that users cannot change their password with incorrect current password.
     *
     * @return void
     */
    public function test_users_cannot_change_password_with_incorrect_current_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);
        
        $response = $this->actingAs($user)->post('/profile/change-password', [
            'current_password' => 'wrongpassword',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'newpassword',
        ]);
        
        $response->assertRedirect('/profile/change-password');
        $response->assertSessionHasErrors('current_password');
    }
}