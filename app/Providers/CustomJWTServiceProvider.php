<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Firebase\JWT\JWT;      // Richiede composer require firebase/php-jwt
use Firebase\JWT\Key;

class CustomJWTServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('custom.jwt', function ($app) {
            return new class {
                // Firma un token con una secret specifica (es: per utente)
                public function signToken(array $payload, string $secret)
                {
                    return JWT::encode($payload, $secret, 'HS512');
                }

                // Verifica un token con una secret specifica
                public function verifyToken(string $token, string $secret)
                {
                    try {
                        // Decodifica e verifica firma
                        return JWT::decode($token, new Key($secret, 'HS512'));
                    } catch (\Exception $e) {
                        throw $e;
                    }
                }

                // Decodifica il token SENZA verifica (per estrarre id utente)
                public function decodePayload(string $token)
                {
                    $parts = explode('.', $token);
                    if (count($parts) < 2) {
                        return null;
                    }
                    return json_decode(base64_decode($parts[1]), true);
                }
            };
        });
    }

    public function boot()
    {
        //
    }
}
