<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ApplicationController;

Route::get('/', function () {
    return view('home');
});

Route::controller(EmployeeController::class)->group(function () {
    Route::get('employees', 'index')->name('employees.index');
    Route::get('/employees/selected', 'selected')->name('employees.selected');
    Route::get('/employees/selected-data', [\App\Http\Controllers\EmployeeController::class, 'selectedData'])
    ->name('employees.selectedData');
    Route::post('/employees/selected', 'updateSelected')->name('employees.updateSelected');
    Route::get('employees/{employee_id}', 'show')->name('employees.show');
    
});


Route::controller(ApplicationController::class)->group(function () {
    Route::get('applications', 'index')->name('applications.index');
    Route::get('applications/{id}', 'show')->name('applications.show');
});

