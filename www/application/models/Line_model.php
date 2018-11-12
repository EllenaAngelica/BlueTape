<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Line_model extends CI_Model {
	public function __construct() {
        parent::__construct();
	
        $this->load->config('auth');
        $this->load->config('modules');
    }
	
	public function pushMessage($message) {
		$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('LINE_BOT_CHANNEL_TOKEN'));
		$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('LINE_BOT_CHANNEL_SECRET')]);

		$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message);
		$response = $bot->pushMessage('<to>', $textMessageBuilder);
			
		echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
	}
}
