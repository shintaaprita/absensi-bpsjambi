<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "
    <div style='display: flex; justify-content: center; align-items: center; height: 100vh;'>
        <h1 style='color: blue; font-family: sans-serif;'>Hello World</h1>
    </div>
    ";
});