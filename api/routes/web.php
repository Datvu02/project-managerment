<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $details = [
        'title' => 'M.Work - Nhắc nhở công việc hết hạn trong ngày',
        'body' => 'This is for <b>testing email</b> using smtp'
    ];
    return view('mail.mailDaily')->with([
        'details'=>$details
    ]);
});
Route::get('send-mail', function () {

    $details = [
        'title' => 'Mail from ItSolutionStuff.com',
        'body' => 'This is for <b>testing email</b> using smtp'
    ];

    \Mail::to('uyenvt.vnua@gmail.com')->send(new \App\Mail\SendMail($details));

    dd("Email is Sent.");
});