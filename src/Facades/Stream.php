<?php

namespace StoneC0der\CloudflareStream\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Stream Facade class
 *
 * @author Cedric Megnie <stonecodersoft@gmail.com>
 *
 * @see \StoneC0der\CloudflareStream\Stream
 */
class Stream extends Facade
{
    /**
     * Get the registered name of the component
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cloudflare-stream';
    }
}
