<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ListConvertController extends Controller
{
    public function index()
    {
        return view('layouts.list_convert.index');
    }
}
