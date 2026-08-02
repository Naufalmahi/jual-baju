<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class MidtransService
{
    private $merchantId;
    private $clientKey;
    private $serverKey;
    private $isProduction;
    private $baseUrl;

    public function __construct()
    {
        $this->merchantId = config('midtrans.merchant_id');
        $this->clientKey = config('midtrans.client_key');
        $this->serverKey = config('midtrans.server_key');
        $this->isProduction = config('midtrans.is_production');
        $this->baseUrl = $this->isProduction 
            ? 'https://app.midtrans.com/api/v2'
            : 'https://app.sandbox.midtrans.com/api/v2';

        // Validate credentials
        if (!$this->merchantId || !$this->clientKey || !$this->serverKey) {
            Log::error('Midtrans credentials missing', [
                'merchant_id' => $this->merchantId ? 'set' : 'missing',
                'client_key' => $this->clientKey ? 'set' : 'missing',
                'server_key' => $this->serverKey ? 'set' : 'missing',
            ]);
            throw new Exception('Midtrans credentials not properly configured in .env');
        }
    }

    /**
     * Create a transaction token for Snap
     */
    public function createSnapTransaction(array $transactionData)
    {
        try {
            $url = $this->isProduction
                ? 'https://app.midtrans.com/snap/v1/transactions'
                : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
            
            $authHeader = 'Basic ' . base64_encode($this->serverKey . ':');
            
            $headers = [
                'Authorization' => $authHeader,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ];

            Log::info('=== MIDTRANS SNAP API REQUEST ===', [
                'url' => $url,
                'merchant_id' => $this->merchantId,
                'auth_header_first_20_chars' => substr($authHeader, 0, 20) . '...',
            ]);

            Log::info('Snap request payload:', $transactionData);

            $response = Http::withHeaders($headers)->timeout(30)->post($url, $transactionData);

            Log::info('=== MIDTRANS SNAP API RESPONSE ===', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->failed()) {
                $statusCode = $response->status();
                $responseBody = $response->body();
                
                try {
                    $errorData = json_decode($responseBody, true);
                } catch (\Exception $e) {
                    $errorData = $responseBody;
                }
                
                $errorMessage = $errorData['id'] ?? $errorData['errors'] ?? $errorData['error_message'] ?? $responseBody ?? "Unknown error (HTTP {$statusCode})";
                
                Log::error('=== MIDTRANS SNAP API ERROR ===', [
                    'status' => $statusCode,
                    'url' => $url,
                    'error_message' => $errorMessage,
                    'full_response' => $errorData,
                ]);
                
                throw new Exception('Midtrans Snap API Error (HTTP ' . $statusCode . '): ' . json_encode($errorMessage));
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('=== MIDTRANS SNAP EXCEPTION ===', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Create a payment charge
     */
    public function createCharge(array $chargeData)
    {
        return $this->sendRequest('charge', $chargeData, 'POST');
    }

    /**
     * Get transaction status
     */
    public function getTransactionStatus($transactionId)
    {
        return $this->sendRequest($transactionId . '/status', [], 'GET');
    }

    /**
     * Approve transaction (for e-wallet/transfer)
     */
    public function approveTransaction($transactionId)
    {
        return $this->sendRequest($transactionId . '/approve', [], 'POST');
    }

    /**
     * Cancel transaction
     */
    public function cancelTransaction($transactionId)
    {
        return $this->sendRequest($transactionId . '/cancel', [], 'POST');
    }

    /**
     * Refund transaction
     */
    public function refundTransaction($transactionId, $refundAmount = null)
    {
        $data = [];
        if ($refundAmount) {
            $data['refund_amount'] = $refundAmount;
        }
        return $this->sendRequest($transactionId . '/refund', $data, 'POST');
    }

    /**
     * Send HTTP request to Midtrans API
     */
    private function sendRequest($endpoint, array $data = [], $method = 'GET')
    {
        try {
            $url = "{$this->baseUrl}/{$endpoint}";
            
            $authHeader = 'Basic ' . base64_encode($this->serverKey . ':');
            
            $headers = [
                'Authorization' => $authHeader,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ];

            Log::info('=== MIDTRANS API REQUEST ===', [
                'method' => $method,
                'endpoint' => $endpoint,
                'url' => $url,
                'merchant_id' => $this->merchantId,
                'auth_header_first_20_chars' => substr($authHeader, 0, 20) . '...',
            ]);

            if (!empty($data)) {
                Log::info('Request payload:', $data);
            }

            if ($method === 'GET') {
                $response = Http::withHeaders($headers)->timeout(30)->get($url);
            } else {
                $response = Http::withHeaders($headers)->timeout(30)->post($url, $data);
            }

            Log::info('=== MIDTRANS API RESPONSE ===', [
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body(),
            ]);

            if ($response->failed()) {
                $statusCode = $response->status();
                $responseBody = $response->body();
                
                try {
                    $errorData = json_decode($responseBody, true);
                } catch (\Exception $e) {
                    $errorData = $responseBody;
                }
                
                $errorMessage = $errorData['id'] ?? $errorData['errors'] ?? $errorData['error_message'] ?? $responseBody ?? "Unknown error (HTTP {$statusCode})";
                
                Log::error('=== MIDTRANS API ERROR ===', [
                    'status' => $statusCode,
                    'endpoint' => $endpoint,
                    'error_message' => $errorMessage,
                    'full_response' => $errorData,
                ]);
                
                throw new Exception('Midtrans API Error (HTTP ' . $statusCode . '): ' . json_encode($errorMessage));
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('=== MIDTRANS REQUEST EXCEPTION ===', [
                'message' => $e->getMessage(),
                'endpoint' => $endpoint,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Get Client Key for frontend
     */
    public function getClientKey()
    {
        return $this->clientKey;
    }

    /**
     * Get Snap.js URL for frontend
     */
    public function getSnapUrl()
    {
        return $this->isProduction
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }

    /**
     * Get Merchant ID
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * Get API Base URL
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature($orderId, $statusCode, $grossAmount, $serverKey, $signatureKey)
    {
        $signature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        return $signature === $signatureKey;
    }
}
