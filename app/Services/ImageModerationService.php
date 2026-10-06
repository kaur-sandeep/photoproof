<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class ImageModerationService
{
    public function check(UploadedFile $file): array
    {
        if (! config('image_moderation.enabled')) {
            // Disabling moderation is an explicit operational choice, not a service failure.
            return $this->approved([], []);
        }

        $url = rtrim((string) config('image_moderation.url'), '/');
        if (! $this->isPrivateModerationUrl($url)) {
            Log::warning('Image moderation unavailable: unsafe service URL configured.', ['url' => $url]);
            return $this->failed();
        }

        $realPath = $file->getRealPath();
        Log::info('Image moderation request started', [
            'url' => $url . '/moderate',
            'file_exists' => $file->isValid(),
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        if (! $file->isValid() || $realPath === false || ! is_file($realPath)) {
            Log::error('Image moderation unavailable: uploaded temporary file is not available.', [
                'upload_error' => $file->getError(),
            ]);
            return $this->failed();
        }

        $handle = null;
        try {
            $handle = fopen($realPath, 'rb');
            if ($handle === false) {
                Log::error('Image moderation unavailable: could not open uploaded temporary file.');
                return $this->failed();
            }

            $response = Http::timeout((int) config('image_moderation.timeout'))
                ->connectTimeout((int) config('image_moderation.connect_timeout'))
                ->acceptJson()
                ->attach('file', $handle, $file->getClientOriginalName())
                ->post($url . '/moderate');

            Log::info('Image moderation response', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body' => $response->body(),
            ]);

            if (! $response->successful() || ! is_array($payload = $response->json())
                || ! array_key_exists('success', $payload) || ! array_key_exists('allowed', $payload)
                || $payload['success'] !== true || ! is_bool($payload['allowed'])) {
                Log::warning('Image moderation unavailable: invalid local service response.', [
                    'http_status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return $this->failed();
            }

            $categories = is_array($payload['categories'] ?? null) ? $payload['categories'] : [];
            $detections = is_array($payload['detections'] ?? null) ? $payload['detections'] : [];
            $category = $detections[0]['category'] ?? $this->categoryFromReason($payload['reason'] ?? null);
            $confidence = isset($detections[0]['confidence']) ? (float) $detections[0]['confidence']
                : ($category !== null && isset($categories[$category]) ? (float) $categories[$category] : null);
            if ($payload['allowed']) {
                Log::info('Image moderation approved.');
                return $this->approved($categories, $detections, $payload, $category, $confidence);
            }

            Log::info('Image moderation rejected.', ['reason' => $payload['reason'] ?? 'unknown', 'category' => $category]);
            return [
                'allowed' => false, 'status' => 'rejected', 'reason' => $payload['reason'] ?? 'prohibited_content',
                'category' => $category, 'confidence' => $confidence, 'categories' => $categories,
                'detections' => $detections, 'response' => $payload,
            ];
        } catch (ConnectionException $exception) {
            Log::error('Image moderation connection exception', [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
            ]);
            return $this->failed();
        } catch (Throwable $exception) {
            Log::error('Image moderation request exception', [
                'exception' => get_class($exception),
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
            ]);
            return $this->failed();
        } finally {
            if (is_resource($handle)) {
                fclose($handle);
            }
        }
    }

    private function approved(array $categories, array $detections, array $response = [], ?string $category = null, ?float $confidence = null): array
    {
        return ['allowed' => true, 'status' => 'approved', 'reason' => null, 'category' => $category,
            'confidence' => $confidence, 'categories' => $categories, 'detections' => $detections, 'response' => $response];
    }

    private function failed(): array
    {
        return ['allowed' => false, 'status' => 'failed', 'reason' => 'service_unavailable',
            'category' => null, 'confidence' => null, 'categories' => [], 'detections' => [], 'response' => []];
    }

    private function categoryFromReason(mixed $reason): ?string
    {
        return is_string($reason) && preg_match('/^(weapon|nsfw|violence)_detected$/', $reason, $matches)
            ? $matches[1] : null;
    }

    private function isPrivateModerationUrl(string $url): bool
    {
        $parts = parse_url($url);
        if (! is_array($parts) || ! in_array($parts['scheme'] ?? null, ['http', 'https'], true) || isset($parts['user'], $parts['pass'])) {
            return false;
        }
        $host = strtolower($parts['host'] ?? '');
        if ($host === 'localhost' || $host === '::1') return true;
        if (! filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) return false;
        $ip = ip2long($host);
        if ($ip === false) return false;
        // Allow the complete IPv4 loopback range as well as RFC1918 ranges.
        return ($ip >= ip2long('127.0.0.0') && $ip <= ip2long('127.255.255.255'))
            || ($ip >= ip2long('10.0.0.0') && $ip <= ip2long('10.255.255.255'))
            || ($ip >= ip2long('172.16.0.0') && $ip <= ip2long('172.31.255.255'))
            || ($ip >= ip2long('192.168.0.0') && $ip <= ip2long('192.168.255.255'));
    }
}
