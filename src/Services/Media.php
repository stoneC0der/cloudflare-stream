<?php

namespace StoneC0der\CloudflareStream\Services;

use GuzzleHttp\RequestOptions;
use StoneC0der\CloudflareStream\Base;

trait Media
{
    use Base;

    public function initiate(array $data = []): array
    {

        $response = $this->http->post(
            'stream',
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

    public function copy(array $data): array
    {

        $response = $this->http->post(
            'stream/copy',
            [
                RequestOptions::JSON => $data,
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Tus-Resumable' => '1.0.0',
                    'Upload-Creator' => 'creator-id_' . $data['creator'] ?? null,
                    'Upload-Length' => '',
                    'Upload-Metadata' => '',
                    'X-Auth-Email' => $this->authEmail,
                    'Authorization' => "Bearer $this->authKey",
                ],
            ]
        );

        return $this->response($response);
    }

    public function directUpload(array $data): array
    {

        $response = $this->http->post(
            'stream/direct_upload',
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
     * Create a new download
     *
     * @param string $uid
     * @param array $data
     * @return array
     */
    public function createDownload(string $uid, array $data): array
    {
        $response = $this->http->post(
            "stream/$uid/downloads",
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
     * Delete an existing download
     *
     * @param string $uid
     * @return array
     */
    public function deleteDownload(string $uid): array
    {
        $response = $this->http->delete(
            "stream/$uid/downloads",
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
     * Get downloads
     *
     * @return array
     */
    public function getDownload(string $uid): array
    {
        $response = $this->http->get(
            "stream/$uid/downloads",
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
    public function updateVideo(string $uid, array $data): array
    {
        $response = $this->http->put(
            "stream/$uid",
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
     * @return array
     */
    public function getVideos(): array
    {
        $response = $this->http->get(
            'stream',
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
    public function getVideo(string $uid): array
    {
        $response = $this->http->get(
            "stream/$uid",
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
    public function deleteVideo(string $uid): array
    {
        $response = $this->http->delete(
            "stream/$uid",
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

    public function searchVideo(string $qs): array
    {
        return $this->response($this->http->get('stream?search=' . $qs));
    }

    /**
     * Create a new watermarks
     *
     * @param string $uid
     * @param array $data
     * @return array
     */
    public function createAudio(string $uid, array $data = []): array
    {
        $response = $this->http->post(
            "stream/$uid/audio/copy",
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
    public function getAudio(string $uid): array
    {
        $response = $this->http->get(
            "stream/$uid/audio",
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
     * @param string $audioUid
     * @param array $data
     * @return array
     */
    public function updateAudio(string $uid, string $audioUid, array $data): array
    {
        $response = $this->http->put(
            "stream/$uid/audio/$audioUid",
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
     * @param string $audioUid
     * @return array
     */
    public function deleteAudio(string $uid, string $audioUid): array
    {
        $response = $this->http->delete(
            "stream/$uid/audio/$audioUid",
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
