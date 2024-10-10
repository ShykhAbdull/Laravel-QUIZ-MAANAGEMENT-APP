<?php



namespace App\Http\Middleware;



use Closure;

use Illuminate\Support\Facades\Auth;



class CustomPermissionMiddleware

{

    public function handle($request, Closure $next, $permission)

    {

        // Check if the authenticated user has the required permission

        if (Auth::check() && Auth::user()->can($permission)) {

            return $next($request);

        }



        // Return custom JSON response for forbidden access

        return response()->json([

            'success' => false,

            'message' => 'You do not have permission to attempt this quiz.'

        ], 403); // 403 Forbidden

    }

}