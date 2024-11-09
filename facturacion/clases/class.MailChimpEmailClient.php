<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
//require_once('../../global/vendor/autoload.php');
//use MailchimpTransactional\ApiClient;
//include_once ('../../global/vendor/mailchimp/transactional/lib/Configuration.php');
//require_once  '../../global/vendor/mailchimp/transactional/lib/Configuration.php';

function run(){
    try {
        $mailchimp = new ApiClient();
        $mailchimp->setApiKey('db92d1f57d05a77648ca71660e2a5297-us19');
        $response = $mailchimp->users->ping();
        print_r($response);
    } catch (Error $e) {
        echo 'Error: ',  $e->getMessage(), "\n";
    }
}
run();