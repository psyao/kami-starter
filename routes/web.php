<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Psyao\Kami\Facades\Kami;

Route::view('/', 'home');

// Last: one fallback route answers every Entry's Path, so the routes above always win.
Kami::routes();
