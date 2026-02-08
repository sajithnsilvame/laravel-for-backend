<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class HealthCheckController extends Controller
{
    #[OA\Get(
        path: '/health',
        summary: 'Health check',
        description: 'Returns the API health status',
        tags: ['General'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'API is running',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'ok'),
                    ],
                ),
            ),
        ],
    )]
    public function __invoke(): JsonResponse
    {
        return response()->json(['status' => 'ok']);
    }
}
