<?php
require_once '../vendor/autoload.php';

/* use MailchimpTransactional;
use MailchimpTransactional\ApiClient;
 */
class MailClient extends MailchimpTransactional\ApiClient
{

    private $APIKEY = "ATZIImbOdLPqJjKLLKvy4w";

    function __construct()
    {
        parent::__construct();
        $this->setApiKey($this->APIKEY);
    }

    function ping()
    {
        try {
            $response = $this->users->ping();
            print_r($response);
        } catch (Error $e) {
            echo 'Error: ', $e->getMessage(), "\n";
        }
    }

    function send($to = array(array('email'=>null, 'name'=>null, 'type'=>'to')))
    {
        try {
            $html = "<p>Hola mundo</p>";
            $subject = "Prueba";
            $fromEmail = "lmalcantara@caasdenlinea.com.do";
            $fromName = "Departamento de desarrollo";
            print_r($to);
            $message = array('message'=>array('html' => $html, 'subject' => $subject,'from_email'=>$fromEmail, 'from_name'=>$fromName, 'to'=>$to));
            $response = $this->messages->send($message);
            print_r($response);
        } catch (Error $e) {
            echo 'Error: ', $e->getMessage(), "\n";
        }
    }
}

$mailClient = new MailClient();
//$mailClient->ping();
$to = array();
array_push($to, array('email'=>'desarrollo@aceadominicana.com'));
array_push($to, array('email'=>'lmalcantara@caasdenlinea.com.do'));
$mailClient->send($to);
