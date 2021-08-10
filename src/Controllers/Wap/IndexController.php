<?php

namespace Jncinet\CloudflareStream\Controllers\Wap;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return view('cf-stream::wap.index');
    }
}
