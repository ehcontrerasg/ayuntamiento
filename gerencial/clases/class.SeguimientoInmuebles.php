<?php
include_once 'class.conexion.php';

class SeguimientoInmuebles extends ConexionClass
{

    public function __construct(){
        parent::__construct();
    }

    //Usuarios activos
    function UsuariosActivos(){
        $sql="SELECT count(INM.CODIGO_INM) USUARIOS_ACTIVOS
              FROM SGC_TT_INMUEBLES INM,MAESTRO_SEGUMIENTO_INMUEBLES SI
              where INM.CODIGO_INM = SI.INMUEBLE AND INM.ID_ESTADO NOT IN ('CC',
                                                                           'CB',
                                                                           'CT',
                                                                           'CK')";

        $resultado = oci_parse($this->_db,$sql);

        if(oci_execute($resultado)){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }
    }

    //USUARIOS CATASTRADOS
    function UsuariosCatastrados(){
          $sql= "SELECT count(INM.CODIGO_INM) USUARIOS_CATASTRADOS
                 FROM SGC_TT_INMUEBLES INM, MAESTRO_SEGUMIENTO_INMUEBLES SI
                 where INM.CODIGO_INM = si.INMUEBLE";

        $resultado = oci_parse($this->_db,$sql);

        if(oci_execute($resultado)){
            oci_close($this->_db);
            return $resultado;
        }else {
            oci_close($this->_db);
            return false;
        }
    }

    //CONEXIONES CON MEDIDOR
    function ConexionesConMedidor(){
        $sql= "SELECT COUNT(MI.COD_INMUEBLE) CONEXIONES_CON_MEDIDOR
               FROM MAESTRO_SEGUMIENTO_INMUEBLES SI,SGC_TT_MEDIDOR_INMUEBLE MI
               where MI.COD_INMUEBLE = SI.INMUEBLE AND
                     MI.FECHA_INSTALACION IS NOT NULL";

        $resultado = oci_parse($this->_db,$sql);

        if(oci_execute($resultado)){
            oci_close($this->_db);
            return $resultado;
        }else {
            oci_close($this->_db);
            return false;
        }
    }

    //MEDIDORES LEIDOS
    function MedidoresLeidos($periodo){
        $sql= "SELECT COUNT(SI.INMUEBLE) MEDIDORES_LEIDOS
               FROM MAESTRO_SEGUMIENTO_INMUEBLES SI, SGC_TT_INMUEBLES INM, SGC_TT_REGISTRO_LECTURAS RL
               WHERE INM.CODIGO_INM = SI.INMUEBLE AND
                     INM.CODIGO_INM= RL.COD_INMUEBLE AND
                     RL.FECHA_LECTURA IS NOT NULL AND
                     RL.PERIODO = $periodo";

        $resultado = oci_parse($this->_db,$sql);

        if(oci_execute($resultado)){
            oci_close($this->_db);
            return $resultado;
        }else {
            oci_close($this->_db);
            return false;
        }
    }

    //NUMERO DE CONEXIONES CON MEDIDOR
    function NumeroConexionesConMedidor(){
        $sql = "SELECT COUNT(MI.COD_INMUEBLE) CONEXIONES_CON_MEDIDOR
                FROM SGC_TT_INMUEBLES INM, MAESTRO_SEGUMIENTO_INMUEBLES SI,SGC_TT_MEDIDOR_INMUEBLE MI
                where INM.CODIGO_INM = SI.INMUEBLE AND
                      MI.COD_INMUEBLE = INM.CODIGO_INM AND
                      MI.FECHA_BAJA IS NULL AND
                      MI.FECHA_INSTALACION IS NOT NULL";

        $resultado = oci_parse($this->_db,$sql);

        if(oci_execute($resultado)){
            oci_close($this->_db);
            return $resultado;
        }else {
            oci_close($this->_db);
            return false;
        }
    }

    //FACTURAS EMITIDAS
    function FacturasEmitidas($periodo){
        $sql = "SELECT COUNT(ORR.TOTAL_ORI)FACTURAS_EMITIDAS FROM SGC_TT_FACTURA ORR, MAESTRO_SEGUMIENTO_INMUEBLES SI, SGC_TT_INMUEBLES I
                WHERE I.CODIGO_INM = ORR.INMUEBLE AND
                      I.CODIGO_INM = SI.INMUEBLE AND
                      ORR.FEC_EXPEDICION IS NOT NULL AND
                      ORR.PERIODO = $periodo AND
                      I.ID_ESTADO NOT IN ('CC',
                                          'CB',
                                          'CT',
                                          'CK')";

        $resultado = oci_parse($this->_db,$sql);

        if(oci_execute($resultado)){
            oci_close($this->_db);
            return $resultado;
        }else {
            oci_close($this->_db);
            return false;
        }
    }

