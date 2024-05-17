<?php

namespace StoneC0der\CloudflareStream;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use JsonException;
use Psr\Http\Message\ResponseInterface;

class Base
{
    protected Client $http;
    protected ?string $accountId;
    protected ?string $authKey;
    protected ?string $authEmail;

    public function __construct()
    {
        $this->accountId = config('cloudflare-stream.accountId');
        $this->authKey = config('cloudflare-stream.authKey');
        $this->authEmail = config('cloudflare-stream.authEMail');

        $this->http = new Client(['base_uri' => "https://api.cloudflare.com/client/v4/accounts/$this->accountId/stream/",
            RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Tus-Resumable' => '1.0.0',
                'Upload-Creator' => 'creator-id_'. auth()->user()->first_name
                    ?? auth()->user()->name ?? 'N/A'
                    .auth()->id() ?? auth()->user()->uuid ?? '',
                'Upload-Length' => '',
                'Upload-Metadata' => '',
                'X-Auth-Email' => $this->authEmail,
                'Authorization' => "Bearer $this->authKey",
            ],
        ]);
    }

    /**
     * @throws JsonException
     */
    protected function response(ResponseInterface $response): array
    {
        if ($response->getReasonPhrase() === 'OK') {
            return json_decode((string)$response->getBody(), true, 100, JSON_THROW_ON_ERROR);
        }
        return [];
    }
}
