<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/mail-test', function () {
    Mail::raw('Lucy funcionando.', function ($message) {
        $message->to('test@example.com')->subject('Prueba Lucy Mail');
    });
    return 'Email enviado!';
});

