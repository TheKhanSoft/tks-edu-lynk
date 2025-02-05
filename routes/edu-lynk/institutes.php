<?php

use App\Http\Controllers\InstituteController;
use App\Http\Controllers\Institute\ClassNameController;
use App\Http\Controllers\Institute\ClassSectionController;
use App\Http\Controllers\Institute\DepartmentController;
use App\Http\Controllers\Institute\SectionController;
use App\Http\Controllers\Institute\SubjectController;
use Illuminate\Support\Facades\Route;

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified', 'admin'
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });

Route::resource('institutes', InstituteController::class);
Route::resource('departments', DepartmentController::class);
Route::resource('classes', ClassNameController::class);
Route::resource('sections', SectionController::class);
Route::resource('subjects', SubjectController::class);
Route::resource('class-section-subjects', ClassSectionController::class);
