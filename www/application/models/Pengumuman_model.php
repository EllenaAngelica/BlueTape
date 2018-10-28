<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengumuman_model extends CI_Model {
	public function __construct() {
        parent::__construct();
	
        $this->load->config('auth');
        $this->load->config('modules');
    }
	
	public function checkEmail(){
		$newEmails = null;
		
		$hostname = getEnv('HOSTNAME_INCOMING_EMAIL');
		$username = getEnv('ANNOUNCEMENT_EMAIL');
		$password = getEnv('ANNOUNCEMENT_PASSWORD');

		$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
		
		$emails = imap_search($inbox,'UNSEEN');

		if($emails) {
			$i = 0;
			foreach($emails as $emailNumber) {
				$header = imap_headerinfo($inbox,$emailNumber);
				$from = $header->from;
				foreach($from as $id => $object){
					$fromaddress = $object->mailbox . "@" . $object->host;
				}
				
				$structure = imap_fetchstructure($inbox, $emailNumber);
				
				if(isset($structure->parts) && is_array($structure->parts) && isset($structure->parts[1])) {
					$attachmentExist = 'N';
					$partNumber = '2';
					if(isset($structure->parts[0]->parts)){
						$attachmentExist = 'Y';
						$partNumber = '1.2';
					}
					$part = $structure->parts[1];
					$message = imap_fetchbody($inbox,$emailNumber,$partNumber);

					print_r($structure);
					if($part->encoding == 3) {
						$message = imap_base64($message);
					} else if($part->encoding == 1) {
						$message = imap_8bit($message);
					} else {
						$message = imap_qprint($message);
					}
				}
				
				$newEmails[$i]['emailFrom'] = $fromaddress;
				$newEmails[$i]['from'] = $header->fromaddress;
				$newEmails[$i]['date'] = date("Y-m-d H:i:s", $header->udate);
				$newEmails[$i]['subject'] = $header->subject;
				$newEmails[$i]['body'] = $message;
				$newEmails[$i]['attachementExist'] = $attachmentExist;
				$i++;
			}
		} 
		
		$errors = imap_errors();

		imap_close($inbox);
		
		return $newEmails;
	}
	
	public function proceedEmail($newEmail){
		$this->config->load('pengumuman');
		$terverifikasi = 0;
		$daftarEmailTerverifikasi = $this->config->item('pengirimTerverifikasi');
		foreach($daftarEmailTerverifikasi as $emailTerverifikasi){
			if($newEmail['emailFrom'] == $emailTerverifikasi){
				$terverifikasi = 1;
			}
		}
		
		if($terverifikasi == 1){
			$this->db->insert('Pengumuman', array(
				'namaPengirim' => $newEmail['from'],
				'emailPengirim' => $newEmail['emailFrom'],
				'waktuTerkirim' => $newEmail['date'],
				'subjek' => $newEmail['subject'],
				'isi' => $newEmail['body'],
				'ketersediaanLampiran' => $newEmail['attachementExist']
			));
		}
	}
}