    //VALOR RECAUDADO
    function ValorRecaudado($periodo){
        $sql= "SELECT DISTINCT ((SELECT SUM(P.IMPORTE)CANTIDAD
                  FROM SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R,SGC_TP_INMUEBLES_INCORPORADOS i
                  WHERE C.ID_CAJA = P.ID_CAJA
                    AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                    AND R.ENTIDAD_COD=E.COD_ENTIDAD
                    AND E.VALIDA_REPORTES='S'
                    AND P.ESTADO='A'
                    AND I.INMUEBLE = P.INM_CODIGO
                    AND TO_CHAR(P.FECHA_PAGO,'YYYYMM')=F.PERIODO
                 )
                   +
                 (SELECT SUM(P.IMPORTE)CANTIDAD
                  FROM SGC_TT_OTROS_RECAUDOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO R, SGC_TP_INMUEBLES_INCORPORADOS i
                  WHERE C.ID_CAJA = P.CAJA
                    AND C.ID_PUNTO_PAGO=R.ID_PUNTO_PAGO
                    AND R.ENTIDAD_COD=E.COD_ENTIDAD
                    AND E.VALIDA_REPORTES='S'
                    AND P.ESTADO IN ('T','A')
                    AND I.INMUEBLE = P.INMUEBLE
                    AND TO_CHAR(P.FECHA_PAGO,'YYYYMM')=F.PERIODO
                 )) RECAUDOS
              FROM SGC_TT_FACTURA F,SGC_TP_INMUEBLES_INCORPORADOS i1
              WHERE
                  f.INMUEBLE = I1.INMUEBLE AND
                  F.FEC_EXPEDICION IS NOT NULL AND
                  F.PERIODO = $periodo";

        $resultado = oci_parse($this->_db,$sql);

        if(oci_execute($resultado)){
            oci_close($this->_db);
            return $resultado;
        }else {
            oci_close($this->_db);
            return false;
        }
    }

    //VALOR FACTURADO
    function ValorFacturado($periodo){

        $sql = "SELECT SUM(ORR.TOTAL_ORI)VALOR_FACTURADO FROM SGC_TT_FACTURA ORR, MAESTRO_SEGUMIENTO_INMUEBLES SI
                WHERE SI.INMUEBLE=ORR.INMUEBLE AND
                      ORR.FEC_EXPEDICION IS NOT NULL AND
                      ORR.PERIODO = $periodo";

        $resultado = oci_parse($this->_db,$sql);

        if(oci_execute($resultado)){
            oci_close($this->_db);
            return $resultado;
        }else {
            oci_close($this->_db);
            return false;
        }
    }

    //NUMERO DE CUENTAS PAGADAS

    function CuentasPagadas($periodo){
        $sql = "SELECT count(ORR.IMPORTE) NUMERO_CUENTAS_PAGADAS FROM SGC_TT_PAGOS ORR, MAESTRO_SEGUMIENTO_INMUEBLES SI,SGC_TP_CAJAS_PAGO CP
                WHERE SI.INMUEBLE=ORR.INM_CODIGO AND
                      CP.ID_CAJA=ORR.ID_CAJA AND
                      CP.VALIDA_REPORTES='S' AND
                      ORR.ESTADO<>'I' AND
                      TO_char(ORR.FECHA_PAGO,'YYYYMM')=$periodo";

        $resultado = oci_parse($this->_db,$sql);

        if(oci_execute($resultado)){
            oci_close($this->_db);
            return $resultado;
        }else {
            oci_close($this->_db);
            return false;
        }
    }

    //CUENTAS EMITIDAS
    function CuentasEmitidas(){
        $sql = "SELECT COUNT(ORR.INMUEBLE)NUMERO_CUENTAS_EMITIDAS FROM SGC_TT_FACTURA ORR, MAESTRO_SEGUMIENTO_INMUEBLES SI
                WHERE SI.INMUEBLE=ORR.INMUEBLE AND
                      ORR.FEC_EXPEDICION IS NOT NULL AND
                      ORR.PERIODO = 201903";

        $resultado = oci_parse($this->_db,$sql);

        if(oci_execute($resultado)){
            oci_close($this->_db);
            return $resultado;
        }else {
            oci_close($this->_db);
            return false;
        }
    }

    //CUENTAS RECLAMADAS
    function CuentasReclamadas($periodo){
        $sql = "SELECT COUNT(PQR.CODIGO_PQR) NUMERO_CUENTAS_RECLAMADAS
                FROM MAESTRO_SEGUMIENTO_INMUEBLES SI, SGC_TT_PQRS PQR
                WHERE SI.INMUEBLE = PQR.COD_INMUEBLE AND
                      PQR.TIPO_PQR = '1' AND
                      TO_CHAR(PQR.FECHA_PQR,'YYYYMM') = $periodo";

        $resultado = oci_parse($this->_db,$sql);

        if(oci_execute($resultado)){
            oci_close($this->_db);
            return $resultado;
        }else {
            oci_close($this->_db);
            return false;
        }
    }

    //PORCENTAJE
    function Porcentaje($valor1,$valor2){
        $resultado = round(($valor1/$valor2)*100,2)."%";
        return $resultado;
    }

}