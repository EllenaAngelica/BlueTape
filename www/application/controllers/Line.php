<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Line extends CI_Controller {

	public function webhook(){
		$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('LINE_BOT_CHANNEL_TOKEN'));
		$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('LINE_BOT_CHANNEL_SECRET')]);
		

		$httpBody = file_get_contents('php://input');
		/**
		$signature = isset($_SERVER['HTTP_X_LINE_SIGNATURE']) ? $_SERVER['HTTP_X_LINE_SIGNATURE'] : "-";
		if (empty($signature)) {
			return $res->withStatus(400, 'Bad Request');
        }
		
		try {
        	$events = $bot->parseEventRequest($req->getBody(), $signature[0]);
        } catch (InvalidSignatureException $e) {
			return $res->withStatus(400, 'Invalid signature');
		} catch (InvalidEventRequestException $e) {
			return $res->withStatus(400, "Invalid event request");
        }
		**/
		$events = json_decode($httpBody, true);
		
		foreach ($events['events'] as $event) {
			if ($event instanceof FollowEvent) {
				$this->bot->replyText($this->followEvent->getReplyToken(), 'Got followed event');
			}
		}
		http_response_code(200);
	}
}

