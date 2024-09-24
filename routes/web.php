<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


use Illuminate\Support\Facades\Mail;

Route::get('/test-email', function () {
    Mail::raw('This is a test email.', function ($message) {
        $message->to('your-email@example.com')
                ->subject('Test Email');
    });
    return 'Email sent!';
});


use App\Models\User;
use App\Notifications\WeatherAlertNotification;

Route::get('/test-notification', function () {
    $user = User::first();  // Replace with appropriate user
    $user->notify(new WeatherAlertNotification(15, 9)); // Example values for testing
    return 'Notification sent!';
});