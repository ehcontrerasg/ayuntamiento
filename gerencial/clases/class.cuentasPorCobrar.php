<?php
include_once 'class.conexion.php';

 class CuentasPorCobrar extends ConexionClass{

        public function  __construct(){
            parent::__construct();
        }


     public function GetCuentasPorCobrar($where,$proyecto){

            $where_proyecto= ["",""];
            if($proyecto!=''){
                $where_proyecto[0]= " AND I1.ID_PROYECTO = '$proyecto' ";
                $where_proyecto[1]= " AND I.ID_PROYECTO = '$proyecto' ";

            }

            $sql = "SELECT U.ID_USO, SUM(F.TOTAL-F.TOTAL_PAGADO+F.TOTAL_DEBITO-F.TOTAL_CREDITO) DEUDA
                    FROM SGC_TP_USOS U,SGC_TT_INMUEBLES I,SGC_TP_ACTIVIDADES ACT,
                         (
                         SELECT  I1.CODIGO_INM,MIN(F1.FECHA_CORTE) FECHA_CORTE
                         FROM SGC_TT_FACTURA F1, SGC_TT_INMUEBLES I1
                         WHERE I1.CODIGO_INM = F1.INMUEBLE AND
                               F1.FACTURA_PAGADA = 'N' AND
                               F1.FECHA_PAGO IS NULL AND
                               F1.FEC_EXPEDICION IS NOT NULL
                               ".$where_proyecto[0]."
                         GROUP BY  I1.CODIGO_INM
                         )VENC,SGC_TT_FACTURA F
                    WHERE
                        VENC.CODIGO_INM = I.CODIGO_INM
                        AND I.SEC_ACTIVIDAD = ACT.SEC_ACTIVIDAD
                        AND U.ID_USO = ACT.ID_USO
                        AND U.ID_USO IN ('R','O','M','D','C','I','L','PC','S')"
                        .$where.
                        " AND i.ZONA_FRANCA = 'N'
                        AND I.CODIGO_INM = F.INMUEBLE ".$where_proyecto[1]."   
                    GROUP BY U.ID_USO";

            $resultado  = oci_parse($this->_db, $sql);

            if(oci_execute($resultado)){
                oci_close($this->_db);
                return $resultado;
            }else{
                return false;
            }
     }

     public function GetCuentasPorCobrarZF($where,$proyecto){

         $where_proyecto= ["",""];
         if($proyecto!=''){
             $where_proyecto[0]= " AND I1.ID_PROYECTO = '$proyecto' ";
             $where_proyecto[1]= " AND I.ID_PROYECTO = '$proyecto' ";
         }

           $sql  = "SELECT NVL(SUM(F.TOTAL-F.TOTAL_PAGADO+F.TOTAL_DEBITO-F.TOTAL_CREDITO),0) DEUDA, 'ZF' ID_USO
                     FROM SGC_TT_INMUEBLES I,
                          (
                         SELECT  I1.CODIGO_INM,MIN(F1.FECHA_CORTE) FECHA_CORTE
                         FROM SGC_TT_FACTURA F1, SGC_TT_INMUEBLES I1
                         WHERE I1.CODIGO_INM = F1.INMUEBLE 
                               AND F1.FACTURA_PAGADA = 'N' 
                               AND F1.FECHA_PAGO IS NULL ".$where_proyecto[0]."
                               AND F1.FEC_EXPEDICION IS NOT NULL
                         GROUP BY  I1.CODIGO_INM
                         )VENC,SGC_TT_FACTURA F
                     WHERE
                         VENC.CODIGO_INM = I.CODIGO_INM"
                         .$where.
                         " AND i.ZONA_FRANCA = 'S' 
                           AND I.CODIGO_INM = F.INMUEBLE"
                           .$where_proyecto[1];

         $resultado  = oci_parse($this->_db, $sql);

         if(oci_execute($resultado)){
             oci_close($this->_db);
             return $resultado;
         }else{
             return false;
         }
     }

     public function GetCantidadUsuariosCuentasPorCobrar($where,$proyecto){

         $where_proyecto= ["",""];
         if($proyecto!=''){
             $where_proyecto[0]= " AND I1.ID_PROYECTO = '$proyecto' ";
             $where_proyecto[1]= " AND I.ID_PROYECTO = '$proyecto' ";

         }

         $sql= "SELECT U.ID_USO,COUNT(I.CODIGO_INM) CANTIDAD_USUARIOS
                FROM SGC_TP_USOS U,SGC_TT_INMUEBLES I,SGC_TP_ACTIVIDADES ACT,
                     (
                     SELECT  I1.CODIGO_INM,MIN(F1.FECHA_CORTE) FECHA_CORTE
                     FROM SGC_TT_FACTURA F1, SGC_TT_INMUEBLES I1
                     WHERE I1.CODIGO_INM = F1.INMUEBLE
                           AND F1.FACTURA_PAGADA = 'N'
                           AND F1.FECHA_PAGO IS NULL"
                            .$where_proyecto[0].
                           "AND F1.FEC_EXPEDICION IS NOT NULL
                     GROUP BY  I1.CODIGO_INM
                     )VENC,SGC_TT_FACTURA F
                WHERE
                    VENC.CODIGO_INM = I.CODIGO_INM
                   AND I.SEC_ACTIVIDAD = ACT.SEC_ACTIVIDAD
                   AND U.ID_USO = ACT.ID_USO
                   AND U.ID_USO IN ('R','O','M','D','C','I','L','PC','S')"
                      .$where.
                  "AND i.ZONA_FRANCA = 'N'
                   AND I.CODIGO_INM = F.INMUEBLE"
                    .$where_proyecto[1].
                "GROUP BY U.ID_USO";

         $resultado  = oci_parse($this->_db, $sql);

         if(oci_execute($resultado)){
             oci_close($this->_db);
             return $resultado;
         }else{
             return false;
         }
     }

     public function GetCantidadUsuariosCuentasPorCobrarZF($where,$proyecto){
         $where_proyecto= ["",""];
         if($proyecto!=''){
             $where_proyecto[0]= " AND I1.ID_PROYECTO = '$proyecto' ";
             $where_proyecto[1]= " AND I.ID_PROYECTO = '$proyecto' ";

         }
         $sql  = "SELECT COUNT(F.TOTAL-F.TOTAL_PAGADO+F.TOTAL_DEBITO-F.TOTAL_CREDITO) CANTIDAD_USUARIOS, 'ZF' ID_USO
                     FROM SGC_TT_INMUEBLES I,
                          (
                         SELECT  I1.CODIGO_INM,MIN(F1.FECHA_CORTE) FECHA_CORTE
                         FROM SGC_TT_FACTURA F1, SGC_TT_INMUEBLES I1
                         WHERE I1.CODIGO_INM = F1.INMUEBLE 
                               AND F1.FACTURA_PAGADA = 'N' 
                               AND F1.FECHA_PAGO IS NULL"
                                .$where_proyecto[0].
                               "AND F1.FEC_EXPEDICION IS NOT NULL
                         GROUP BY  I1.CODIGO_INM
                         )VENC,SGC_TT_FACTURA F
                     WHERE
                         VENC.CODIGO_INM = I.CODIGO_INM".
                      $where.
                      " AND i.ZONA_FRANCA = 'S' 
                        AND I.CODIGO_INM = F.INMUEBLE"
                        .$where_proyecto[1];

         $resultado  = oci_parse($this->_db, $sql);

         if(oci_execute($resultado)){
             oci_close($this->_db);
             return $resultado;
         }else{
             return false;
         }
     }

     public function grupoUsos(){
         $sql="SELECT U.ID_USO,U.DESC_USO
              FROM SGC_TP_USOS U
              WHERE U.ID_USO IN ('R','C','I','O','D','M','S','L','PC')
              ORDER BY U.DESC_USO
              ";

         $resultado = oci_parse($this->_db,$sql);

         $banderas=oci_execute($resultado);
         if($banderas==TRUE)
         {
             oci_close($this->_db);
             return $resultado;
         }
         else
         {
             oci_close($this->_db);
             echo "false";
             return false;
         }
     }
}
