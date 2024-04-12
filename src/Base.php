<?php

namespace StoneC0der\CloudflareStream;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class Base
{
    protected $http;
    private $accountId;
    private $authKey;
    private $authEMail;

    public function __construct(array $config = [])
    {
        if (empty($config)) {
            $this->accountId = config('cloudflare-stream.accountId');
            $this->authKey = config('cloudflare-stream.authKey');
            $this->authEMail = config('cloudflare-stream.authEMail');
        } else {
            $this->accountId = $config['account_id'];
            $this->authKey = $config['auth_key'];
            $this->authEMail = $config['auth_email'];
        }

        $this->http = new Client([
            'base_uri' => `https://api.cloudflare.com/client/v4/accounts/{$this->accountId}/stream/`,
            RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'X-Auth-Email' => $this->authEMail,
                'X-Auth-Key' => $this->authKey,
            ],
        ]);
    }

    protected function response(ResponseInterface $response) {
        if ($response->getReasonPhrase() === 'OK') {
            return json_decode((string)$response->getBody(), true);
        }

        return null;
    }
}
