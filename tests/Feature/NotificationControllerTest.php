<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Notification;

class NotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that authenticated users can get their notifications.
     *
     * @return void
     */
    public function test_authenticated_users_can_get_their_notifications()
    {
        $user = User::factory()->create();
        
        // Create some notifications for the user
        Notification::factory()->count(3)->create(['user_id' => $user->user_id]);
        
        $response = $this->actingAs($user)->get('/notifications');
        
        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /**
     * Test that guest users cannot get notifications.
     *
     * @return void
     */
    public function test_guest_users_cannot_get_notifications()
    {
        $response = $this->get('/notifications');
        
        $response->assertRedirect('/login');
    }

    /**
     * Test that users can mark notifications as read.
     *
     * @return void
     */
    public function test_users_can_mark_notifications_as_read()
    {
        $user = User::factory()->create();
        
        // Create some unread notifications for the user
        $notifications = Notification::factory()->count(2)->create([
            'user_id' => $user->user_id,
            'is_read' => false
        ]);
        
        $notificationIds = $notifications->pluck('id')->toArray();
        
        $response = $this->actingAs($user)->post('/notifications/mark-read', [
            'notification_ids' => $notificationIds
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        // Check that notifications were marked as read
        foreach ($notificationIds as $id) {
            $this->assertDatabaseHas('notifications', [
                'id' => $id,
                'is_read' => true
            ]);
        }
    }

    /**
     * Test that users can delete their notifications.
     *
     * @return void
     */
    public function test_users_can_delete_their_notifications()
    {
        $user = User::factory()->create();
        
        // Create some notifications for the user
        $notifications = Notification::factory()->count(2)->create([
            'user_id' => $user->user_id
        ]);
        
        $notificationIds = $notifications->pluck('id')->toArray();
        
        $response = $this->actingAs($user)->post('/notifications/delete', [
            'notification_ids' => $notificationIds
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        // Check that notifications were deleted
        foreach ($notificationIds as $id) {
            $this->assertDatabaseMissing('notifications', [
                'id' => $id
            ]);
        }
    }

    /**
     * Test that users can get their unread notification count.
     *
     * @return void
     */
    public function test_users_can_get_unread_notification_count()
    {
        $user = User::factory()->create();
        
        // Create some notifications for the user (mix of read and unread)
        Notification::factory()->count(2)->create([
            'user_id' => $user->user_id,
            'is_read' => false
        ]);
        
        Notification::factory()->count(3)->create([
            'user_id' => $user->user_id,
            'is_read' => true
        ]);
        
        $response = $this->actingAs($user)->get('/notifications/unread-count');
        
        $response->assertStatus(200);
        $response->assertJson(['count' => 2]);
    }
}