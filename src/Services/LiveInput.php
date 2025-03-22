<?php

namespace StoneC0der\CloudflareStream\Services;

use GuzzleHttp\RequestOptions;
use StoneC0der\CloudflareStream\Base;

trait LiveInput
{
    use Base;

    /**
     * Create a new watermarks
     *
     * @param array $data
     * @return array
     */
    public function createLiveInput(array $data): array
    {
        $response = $this->http->post(
            "accounts/$this->accountId/stream/live_inputs",
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
     * Get all watermarks
     *
     * @param string $uid
     * @return array
     */
    public function getLiveInputs(bool $includeCounts = false): array
    {
        $response = $this->http->get(
            "accounts/$this->accountId/stream/live_inputs",
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
     * Get all watermarks
     *
     * @param string $uid
     * @return array
     */
    public function getLiveInput(string $uid): array
    {
        $response = $this->http->get(
            "accounts/$this->accountId/stream/live_inputs/$uid",
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
     * @param array $data
     * @return array
     */
    public function updateLiveInput(string $uid, array $data): array
    {
        $response = $this->http->put(
            "accounts/$this->accountId/stream/live_inputs/$uid",
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

        return $this->response($response);;
    }

    /**
     * Delete an existing watermarks
     *
     * @param string $uid
     * @return array
     */
    public function deleteLiveInput(string $uid): array
    {
        $response = $this->http->delete(
            "accounts/$this->accountId/stream/live_inputs/$uid",
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
     * @param string $uid
     * @param array $data
     * @return array
     */
    public function createOutput(string $uid, array $data): array
    {
        $response = $this->http->post(
            "accounts/$this->accountId/stream/live_inputs/$uid/outputs",
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
     * Get all watermarks
     *
     * @param string $uid
     * @return array
     */
    public function getOutputs(string $uid): array
    {
        $response = $this->http->get(
            "accounts/$this->accountId/stream/live_inputs/$uid/outputs",
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
     * @param string $outputUid
     * @param array $data
     * @return array
     */
    public function updateOutput(string $uid, string $outputUid, array $data): array
    {
        $response = $this->http->put(
            "accounts/$this->accountId/stream/live_inputs/$outputUid/output/$uid",
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

        return $this->response($response);;
    }

    /**
     * Delete an existing watermarks
     *
     * @param string $uid
     * @param string $outputUid
     * @return array
     */
    public function deleteOutput(string $uid, string $outputUid): array
    {
        $response = $this->http->delete(
            "accounts/$this->accountId/stream/live_inputs/$outputUid/output/$uid",
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
