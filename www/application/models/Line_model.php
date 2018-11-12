<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Line_model extends CI_Model {
	private $channelAccessToken;
	private $channelSecret;
	private $webhookResponse;
	private $webhookEventObject;
	private $apiReply;
	private $apiPush;
	
	public function __construct(){
		parent::__construct();
	
        $this->load->config('auth');
        $this->load->config('modules');
		
		$this->channelAccessToken = getenv('LINE_BOT_CHANNEL_TOKEN');
		$this->channelSecret = getenv('LINE_BOT_CHANNEL_SECRET');
		$this->apiReply = "https://api.line.me/v2/bot/message/reply";
		$this->apiPush = "https://api.line.me/v2/bot/message/push";
		$this->webhookResponse = file_get_contents('php://input');
		$this->webhookEventObject = json_decode($this->webhookResponse);
	}
	
	private function httpPost($api,$body){
		$ch = curl_init($api); 
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body)); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
		'Content-Type: application/json; charser=UTF-8', 
		'Authorization: Bearer '.$this->channelAccessToken)); 
		$result = curl_exec($ch); 
		curl_close($ch); 
		return $result;
	}
	
	public function push($body){
		$api = $this->apiPush;
		$result = $this->httpPost($api, $body);
		return $result;
    }
	
	public function pushMessage($to, $text){
		$body = array(
		    'to' => $to,
		    'messages' => [
			array(
			    'type' => 'text',
			    'text' => $text
			)
		    ]
		);
		$this->push($body);
	 }
}
