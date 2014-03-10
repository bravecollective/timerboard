<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

// GUEST REQUIRED ROUTES
Route::group(array('before' => 'guest'), function()
{
	Route::get('login', array('as' => 'login', 'uses' => 'LoginController@loginView'));
	Route::post('login', array('uses' => 'LoginController@loginAction'));

	Route::get('logintest', array('as' => 'login', 'uses' => 'LoginTestController@loginView'));
	Route::post('logintest', array('uses' => 'LoginTestController@loginAction'));

	Route::get('infotest', array('as' => 'login', 'uses' => 'LoginTestController@infoAction'));
});

// LOGIN REQUIRED ROUTES
Route::group(array('before' => 'auth'), function()
{
	// Basic URLs
	Route::get('/', array('as' => 'home', 'uses' => 'TimerController@listAllTimersView'));
	Route::get('active', array('as' => 'active', 'uses' => 'TimerController@listActiveTimersView'));
	Route::get('expired', array('as' => 'expired', 'uses' => 'TimerController@listExpiredTimersView'));

	Route::group(array('before' => 'edit'), function()
	{
		Route::get('search', array('as' => 'search_map', 'uses' => 'TimerController@searchCelestialAction'));

		Route::get('add', array('as' => 'add_timer', 'uses' => 'TimerController@addTimerView'));
		Route::post('add', array('uses' => 'TimerController@addTimerAction'));

		// modify status
		Route::get('bash/{id}', array('as' => 'bash_timer', 'uses' => 'TimerController@bashTimerAction'));
		Route::get('win/{id}', array('as' => 'win_timer', 'uses' => 'TimerController@winTimerAction'));
		Route::get('fail/{id}', array('as' => 'fail_timer', 'uses' => 'TimerController@failTimerAction'));

		// delete timer
		Route::get('delete/{id}', array('as' => 'delete_timer', 'uses' => 'TimerController@deleteTimerAction'));
	});

	//
	Route::get('logout', array('as' => 'logout', 'uses' => 'LoginController@logoutAction'));
});
