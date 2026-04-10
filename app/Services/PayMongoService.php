<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class PayMongoService
{
    private string $baseUrl  = 'https://api.paymongo.com/v1';
    private string $secretKey;

    // ✅ All valid PayMongo payment method types
    private array $validPaymentMethods = [
        'card',
        'gcash',
        'paymaya',   // ← correct spelling
        'grab_pay',  // ← correct spelling
        'dob',
        'dob_ubp',
        'billease',
        'qrph',
    ];

    public function __construct()
    {
        $key = config('services.paymongo.secret_key');

        if (blank($key)) {
            throw new RuntimeException(
                'PayMongo secret key is missing. Run: php artisan config:clear'
            );
        }

        $this->secretKey = $key;
    }

    /*──────────────────────────────────────────
     | Create a Checkout Session
     ──────────────────────────────────────────*/
    public function createCheckoutSession(array $data): array
    {
        $amountInCentavos = (int) round($data['amount'] * 100);

        // Guard: minimum ₱100
        if ($amountInCentavos < 10000) {
            throw new RuntimeException(
                "Amount too small. PayMongo minimum is ₱100. " .
                "Your amount: ₱{$data['amount']} ({$amountInCentavos} centavos)"
            );
        }

        $payload = [
            'data' => [
                'attributes' => [
                    'billing' => [
                        'name'  => $data['customer_name'],
                        'email' => $data['customer_email'],
                    ],
                    'send_email_receipt'  => false,
                    'show_description'    => true,
                    'show_line_items'     => true,
                    'cancel_url'          => $data['cancel_url'],
                    'success_url'         => $data['success_url'],
                    'description'         => $data['description'],
                    'line_items'          => [
                        [
                            'currency' => 'PHP',
                            'amount'   => $amountInCentavos,
                            'name'     => $data['description'],
                            'quantity' => 1,
                        ],
                    ],
                    // ✅ Correct payment method type names
                    'payment_method_types' => [
                        'card',
                        'gcash',
                        'paymaya',
                        'grab_pay',
                    ],
                    'metadata' => [
                        'application_id' => (string) $data['application_id'],
                        'payment_id'     => (string) $data['payment_id'],
                        'reference'      => $data['reference'],
                    ],
                ],
            ],
        ];

        Log::info('PayMongo Checkout Request', [
            'amount_centavos' => $amountInCentavos,
            'success_url'     => $data['success_url'],
            'cancel_url'      => $data['cancel_url'],
        ]);

        $response = Http::withBasicAuth($this->secretKey, '')
            ->withHeaders(['Content-Type' => 'application/json'])
            ->timeout(30)
            ->retry(2, 1000)
            ->post("{$this->baseUrl}/checkout_sessions", $payload);

        Log::info('PayMongo Checkout Response', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        if ($response->failed()) {
            $errorDetail = $response->json('errors.0.detail')
                ?? $response->json('errors.0.code')
                ?? $response->body();

            throw new RuntimeException(
                "PayMongo error: {$errorDetail}"
            );
        }

        return $response->json('data');
    }

    /*──────────────────────────────────────────
     | Retrieve a Checkout Session
     ──────────────────────────────────────────*/
    public function retrieveCheckoutSession(string $checkoutId): array
    {
        $response = Http::withBasicAuth($this->secretKey, '')
            ->withHeaders(['Content-Type' => 'application/json'])
            ->timeout(30)
            ->get("{$this->baseUrl}/checkout_sessions/{$checkoutId}");

        Log::info('PayMongo Retrieve Session', [
            'checkout_id' => $checkoutId,
            'status'      => $response->status(),
            'body'        => $response->body(),
        ]);

        if ($response->failed()) {
            throw new RuntimeException(
                "Failed to retrieve checkout session [{$response->status()}]: {$response->body()}"
            );
        }

        return $response->json('data');
    }
}