<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;

Route::redirect('/', '/login');

Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('systemCalendar')->with('status', session('status'));
    }

    return redirect()->route('systemCalendar');
});

// Aktifkan route registrasi
Auth::routes();

// Group route untuk admin
// Route::group(['prefix' => 'admin', 'as' => '', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
// });

Route::get('/', 'HomeController@index')->name('home');

// Permissions
Route::delete('permissions/destroy', 'Admin\PermissionsController@massDestroy')->name('permissions.massDestroy');
Route::resource('permissions', 'Admin\PermissionsController');

// Roles
Route::delete('roles/destroy', 'Admin\RolesController@massDestroy')->name('roles.massDestroy');
Route::resource('roles', 'Admin\RolesController');

// Users
Route::delete('users/destroy', 'Admin\UsersController@massDestroy')->name('users.massDestroy');
Route::resource('users', 'Admin\UsersController');

// Events
Route::delete('events/destroy', 'Admin\EventsController@massDestroy')->name('events.massDestroy');
Route::resource('events', 'Admin\EventsController');

// Calendar
Route::get('system-calendar', 'SystemCalendarController@index')->name('systemCalendar');

