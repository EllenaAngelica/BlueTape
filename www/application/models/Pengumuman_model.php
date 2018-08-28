<?php
defined('BASEPATH') OR exit('No direct script access allowed');


define('CLIENT_SECRET_PATH', BASEPATH . '/core/client_secret.json');
define('CREDENTIALS_PATH', '~/.credentials/appliction_default_credentials.json');

class Pengumuman_model extends CI_Model {
	private $client;
    
	public function __construct() {
        parent::__construct();
		
        $this->load->config('auth');
        $this->load->config('modules');
    }
	
	public function generateHistoryId(){
		$userId = 'me';
		$access_token = $_SESSION['token']['access_token'];
		$topic_name = 'projects/bluetape-201512/topics/pengumuman';
		// POST request    
		$ch = curl_init('https://www.googleapis.com/gmail/v1/users/' . $userId . '/watch');

		curl_setopt_array($ch, array(
			CURLOPT_POST => TRUE,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $access_token,
				'Content-Type: application/json'
			),
			CURLOPT_POSTFIELDS => json_encode(array(
				'labelIds' => ["INBOX"],
				'topicName' => $topic_name
			))
		));
		$reply = curl_exec($ch);
		$historyId = explode('"',$reply)[3];
		$_SESSION['hId'] = $historyId;
	}
	
	public function refreshNotification(){
		$userId = 'me';
		$access_token = $_SESSION['token']['access_token'];
		
		$ch2 = curl_init('https://www.googleapis.com/gmail/v1/users/' . $userId . '/history?historyTypes=messageAdded&labelId=INBOX&startHistoryId=' . $_SESSION['hId']);
		curl_setopt_array($ch2, array(
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $access_token,
				'Content-Type: application/json'
			)
		));
			
		$reply2 = curl_exec($ch2);
		curl_close($ch2);
		return $reply2;
	}
	
	public function getMessageList(){
		$userId = 'me';
		$access_token = $_SESSION['token']['access_token'];
		
		$ch3 = curl_init('https://www.googleapis.com/gmail/v1/users/' . $userId . '/messages?includeSpamTrash=false&labelIds=INBOX&maxResults=5');
		curl_setopt_array($ch3, array(
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $access_token,
				'Content-Type: application/json'
			)
		));
			
		$reply3 = curl_exec($ch3);
		curl_close($ch3);
		return $reply3;
	}
	
	public function getInformation($mId){
		$userId = 'me';
		$access_token = $_SESSION['token']['access_token'];
		
		$ch2 = curl_init('https://www.googleapis.com/gmail/v1/users/' . $userId . '/messages/' . $mId . '?format=metadata&metadataHeaders=Delivered-To&metadataHeaders=From&metadataHeaders=Date&metadataHeaders=Subject');
		curl_setopt_array($ch2, array(
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $access_token,
				'Content-Type: application/json'
			)
		));
			
		$reply2 = curl_exec($ch2);
		return $reply2;
	}
}
