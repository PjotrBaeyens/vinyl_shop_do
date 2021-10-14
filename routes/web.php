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
      return view('admin/welcome');

 });
Route::view('/', 'admin/home');
Route::view('contact-us', 'admin/contact');

Route::prefix('admin') -> group(function(){

    Route::redirect('/', 'admin/records/index');
    Route::get('records', 'Admin\Recordcontroller@index');
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
