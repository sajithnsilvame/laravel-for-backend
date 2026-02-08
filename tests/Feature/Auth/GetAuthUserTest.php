<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('returns the authenticated user with role', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->getJson('/api/user');

    $response->assertSuccessful()
        ->assertJsonStructure([
            'user' => ['id', 'name', 'email', 'role'],
        ])
        ->assertJsonPath('user.id', $user->id);
});

it('rejects unauthenticated requests', function () {
    $this->getJson('/api/user')
        ->assertUnauthorized();
});
