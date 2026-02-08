<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('logs out the authenticated user successfully', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $this->postJson('/api/logout')
        ->assertSuccessful()
        ->assertJson([
            'message' => 'Logged out successfully.',
        ]);
});

it('rejects logout for unauthenticated users', function () {
    $this->postJson('/api/logout')
        ->assertUnauthorized();
});
