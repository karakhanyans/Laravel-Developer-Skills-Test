<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', 'ProductController@index')->name('home');
Route::get('/get-products', 'ProductController@getProducts');
Route::post('/add-product', 'ProductController@addProduct');
Route::put('/edit-product/{product_index}', 'ProductController@editProduct');
Route::delete('/delete-product/{product_index}', 'ProductController@deleteProduct');