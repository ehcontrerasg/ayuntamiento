<?php
error_reporting(E_ALL);
ini_set('display_errors', '1'); 
class TestFacturaInstantanea{
    static function servicios(){
        return array(
            array(
                'servicio'=>'Agua',
                'uso'=>'Residencial',
                'tarifa'=>'52',
                'unidades'=>'1'
            ),
            array(
                'servicio'=>'Alcantarillado Red',
                'uso'=>'Residencial',
                'tarifa'=>'53',
                'unidades'=>'2'
            ),
            array(
                'servicio'=>'Mant. Medidor',
                'uso'=>'Residencial',
                'tarifa'=>'53',
                'unidades'=>'2'
            ),
            array(
                'servicio'=>'Mant. Medidor',
                'uso'=>'Proy. Construccion',
                'tarifa'=>'53',
                'unidades'=>'2'
            ),
            array(
                'servicio'=>'Mant. Medidor',
                'uso'=>'Residencial',
                'tarifa'=>'53',
                'unidades'=>'2'
            )
        );
    }
}