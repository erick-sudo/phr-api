<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

abstract class Controller
{
    public function transformPagination(LengthAwarePaginator $paginator)
    {
        return [
            'total' => $paginator->total(),
            'per_page' => $paginator->perPage(),
            'items' => $paginator->items(),
            'current_page' => $paginator->currentPage()
        ];
    }

    public function generateUsersJwtToken(User $user)
    {
        // Payload
        $payload = [
            'email' => $user->email
        ];

        // Secret key
        $secretKey = env('JWT_SECRET', 'jwt-secret-key');

        // Token expiration time (10 minutes from now)
        $expirationTime = time() + (10 * 60);

        // Build the token using APP_KEY as the secret key
        $token = JWT::encode($payload, $secretKey, 'HS256', $expirationTime);

        $signature = hash_hmac("sha256", "$token:{$user->email}", $secretKey);

        // Construct the URL with token, expiration time, and signature
        $url = "http://localhost:5173/activate_account?token=" . urlencode($token) . "&expires=" . urlencode($expirationTime) . "&signature=" . urlencode($signature);

        return [
            'token' => $token,
            'url' => $url
        ];
    }

    public function sendMail($recipient, $subject, $body)
    {

        $apiKey = env('HTTP_MAIL_CLIENT_API_KEY');

        $client = new Client([
            'base_uri' => env('HTTP_MAIL_CLIENT'),
            'timeout'  => 10,
            'headers' => [
                'Content-Type'  => 'multipart/form-data',
                'Authorization' => 'App ' . $apiKey
            ]
        ]);

        // <erickochiengobuya@gmail.com>

        try {
            $response = $client->post('email/3/send', [
                'multipart' => [
                    [
                        'name' => 'from',
                        'contents' => 'CINEN <erickochiengobuya@gmail.com>'
                    ],
                    [
                        'name' => 'to',
                        'contents' => 'erickochiengobuya@gmail.com'
                    ],
                    [
                        'name' => 'subject',
                        'contents' => $subject
                    ],
                    [
                        'name' => 'html',
                        'contents' => "<h1>Please follow the link below to activate your account:</h1><p>{$body}</p>"
                    ],
                    [
                        'name' => 'bulkId',
                        'contents' => 'customBulkId'
                    ],
                    [
                        'name' => 'intermediateReport',
                        'contents' => 'true'
                    ]
                ],
            ]);

            Log::error($response->getStatusCode());
            return $response;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                Log::error('Unexpected HTTP status: ' . $e->getResponse()->getStatusCode() . ' ' . $e->getResponse()->getBody());
            } else {
                Log::error('Error: ' . $e->getMessage());
            }
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }
}
