<?php

use Abdurrahaman\Installer\Controllers\InstallationController;
use Illuminate\Support\Facades\Route;

Route::prefix('install')->group(function(){
    Route::get('/',[InstallationController::class,'index']);
    Route::get('requirements',[InstallationController::class,'requirements'])->name('install.requirements');
    Route::get('database',[InstallationController::class,'database'])->name('install.database');
    Route::post('db/store',[InstallationController::class,'dbStore'])->name('install.dbStore');
    Route::get('user',[InstallationController::class,'user'])->name('install.user');
    Route::post('user/store',[InstallationController::class,'userStore'])->name('install.userStore');
    Route::get('success',[InstallationController::class,'success'])->name('install.success');
});
