<?php
require('../clases/class.facturas.php');
require('../clases/class.inmuebles.php');

class DatosFacturaInstantanea {
    
    private $classFactura = null;
    private $inmueble = null;
    function __construct(){
        $this->classFactura = new facturas();
        $this->inmueble = new inmuebles();
    }

    function consecutivoFactura($codigoInmueble,$periodo){
        $datos = $this->classFactura->ConsecFact($codigoInmueble,$periodo);
        while ($fila = oci_fetch_assoc($datos)) {
            $consecutivoFactura = $fila['CONSEC_FACTURA'];
        }
        return $consecutivoFactura;
    }

    function datosFactura($consecutivoFactura){
        $datosFactura = $this->classFactura->datosFacturaPdf($consecutivoFactura);
        $arr = array();
        while ($fila = oci_fetch_assoc($datosFactura)) {
            array_push($arr, array(
                'codigo_inmueble'=> $fila['CODIGO_INM'],
                'tipo_documento'=> $fila['TIPODOC'],
                'vence_ncf'=> $fila['VENCE_NCF'],
                'ncf'=> $fila['NCF'],
                'catastro'=> $fila['CATASTRO'],
                'alias'=> $fila['ALIAS'],
                'direccion'=> $fila['DIRECCION'],
                'desc_urbanizacion'=> $fila['DESC_URBANIZACION'],
                'id_zona'=> $fila['ID_ZONA'],
                'fecha_expedicion'=> $fila['FECEXP'],
                'periodo'=> $fila['PERIODO'],
                'id_proceso'=> $fila['ID_PROCESO'],
                'gerencia'=> $fila['GERENCIA'],
                'descripcion_medidor'=> $fila['DESC_MED'],
                'desc_calibre'=> $fila['DESC_CALIBRE'],
                'serial'=> $fila['SERIAL'],
                'fec_vcto'=> $fila['FEC_VCTO'],
                'feccorte'=> $fila['FECCORTE'],
                'id_proyecto'=> $fila['ID_PROYECTO'],
                'msj_ncf'=> $fila['MSJ_NCF'],
                'msj_periodo'=> $fila['MSJ_PERIODO'],
                'msj_alerta'=> $fila['MSJ_ALERTA'],
                'msj_buro'=> $fila['MSJ_BURO'],
                'documento'=> $fila['DOCUMENTO'],
                'id_tipo_cliente'=> $fila['ID_TIPO_CLIENTE']
            ));
        }
        return $arr[0];
    }
    
    function saldoFavor($codigoInmueble){
        $saldoFavor = oci_fetch_assoc($this->inmueble->saldoFavor($codigoInmueble))["SALDO"];
        return ($saldoFavor == null) ? 0 : $saldoFavor;        
    }

    function diferido($codigoInmueble){
        $diferido = oci_fetch_assoc($this->inmueble->DiferidoDeud($codigoInmueble))["DIFERIDO"];
        return ($diferido == null) ? 0 : $diferido; 
    }

    function servicios($codigoInmueble){
        $datos = $this->classFactura->datosServiciosPdf($codigoInmueble);
        $arr = array();
        while ($fila = oci_fetch_assoc($datos)) {
            array_push($arr, array(
                'servicio' => $fila["DESC_SERVICIO"],
                'uso' => $fila["DESC_USO"],
                'codigo_tarifa' => $fila["CODIGO_TARIFA"],
                'unidades' => $fila["UNIDADES_TOT"],
                'tarifa' => $fila["CONSEC_TARIFA"],
                'codigo_servicio' => $fila["COD_SERVICIO"],
                'opera_corte' => $fila["OPERA_CORTE"],
            ));
        }
        return $arr;
    }

    function detallServicios($consecutivoFactura){
        $datos = $this->classFactura->detalleFacturaPdf($consecutivoFactura);
        $arr = array();
        while ($fila = oci_fetch_assoc($datos)) {
         array_push($arr,array(
            'concepto' => $fila['CONCEPTO'],
            'rango' => $fila['RANGO'],
            'unidades' => $fila['UNIDADES'],
            'valor' => $fila['VALOR'],
            'cod_servicio' => $fila['COD_SERVICIO']
         ));   
        }
        return $arr;
    }

    

    function detalleServiciosDomiciliados($consecutivoFactura){
        $detalleFactura = $this->classFactura->detalleFacturaPdf($consecutivoFactura);    

        $arr = array('servicios_domiciliarios'=>array(), 'otros_conceptos'=>array());
        while ($fila = oci_fetch_assoc($detalleFactura)) {
            

            //Otros Conceptos
            if($fila['COD_SERVICIO'] > 4){
                array_push($arr["otros_conceptos"],array(
                    'concepto' => $fila['CONCEPTO'],
                    'importe' => round($fila['VALOR'])
                ));
                continue;
            };

            //Servicios domiciliarios
            array_push($arr["servicios_domiciliarios"], array(
                'concepto' =>$fila['CONCEPTO'],
                'rango'=> $fila['RANGO'],
                'unidades' => $fila['UNIDADES'],
                'importe' => round($fila['VALOR']),
                'codigo_servicio' => $fila['COD_SERVICIO'],
            ));
        }
        return $arr;
    }

    function datosLecturas($codigoInmueble, $periodo){
        $datos = $this->classFactura->datosLecturaPdf($codigoInmueble, $periodo);
        $arr = array();
        while ($fila = oci_fetch_assoc($datos)) {
            array_push($arr,array(
                'lectura_actual' => $fila["LECTURA_ACTUAL"],
                'feclec' => $fila["FECLEC"]
            ));
        }
        return $arr;
    }

    function consumo($consecutivoFactura){
        $datos = $this->classFactura->datosConsumoFactPdf($consecutivoFactura);
        $datos = oci_fetch_assoc($datos);
        $consumo = $datos["CONSUMO"];
        return $consumo;
    }


    function otrosConceptos($consecutivoFactura){
        $detalleFactura = $this->classFactura->detalleFacturaPdf($consecutivoFactura);    

        $arr = array();
        while ($fila = oci_fetch_assoc($detalleFactura)) {
            
            if($fila['COD_SERVICIO'] < 4) continue;

            array_push($arr, array(
                'concepto' =>$fila['CONCEPTO'],
                'rango'=> $fila['RANGO'],
                'unidades' => $fila['UNIDADES'],
                'importe' => $fila['VALOR'],
                'codigo_servicio' => $fila['COD_SERVICIO'],
            ));
        }
        return $arr;
    }
}
