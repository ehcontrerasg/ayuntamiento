<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 11/2/2015
 * Time: 3:47 PM
 */
include_once '../../clases/class.conexion.php';
class Observacion extends ConexionClass
{
    private $codresult;
    private $msgresult;

    public function __construct()
    {
        parent::__construct();
        $this->codresult = "";
        $this->msgresult = "";
    }

    public function ObsTotal($where, $sort, $start, $end)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        select * from (
                        SELECT OI.CODIGO_OBS,OI.ASUNTO,REPLACE(REPLACE(REPLACE(DESCRIPCION,CHR(10),' ') ,CHR(13),' ') ,'  ',' ') DESCRIPCION,OI.FECHA,U.LOGIN  FROM SGC_TT_OBSERVACIONES_INM OI, SGC_TT_USUARIOS U
                        WHERE U.ID_USUARIO=OI.USR_OBSERVACION

                        $where
                        $sort
                   )where  rownum<1000
                        )a WHERE  ROWNUM<=$start
                    ) WHERE rnum >= $end+1";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db, $sql
        );

        $banderas = oci_execute($resultado);
        if ($banderas == true) {

            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function formpago($codpago)
    {
        $sql = "SELECT FP.DESCRIPCION  FROM  SGC_TT_MEDIOS_recaudo MP, SGC_TP_FORMA_PAGO FP
              WHERE MP.id_otrrec =$codpago
              AND  FP.CODIGO=MP.ID_FORM_PAGO";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db, $sql
        );

        $banderas = oci_execute($resultado);
        if ($banderas == true) {

            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function lugPago($codpago)
    {
        $sql = "SELECT CP.DESCRIPCION DESC_CAJA,PP.DESCRIPCION DESC_PUNTO , EP.DESC_ENTIDAD FROM  sgc_tt_otros_recaudos p, sgc_tp_cajas_pago cp, sgc_tp_punto_pago pp, sgc_tp_entidad_pago ep
                WHERE  P.caja=cp.id_caja
                and PP.ID_PUNTO_PAGO=CP.ID_PUNTO_PAGO
                and EP.COD_ENTIDAD=PP.ENTIDAD_COD
                and P.codigo='$codpago'";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db, $sql
        );

        $banderas = oci_execute($resultado);
        if ($banderas == true) {

            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    //  Metodo para seleccionar los tipos de Observaciones
    public function seltiposObs()
    {
        $sql = "SELECT TOB.CODIGO, TOB.DESCRIPCION FROM SGC_TP_TIPOS_OBS TOB
            WHERE TOB.VISIBLE='S'
            ORDER BY DESCRIPCION ";

        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    //  Metodo para guardar una nueva observacion
    public function NuevaObs($usrobs, $asunto, $descripcion, $codigo, $inmueble)
    {
        /*$datos = array(
        usuario     => $usrobs,
        asunto      => $asunto,
        descripcion => $descripcion,
        codigo      => $codigo,
        inmueble    => $inmueble,
        );*/

        $sql       = "BEGIN AGC_P_ING_OBS('$asunto','$codigo','$descripcion','$usrobs',$inmueble,:PCODRESULT,:PMSGRESULT);COMMIT;END;";
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ':PCODRESULT', $this->codresult, "123");
        oci_bind_by_name($resultado, ':PMSGRESULT', $this->msgresult, "123");
        $bandera = oci_execute($resultado);
        if ($bandera) {
            if ($this->codresult > 0) {
                oci_close($this->_db);
                return $this->msgresult . " " . $this->codresult;
            } else {
                oci_close($this->_db);
                return 'true';
            }
        } else {
            oci_close($this->_db);
            return false;
        }
    }

}
