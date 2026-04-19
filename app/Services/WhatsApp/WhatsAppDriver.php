<?php

namespace App\Services\WhatsApp;

abstract class WhatsAppDriver
{
    /**
     * Send a text message.
     */
    abstract public function sendText(string $to, string $message): array;

    /**
     * Send a media message (image, document, etc.).
     */
    abstract public function sendMedia(string $to, string $mediaUrl, ?string $caption = null, string $mediaType = 'image'): array;

    /**
     * Send a document.
     */
    abstract public function sendDocument(string $to, string $documentUrl, ?string $filename = null, ?string $caption = null): array;

    /**
     * Send a list message with sections and rows.
     */
    abstract public function sendList(
        string $to,
        string $title,
        string $description,
        string $buttonText,
        array $sections,
        ?string $footerText = null
    ): array;

    /**
     * Send a location message.
     */
    abstract public function sendLocation(
        string $to,
        float $latitude,
        float $longitude,
        ?string $name = null,
        ?string $address = null
    ): array;

    /**
     * Check if the connection/session is active.
     */
    abstract public function isConnected(): bool;

    /**
     * Get the driver name identifier.
     */
    abstract public function getDriverName(): string;

    /**
     * Format phone number to WhatsApp format (with @s.whatsapp.net or @g.us).
     */
    protected function formatNumber(string $number): string
    {
        // Remove any non-numeric characters except @ (for group IDs)
        if (str_contains($number, '@')) {
            return $number;
        }

        $number = preg_replace('/[^0-9]/', '', $number);

        return $number;
    }
}
