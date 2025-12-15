<?php

use App\Http\Controllers\RentalSourceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RentalSourceController::class,'index'])->name('index');
