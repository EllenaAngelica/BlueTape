<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Line extends CI_Controller {

	public function webhook($event){
		$channelSecret = getenv('LINE_BOT_CHANNEL_SECRET');
		$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('LINE_BOT_CHANNEL_TOKEN'));
		$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);
		
		$httpRequestBody = file_get_contents('php://input');
		$hash = hash_hmac('sha256', $httpRequestBody, $channelSecret, true);
		$signature = base64_encode($hash);

		if (empty($signature)) {
			http_response_code(400);
        	//'Bad Request'
        }
		
		try {
        	$events = $bot->parseEventRequest($httpRequestBody, $signature);
        } catch (InvalidSignatureException $e) {
			http_response_code(400);
			//'Invalid signature'
		} catch (InvalidEventRequestException $e) {
			http_response_code(400); 
			//"Invalid event request"
        }
	}
}

