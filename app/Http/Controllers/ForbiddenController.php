<?php

namespace Corp\Http\Controllers;

use Illuminate\Http\Request;

use Corp\Http\Requests;

class ForbiddenController extends Controller
{
    public function show()
    {
        return view(config('settings.theme') . '.403');
    }
}
