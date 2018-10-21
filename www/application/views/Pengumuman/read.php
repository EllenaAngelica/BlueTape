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
					<h3><?=$pengumuman['subjek']?></h3>
					<p>
						by : <?=$pengumuman['nama']?> (<?=$pengumuman['email']?>) on <?=date("D,d M Y H:i:s", strtotime($pengumuman['waktu_terkirim']))?>
					</p>
					<br><br>
					<?= nl2br(nl2br($pengumuman['isi'])); ?>
					<?php if($pengumuman['ketersediaan_lampiran'] == 'Y'):?>
							<br><br>
							<p>
								*) Pengumuman ini memiliki lampiran, silahkan memeriksa langsung email student Anda untuk mengunduhnya.
							</p>
					<?php
						endif;
					?>
				</div>
			</div>
		</div>
    </body>
</html>