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
        // The base URL might need to include the instance name if it's part of the path
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

    /**
     * Send a list message with sections and rows.
     */
    public function sendList(
        string $to,
        string $title,
        string $description,
        string $buttonText,
        array $sections,
        ?string $footerText = null
    ): array {
        $formattedNumber = $this->formatNumber($to);

        $listEndpoint = rtrim($this->baseUrl, '/') . "/message/sendList/{$this->instanceId}";

        $payload = [
            'number' => $formattedNumber,
            'title' => $title,
            'description' => $description,
            'buttonText' => $buttonText,
            'footerText' => $footerText,
            'sections' => $sections,
        ];

        $response = Http::asForm()->post($listEndpoint, [
            'number' => $formattedNumber,
            'title' => $title,
            'description' => $description,
            'buttonText' => $buttonText,
            'footerText' => $footerText,
            'sections' => json_encode($sections), // Sections need to be JSON encoded for form data
        ]);

        return $response->json();
    }

    /**
     * Send a location message.
     */
    public function sendLocation(
        string $to,
        float $latitude,
        float $longitude,
        ?string $name = null,
        ?string $address = null
    ): array {
        $formattedNumber = $this->formatNumber($to);

        // The endpoint for location messages seems to be /message/sendLocation/{instance}
        $locationEndpoint = rtrim($this->baseUrl, '/') . "/message/sendLocation/{$this->instanceId}";

        $response = Http::asForm()->post($locationEndpoint, [
            'number' => $formattedNumber,
            'name' => $name,
            'address' => $address,
            'latitude' => $latitude,
            'longitude' => $longitude,
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

            $data = $response->json();
            return $response->successful() && ($data['status'] ?? $data['state'] ?? '') === 'connected';

        } catch (\Exception $e) {
            return false;
        }
    }

    public function getDriverName(): string
    {
        return 'evolutionapi';
    }
}
