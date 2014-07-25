<?php

use Carbon\Carbon;

class TimerController extends BaseController
{
	const LAYOUT = 'layouts.home';

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

	public function listAllTimersView()
	{
		$activeTimers = Timers::where('outcome', '=', '0')->orderBy('timeExiting', 'asc')->get();

		$oldTimers = Timers::where('bashed', '!=', '0')->where('outcome', '!=', '0')->orderBy('timeExiting', 'desc')->paginate(30);
		$oldTimers->setBaseUrl('expired');

		// make timers page
		$pageContentView = View::make('home')
			->with(array('activeTimers' => $activeTimers))
			->nest('timer_table_new', 'parts/timer_table', array('timers' => $activeTimers, 'paginate' => false))
			->nest('timer_table_old', 'parts/timer_table', array('timers' => $oldTimers, 'paginate' => true));

		// make main layout page
		$layoutView = View::make(self::LAYOUT)
			->with('page_content', $pageContentView)
			->nest('navigation', 'navigation')
			->nest('footer', 'parts/footer');

		return $layoutView;
	}

	public function listActiveTimersView()
	{
		$activeTimers = Timers::where('outcome', '=', '0')->orderBy('timeExiting', 'asc')->get();

		// make timers page
		$pageContentView = View::make('active')
		                       ->with(array('activeTimers' => $activeTimers))
		                       ->nest('timer_table', 'parts/timer_table', array('timers' => $activeTimers, 'paginate' => false));

		// make main layout page
		$layoutView = View::make(self::LAYOUT)
		                  ->with('page_content', $pageContentView)
		                  ->nest('navigation', 'navigation')
		                  ->nest('footer', 'parts/footer');

		return $layoutView;
	}

	public function listExpiredTimersView()
	{
		$oldTimers = Timers::where('bashed', '!=', '0')->where('outcome', '!=', '0')->orderBy('timeExiting', 'desc')->paginate(30);

		// make timers page
		$pageContentView = View::make('expired')
		                       ->nest('timer_table', 'parts/timer_table', array('timers' => $oldTimers, 'paginate' => true));

		// make main layout page
		$layoutView = View::make(self::LAYOUT)
		                  ->with('page_content', $pageContentView)
		                  ->nest('navigation', 'navigation')
		                  ->nest('footer', 'parts/footer');

		return $layoutView;
	}

	public function addTimerView()
	{
		$this->layout = self::LAYOUT;
		$view = View::make(self::LAYOUT)
		            ->nest('navigation', 'navigation')
		            ->nest('footer', 'parts/footer')
		            ->nest('page_content', 'add'
			);
		return $view;
	}

	public function addTimerAction()
	{
		$rules = array(
			'itemID' => 'required',
			'structureType' => 'required',
			'timerType' => 'required',
			'days' => 'required',
			'hours' => 'required',
			'mins' => 'required',
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails())
		{
			return Redirect::route('add_timer')
				->with('flash_error', 'Please fill in all Timer Information')
				->withInput();
		}
		else
		{
			$time = Carbon::now()->addDays((int)Input::get('days'))->addHours((int)Input::get('hours'))->addMinutes((int)Input::get('mins'));

			// DO REGISTRATION
			Timers::create(array(
                'itemID' => Input::get('itemID'),
                'structureType' => (int)Input::get('structureType'),
                'structureStatus' => (int)Input::get('structureStatus'),
                'timeExiting' => date('Y-m-d H:i:s', $time->timestamp),
                'bashed' => '0',
                'timerType' => Input::get('timerType'),
                'outcome' => '0',
                'user_id' => Auth::user()->id,
			));

			return Redirect::route('home')
				->with('flash_msg', 'New Timer Added');
		}
	}
        
        public function detailsTimerView($id)
        {
                $timer = Timers::find($id);
                $this->layout = self::LAYOUT;
		$view = View::make(self::LAYOUT)
                            ->nest('navigation', 'navigation')
                            ->nest('footer', 'parts/footer')
                            ->nest('page_content', 'details', array('timer' => $timer));
		return $view;
        }
        
        public function detailsTimerAction($id)
        {
                $timer = Timers::find($id);
                $content = Input::get('notes');
                if(!empty($content))
                {
                        $content = nl2br(htmlentities($content));
                        $note = new Notes(array('content' => $content));
                        $note->user()->associate(Auth::user());
                        $timer->notes()->save($note);
                        return Redirect::route('details', $timer->id)
                                        ->with('flash_msg', 'Note added.');
                }
                return Redirect::route('details', $timer->id)
                        ->with('flash_error', 'Unable to add note. Try adding some content.');
        }
        
