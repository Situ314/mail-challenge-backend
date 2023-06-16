<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Route::get('preview', function () {
//    $markdown = new \Illuminate\Mail\Markdown(view(), config('mail.markdown'));
//    $email = \App\Models\Email::find(1);
//    return $markdown->render("mail.woowup_mail", [$email]);
//});
