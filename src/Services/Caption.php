<?php

namespace StoneC0der\CloudflareStream\Services;

use GuzzleHttp\RequestOptions;
use StoneC0der\CloudflareStream\Base;

trait Caption
{
    use Base;
    
    /**
     * Create a new watermarks
     *
     * @param string $uid
     * @param string $lang The language tag in BCP 47 format.
     * @return array
     */
    public function generate(string $uid, string $lang): array
    {
        $response = $this->http->post(
            "accounts/$this->accountId/stream/$uid/captions/$lang/generate",
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
     * Get all watermarks
     *
     * @param string $uid
     * @param string $lang The language tag in BCP 47 format.
     * @return array
     */
    public function getCaptions(string $uid): array
    {
        $response = $this->http->get(
            "accounts/$this->accountId/stream/$uid/captions",
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
     * Get all watermarks
     *
     * @param string $uid
     * @param string $lang The language tag in BCP 47 format.
     * @return array
     */
    public function getForLanguage(string $uid, string $lang): array
    {
        $response = $this->http->get(
            "accounts/$this->accountId/stream/$uid/captions/$lang",
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
     * 
     */
    public function getVtt(string $uid, string $lang): array
    {
        $response = $this->http->get(
            "accounts/$this->accountId/stream/$uid/captions/$lang",
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
     * Get watermarks details
     *
     * @param string $uid
     * @param string $lang The language tag in BCP 47 format.
     * @param array $data
     * @return array
     */
    public function uploadCaption(string $uid, string $lang, array $data): array
    {
        $response = $this->http->put(
            "accounts/$this->accountId/stream/$uid/caption/$lang",
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
     * Delete an existing watermarks
     *
     * @param string $uid
     * @param string $lang The language tag in BCP 47 format.
     * @return array
     */
    public function deleteCaption(string $uid, string $lang): array
    {
        $response = $this->http->delete(
            "accounts/$this->accountId/stream/$uid/captions/$lang",
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
