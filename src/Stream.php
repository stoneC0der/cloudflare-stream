<?php

namespace StoneC0der\CloudflareStream;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Carbon;

class Stream extends Base
{
    // 通过链接上传
    public function uploadViaLink(string $vlink)
    {
        try {
            $response = $this->http->post('copy',
                [
                    RequestOptions::JSON => [
                        'url' => $vlink
                    ]
                ]);
            return $this->response($response);
        } catch (GuzzleException $e) {
        }
        return null;
    }

    // 服务端上传
    public function uploadVideoFile($file)
    {
        try {
            $response = $this->http->post('stream',
                [
                    RequestOptions::MULTIPART => [
                        [
                            'name' => 'file',
                            'contents' => $file,
                        ]
                    ]
                ]);
            return $this->response($response);
        } catch (GuzzleException $e) {
        }
        return null;
    }

    // 生成上传链接
    public function directCreatorUploads(int $maxDurationSeconds = 1, int $expirySecond = 0,
                                         bool $requireSignedURLs = false,
                                         array $allowedOrigins = [],
                                         float $thumbnailTimestampPct = 0.0,
                                         string $watermark = null,
                                         array $meta = [])
    {
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
            $response = $this->http->post('direct_upload',
                [
                    RequestOptions::JSON => $data
                ]);
            return $this->response($response);
        } catch (GuzzleException $e) {
        }
        return null;
    }

    // 搜索视频
    public function search(string $qs)
    {
        try {
            return $this->response($this->http->get('stream?search=' . $qs));
        } catch (GuzzleException $e) {
        }
        return null;
    }

    // 设置视频权限
    public function setVideoAuth($uid)
    {
        try {
            return $this->response($this->http->post('stream/' . $uid, [
                RequestOptions::JSON => [
                    'uid' => $uid,
                    'requireSignedURLs' => true,
                ]
            ]));
        } catch (GuzzleException $e) {
        }
        return null;
    }

    // 上传水印图片
    public function watermarkUpload($file, string $name = '', float $opacity = 1.0,
                                    float $padding = 0.05, float $scale = 0.15, int $position = 0)
    {
        try {
            $response = $this->http->post('watermarks',
                [
                    RequestOptions::MULTIPART => [
                        ['name' => 'file', 'contents' => $file,],
                        ['name' => 'name', 'contents' => $name,],
                        ['name' => 'opacity', 'contents' => $opacity,],
                        ['name' => 'padding', 'contents' => $padding,],
                        ['name' => 'scale', 'contents' => $scale,],
                        ['name' => 'position', 'contents' => self::POSITION[$position],],
                    ]
                ]);
            return $this->response($response);
        } catch (GuzzleException $e) {
        }
        return null;
    }

    // 上传水印图片
    public function watermarkViaLinkUpload($url, string $name = '', float $opacity = 1.0,
                                           float $padding = 0.05, float $scale = 0.15, int $position = 0)
    {
        try {
            $response = $this->http->post('watermarks',
                [
                    RequestOptions::JSON => [
                        'url' => $url,
                        'name' => $name,
                        'opacity' => $opacity,
                        'padding' => $padding,
                        'scale' => $scale,
                        'position' => self::POSITION[$position],
                    ]
                ]);
            return $this->response($response);
        } catch (GuzzleException $e) {
        }
        return null;
    }

    // 水印配置信息
    public function watermarkInfo(string $uid)
    {
        try {
            return $this->response($this->http->get('watermarks/' . $uid));
        } catch (GuzzleException $e) {
        }
        return null;
    }

    // 所有水印配置
    public function watermarks()
    {
        try {
            return $this->response($this->http->get('watermarks/'));
        } catch (GuzzleException $e) {
        }
        return null;
    }

    // 删除水印配置
    public function deleteWatermark($uid)
    {
        try {
            return $this->response($this->http->delete('watermarks/' . $uid));
        } catch (GuzzleException $e) {
        }
        return null;
    }
}
