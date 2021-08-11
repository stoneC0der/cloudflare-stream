<?php

namespace Jncinet\CloudflareStream\Controllers\Wap;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        $uploadUrl = '';
        return view('cf-stream::index', compact('uploadUrl'));
    }
}
