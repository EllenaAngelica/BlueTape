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
	
	public function checkEmail(){
		$hostname = $_ENV['HOSTNAME_INCOMING_EMAIL'];
		$username = $_ENV['ANNOUNCEMENT_EMAIL'];
		$password = $_ENV['ANNOUNCEMENT_PASSWORD'];

		$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
		
		$emails = imap_search($inbox,'ALL');

		if($emails) {
			rsort($emails);
			foreach($emails as $email_number) {
				$overview = imap_fetch_overview($inbox,$email_number,0);
				$message = imap_fetchbody($inbox,$email_number,2);
				
				echo "Status : " . ($overview[0]->seen ? 'read' : 'unread') . "\n";
				echo "Subject : " . $overview[0]->subject . "\n";
				echo "From : " . $overview[0]->from . "\n";
				echo "Date : " . $overview[0]->date . "\n";
				echo "Body : " . $message . "\n";
			}
		} 

		imap_close($inbox);
	}
}
