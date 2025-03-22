<?php

namespace StoneC0der\CloudflareStream;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Carbon\Carbon;
use InvalidArgumentException;
use JsonException;
use StoneC0der\CloudflareStream\Services\Caption;
use StoneC0der\CloudflareStream\Services\LiveInput;
use StoneC0der\CloudflareStream\Services\Media;
use StoneC0der\CloudflareStream\Services\Utils;

class Stream
{
    use Caption, LiveInput, Media, Utils;
    
    public const POSITION = [];
    // 通过链接上传

    public function checkToken(): array
    {
        try {
            return $this->verifyToken();
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
            $data = array_filter([
                'url' => $url,
                'allowedOrigins' => $options['allowedOrigins'] ?? null,
                'creator' => $options['creator'] ?? null,
                'meta' => $options['meta'] ?? null,
                'requireSignedURLs' => $options['require_signed_urls'] ?? false,
                'scheduledDeletion' => $options['expiry'] ?? null,
                'thumbnailTimestampPct' => $options['thumbnailTimestampPct'] ?? 0,
            ]);
            return $this->copy($data);
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
    public function getVideo(string $uid): array
    {
        try {
            return $this->getVideo($uid);
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
    // public function getStreamSignedToken(
    //     string $id,
    //     int $expiresIn = 3600,
    //     bool $downloadable = false,
    //     array $accessRules = []
    // ): array {
    //     try {
    //         $response = $this->http->post("{$id}/token", [
    //             "exp" => time() + $expiresIn,
    //             "downloadable" => $downloadable,
    //             "accessRules" => $accessRules
    //         ]);
    //         return $this->response($response);
    //     } catch (GuzzleException | JsonException $e) {
    //     }
    //     return [];
    // }

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
            return $this->directUpload($data);
        } catch (GuzzleException | JsonException $e) {
        }
        return null;
    }

    // 搜索视频
    public function search(string $qs): ?array
    {
        try {
            return $this->searchVideo($qs);
        } catch (GuzzleException | JsonException $e) {
        }
        return null;
    }

    // // 设置视频权限
    // public function setVideoAuth($uid): ?array
    // {
    //     try {
    //         return $this->response($this->http->post('accounts/$this->accountId/stream/' . $uid, [
    //             RequestOptions::JSON => [
    //                 'uid' => $uid,
    //                 'requireSignedURLs' => true,
    //             ]
    //         ]));
    //     } catch (GuzzleException | JsonException $e) {
    //     }
    //     return null;
    // }

    // 上传水印图片
    public function addWatermark(
        $file,
        string $name = '',
        float $opacity = 1.0,
        float $padding = 0.05,
        float $scale = 0.15,
        int $position = 0
    ): ?array {
        try {
            $data = [
                ['name' => 'file', 'contents' => $file,],
                ['name' => 'name', 'contents' => $name,],
                ['name' => 'opacity', 'contents' => $opacity,],
                ['name' => 'padding', 'contents' => $padding,],
                ['name' => 'scale', 'contents' => $scale,],
                ['name' => 'position', 'contents' => self::POSITION[$position],],
            ];
            return $this->createWatermark($data);
        } catch (GuzzleException | JsonException $e) {
        }
        return null;
    }

    // 水印配置信息
    public function watermark(string $uid)
    {
        try {
            return $this->getWatermark($uid);
        } catch (GuzzleException | JsonException $e) {
        }
        return null;
    }

    // 所有水印配置
    public function watermarks(): ?array
    {
        try {
            return $this->getWatermarks();
        } catch (GuzzleException | JsonException $e) {
        }
        return null;
    }

    // 删除水印配置
    public function removeWatermark($uid): ?array
    {
        try {
            return $this->deleteWatermark($uid);
        } catch (GuzzleException | JsonException $e) {
        }
        return null;
    }
}
