<?php
 class FSubmit2{

    public $method = null;
    public $url    = null;
    public $data   = null;

    private function headers($headers = []){

        /*Función que devuelve el string de los headers de la petición.*/

        $countHeaders = count($headers);
        if($countHeaders == 0) return false;

        $stringHeaders = "";
        for($i = 0 ; $i < $countHeaders ; $i++ ){
            $stringHeaders .= $headers[$i]."\r\n";
        }

        return $stringHeaders;

    }

    public function submit(){

        if ($this->url == null) return false;

        if($this->method == 'POST'){
            return $this->httpPost();
        }else if($this->method == 'GET'){
            return false;
        }

        return false;
    }

    private function httpPost(){

        //Declaración de los headers de la petición.
        $dataPost = null;
        if($this->data != null){
            if(!is_array($this->data)){
                $headers    = [
                    'Content-Type: application/json',
                    'Accept: application/json'
                ];
                $dataPost   = $this->data;
            }else{
                $dataPost   = http_build_query($this->data);
                $headers    = [
                    'Content-type: application/x-www-form-urlencoded',
                ];
            }
        }
        //Fin de la declaración de los headers de la petición.

        $options = array(
            'http' => array(
                'header'  => $this->headers($headers),
                'method'  => 'POST',
                'content' => $dataPost
            )
        );
        $context  = stream_context_create($options);

        return file_get_contents($this->url, false, $context);
    }
}

$urlIkey    = 'https://lab.cardnet.com.do/api/payment/idenpotency-keys';
$urlSale    = 'https://lab.cardnet.com.do/api/payment/transactions/sales';
$dataSale   = [
    'idempotency-key'=> '5cfeb4cc52a5490780e481c6eebb087f',
    'merchant-id'=> 349000000,
    'terminal-id'=> 58585858,
    'card-number'=> 5488780025245579,
    'environment'=> 'ECommerce',
    'expiration-date'=> '01/23',
    'cvv'=> 494,
    'amount'=> 187,
    'currency'=> 214,
    'invoice-number'=> 22050768,
    'client-ip'=> '172.16.1.250',
    'reference-number'=> 198560,
    'token'=> '5cfeb4cc52a5490780e481c6eebb087f',
    'tax'=> 0,
    'tip'=> 0
];

$fSubmit2           = new FSubmit2();
$fSubmit2->url      = $urlSale;
$fSubmit2->method   = 'POST';
//$fSubmit2->data     = json_encode($dataSale);
$respuesta          = $fSubmit2->submit();
$array              = json_decode($respuesta,1);
//echo $respuesta;
//echo $array['response-code-desc'];
print_r($array);
//var_dump($respuesta);