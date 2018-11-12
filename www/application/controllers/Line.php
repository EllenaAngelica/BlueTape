<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Line extends CI_Controller {

	public function webhook(\Slim\Http\Request $req, \Slim\Http\Response $res){
		$channelSecret = getenv('LINE_BOT_CHANNEL_SECRET');
		$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('LINE_BOT_CHANNEL_TOKEN'));
		$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);
		
		$signature = $req->getHeader(HTTPHeader::LINE_SIGNATURE);
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
		
		foreach ($events as $event) {
			if ($event instanceof FollowEvent) {
				$this->bot->replyText($this->followEvent->getReplyToken(), 'Got followed event');
			}
		}
		http_response_code(200);
		$res->write('OK');
        return $res;
	}
}

