<?php

namespace App\Http\Middleware;

use App\Models\RequestLogs;
use Closure;
use Illuminate\Http\Request;

class RequestLogsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // // Log the request details
        // $requestBody = $request->all();  // Collect the entire request body
        // $requestBody = !empty($requestBody) ? json_encode($requestBody) : null;  // Handle null or empty request body

        $req_body = $request->getContent();

        $saveRequest =  RequestLogs::create([
            'request_method' => $request->method(),
            'request_url' => $request->fullUrl(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'request_body' => empty($req_body)  ?  null  :  $req_body,
            'request_time' => now(),
        ]);

        // Attach the request log ID to the request
        $request->merge(['request_log_idd' => $saveRequest->id]);

        // Proceed to the next middleware or controller
        return $next($request);
    }



    public function terminate($request, $response)
    {
        // Check if the request has a valid request_log_idd before trying to update
        if ($request->has('request_log_idd')) {
            // Find the corresponding request log and update with response data
            RequestLogs::find($request->request_log_idd)->update([
                'response_body' => $response->getContent(),
                'response_status_code' => $response->getStatusCode()
            ]);
        }
    }
}
