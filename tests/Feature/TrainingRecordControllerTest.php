<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\TrainingRecord;

class TrainingRecordControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that authenticated users can view training records.
     *
     * @return void
     */
    public function test_authenticated_users_can_view_training_records()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/training_records');
        
        $response->assertStatus(200);
    }

    /**
     * Test that guest users cannot view training records.
     *
     * @return void
     */
    public function test_guest_users_cannot_view_training_records()
    {
        $response = $this->get('/training_records');
        
        $response->assertRedirect('/login');
    }

    /**
     * Test that users can create training records.
     *
     * @return void
     */
    public function test_users_can_create_training_records()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/training_records', [
            'title' => 'Test Training',
            'description' => 'This is a test training',
            'start_date' => '2025-12-01',
            'end_date' => '2025-12-05',
            'venue' => 'Test Venue',
            'nature' => 'Internal',
            'scope' => 'Local',
        ]);
        
        $response->assertRedirect('/training_records');
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('training_records', [
            'title' => 'Test Training',
            'user_id' => $user->user_id,
        ]);
    }

    /**
     * Test that users can view a specific training record.
     *
     * @return void
     */
    public function test_users_can_view_specific_training_record()
    {
        $user = User::factory()->create();
        $trainingRecord = TrainingRecord::factory()->create(['user_id' => $user->user_id]);
        
        $response = $this->actingAs($user)->get("/training_records/{$trainingRecord->id}");
        
        $response->assertStatus(200);
        $response->assertSee($trainingRecord->title);
    }

    /**
     * Test that users can edit their own training records.
     *
     * @return void
     */
    public function test_users_can_edit_their_own_training_records()
    {
        $user = User::factory()->create();
        $trainingRecord = TrainingRecord::factory()->create(['user_id' => $user->user_id]);
        
        $response = $this->actingAs($user)->put("/training_records/{$trainingRecord->id}", [
            'title' => 'Updated Training Title',
            'description' => 'Updated description',
            'start_date' => '2025-12-10',
            'end_date' => '2025-12-15',
            'venue' => 'Updated Venue',
            'nature' => 'External',
            'scope' => 'Regional',
            'status' => 'upcoming',
        ]);
        
        $response->assertRedirect('/training_records');
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('training_records', [
            'id' => $trainingRecord->id,
            'title' => 'Updated Training Title',
        ]);
    }

    /**
     * Test that users cannot edit other users' training records.
     *
     * @return void
     */
    public function test_users_cannot_edit_other_users_training_records()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $trainingRecord = TrainingRecord::factory()->create(['user_id' => $user1->user_id]);
        
        $response = $this->actingAs($user2)->put("/training_records/{$trainingRecord->id}", [
            'title' => 'Unauthorized Update',
        ]);
        
        $response->assertStatus(404); // Should not find the record
    }

    /**
     * Test that users can delete their own training records.
     *
     * @return void
     */
    public function test_users_can_delete_their_own_training_records()
    {
        $user = User::factory()->create();
        $trainingRecord = TrainingRecord::factory()->create(['user_id' => $user->user_id]);
        
        $response = $this->actingAs($user)->delete("/training_records/{$trainingRecord->id}");
        
        $response->assertRedirect('/training_records');
        $response->assertSessionHas('success');
        
        $this->assertDatabaseMissing('training_records', [
            'id' => $trainingRecord->id,
        ]);
    }
}