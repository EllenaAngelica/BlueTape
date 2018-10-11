<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengumuman_model extends CI_Model {
	public function __construct() {
        parent::__construct();
	
        $this->load->config('auth');
        $this->load->config('modules');
    }
	
	public function checkEmail(){
		$new_emails = null;
		
		$hostname = $_ENV['HOSTNAME_INCOMING_EMAIL'];
		$username = $_ENV['ANNOUNCEMENT_EMAIL'];
		$password = $_ENV['ANNOUNCEMENT_PASSWORD'];

		$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
		
		$emails = imap_search($inbox,'UNSEEN');

		if($emails) {
			$i = 0;
			foreach($emails as $email_number) {
				$header = imap_headerinfo($inbox,$email_number);
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
					
					if(isset($structure->parts[0]->parts)){
						$attachment_exist = 'Y';
					}else{
						$attachment_exist = 'N';
					}
				}
				
				$new_emails[$i]['email_from'] = $header->senderaddress;
				$new_emails[$i]['date'] = date("Y-m-d H:i:s", $header->udate);
				$new_emails[$i]['subject'] = $header->subject;
				$new_emails[$i]['body'] = $message;
				$new_emails[$i]['attachement_exist'] = $attachment_exist;
				$i++;
			}
		} 

		imap_close($inbox);
		
		return $new_emails;
	}
	
	public function proceedEmail($new_email){
		$email_id = null;
		
		$this->db->select('id');
		$query = $this->db->get_where('Pengirim_Terverifikasi', ['email'=>$new_email['email_from']],1);
		if($query->num_rows() == 1){
			$email_id = $query->result()->id;
		}
		
		if($email_id!=null){
			$this->db->insert('Pengumuman', array(
				'email_id' => $email_id,
				'waktu_terkirim' => $new_email['date'],
				'subjek' => $new_email[$i]['subject'],
				'isi' => $new_email[$i]['body'],
				'ketersediaan_lampiran' => $new_email[$i]['attachement_exist']
			));
		}
	}
}
