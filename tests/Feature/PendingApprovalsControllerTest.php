<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\TrainingRecord;

class PendingApprovalsControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that unit directors can view pending approvals.
     *
     * @return void
     */
    public function test_unit_directors_can_view_pending_approvals()
    {
        $user = User::factory()->create(['role' => 'unit_director']);
        
        // Create some pending training records
        TrainingRecord::factory()->count(3)->create(['status' => 'pending']);
        
        $response = $this->actingAs($user)->get('/pending-approvals');
        
        $response->assertStatus(200);
        $response->assertSee('Pending Approvals');
    }

    /**
     * Test that non-unit directors cannot view pending approvals.
     *
     * @return void
     */
    public function test_non_unit_directors_cannot_view_pending_approvals()
    {
        $user = User::factory()->create(['role' => 'staff']);
        
        $response = $this->actingAs($user)->get('/pending-approvals');
        
        $response->assertStatus(403); // Forbidden
    }

    /**
     * Test that unit directors can approve pending training records.
     *
     * @return void
     */
    public function test_unit_directors_can_approve_pending_training_records()
    {
        $user = User::factory()->create(['role' => 'unit_director']);
        $trainingRecord = TrainingRecord::factory()->create(['status' => 'pending']);
        
        $response = $this->actingAs($user)->post("/pending-approvals/{$trainingRecord->id}/approve");
        
        $response->assertRedirect('/pending-approvals');
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('training_records', [
            'id' => $trainingRecord->id,
            'status' => 'approved'
        ]);
    }

    /**
     * Test that unit directors can reject pending training records.
     *
     * @return void
     */
    public function test_unit_directors_can_reject_pending_training_records()
    {
        $user = User::factory()->create(['role' => 'unit_director']);
        $trainingRecord = TrainingRecord::factory()->create(['status' => 'pending']);
        
        $response = $this->actingAs($user)->post("/pending-approvals/{$trainingRecord->id}/reject");
        
        $response->assertRedirect('/pending-approvals');
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('training_records', [
            'id' => $trainingRecord->id,
            'status' => 'rejected'
        ]);
    }

    /**
     * Test that non-unit directors cannot approve training records.
     *
     * @return void
     */
    public function test_non_unit_directors_cannot_approve_training_records()
    {
        $user = User::factory()->create(['role' => 'staff']);
        $trainingRecord = TrainingRecord::factory()->create(['status' => 'pending']);
        
        $response = $this->actingAs($user)->post("/pending-approvals/{$trainingRecord->id}/approve");
        
        $response->assertStatus(403); // Forbidden
    }
}