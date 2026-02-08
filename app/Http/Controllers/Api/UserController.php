<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Users',
    description: 'API Endpoints for User Management'
)]
class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    #[OA\Get(
        path: '/users',
        summary: 'Get all users',
        description: 'Returns a paginated list of users',
        tags: ['Users'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 15)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of users',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'meta', type: 'object'),
                        new OA\Property(property: 'links', type: 'object'),
                    ]
                )
            ),
        ],
    )]
    public function index(Request $request): AnonymousResourceCollection
    {
        $users = $this->userService->getAllUsers($request->query('per_page', 15));

        return UserResource::collection($users);
    }

    #[OA\Get(
        path: '/users/{id}',
        summary: 'Get user details',
        description: 'Returns user details',
        tags: ['Users'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User details',
                content: new OA\JsonContent(properties: [new OA\Property(property: 'data', type: 'object')])
            ),
            new OA\Response(response: 404, description: 'User not found'),
        ],
    )]
    public function show(int $id): UserResource|JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (! $user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        return new UserResource($user);
    }

    #[OA\Put(
        path: '/users/{id}',
        summary: 'Update user',
        description: 'Updates user details',
        tags: ['Users'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'email', type: 'string', format: 'email'),
                    new OA\Property(property: 'password', type: 'string', format: 'password'),
                    new OA\Property(property: 'mobile_number', type: 'string'),
                    new OA\Property(property: 'profile_picture', type: 'string'),
                    new OA\Property(property: 'role_id', type: 'integer'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'User updated',
                content: new OA\JsonContent(properties: [new OA\Property(property: 'data', type: 'object')])
            ),
            new OA\Response(response: 404, description: 'User not found'),
            new OA\Response(response: 422, description: 'Validation error'),
        ],
    )]
    public function update(UpdateUserRequest $request, int $id): UserResource|JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (! $user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $this->userService->updateUser($user, $request->validated());

        return new UserResource($user->refresh());
    }

    #[OA\Delete(
        path: '/users/{id}',
        summary: 'Delete user',
        description: 'Deletes a user',
        tags: ['Users'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'User deleted successfully'),
            new OA\Response(response: 404, description: 'User not found'),
            new OA\Response(response: 403, description: 'Unauthorized'),
        ],
    )]
    public function destroy(int $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (! $user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        try {
            $this->userService->deleteUser($user);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'User deleted successfully.']);
    }
}
