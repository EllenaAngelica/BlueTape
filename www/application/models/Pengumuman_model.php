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

					if(isset($structure->parts[0]->parts)){
						$attachmentExist = 'Y';
					}

					$bodymsg = imap_qprint(imap_fetchbody($inbox, $emailNumber, 1.2));

					if (empty($bodymsg)) {
						$bodymsg = imap_qprint(imap_fetchbody($inbox, $emailNumber, 1));
					}
				}
				
				$newEmails[$i]['emailFrom'] = $fromaddress;
				$newEmails[$i]['from'] = $header->fromaddress;
				$newEmails[$i]['date'] = date("Y-m-d H:i:s", $header->udate);
				$newEmails[$i]['subject'] = $header->subject;
				$newEmails[$i]['body'] = $bodymsg;
				$newEmails[$i]['attachmentExist'] = $attachmentExist;
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
				'ketersediaanLampiran' => $newEmail['attachmentExist']
			));
			$justInserted = $this->db->select("*")->order_by('id',"desc")->limit(1)->get('Pengumuman')->row();
			$id = $justInserted->id;
			
			$message = "Ada pengumuman baru! Silahkan klik link ini untuk melihatnya : " . base_url() . "pengumuman/read/" . $id;
			$this->load->model('Pengumuman_Line_model');
			$this->Pengumuman_Line_model->pushMessageToAllFollowers($message);
		}
	}
}
