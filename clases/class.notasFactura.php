<?php

     include_once "class.conexion.php";

    class NotasFactura extends ConexionClass
    {


        private $mesrror;
        private $coderror;

        public function getMesrror()
        {
            return $this->mesrror;
        }

        public function getCoderror()
        {
            return $this->coderror;
        }

        public function __construct()
        {
            parent::__construct();

        }


        function GetDatosCliente($idNota=0)
        {
          $sql= "SELECT nf.ID_NOTA, I.ID_PROYECTO, NCFU.ID_NCF|| NF.NCF_CONSEC NCF, NCFU2.ID_NCF|| F.NCF_CONSEC NCF_MODIFICADO, NF.DOCUMENTO, NF.NOMBRE_CLIENTE, NF.TOTAL_NOTA,NF.FECHA_EMISION,TN.DESCRIPCION TIPO_NOTA
                    FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F,  SGC_TT_NOTAS_FACTURAS NF, SGC_TP_NCF_USOS NCFU,SGC_TP_TIPOS_NOTA TN,
                    SGC_TP_NCF_USOS NCFU2
                    WHERE
                      I.CODIGO_INM=F.INMUEBLE AND
                      F.CONSEC_FACTURA=NF.FACTURA_APLICA AND
                      NF.ID_NOTA='$idNota' AND
                      NCFU.ID_NCF_USO = NF.ID_NCF AND
                      NCFU2.ID_NCF_USO = F.NCF_ID AND 
                      TN.ID = NF.TIPO_NOTA  ";

            $resultado=oci_parse($this->_db,$sql);
            $bandera=oci_execute($resultado);
            if($bandera){
                oci_close($this->_db);
                return $resultado;
            }else{
                oci_close($this->_db);
                return false;
            }


        }

        function GetRNCProyecto($codParametro=0)
        {
            $sql = "SELECT P.VAL_PARAMETRO RNC 
                    FROM SGC_TP_PARAMETROS P 
                    WHERE P.COD_PARAMETRO = '$codParametro'";

            $resultado=oci_parse($this->_db,$sql);
            $bandera=oci_execute($resultado);
            if($bandera){
                oci_close($this->_db);
                return $resultado;
            }else{
                oci_close($this->_db);
                return false;
            }
        }




        function GetNotasFacturas($inmueble)
        {
            $sql = "SELECT 
                    NF.ID_NOTA, 
                    NF.FACTURA_APLICA,
                    NF.TIPO_NOTA,
                    nf.TOTAL_NOTA,
                    FAC.TOTAL    

        FROM SGC_TT_NOTAS_FACTURAS NF , SGC_TT_FACTURA fac
            where nf.FACTURA_APLICA=fac.CONSEC_FACTURA and 
                  fac.INMUEBLE=$inmueble AND 
                  NF.POSIBLE_ANULAR='S' and 
                  nf.ANULADA='N'

";

            $resultado=oci_parse($this->_db,$sql);
            $bandera=oci_execute($resultado);
            if($bandera){
                oci_close($this->_db);
                return $resultado;
            }else{
                oci_close($this->_db);
                return false;
            }
        }

        function GetDetalleFactura($idFactura=0)
        {
           $sql="SELECT (CASE DNF.UNIDADES
                             WHEN 0 THEN '1 X '|| ROUND(DNF.VALOR,3)
                             ELSE DNF.UNIDADES||' X '|| ROUND(DNF.VALOR/DNF.UNIDADES,3) END) CANT, S.DESC_SERVICIO DESCRIPCION,
                  '0.00' ITBIS ,DNF.VALOR
                 FROM
                     SGC_TT_DETALLE_NOTAS_FACTURAS DNF, SGC_TT_NOTAS_FACTURAS NF, SGC_TP_SERVICIOS S
                  WHERE
                    DNF.ID_NOTA_FACT = NF.ID_NOTA AND
                    DNF.CONCEPTO = S.COD_SERVICIO AND
                    DNF.VALOR != 0 AND
                    NF.ID_NOTA= '$idFactura' AND 
                    NF.ANULADA='N'";

            $resultado=oci_parse($this->_db,$sql);
            $bandera=oci_execute($resultado);
            if($bandera){
                oci_close($this->_db);
                return $resultado;
            }else{
                oci_close($this->_db);
                return false;
            }

        }

        function GetCantidadNotas($idFactura=0){
            $sql="SELECT COUNT(*) CANTIDAD 
                  FROM SGC_TT_NOTAS_FACTURAS NF 
                  WHERE NF.FACTURA_APLICA=$idFactura
                        AND  FECHA_ANULACION IS NULL
                        AND USR_ANULACION IS NULL";

            $resultado=oci_parse($this->_db,$sql);
            $bandera=oci_execute($resultado);
            if($bandera){
                oci_close($this->_db);
                return $resultado;
            }else{
                oci_close($this->_db);
                return false;
            }

        }

        function GetNotasFactura($idFactura=0)
        {
            $sql="SELECT NF.ID_NOTA FROM SGC_TT_NOTAS_FACTURAS NF WHERE NF.FACTURA_APLICA=$idFactura AND  FECHA_ANULACION IS NULL
                        AND USR_ANULACION IS NULL 
                        AND NF.ANULADA='N'
                        ";

            $resultado=oci_parse($this->_db,$sql);
            $bandera=oci_execute($resultado);
            if($bandera){
                oci_close($this->_db);
                return $resultado;
            }else{
                oci_close($this->_db);
                return false;
            }

        }


        public function anulaNota($idNota, $usr)
        {
            $idNota = addslashes($idNota);
            $usr = addslashes($usr);


            $sql = "BEGIN SGC_P_ANULA_NOTAS($idNota,'$usr',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
            $resultado = oci_parse($this->_db, $sql);
            oci_bind_by_name($resultado, ":PMSGRESULT", $this->mesrror, 1000);
            oci_bind_by_name($resultado, ":PCODRESULT", $this->coderror);
            $bandera = oci_execute($resultado);
            oci_close($this->_db);
            echo  $this->mesrror;
            if ($bandera == true) {
                if ($this->coderror > 0) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }



    }