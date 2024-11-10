<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/
define('METHOD','AES-256-CBC');
define('SECRET_KEY','$sinq4421');
define('SECRET_IV','101712');
class EncriptDecript{

    public static function encryption($string){
        $output=FALSE;
        $key=hash('sha256', SECRET_KEY);
        $iv=substr(hash('sha256', SECRET_IV), 0, 16);
        $output=openssl_encrypt($string, METHOD, $key, 0, $iv);
        $output=base64_encode($output);
        return $output;
    }
    public static function decryption($string){
        $key=hash('sha256', SECRET_KEY);
        $iv=substr(hash('sha256', SECRET_IV), 0, 16);
        $output=openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
        return $output;
    }


}


$sec = new EncriptDecript();
/*$encript = $sec->encrypt("1234");
echo "ENCRIPT: ".$encript;*/

$encript = $sec->encryption("5488780025245579");
echo $encript;
$decript = $sec->decryption($encript);
echo $decript;
//echo "DECRIPT: ".$decript;
//echo /*utf8_encode(*/$sec->decrypt("G�=>Mo0","12345")/*)*/;



