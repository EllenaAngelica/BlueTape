<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use LINE\LINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\Event\AccountLinkEvent;
use LINE\LINEBot\Event\BeaconDetectionEvent;
use LINE\LINEBot\Event\FollowEvent;
use LINE\LINEBot\Event\JoinEvent;
use LINE\LINEBot\Event\LeaveEvent;
use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\Event\MessageEvent\AudioMessage;
use LINE\LINEBot\Event\MessageEvent\ImageMessage;
use LINE\LINEBot\Event\MessageEvent\LocationMessage;
use LINE\LINEBot\Event\MessageEvent\StickerMessage;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\Event\MessageEvent\UnknownMessage;
use LINE\LINEBot\Event\MessageEvent\VideoMessage;
use LINE\LINEBot\Event\PostbackEvent;
use LINE\LINEBot\Event\UnfollowEvent;
use LINE\LINEBot\Event\UnknownEvent;
use LINE\LINEBot\Exception\InvalidEventRequestException;
use LINE\LINEBot\Exception\InvalidSignatureException;

class Line_model extends CI_Model {
	private $channelAccessToken;
	private $channelSecret;
	private $httpClient;
	private $bot;
	
	public function __construct(){
		parent::__construct();
	
        $this->load->config('auth');
        $this->load->config('modules');
		
		$this->channelAccessToken = getenv('LINE_BOT_CHANNEL_TOKEN');
		$this->channelSecret = getenv('LINE_BOT_CHANNEL_SECRET');
		$this->httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($this->channelAccessToken);
		$this->bot = new \LINE\LINEBot($this->httpClient, ['channelSecret' => $this->channelSecret]);
	}

	public function proceedWebhook($httpRequestBody, $xLineSignature){
		$valid = $this->bot->validateSignature($httpRequestBody, $xLineSignature);
		$this->db->insert('Line_Followers', array(
			'userId' => $valid
		));
		if($valid){
			try{
				$events = $this->bot->parseEventRequest($httpRequestBody, $xLineSignature);

				foreach ($events as $event) {
					if ($event instanceof \LINE\LINEBot\Event\MessageEvent) {
						if ($event instanceof \LINE\LINEBot\Event\MessageEvent\TextMessage) {
							$this->bot->replyText($event->getReplyToken(), $event->getText());
						} elseif ($event instanceof StickerMessage) {
							
						} elseif ($event instanceof LocationMessage) {
							
						} elseif ($event instanceof ImageMessage) {
							
						} elseif ($event instanceof AudioMessage) {
							
						} elseif ($event instanceof VideoMessage) {
							
						} elseif ($event instanceof UnknownMessage) {
							http_response_code(400); // Invalid event type
						} else {
							http_response_code(400); // Invalid event type
						}
					} elseif ($event instanceof UnfollowEvent) {
						$id = $event->getUserId();
						$this->db->delete('Line_Followers', array('userId' => $id));
					} elseif ($event instanceof \LINE\LINEBot\Event\FollowEvent) {
						$this->db->insert('Line_Followers', array(
							'userId' => $event->getUserId()
						));
						$this->bot->replyText($event->getReplyToken(), 'Thank you for following me! XD');
					} elseif ($event instanceof JoinEvent) {
						
					} elseif ($event instanceof LeaveEvent) {
						
					} elseif ($event instanceof PostbackEvent) {
						
					} elseif ($event instanceof BeaconDetectionEvent) {
						
					} elseif ($event instanceof AccountLinkEvent) {
						
					} elseif ($event instanceof UnknownEvent) {
						http_response_code(400); // Invalid event type
					} else {
						http_response_code(400); // Invalid event type
					}
				}
			} catch (InvalidSignatureException $e) {
				http_response_code(400); // Invalid signature
			} catch (InvalidEventRequestException $e) {
				http_response_code(400); // Invalid event request
			}
		}
	}
	
	public function pushMessageToAllFollowers($text){
		$tos = [];
		$query = $this->db->get('Line_Followers');
		foreach ($query->result() as $row){
			$tos[] = $row->userId;
		}

        $ref = new ReflectionClass('LINE\LINEBot\MessageBuilder\TextMessageBuilder');
        $textMessageBuilder = $ref->newInstanceArgs(array_merge([$text]));
		$this->bot->multicast($tos, $textMessageBuilder);
	}
}
