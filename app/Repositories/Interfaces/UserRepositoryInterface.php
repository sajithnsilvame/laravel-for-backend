<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): User;

    public function paginate(int $perPage): LengthAwarePaginator;

    public function findById(int $id): ?User;

    public function update(User $user, array $data): bool;

    public function delete(User $user): bool;

    public function findByEmail(string $email): ?User;
}
