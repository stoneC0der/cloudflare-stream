<?php

namespace StoneC0der\CloudflareStream;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Carbon\Carbon;
use InvalidArgumentException;
use JsonException;

class Stream extends Base
{
    public const POSITION = [];
    // 通过链接上传

    public function verifyToken(string $token): array
    {
        try {
            $response = $this->http->get('token/verify', [
                RequestOptions::HEADERS => [
                    'Authorization' => "Bearer $this->authKey",
                    'Content-Type' => 'application/json',
                ],
            ]);

            return $this->response($response);
        } catch (JsonException $e) {
            throw new InvalidArgumentException($e->getMessage());
        } catch (ClientException $e) {
            throw new InvalidArgumentException($e->getResponse()->getBody()->getContents());
        }
    }

    /**
     * @throws GuzzleException
     */
    public function uploadViaLink(string $url, array $options = []): array
    {
        try {
            $response = $this->http->post(
                'stream/copy',
                [
                    RequestOptions::JSON => array_filter([
                        'url' => $url,
                        'allowedOrigins' => $options['allowedOrigins'] ?? null,
                        'creator' => $options['creator'] ?? null,
                        'meta' => $options['meta'] ?? null,
                        'requireSignedURLs' => $options['require_signed_urls'] ?? false,
                        'scheduledDeletion' => $options['expiry'] ?? null,
                        'thumbnailTimestampPct' => $options['thumbnailTimestampPct'] ?? 0,
                    ]),
                    RequestOptions::HEADERS => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'Tus-Resumable' => '1.0.0',
                        'Upload-Creator' => 'creator-id_' . $options['creator'] ?? null,
                        'Upload-Length' => '',
                        'Upload-Metadata' => '',
                        'X-Auth-Email' => $this->authEmail,
                        'Authorization' => "Bearer $this->authKey",
                    ],
                ]
            );
            return $this->response($response);
        } catch (JsonException $e) {
            throw new InvalidArgumentException($e->getMessage());
        } catch (ClientException $e) {
            throw new InvalidArgumentException($e->getResponse()->getBody()->getContents());
        }
        return [];
    }

    /**
     * @throws GuzzleException
     */
    public function getVideo(string $uuid): array
    {
        try {
            $response = $this->http->get("stream/$uuid", [
                RequestOptions::HEADERS => [
                    'Content-Type' => 'application/json',
                    'X-Auth-Email' => $this->authEmail,
                    'Authorization' => "Bearer $this->authKey",
                ]
            ]);
            return $this->response($response);
        } catch (JsonException $e) {
            throw new InvalidArgumentException($e->getMessage());
        } catch (ClientException $e) {
            throw new InvalidArgumentException($e->getResponse()->getBody()->getContents());
        }
        return [];
    }

    /**
     * Get signed token to be used for video URL
     *
     * Access Rules structure
     * "accessRules": [
     * {
     * "type": "ip.geoip.country",
     * "country": ["US", "MX"],
     * "action": "allow",
     * },
     * {
     * "type": "ip.src",
     * "ip": ["93.184.216.0/24", "2400:cb00::/32"],
     * "action": "allow",
     * },
     * {
     * "type": "any",
     * "action": "block",
     * },
     * ]
     * Supported types for access rules are any, ip.src, ip.geoip.country
     * Supported action are allow and block
     *
     * @param  string  $id
     * @param  int  $expiresIn
     * @param  bool  $downloadable
     * @param  array  $accessRules
     * @return array
     */
    public function getStreamSignedToken(
        string $id,
        int $expiresIn = 3600,
        bool $downloadable = false,
        array $accessRules = []
    ): array {
        try {
            $response = $this->http->post("{$id}/token", [
                "exp" => time() + $expiresIn,
                "downloadable" => $downloadable,
                "accessRules" => $accessRules
            ]);
            return $this->response($response);
        } catch (GuzzleException | JsonException $e) {
        }
        return [];
    }

    // 服务端上传
    public function uploadVideoFile($file): ?array
    {
        try {
            $response = $this->http->post(
                'stream',
                [
                    RequestOptions::MULTIPART => [
                        [
                            'name' => 'file',
                            'contents' => $file,
                        ]
                    ]
                ]
            );
            return $this->response($response);
        } catch (GuzzleException | JsonException $e) {
        }
        return null;
    }

    // 生成上传链接
    public function directCreatorUploads(
        int $maxDurationSeconds = 1,
        int $expirySecond = 0,
        bool $requireSignedURLs = false,
        array $allowedOrigins = [],
        float $thumbnailTimestampPct = 0.0,
        string $watermark = null,
        array $meta = []
    ): ?array {
        $data = [];
        // 用户上传的视频的最长持续时间
        $data['maxDurationSeconds'] = $maxDurationSeconds;
        if ($data['maxDurationSeconds'] < 1) {
            $data['maxDurationSeconds'] = 1;
        }
        if ($data['maxDurationSeconds'] > 21600) {
            $data['maxDurationSeconds'] = 21600;
        }
        // URL 过期的时间
        if ($expirySecond > 0) {
            // 过期时间不能小于2分钟
            if ($expirySecond < 120) {
                $expirySecond = 120;
            }
            if ($expirySecond > 21600) {
                $expirySecond = 21600;
            }
            $data['expiry'] = Carbon::now()->subSeconds($expirySecond)->toRfc3339String();
        }
        // 是否限制访问域名
        $data['requireSignedURLs'] = $requireSignedURLs;
        // 可以访问的域名列表
        $data['allowedOrigins'] = $allowedOrigins;
        // 视频截图的百分比位置0.0-1.0
        $data['thumbnailTimestampPct'] = $thumbnailTimestampPct;
        // 视频中的水印文件
        $data['watermark']['uid'] = $watermark;
        // 元数据设置
        $data['meta'] = $meta;

        try {
            $response = $this->http->post(
                'direct_upload',
                [
                    RequestOptions::JSON => $data
                ]
            );
            return $this->response($response);
        } catch (GuzzleException | JsonException $e) {
        }
        return null;
    }

    // 搜索视频
    public function search(string $qs): ?array
    {
        try {
            return $this->response($this->http->get('stream?search=' . $qs));
        } catch (GuzzleException | JsonException $e) {
        }
        return null;
    }

    // 设置视频权限
    public function setVideoAuth($uid): ?array
    {
        try {
            return $this->response($this->http->post('stream/' . $uid, [
                RequestOptions::JSON => [
                    'uid' => $uid,
                    'requireSignedURLs' => true,
                ]
            ]));
        } catch (GuzzleException | JsonException $e) {
        }
        return null;
    }

    // 上传水印图片
    public function watermarkUpload(
        $file,
        string $name = '',
        float $opacity = 1.0,
        float $padding = 0.05,
        float $scale = 0.15,
        int $position = 0
    ): ?array {
        try {
            $response = $this->http->post(
                'watermarks',
                [
                    RequestOptions::MULTIPART => [
                        ['name' => 'file', 'contents' => $file,],
                        ['name' => 'name', 'contents' => $name,],
                        ['name' => 'opacity', 'contents' => $opacity,],
                        ['name' => 'padding', 'contents' => $padding,],
                        ['name' => 'scale', 'contents' => $scale,],
                        ['name' => 'position', 'contents' => self::POSITION[$position],],
                    ]
                ]
            );
            return $this->response($response);
        } catch (GuzzleException | JsonException $e) {
        }
        return null;
    }

    // 上传水印图片
    public function watermarkViaLinkUpload(
        $url,
        string $name = '',
        float $opacity = 1.0,
        float $padding = 0.05,
        float $scale = 0.15,
        int $position = 0
    ): ?array {
        try {
            $response = $this->http->post(
                'watermarks',
                [
                    RequestOptions::JSON => [
                        'url' => $url,
                        'name' => $name,
                        'opacity' => $opacity,
                        'padding' => $padding,
                        'scale' => $scale,
                        'position' => self::POSITION[$position],
                    ]
                ]
            );
            return $this->response($response);
        } catch (GuzzleException | JsonException $e) {
        }
        return null;
    }

    // 水印配置信息
    public function watermarkInfo(string $uid)
    {
        try {
            return $this->response($this->http->get('watermarks/' . $uid));
        } catch (GuzzleException | JsonException $e) {
        }
        return null;
    }

    // 所有水印配置
    public function watermarks(): ?array
    {
        try {
            return $this->response($this->http->get('watermarks/'));
        } catch (GuzzleException | JsonException $e) {
        }
        return null;
    }

    // 删除水印配置
    public function deleteWatermark($uid): ?array
    {
        try {
            return $this->response($this->http->delete('watermarks/' . $uid));
        } catch (GuzzleException | JsonException $e) {
        }
        return null;
    }
}
