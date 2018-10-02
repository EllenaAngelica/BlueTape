<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengumuman_model extends CI_Model {
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
		
		$emails = imap_search($inbox,'UNSEEN');

		if($emails) {
			foreach($emails as $email_number) {
				$overview = imap_fetch_overview($inbox,$email_number,0);
				$structure = imap_fetchstructure($inbox, $email_number);
				
				if(isset($structure->parts) && is_array($structure->parts) && isset($structure->parts[1])) {
					$part = $structure->parts[1];
					$message = imap_fetchbody($inbox,$email_number,2);

					if($part->encoding == 3) {
						$message = imap_base64($message);
					} else if($part->encoding == 1) {
						$message = imap_8bit($message);
					} else {
						$message = imap_qprint($message);
					}
				}
			
				echo "Status : " . ($overview[0]->seen ? 'read' : 'unread') . "<br>";
				echo "Subject : " . $overview[0]->subject . "<br>";
				echo "From : " . $overview[0]->from . "<br>";
				echo "Date : " . $overview[0]->date . "<br>";
				echo "Body : " . $message . "<br>";
			}
		} 

		imap_close($inbox);
	}
}
