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

Route::prefix("admin")->namespace("Admin")->group(function() {
    Route::get("albums", "AlbumController@show")->name("album.show");
    Route::match(['get', 'post'], "album/create", "AlbumController@createAlbum")->name('album.create');
    Route::match(['get', 'post'], 'album/edit/{id}', "AlbumController@editAlbum")->name('album.edit');
    Route::post("album/delete/{id}", "AlbumController@deleteAlbum")->name('album.delete');

    Route::get("album/{album_id}/fotos", "PhotoController@show")->name('photo.show');
    Route::match(['get', 'post'], "album/{album_id}/fotos/nieuw", "PhotoController@uploadPhoto")->name("photo.new");
});


Auth::routes();
