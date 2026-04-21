<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Tentative de connexion avec rate limiting
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            "login_personnel" => "required|string",
            "password_personnel" => "required|string|min:6"
        ]);

        // Rate Limiting: 5 tentatives par minute par IP
        $key = 'login-attempts:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            
            return response()->json([
                "message" => "Trop de tentatives de connexion. Veuillez réessayer dans {$seconds} secondes.",
                "retry_after" => $seconds
            ], 429);
        }

        $personnel = Personnel::where("login_personnel", $credentials["login_personnel"])->first();

        if (!$personnel || !Hash::check($credentials["password_personnel"], $personnel->password_personnel)) {
            // Incrémenter le compteur de tentatives échouées
            RateLimiter::hit($key, 60); // Bloque pendant 60 secondes après 5 tentatives
            
            return response()->json(["message" => "Identifiants invalides"], 401);
        }

        // Réinitialiser le compteur en cas de succès
        RateLimiter::clear($key);

        // Supprimer les anciens tokens
        DB::table('personal_access_tokens')
            ->where('tokenable_id', $personnel->code_personnel)
            ->delete();

        // Créer un nouveau token avec expiration
        $expiresAt = now()->addMinutes(config('sanctum.expiration', 1440));
        $token = $personnel->createToken('user_token', ['*'], $expiresAt)->plainTextToken;

        // Envoyer une notification par email (mot de passe masqué pour sécurité)
        try {
            $plainPassword = $credentials["password_personnel"];
            \Illuminate\Support\Facades\Mail::to($personnel->login_personnel)->send(new \App\Mail\LoginNotification($personnel, $plainPassword));
            \Illuminate\Support\Facades\Log::info('Login notification sent', ['to' => $personnel->login_personnel]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send login notification', ['exception' => $e->getMessage()]);
        }

        return response()->json([
            "access_token" => $token,
            "token_type" => "Bearer",
            "expires_at" => $expiresAt->toIso8601String(),
            "personnel" => $personnel
        ]);
    }

    /**
     * Déconnexion avec suppression du token
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(["message" => "Déconnexion réussie"], 200);
    }
}
