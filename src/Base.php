<?php

namespace StoneC0der\CloudflareStream;

use GuzzleHttp\Client;
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
        $this->accountId = env('cloudflare-stream.accountId');
        $this->authKey = env('cloudflare-stream.authKey');
        $this->authEmail = env('cloudflare-stream.authEMail');

        $this->http = new Client([
            'base_uri' => "https://api.cloudflare.com/client/v4/accounts/$this->accountId/"
        ]);
    }

    /**
     * @throws JsonException
     */
    protected function response(ResponseInterface $response): array
    {
        if ($response->getStatusCode() === 200) {
            return json_decode((string)$response->getBody(), true, 100, JSON_THROW_ON_ERROR);
        }
        return [];
    }
}
