<?php

use Illuminate\Auth\GenericUser;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\UserProviderInterface;

class ApiUserProvider implements UserProviderInterface {

	public function retrieveById($identifier)
	{
		return ApiUser::find($identifier);
	}

	public function retrieveByCredentials(array $credentials)
	{
		try
		{
			$user = ApiUser::where('token', '=', $credentials['token'])->get();
			if(isset($user[0]))
			{
				return $user[0];
			}
			else
			{
				$api = new Brave\API(Config::get('braveapi.application-endpoint'), Config::get('braveapi.application-identifier'), Config::get('braveapi.local-private-key'), Config::get('braveapi.remote-public-key'));
				$result = $api->core->info(array('token' => $credentials['token']));

				//
				if(!isset($result->character->name))
				{
					return Redirect::route('logintest')
					               ->with('flash_error', 'Login Failed, Please Try Again');
				}

				//
				$user = $this->updateUser($credentials['token'], $result);
				return $user;
			}
		}
		catch(Exception $e)
		{
			return false;
		}
	}

	public function validateCredentials(UserInterface $user, array $credentials)
	{
		if(isset($user->token) and $user->token == $credentials['token'])
		{
			return true;
		}

		try
		{
			$api = new Brave\API(Config::get('braveapi.application-endpoint'), Config::get('braveapi.application-identifier'), Config::get('braveapi.local-private-key'), Config::get('braveapi.remote-public-key'));
			$result = $api->core->info(array('token' => $credentials['token']));

			if(!isset($result->character->name))
			{
				return Redirect::route('logintest')
				               ->with('flash_error', 'Login Failed, Please Try Again');
			}

			$this->updateUser($credentials['token'], $result);
			return true;
		}
		catch(Exception $e)
		{
			return Redirect::route('logintest')
				->with('flash_error', 'Login Failed, Please Try Again');
		}
	}

	private function updateUser($token, $result)
	{
		// validate permissions
		$permission = 0;
		foreach(Config::get('braveapi.auth-edit-tags') as $tag)
		{
			if(in_array($tag, $result->tags) and $permission == 0) // check for special group
			{
				$permission = 1;
			}
		}

		// check for existing user
		$userfound = ApiUser::find($result->character->id);
		if($userfound == false)
		{
			// no user found, create it
			$userfound = ApiUser::create(array(
				                'id' => $result->character->id,
				                'token' => $token,
				                'character_name' => $result->character->name,
				                'alliance_id' => $result->alliance->id,
				                'alliance_name' => $result->alliance->name,
				                'tags' => json_encode($result->tags),
				                'status' => 1,
				                'permission' => $permission
			                ));
		}
		else
		{
			// update the existing user
			$userfound->token = $token;
			$userfound->status = 1;
			$userfound->permission = $permission;
			$userfound->token = $token;
			$userfound->character_name = $result->character->name;
			$userfound->alliance_id = $result->alliance->id;
			$userfound->alliance_name = $result->alliance->name;
			$userfound->tags = json_encode($result->tags);

			$userfound->save();
		}

		return $userfound;
	}
}
