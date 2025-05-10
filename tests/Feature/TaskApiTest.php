<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_login_and_perform_task_actions()
    {
        // Register
        $registerResponse = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $registerResponse->assertStatus(200);

        // Login
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);
        $loginResponse->assertStatus(200);

        $token = $loginResponse->json('token');
        $headers = ['Authorization' => 'Bearer ' . $token];

        //Create a task
        $createResponse = $this->postJson('/api/tasks', [
            'title' => 'Test Task',
            'body' => 'This is a test task.',
        ], $headers);
        $createResponse->assertStatus(201);

        $taskId = $createResponse->json('data.id'); // correct path

        //Get tasks and check task is listed
        $getResponse = $this->getJson('/api/tasks', $headers);
        $getResponse->assertStatus(200);
        $getResponse->assertJsonFragment(['title' => 'Test Task']);

        // Get the task before toggling
        $task = $getResponse->json('data.0');
        $originalStatus = $task['is_completed'];

        $toggleResponse = $this->postJson("/api/tasks/{$taskId}/complete", [], $headers);
        $toggleResponse->assertStatus(200);

        //Check that status
        $newStatus = $toggleResponse->json('data.is_completed');
        $this->assertNotEquals($originalStatus, $newStatus);

        //Toggle
        $secondToggle = $this->postJson("/api/tasks/{$taskId}/complete", [], $headers);
        $secondToggle->assertStatus(200);

        $finalStatus = $secondToggle->json('data.is_completed');
        $this->assertEquals($originalStatus, $finalStatus);

        //Logout
        $logoutResponse = $this->postJson('/api/logout', [], $headers);
        $logoutResponse->assertStatus(200);
    }
}
