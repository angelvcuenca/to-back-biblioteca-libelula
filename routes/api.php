<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AutorController;
use App\Http\Controllers\Api\LibroController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*Route::middleware('auth:api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
    });
});*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('show-all-users', [AuthController::class, 'showUsers']);
    Route::post('register', [AuthController::class, 'register']);
});



Route::middleware('auth:api')->group(function () {
    Route::prefix('v1')->group(function () {
        //todo: endpoint de autores
        Route::get('all-authors', [AutorController::class, 'show']); //todo:endpoint para listar todos los autores
        Route::post('save-author', [AutorController::class, 'store']); //todo:endpoint para guardar autores
        Route::get('get-id-author/{id}', [AutorController::class, 'showId']); //todo:endpoint para listar autor por id
        Route::put('update-author/{id}', [AutorController::class, 'update']); //todo:endpoint para actualizar el autor por id
        Route::patch('update-partial-author/{id}', [AutorController::class, 'updatePartial']); //todo:endpoint para actualizar el autor por id y por campos especificos
        Route::delete('delete-author/{id}', [AutorController::class, 'destroy']); //todo:endpoint para eliminar el autor por id

        //todo: endpoint de libros
        Route::get('all-books', [LibroController::class, 'show']); //todo:endpoint para listar todos los libros
        Route::get('get-id-book/{id}', [LibroController::class, 'showId']); //todo:endpoint para listar libro por id
        Route::post('save-book', [LibroController::class, 'store']); //todo:endpoint para guardar libros
        Route::put('update-book/{id}', [LibroController::class, 'update']); //todo:endpoint para actualizar el libro por id
        Route::patch('update-partial-book/{id}', [LibroController::class, 'updatePartial']); //todo:endpoint para actualizar el libro por id y por campos especificos
        Route::delete('delete-book/{id}', [LibroController::class, 'destroy']); //todo:endpoint para eliminar el libro por id
    });

});


