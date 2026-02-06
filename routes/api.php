<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VagaController;

Route::apiResource('empresas', EmpresaController::class);
Route::apiResource('usuarios', UsuarioController::class);
Route::apiResource('vagas', VagaController::class);
Route::post('vagas/candidatar', [VagaController::class, 'candidatar']);
