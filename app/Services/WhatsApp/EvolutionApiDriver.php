<?php

namespace App\Services\WhatsApp;

use Illuminate\Support\Facades\Http;

class EvolutionApiDriver extends WhatsAppDriver
{
    protected string $instanceId;
    protected string $token;
    protected string $baseUrl;

    public function __construct()
    {
        $this->instanceId = config('services.whatsapp.evolutionapi.instance_id');
        $this->token = config('services.whatsapp.evolutionapi.token');
        $this->baseUrl = config('services.whatsapp.evolutionapi.base_url');

        if (empty($this->instanceId) || empty($this->token) || empty($this->baseUrl)) {
            throw new \Exception('Evolution API credentials not configured.');
        }
    }

    public function sendText(string $to, string $message): array
    {
        $formattedNumber = $this->formatNumber($to);

        $response = Http::post("{$this->baseUrl}/message", [
            'instanceId' => $this->instanceId,
            'token' => $this->token,
            'to' => $formattedNumber,
            'body' => $message,
        ]);

        return $response->json();
    }

    public function sendMedia(string $to, string $mediaUrl, ?string $caption = null, string $mediaType = 'image'): array
    {
        $formattedNumber = $this->formatNumber($to);

        $response = Http::post("{$this->baseUrl}/media", [
            'instanceId' => $this->instanceId,
            'token' => $this->token,
            'to' => $formattedNumber,
            'mediaUrl' => $mediaUrl,
            'mediaType' => $mediaType, // 'image', 'audio', 'video'
            'caption' => $caption ?? '',
        ]);

        return $response->json();
    }

    public function sendDocument(string $to, string $documentUrl, ?string $filename = null, ?string $caption = null): array
    {
        $formattedNumber = $this->formatNumber($to);

        // Evolution API's 'media' endpoint can handle documents if mediaType is set to 'document'
        // Note: some Evolution API versions might have a dedicated 'document' endpoint. Adjust if needed.
        $response = Http::post("{$this->baseUrl}/media", [
            'instanceId' => $this->instanceId,
            'token' => $this->token,
            'to' => $formattedNumber,
            'mediaUrl' => $documentUrl,
            'mediaType' => 'document',
            'filename' => $filename,
            'caption' => $caption ?? '',
        ]);

        return $response->json();
    }

    public function isConnected(): bool
    {
        try {
            $response = Http::get("{$this->baseUrl}/instance", [
                'instanceId' => $this->instanceId,
                'token' => $this->token,
            ]);

            // Check for a successful response and a status indicating connection
            // The exact field might vary based on Evolution API version. Common ones include 'instance', 'status', 'state'.
            // Assuming 'status' field exists and 'connected' is a possible value. Adjust this check as needed.
            $data = $response->json();
            return $response->successful() && ($data['status'] ?? $data['state'] ?? '') === 'connected';

        } catch (\Exception $e) {
            // Log the error if needed
            // \Log::error("Evolution API connection check failed: " . $e->getMessage());
            return false;
        }
    }

    public function getDriverName(): string
    {
        return 'evolutionapi';
    }
}
