<?php

use App\User;
use App\Genre;
use App\Record;
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
      return view('admin/welcome');

 });
Route::view('/', 'admin/home');
Route::get('shop','ShopController@index');
Route::get('shop/{id}', 'ShopController@show');
Route::get('shop_alt', 'ShopController@alt');
Route::get('contact-us', 'ContactUsController@show');
Route::post('contact-us', 'ContactUsController@sendEmail');

Route::middleware(['auth', 'Admin'])->prefix('admin')->group(function () {

    Route::redirect('/', 'admin/records/index');
    Route::resource('genres', 'Admin\GenreController');
    Route::get('genres2/qryGenres', 'Admin\Genre2Controller@qryGenres');
    Route::resource('genres2', 'Admin\Genre2Controller', ['parameters' => ['genres2' => 'genre']]);
    Route::resource('records', 'Admin\RecordController');
});
route::prefix('api')->group(function(){
   route::get('users', function(){
       return User::get();
   });
    route::get('genres', function(){
        return Genre::with('records')->get();
    });
    route::get('records', function(){
        return Record::with('genre')->get();
    });
});
Route::middleware(['auth'])->prefix('user')->group(function () {
    Route::redirect('/', '/user/profile');
    Route::get('profile', 'User\ProfileController@edit');
    Route::post('profile', 'User\ProfileController@update');
    Route::get('password', 'User\PasswordController@edit');
    Route::post('password', 'User\PasswordController@update');
});
      //  Route::get('admin/records', function(){
        //    $records = [
                //'Queen - Greatest Hits',
              //  'The Rolling Stones - Sticky Fingers',
            //    'The Beatles - Abbey Road'
          //  ];
        //
       //    return view('admin.records.index', ['abc' => $records
     //      ]);
   //     });

/*
   Route::redirect('/', '/admin/records');
   Route::get('records', function(){
     $records = [
         'Queen - Greatest Hits',
         'The Rolling Stones - Sticky Fingers',
         'The Beatles - Abbey Road'
     ];
     return view('admin.records.index', [
         'abc' => $records
     ]);
  });
*/

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
