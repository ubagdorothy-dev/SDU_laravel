<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\TrainingRecord;
use App\Models\TrainingProof;

class TrainingProofControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that authenticated users can upload proof for completed training records.
     *
     * @return void
     */
    public function test_users_can_upload_proof_for_completed_training_records()
    {
        Storage::fake('public');
        
        $user = User::factory()->create();
        $trainingRecord = TrainingRecord::factory()->create([
            'user_id' => $user->user_id,
            'status' => 'completed'
        ]);
        
        $file = UploadedFile::fake()->image('proof.jpg');
        
        $response = $this->actingAs($user)->post("/training_records/{$trainingRecord->id}/upload-proof", [
            'proof_file' => $file,
        ]);
        
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        // Assert the file was stored
        Storage::disk('public')->assertExists('training_proofs/proof_' . $trainingRecord->id . '_' . $user->user_id . '_*');
        
        // Assert the proof record was created
        $this->assertDatabaseHas('training_proofs', [
            'training_id' => $trainingRecord->id,
            'user_id' => $user->user_id,
            'status' => 'pending',
        ]);
        
        // Assert the training record was updated
        $this->assertDatabaseHas('training_records', [
            'id' => $trainingRecord->id,
            'proof_uploaded' => true,
        ]);
    }

    /**
     * Test that users cannot upload proof for non-completed training records.
     *
     * @return void
     */
    public function test_users_cannot_upload_proof_for_non_completed_training_records()
    {
        $user = User::factory()->create();
        $trainingRecord = TrainingRecord::factory()->create([
            'user_id' => $user->user_id,
            'status' => 'upcoming'
        ]);
        
        $file = UploadedFile::fake()->image('proof.jpg');
        
        $response = $this->actingAs($user)->post("/training_records/{$trainingRecord->id}/upload-proof", [
            'proof_file' => $file,
        ]);
        
        $response->assertRedirect();
        $response->assertSessionHasErrors('error');
    }

    /**
     * Test that users cannot upload proof for other users' training records.
     *
     * @return void
     */
    public function test_users_cannot_upload_proof_for_other_users_training_records()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $trainingRecord = TrainingRecord::factory()->create([
            'user_id' => $user1->user_id,
            'status' => 'completed'
        ]);
        
        $file = UploadedFile::fake()->image('proof.jpg');
        
        $response = $this->actingAs($user2)->post("/training_records/{$trainingRecord->id}/upload-proof", [
            'proof_file' => $file,
        ]);
        
        $response->assertStatus(404); // Should not find the record
    }

    /**
     * Test that authenticated users can download their training proofs.
     *
     * @return void
     */
    public function test_users_can_download_their_training_proofs()
    {
        Storage::fake('public');
        
        $user = User::factory()->create();
        $trainingRecord = TrainingRecord::factory()->create([
            'user_id' => $user->user_id,
            'status' => 'completed'
        ]);
        
        // Create a training proof
        $trainingProof = TrainingProof::factory()->create([
            'training_id' => $trainingRecord->id,
            'user_id' => $user->user_id,
            'file_path' => 'training_proofs/test_proof.jpg'
        ]);
        
        // Create a fake file
        Storage::disk('public')->put('training_proofs/test_proof.jpg', 'fake content');
        
        $response = $this->actingAs($user)->get("/training_proofs/{$trainingProof->id}/download");
        
        $response->assertOk();
        $response->assertHeader('content-type', 'image/jpeg');
    }

    /**
     * Test that users cannot download other users' training proofs.
     *
     * @return void
     */
    public function test_users_cannot_download_other_users_training_proofs()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $trainingRecord = TrainingRecord::factory()->create([
            'user_id' => $user1->user_id,
            'status' => 'completed'
        ]);
        
        // Create a training proof for user1
        $trainingProof = TrainingProof::factory()->create([
            'training_id' => $trainingRecord->id,
            'user_id' => $user1->user_id,
            'file_path' => 'training_proofs/test_proof.jpg'
        ]);
        
        $response = $this->actingAs($user2)->get("/training_proofs/{$trainingProof->id}/download");
        
        $response->assertStatus(404); // Should not find the record
    }
}