<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html class="no-js" lang="en">
    <?php $this->load->view('templates/head_loggedin'); ?>
    <body>
        <?php $this->load->view('templates/topbar_loggedin'); ?>
        <?php $this->load->view('templates/flashmessage'); ?>
        <?php $this->load->view('templates/script_foundation'); ?>
		<div class="row">
            <div class="medium-12 column">
                <div class="callout">
					<h5>Push Notification Pengumuman</h5>
					<p>Untuk mendapatkan push notification dari email Anda, tekan tombol di bawah</p>
					<form method="POST" action="/Pengumuman/pushNotification">
						<input type="submit" class="button" value="Push Notification">
					</form>
				</div>
			</div>
		</div>
    </body>
</html>