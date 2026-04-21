<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenExpiration
{
    /**
     * Vérifie si le token d'authentification est expiré
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            $token = $request->user()->currentAccessToken();
            
            if ($token && $token->expires_at && $token->expires_at->isPast()) {
                // Supprimer le token expiré
                $token->delete();
                
                return response()->json([
                    'message' => 'Votre session a expiré. Veuillez vous reconnecter.',
                    'error' => 'token_expired'
                ], 401);
            }
        }

        return $next($request);
    }
}
