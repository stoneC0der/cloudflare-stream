<?php

namespace StoneC0der\CloudflareStream\Services;

use GuzzleHttp\RequestOptions;
use StoneC0der\CloudflareStream\Base;

trait Utils
{
    use Base;

    public function getEmbed(string $uid): array
    {

        $response = $this->http->get(
            "accounts/$this->accountId/stream/$uid/embed",
            [
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-Auth-Email' => $this->authEmail,
                    'Authorization' => "Bearer $this->authKey",
                ],
            ]
        );

        return $this->response($response);
    }

    public function getStorageUsage(): array
    {
        $response = $this->http->get(
            'accounts/$this->accountId/stream/storage-usage',
            [
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-Auth-Email' => $this->authEmail,
                    'Authorization' => "Bearer $this->authKey",
                ],
            ]
        );

        return $this->response($response);
    }

    public function verifyToken(): array
    {
        $response = $this->http->get(
            'user/token/verify',
            [
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-Auth-Email' => $this->authEmail,
                    'Authorization' => "Bearer $this->authKey",
                ],
            ]
        );

        return $this->response($response);
    }

    public function createSignedUrlToken(string $uid, array $data): array
    {
        $response = $this->http->post(
            "accounts/$this->accountId/stream/$uid/token",
            [
                RequestOptions::JSON => $data,
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-Auth-Email' => $this->authEmail,
                    'Authorization' => "Bearer $this->authKey",
                ],
            ]
        );

        return $this->response($response);
    }

    public function clipVideo(array $data): array
    {
        $response = $this->http->post(
            'accounts/$this->accountId/stream/clip',
            [
                RequestOptions::JSON => $data,
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-Auth-Email' => $this->authEmail,
                    'Authorization' => "Bearer $this->authKey",
                ],
            ]
        );

        return $this->response($response);
    }


    /**
     * Create a new webhook
     *
     * @param array $data
     * @return array
     */
    public function createWebhook(array $data): array
    {
        $response = $this->http->post(
            'accounts/$this->accountId/stream/webhook',
            [
                RequestOptions::JSON => $data,
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-Auth-Email' => $this->authEmail,
                    'Authorization' => "Bearer $this->authKey",
                ],
            ]
        );

        return $this->response($response);
    }

    /**
     * Delete an existing webhook
     *
     * @return array
     */
    public function deleteWebhook(): array
    {
        $response = $this->http->delete(
            'accounts/$this->accountId/stream/webhook',
            [
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-Auth-Email' => $this->authEmail,
                    'Authorization' => "Bearer $this->authKey",
                ],
            ]
        );

        return $this->response($response);
    }

    /**
     * Get webhooks
     *
     * @return array
     */
    public function getWebhooks(): array
    {
        $response = $this->http->get(
            'accounts/$this->accountId/stream/webhook',
            [
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-Auth-Email' => $this->authEmail,
                    'Authorization' => "Bearer $this->authKey",
                ],
            ]
        );

        return $this->response($response);
    }


    /**
     * Create a new watermarks
     *
     * @param array $data
     * @return array
     */
    public function createWatermark(array $data): array
    {
        $response = $this->http->post(
            'accounts/$this->accountId/stream/watermarks',
            [
                RequestOptions::MULTIPART => $data,
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'multipart/form-data',
                    'X-Auth-Email' => $this->authEmail,
                    'Authorization' => "Bearer $this->authKey",
                ],
            ]
        );

        return $this->response($response);
    }

    /**
     * Get all watermarks
     *
     * @return array
     */
    public function getWatermarks(): array
    {
        $response = $this->http->get(
            'accounts/$this->accountId/stream/watermarks',
            [
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-Auth-Email' => $this->authEmail,
                    'Authorization' => "Bearer $this->authKey",
                ],
            ]
        );

        return $this->response($response);;
    }

    /**
     * Get watermarks details
     *
     * @param string $uid
     * @return array
     */
    public function getWatermark(string $uid): array
    {
        $response = $this->http->get(
            "accounts/$this->accountId/stream/watermarks/$uid",
            [
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-Auth-Email' => $this->authEmail,
                    'Authorization' => "Bearer $this->authKey",
                ],
            ]
        );

        return $this->response($response);;
    }

    /**
     * Delete an existing watermarks
     *
     * @param string $uid
     * @return array
     */
    public function deleteWatermark(string $uid): array
    {
        $response = $this->http->delete(
            "accounts/$this->accountId/stream/watermarks/$uid",
            [
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-Auth-Email' => $this->authEmail,
                    'Authorization' => "Bearer $this->authKey",
                ],
            ]
        );

        return $this->response($response);
    }

    /**
     * Create a new key
     *
     * @param array $data
     * @return array
     */
    public function createKey(array $data): array
    {
        $response = $this->http->post(
            'accounts/$this->accountId/stream/keys',
            [
                RequestOptions::JSON => $data,
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-Auth-Email' => $this->authEmail,
                    'Authorization' => "Bearer $this->authKey",
                ],
            ]
        );

        return $this->response($response);
    }

    /**
     * Delete an existing key
     *
     * @param string $uid
     * @return array
     */
    public function deleteKey(string $uid): array
    {
        $response = $this->http->delete(
            "accounts/$this->accountId/stream/keys/$uid",
            [
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-Auth-Email' => $this->authEmail,
                    'Authorization' => "Bearer $this->authKey",
                ],
            ]
        );

        return $this->response($response);
    }

    /**
     * Get keys
     *
     * @return array
     */
    public function getKeys(): array
    {
        $response = $this->http->get(
            'accounts/$this->accountId/stream/keys',
            [
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-Auth-Email' => $this->authEmail,
                    'Authorization' => "Bearer $this->authKey",
                ],
            ]
        );

        return $this->response($response);
    }
}
