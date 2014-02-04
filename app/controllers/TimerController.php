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

		$this->layout = self::LAYOUT;
		$view = View::make(self::LAYOUT)
	        ->nest('navigation', 'navigation')
	        ->nest('footer', 'parts/footer')
	        ->nest('page_content', 'home',
	               array(
	                   'activeTimers' => $activeTimers,
	                   'oldTimers' => $oldTimers
	               )
			);
		return $view;
	}

	public function listActiveTimersView()
	{
		$activeTimers = Timers::where('outcome', '=', '0')->orderBy('timeExiting', 'asc')->get();

		$this->layout = self::LAYOUT;
		$view = View::make(self::LAYOUT)
		            ->nest('navigation', 'navigation')
		            ->nest('footer', 'parts/footer')
		            ->nest('page_content', 'active',
		                   array(
			                   'activeTimers' => $activeTimers
		                   )
			);
		return $view;
	}

	public function listExpiredTimersView()
	{
		$oldTimers = Timers::where('bashed', '!=', '0')->where('outcome', '!=', '0')->orderBy('timeExiting', 'desc')->paginate(30);

		$this->layout = self::LAYOUT;
		$view = View::make(self::LAYOUT)
		            ->nest('navigation', 'navigation')
		            ->nest('footer', 'parts/footer')
		            ->nest('page_content', 'expired',
		                   array(
			                   'oldTimers' => $oldTimers
		                   )
			);
		return $view;
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

	public function bashTimerAction($id)
	{
		$timer = Timers::find($id);
		if($timer != false and $timer->bashed !== "1")
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
		if($timer != false and $timer->outcome === "0")
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
		if($timer != false and $timer->outcome === "0")
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
		if($timer != false and $timer->bashed !== "1")
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