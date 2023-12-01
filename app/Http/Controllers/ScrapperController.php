<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redis;

class ScrapperController extends Controller
{
    function index() {
        return view('delete-button');
    }

    function delete() {
        // job will be queued
        dispatch(new \App\Jobs\DeleteScrapperJob());

        // job will be executed right away
        // dispatch_sync(new \App\Jobs\DeleteScrapperJob());
        
        return to_route('index')->with('success', 'Proses berhasil ditambahkan pada Job!');
    }
}
