<?php

Route::name('posts_path')->get('/posts', 'PostsController@index');
Route::name('create_post_path')->get('/posts/create', 'PostsController@create');
Route::name('store_post_path')->post('/posts', 'PostsController@store');
Route::name('post_path')->get('/posts/{post}', 'PostsController@show');