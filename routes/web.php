<?php

use App\Http\Controllers\PropertyListingController;
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


Route::get('/', [PropertyListingController::class, 'index'])->name('property_list');
Route::delete('/property/{id}', [PropertyListingController::class, 'destroy']);
