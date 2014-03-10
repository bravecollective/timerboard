<?php

class LoginController extends BaseController {

	const LAYOUT = 'layouts.login';

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function loginView()
	{
		$this->layout = self::LAYOUT;
		$view = View::make(self::LAYOUT)
		        ->nest('navigation', 'navigation')
		        ->nest('footer', 'parts/footer')
		        ->nest('page_content', 'login');

		return $view;
	}

	public function loginAction()
	{
		$user = array(
			'token' => Input::get('token')
		);

		if (Auth::attempt($user)) {
			return Redirect::route('home')
			       ->with('flash_notice', 'You are successfully logged in.');
		}

		// authentication failure! lets go back to the login page
		return Redirect::route('login')
		       ->with('flash_error', 'Your username/password combination was incorrect.')
		       ->withInput();
	}

	public function logoutAction()
	{
		Auth::logout();

		return Redirect::route('home')
		       ->with('flash_notice', 'You are successfully logged out.');
	}

}