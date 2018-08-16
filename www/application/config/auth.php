<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$config['domain'] = $_ENV['CI_BASE_URL'];
$config['google-clientid'] = $_ENV['GOOGLE_CLIENTID'];
$config['google-clientsecret'] = $_ENV['GOOGLE_CLIENTSECRET'];
$config['google-redirecturi'] = $config['domain'] . '/auth/oauth2callback';

$config['email-config'] = Array(
    'protocol' => 'smtp',
    'smtp_host' => $_ENV['SMTP_HOST'],
    'smtp_port' => intval($_ENV['SMTP_PORT']),
    'smtp_user' => $_ENV['SMTP_USER'],
    'smtp_pass' => $_ENV['SMTP_PASS'],
    'mailtype' => 'html',
    'charset' => 'iso-8859-1'
);