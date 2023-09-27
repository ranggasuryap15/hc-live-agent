<?php

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/admin', 'AdminController@index')->name('admin');

Route::get('/chat-hc', 'MessagesController@index')->name('hc-live-agent');

Route::get('/load-latest-messages', 'MessagesController@getLoadLatestMessages');

Route::post('/send', 'MessagesController@postSendMessage');

// Route for Read Message 
Route::post('/read-message', 'MessagesController@markAsRead')->name('messages.markAsRead');

// Route for create conversation
Route::post('/conversation/create', 'ConversationController@create')->name('conversation.create');

// Route::get('/fetch-old-messages', 'MessagesController@getOldMessages');
