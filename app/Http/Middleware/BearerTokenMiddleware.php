<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Log;

class BearerTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $allowedRoutes = [
            'login-api'
        ];

        // Check if the route is in the allowed list
        $currentRouteName = $request->route()->getName();
        if (in_array($currentRouteName, $allowedRoutes)) {
            return $next($request);
        }

        if (!$request->hasHeader('Authorization')) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        $bearerToken = $request->bearerToken();

        if (empty($bearerToken)) {
            return response()->json([
                'status' => 0,
                'message' => 'There is no bearer token.'
            ]);
        }

        $pipePosition = strpos($bearerToken, '|');

        if ($pipePosition !== false) {
            $token = substr($bearerToken, $pipePosition + 1);
        } else {
            return response()->json([
              'status' => 0,
              'message' => 'Invalid Bearer token format.'
            ]);
        }


        $tokenModel = Sanctum::personalAccessTokenModel();
        $accessToken = $tokenModel::where('token', hash('sha256', $token))->first();

        if (!$accessToken) {
            return response()->json([
              'status' => 0,
              'message' => 'Bearer token is invalid.'
            ]);
        }

        $user = $accessToken->tokenable;

        if (!$user) {
            return response()->json([
                'status' => 0,
                'message' => 'User associated with the token not found.'
            ]);
        }

        return $next($request);
    }
}
