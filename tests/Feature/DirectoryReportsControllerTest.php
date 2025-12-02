<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Office;

class DirectoryReportsControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that authenticated users can view directory reports.
     *
     * @return void
     */
    public function test_authenticated_users_can_view_directory_reports()
    {
        $user = User::factory()->create();
        
        // Create some offices
        Office::factory()->count(3)->create();
        
        $response = $this->actingAs($user)->get('/directory-reports');
        
        $response->assertStatus(200);
        $response->assertSee('Directory & Reports');
    }

    /**
     * Test that guest users cannot view directory reports.
     *
     * @return void
     */
    public function test_guest_users_cannot_view_directory_reports()
    {
        $response = $this->get('/directory-reports');
        
        $response->assertRedirect('/login');
    }
}