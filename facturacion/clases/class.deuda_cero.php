<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/26/2015
 * Time: 4:33 PM
 */
include_once '../../clases/class.conexion.php';

class deuda_cero extends ConexionClass {
    private $codresult;
    private $msgresult;

    public function getcodresult(){return $this->codresult;}
    public function getmsgresult(){return $this->msgresult;}

    public function __construct()
    {
        parent::__construct();
    }

    public function comprobarDeudaCero($codinmueble){

        $sql="BEGIN SGC_P_VERIFICA_DEUDA_CERO($codinmueble,:PMSGRESULT,:PCODRESULT);COMMIT;END;";
		
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,':PCODRESULT',$this->codresult,"123");
        oci_bind_by_name($resultado,':PMSGRESULT',$this->msgresult,"123");
        $bandera=oci_execute($resultado);
        if($bandera){
            if($this->codresult==0){
                return true;
            }
            else{

                return false;
            }
        }
        else{
            oci_close($this->_db);
            return false;
        }


    }


    public function obtiene_mora($codinmueble){
        $sql="select NVL(SUM(DF.VALOR),0)+NVL(SUM(DF.TOTAL_DEBITO),0)-NVL(SUM(DF.TOTAL_CREDITO),0) MORA from sgc_tt_detalle_factura df, sgc_tt_factura f
              where F.CONSEC_FACTURA=DF.FACTURA
              and F.FACTURA_PAGADA='N'
              AND DF.CONCEPTO in(10,104)
			  AND F.FEC_EXPEDICION IS NOT NULL
              AND F.INMUEBLE=$codinmueble";

        $resultado = oci_parse($this->_db,$sql);
        $banderas=oci_execute($resultado);
        if($banderas)
        {
            oci_fetch($resultado);
            $mora = oci_result($resultado, 'MORA');
            return $mora;
        }
        else
        {

            echo "false";
            return false;
        }
    }


    public function obtiene_servicios($codinmueble){
        $sql="select SUM(DF.VALOR)+sum(df.TOTAL_DEBITO)-SUM(DF.TOTAL_CREDITO) SERVICIOS from sgc_tt_detalle_factura df, sgc_tt_factura f
              where F.CONSEC_FACTURA=DF.FACTURA
              and F.FACTURA_PAGADA='N'
              AND DF.CONCEPTO NOT IN(10,104)
			  AND F.FEC_EXPEDICION IS NOT NULL
              AND F.INMUEBLE=$codinmueble";

        $resultado = oci_parse($this->_db,$sql);
        $banderas=oci_execute($resultado);
        if($banderas)
        {
            oci_fetch($resultado);
            $mora = oci_result($resultado, 'SERVICIOS');

            return $mora;
        }
        else
        {

            echo "false";
            return false;
        }
    }


    public function aplicaPDC($codinmueble,$valordiferido,$mora,$cuotas,$usr,$totApl,$numCuotPag){
        $sql="BEGIN SGC_P_DEUDA_CERO2('$codinmueble','$valordiferido','$mora','$cuotas','$usr','$numCuotPag','$totApl',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,':PCODRESULT',$this->codresult,"123");
        oci_bind_by_name($resultado,':PMSGRESULT',$this->msgresult,10000);
        $bandera=oci_execute($resultado);
       if($bandera){
            if($this->codresult==0){
                return true;
            }
            else{
                echo $this->msgresult;
                return false;
            }
        }
        else{
            echo'error cusnsulta';
            oci_close($this->_db);
            return false;
        }
    }
    
    public function Obtenerdeudacero ($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select * from (
                          select DC.TOTAL_DIFERIDO ,DC.TOTAL_CUOTAS,DC.TOTAL_CUOTAS_PAG,DC.TOTAL_PAGADO,(DC.TOTAL_DIFERIDO-DC.TOTAL_PAGADO) PENDIENTE
                          from sgc_tt_deuda_cero dc
                          where DC.ACTIVA='S'
                          $where
                          $sort
                         )
						)a WHERE  ROWNUM<=$start
					) WHERE rnum >= $end+1";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db,$sql
        );


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


    public function Obtenerdeudaceroinmueble ($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select * from (
                          select DC.ID_DEUDA_CERO, DC.PERIODO_INI, DC.TOTAL_CUOTAS, DC.FECH_ULTPAGO, DC.ACTIVA, DC.TOTAL_CUOTAS_PAG, DC.TOTAL_DIFERIDO, DC.TOTAL_MORA,
                          CL.NOMBRE_CLI, DC.PERIODO_CARGA_REV PERREV from sgc_tt_deuda_cero dc, SGC_TT_CLIENTES CL
                          WHERE CL.CODIGO_CLI=DC.CLIENTE_ACUERDO
                          $where
                          $sort
                         )
						)a WHERE  ROWNUM<=$start
					) WHERE rnum >= $end+1";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db,$sql
        );


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

//////////////////////datos generales inmuebles/////////////////////////////////////////	
	
public function datosInmueble ($codinmueble)
    {
        $sql="SELECT I.ID_ZONA, U.DESC_URBANIZACION, I.DIRECCION, TO_CHAR(I.FEC_ALTA,'DD/MM/YYYY')FEC_ALTA,  I.ID_ESTADO, I.CATASTRO, I.ID_PROCESO, C.CODIGO_CLI, C.ID_CONTRATO,
			C.ALIAS, L.DOCUMENTO, L.TELEFONO, M.SERIAL, I.ID_PROYECTO
			FROM SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES L, SGC_TT_MEDIDOR_INMUEBLE M
			WHERE U.CONSEC_URB(+) = I.CONSEC_URB 
			AND M.COD_INMUEBLE(+) = I.CODIGO_INM 
            AND C.CODIGO_INM(+) = I.CODIGO_INM 
            AND L.CODIGO_CLI(+) = C.CODIGO_CLI 
            AND I.CODIGO_INM = '$codinmueble'";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db,$sql
        );


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
	

	 public function reversa_PDC($codinmueble){
      $sql="BEGIN SGC_P_REVERSO_DEUDA_CERO($codinmueble,:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,':PCODRESULT',$this->codresult,"123");
        oci_bind_by_name($resultado,':PMSGRESULT',$this->msgresult,"123");
        $bandera=oci_execute($resultado);
       if($bandera){
            if($this->codresult==0){
                return true;
            }
            else{

                return false;
            }
        }
        else{
            oci_close($this->_db);
            return false;
        }
    }
	
	 public function verifica_PDC($codinmueble){
        $sql="SELECT ID_DEUDA_CERO FROM SGC_TT_DEUDA_CERO
              WHERE ACTIVA = 'S' AND COD_INMUEBLE=$codinmueble";

        $resultado = oci_parse($this->_db,$sql);
        $banderas=oci_execute($resultado);
        if($banderas)
        {
            oci_fetch($resultado);
            $id_pdc = oci_result($resultado, 'ID_DEUDA_CERO');

            return $id_pdc;
        }
        else
        {

            echo "false";
            return false;
        }
    }

	
}