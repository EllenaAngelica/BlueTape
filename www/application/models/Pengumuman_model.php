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
			
			/* begin output var */
			$output = '';
			
			/* put the newest emails on top */
			rsort($emails);
			
			/* for every email... */
			foreach($emails as $email_number) {
				
				/* get information specific to this email */
				$overview = imap_fetch_overview($inbox,$email_number,0);
				$message = imap_fetchbody($inbox,$email_number,2);
				
				/* output the email header information */
				$output.= '<div class="toggler '.($overview[0]->seen ? 'read' : 'unread').'">';
				$output.= '<span class="subject">'.$overview[0]->subject.'</span> ';
				$output.= '<span class="from">'.$overview[0]->from.'</span>';
				$output.= '<span class="date">on '.$overview[0]->date.'</span>';
				$output.= '</div>';
				
				/* output the email body */
				$output.= '<div class="body">'.$message.'</div>';
			}
			
			echo $output;
		} 

		/* close the connection */
		imap_close($inbox);
	}
}
