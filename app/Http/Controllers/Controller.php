<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'My App API',
    description: 'API Documentation',
)]
#[OA\Server(
    url: '/api',
    description: 'API Server',
)]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'apiKey',
    name: 'Authorization',
    in: 'header',
    description: 'Enter token in format: Bearer <token>',
)]
abstract class Controller
{
    //
}