        public function signUpTimerAction($id)
        {
                $timer = Timers::find($id);
                $role = Request::get('role');
                $confirmed = Request::get('confirmed', true);
                if(is_null($role) or !array_key_exists($role, Timers::$signUpRoles))
                {
                        return Redirect::route('details', $timer->id)
                                ->with('flash_error', 'Error signing up.');
                }
                if($timer->isUserSignedUpAs(Auth::user()->id, $role))
                {
                        return Redirect::route('details', $timer->id)
                                ->with('flash_error', 'Already signed up for this timer.');
                }
                $timer->signUps()->attach(Auth::user()->id, array('role' => $role, 'confirmed' => $confirmed));
                return Redirect::route('details', $timer->id)
                        ->with('flash_msg', 'Signed up successfully.');
        }
        
        public function deleteSignUpTimerAction($id)
        {
                $timer = Timers::find($id);
                $role = Request::get('role');
                if(is_null($role) or !array_key_exists($role, Timers::$signUpRoles))
                {
                        return Redirect::route('details', $timer->id)
                                ->with('flash_error', 'Error removing sign up.');
                }
                if($timer->isUserSignedUpAs(Auth::user()->id, $role))
                {       
                        // This is so dumb, but it's the only way I could find to detach based on an extra column
                        // See here: https://github.com/laravel/framework/issues/3585
                        $timer->signUps()->newPivotStatementForId(Auth::user()->id)->where('role', $role)->delete();
                        return Redirect::route('details', $timer->id)
                                ->with('flash_msg', 'Removed sign up.');
                }
                return Redirect::route('details', $timer->id)
                        ->with('flash_error', 'Not signed up for this timer.');
        }
        
        public function modifySignUpTimerAction($id)
        {
                $timer = Timers::find($id);
                $role = Request::get('role');
                $confirmed = Request::get('confirmed');
                if(is_null($role) or is_null($confirmed) or !array_key_exists($role, Timers::$signUpRoles))
                {
                        return Redirect::route('details', $timer->id)
                                ->with('flash_error', 'Error modifying sign up.');
                }
                
                $signUp = $timer->signUps()->newPivotStatementForId(Auth::user()->id)->where('role', $role);
                $signUp->update(array('confirmed' => $confirmed));
                return Redirect::route('details', $timer->id)
                        ->with('flash_msg', 'Updated sign up successfully.');
        }
        
	public function bashTimerAction($id)
	{
		$timer = Timers::find($id);
		if($timer != false and $timer->bashed !== '1')
		{
			$timer->bashed = 1;
			$timer->save();

			return Redirect::route('home')
			               ->with('flash_msg', 'Timer Marked as Bashed');
		}
		else
		{
			return Redirect::route('home')
			               ->with('flash_error', 'Timer Not Found or Already Bashed');
		}
	}

	public function winTimerAction($id)
	{
		$timer = Timers::find($id);
		if($timer != false and $timer->outcome === '0')
		{
			$timer->bashed = 1;
			$timer->outcome = 1;
			$timer->save();

			return Redirect::route('home')
			               ->with('flash_msg', 'Timer Marked as WIN');
		}
		else
		{
			return Redirect::route('home')
			               ->with('flash_error', 'Timer Not Found or Already Marked');
		}
	}

	public function failTimerAction($id)
	{
		$timer = Timers::find($id);
		if($timer != false and $timer->outcome === '0')
		{
			$timer->bashed = 1;
			$timer->outcome = 2;
			$timer->save();

			return Redirect::route('home')
			               ->with('flash_msg', 'Timer Marked as FAIL');
		}
		else
		{
			return Redirect::route('home')
			               ->with('flash_error', 'Timer Not Found or Already Marked');
		}
	}

	public function deleteTimerAction($id)
	{
		$timer = Timers::find($id);
		if($timer != false and $timer->bashed !== '1')
		{
			$timer->delete();
			return Redirect::route('home')
			               ->with('flash_msg', 'Timer Deleted');
		}
		else
		{
			return Redirect::route('home')
			               ->with('flash_error', 'Timer Not Found or Already Marked');
		}
	}

	public function searchCelestialAction()
	{
		$line = new stdClass();
		$line->locations = array();

		$text = Input::get('q');

		// did we get passed an ID?
		if(ctype_digit($text))
		{
			$result = MapItem::where('itemID', '=', $text)->whereIn('groupID', array('7', '8'))->where('security', '<', '0.8')->take(1)->get();
			if($result != false)
			{
				$result = $result[0];
				$line->locations = array(
					'text' => $result->itemName,
					'id' => $result->itemID
				);
				goto endGetLocation;
				// OH GOD AAAAAAAAAAHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHH
				// MY EYES MY EYES ARE BURNING WITH ACID IT HURTS OHHHHH GODDDD IT HUURRRTTTTTSSSSSSSss..
			}
		}

		$search = MapItem::where('itemName', 'like', $text.'%')->whereIn('groupID', array('7', '8'))->where('security', '<', '0.8')->take(40)->get();
		foreach($search as $row)
		{
			$line->locations[] = array(
				'text' => $row->itemName,
				'id' => $row->itemID
			);
		}

		// GOTO END
		endGetLocation:

		if(!Input::has('callback'))
		{
			return Response::json($line);
		}
		else
		{
			return Response::json($line)->setCallback(Input::get('callback'));
		}
	}
}