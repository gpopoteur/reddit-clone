<?php

Route::get('/posts', 'PostsController@index');
Route::get('/posts/{id}', 'PostsController@show');