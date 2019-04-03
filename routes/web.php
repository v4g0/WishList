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


Route::middleware('auth')->get('/testservice', 'Budget@updateBudget')->name('test');

Route::view('/testa', 'test');


Route::group(['middleware' => 'auth'], function () {
  //Create new product for wishlist
  Route::post('/createnewproduct', 'WishList@createNewWish');
  //Get wishList
  Route::get('/getwishlist', 'WishList@getWishList');
  //Remove product from wishlist
  Route::post('/removefwishlist', 'WishList@removeFromWishList');
  //BuyProducts
  Route::post('/buyproduct', 'BuyProduct');
  //Get budget
  Route::get('/getbudget','Budget@getBudget');
  //Update budget
  Route::post('/updatebudget','Budget@updateBudget');

  //Route for view VUEJS
  Route::view('/', 'index');
});



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
