<?php

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

Route::get('/', 'HomeController@home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::delete('/user/delete', 'UsersController@destroy');
    Route::post('/user/show', 'UsersController@show');
    Route::post('/user/update', 'UsersController@update');

    Route::get('/roles', 'RolesPermissionsController@roles');
    Route::get('/roles/getRoles', 'RolesPermissionsController@getRoles');
    Route::post('/roles/setRoles', 'RolesPermissionsController@setRoles');
    Route::delete('/roles/delete', 'RolesPermissionsController@destroyRole');
    Route::post('/roles/show', 'RolesPermissionsController@showRole');
    Route::post('/roles/update', 'RolesPermissionsController@updateRole');
    Route::post('/roles/add', 'RolesPermissionsController@addRole');

    Route::get('/permissions', 'RolesPermissionsController@permissions');
    Route::get('/permissions/getPermissions', 'RolesPermissionsController@getPermissions');
    Route::post('/permissions/setPermissions', 'RolesPermissionsController@setPermissions');
    Route::delete('/permissions/delete', 'RolesPermissionsController@destroyPermission');
    Route::post('/permissions/show', 'RolesPermissionsController@showPermission');
    Route::post('/permissions/update', 'RolesPermissionsController@updatePermission');
    Route::post('/permissions/add', 'RolesPermissionsController@addPermission');
});

