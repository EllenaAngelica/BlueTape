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
		
		$num = imap_num_msg($inbox); 

		 //if there is a message in your inbox 
		 if( $num >0 ) { 
			  //read that mail recently arrived 
			  echo imap_qprint(imap_body($inbox, $num)); 
		 } 

		 //close the stream 
		 imap_close($inbox); 
	}
}
