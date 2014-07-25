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
			echo "\n\n";
			echo $e->getMessage();
			echo "\n\n";
			echo "Credentials:\n";
			var_dump($credentials);
			echo "\n\n";
			exit;
			return null;
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

	public function retrieveByToken($identifier, $token)
	{

	}

	public function updateRememberToken(UserInterface $user, $token)
	{

	}

	private function updateUser($token, $result)
	{
		// validate permissions
		$permission = 0;
		foreach(Config::get('braveapi.auth-edit-tags') as $tag)
		{
			if(in_array($tag, $result->tags)) // check for special group
			{
				$permission = 1;
				break;
			}
		}

		// per user overrides
		foreach(Config::get('braveapi.auth-edit-users') as $id)
		{
			if($id == $result->character->id) // check for special group
			{
				$permission = 1;
				break;
			}
		}
                
                // Get alliance info
                $api = new Brave\API(Config::get('braveapi.application-endpoint'), Config::get('braveapi.application-identifier'), Config::get('braveapi.local-private-key'), Config::get('braveapi.remote-public-key'));
                $alliance_result = $api->lookup->alliance(array('search' => $result->alliance->id, 'only' => 'short'));

		// check for existing user
		$userfound = ApiUser::find($result->character->id);
		if($userfound == false)
		{
			// no user found, create it
			$userfound = ApiUser::create(array(
				                             'id' => $result->character->id,
				                             'token' => $token,
				                             'remember_token' => '',
				                             'character_name' => $result->character->name,
				                             'alliance_id' => $result->alliance->id,
				                             'alliance_name' => $result->alliance->name,
                                                             'alliance_ticker' => $alliance_result->short,
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
                        $userfound->alliance_ticker = $alliance_result->short;
			$userfound->tags = json_encode($result->tags);

			$userfound->save();
		}

		return $userfound;
	}
}
