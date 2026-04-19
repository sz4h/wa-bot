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

        // The endpoint for list messages might be different from /message or /media
        // Based on the provided curl example, it seems to be /message/sendList/{instance}
        // We need to construct the base URL correctly. If {instance} is part of the path,
        // the baseUrl might need to be adjusted or the instanceId appended.
        // Assuming baseUrl is like 'https://sub.domain.com' and instanceId is 'your-instance-name'
        // The final URL would be 'https://sub.domain.com/message/sendList/your-instance-name'

        // Dynamically construct the URL if the instance name is part of the path
        // This assumes the baseUrl in config might be like 'https://sub.domain.com'
        // and the instance name is 'your-instance-name'
        // If your baseUrl already includes path segments (e.g. 'https://sub.domain.com/api'),
        // you might need to adjust this logic.
        $listEndpoint = rtrim($this->baseUrl, '/') . "/message/sendList/{$this->instanceId}";

        $payload = [
            'number' => $formattedNumber,
            'title' => $title,
            'description' => $description,
            'buttonText' => $buttonText,
            'footerText' => $footerText,
            'sections' => $sections,
        ];

        $response = Http::post($listEndpoint, [
            'instanceId' => $this->instanceId, // This might be redundant if already in URL, check API docs
            'token' => $this->token,          // This might be redundant if already in URL, check API docs
            // The actual payload data might need to be structured differently
            // e.g., inside a 'data' key or directly as params.
            // For now, assuming direct params as in the curl example.
            // The curl example doesn't show instance name in the URL, but it's mentioned.
            // Let's use the provided structure assuming the '/message/sendList/{instance}' part needs the instance name.
        ]);

        // The curl example sends data directly to the endpoint. Let's structure the request to match
        // the curl's data payload. The instance name in the URL is crucial.
        // We'll reconstruct the request to match the curl example's general structure.

        // It seems the instance name should be in the URL, not directly in the payload for this endpoint.
        // Let's also ensure the token is passed correctly.
        // The provided curl example suggests the token is not sent in the post body for this specific endpoint.
        // It might be implicitly handled by the instance name in the URL or via headers.
        // Let's re-evaluate based on the curl example.

        // Re-construct based on the provided curl command:
        // The curl command's structure implies the endpoint is the primary carrier of instance info.
        // The data payload is sent as form data.
        $response = Http::asForm()->post($listEndpoint, [
            'number' => $formattedNumber,
            'title' => $title,
            'description' => $description,
            'buttonText' => $buttonText,
            'footerText' => $footerText,
            'sections' => json_encode($sections), // Sections need to be JSON encoded for form data
        ]);

        // The token might need to be in the header for auth.
        // Example: Http::withToken($this->token)->post(...)
        // If the token is expected in the URL, it should be appended there.
        // As the curl example doesn't show it in the URL or data, let's assume it's handled by the instance
        // or needs to be added as a header if not implicitly handled.
        // For now, let's assume the instanceId in the URL is enough for routing and authentication might be header-based if needed.
        // If this fails, we'll need to investigate how the token is expected (header, query param, etc.)

        return $response->json();
    }


    public function isConnected(): bool
    {
        try {
            // For Evolution API, the instance check might be against the base platform URL,
            // or a specific instance endpoint. The Postman export might clarify this.
            // Let's assume a generic endpoint for now.
            $response = Http::get("{$this->baseUrl}/instance", [
                'instanceId' => $this->instanceId,
                'token' => $this->token,
            ]);

            $data = $response->json();
            // Check for a successful response and a status indicating connection
            // Common statuses might be 'connected', 'active', 'running', etc.
            // This check might need adjustment based on the actual API response for the instance status.
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
