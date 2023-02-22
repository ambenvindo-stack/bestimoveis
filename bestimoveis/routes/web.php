<?php

use App\Http\Controllers\Admin\FotoController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\CidadeController;
use App\Http\Controllers\Admin\ImovelController;
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

//PARTE ADMINISTRATIVA
Route::prefix('admin')->name('admin.')->group(function(){

    Route::resource('cidades', CidadeController::class)->except(['show']);
    Route::resource('imoveis', ImovelController::class);
    Route::resource('imoveis.fotos', FotoController::class)->except(['show', 'edit', 'update']);

 });

//SITE PRICIPAL
Route::resource('/', App\Http\Controllers\Site\CidadeController::class)->only('index');
Route::resource('cidades.imoveis', App\Http\Controllers\Site\ImovelController::class)->only(['index', 'show']);

