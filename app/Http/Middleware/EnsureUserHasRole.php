<?php

namespace App\Http\Middleware;

use App\Enums\RoleEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        \Illuminate\Support\Facades\Log::info('EnsureUserHasRole middleware started', ['roles' => $roles, 'user_id' => $request->user()?->id]);

        $user = $request->user();

        if (! $user) {
            \Illuminate\Support\Facades\Log::info('EnsureUserHasRole: No user found');
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        foreach ($roles as $role) {
            $roleEnum = RoleEnum::tryFrom($role);
            \Illuminate\Support\Facades\Log::info('Checking role', ['role_param' => $role, 'user_role' => $user->role?->slug]);

            if ($roleEnum && $user->hasRole($roleEnum)) {
                return $next($request);
            }
        }

        \Illuminate\Support\Facades\Log::info('EnsureUserHasRole: Unauthorized');
        return response()->json(['message' => 'Unauthorized.'], 403);
    }
}
