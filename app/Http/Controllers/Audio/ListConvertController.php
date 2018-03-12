<?php

namespace App\Http\Controllers\Audio;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ListConvertController extends Controller
{
    public function index()
    {
        return view('layouts.list_convert.index');
    }
}
