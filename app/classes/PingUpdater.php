<?php
/**
 * PingUpdater.php
 * Created by: matthewglinski
 * Date: 6/4/15 2:45 PM
 */

class PingUpdater {

	static public function sendMessageToMuc($muc, $body, $ping = false) {
		$api_key = Config::get('pingapi.api-key');
		$api_secret = Config::get('pingapi.api-secret');

		Log::debug("Starting Ping Request: {$ping} [key: {$api_key}, secret: {$api_secret}] ({$muc}) ".$body);

		$request = Requests::post('https://ping.braveineve.com/api/sendMuc', [], [
			'api_key'       => $api_key,
			'api_secret'    => $api_secret,
			'room'          => $muc,
			'body'          => $body,
			'ping'          => (int)$ping,
		]);

		Log::debug("Response Body: ".(int)$request->body);
		Log::debug("Request Success: ".(int)$request->success);
	}

}