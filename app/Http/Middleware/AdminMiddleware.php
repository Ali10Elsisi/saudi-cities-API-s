<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use app\Http\Controllers\api\BaseController;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = Auth::user();
    
            if ($user && $user->role_id === 1) {
                return $next($request);
            } else {
                $baseController = new BaseController();
                return $baseController->sendError("Unauthorized. You need to be an admin.","",401);
            }
        } catch (Exception $e) {
            return (new BaseController())->sendError($e->getMessage());
        }
    }    
}
