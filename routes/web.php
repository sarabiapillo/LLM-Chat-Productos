<?php

use Illuminate\Support\Facades\Route;

// Redirigir el index a nuestro frontend HTML
Route::get('/', function () {
    return redirect('/admin.html');
});

// Por si el usuario entra a /admin sin el .html
Route::get('/admin', function () {
    return redirect('/admin.html');
});

