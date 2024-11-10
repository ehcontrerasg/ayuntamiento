<?php
namespace Models;

use App;

/**
 * Clase para gestionar los accesos CAASD
 */
class CaasdModel extends App\Conexion
{

    public function __construct()
    {
        parent::__construct();
    }

    public function GetAllData()
    {
        $sql = "SELECT * FROM MAESTRO_INM_SD WHERE ROWNUM <= 50";

        $data = oci_parse($this->_db, $sql);

        if (oci_execute($data)) {
            $result = oci_fetch_array($data);

            if (isset($result['CODIGO'])) {
                $arr1 = array();
                while (oci_fetch($data)) {
                    $arr = array(
                        "CODIGO"               => oci_result($data, 'CODIGO'),
                        "SECTOR"               => oci_result($data, 'SECTOR'),
                        "RUTA"                 => oci_result($data, 'RUTA'),
                        "ID_INMUEBLE"          => oci_result($data, 'ID_INMUEBLE'),
                        "ID_PROCESO"           => oci_result($data, 'ID_PROCESO'),
                        "NOMBRE_CLIENTE"       => oci_result($data, 'NOMBRE_CLIENTE'),
                        "DIRECCION"            => oci_result($data, 'DIRECCION'),
                        "URBANIZACION"         => oci_result($data, 'URBANIZACION'),
                        "ESTADO"               => oci_result($data, 'ESTADO'),
                        "USO"                  => oci_result($data, 'USO'),
                        "FECHA_DE_INSTALACION" => oci_result($data, 'FECHA_DE_INSTALACION'),
                        "SERIAL"               => oci_result($data, 'SERIAL'),
                        "LECTURA"              => oci_result($data, 'LECTURA'),
                        "CALIBRE"              => oci_result($data, 'CALIBRE'),
                        "OBSLECTURA"           => oci_result($data, 'OBSLECTURA'),
                        "FACT_PEND"            => oci_result($data, 'FACT_PEND'),
                        "DAGNO_MED"            => oci_result($data, 'DAGNO_MED'),
                    );
                    array_push($arr1, $arr);
                }

                return array(
                    "Status" => "Success",
                    "Code"   => "00",
                    "Data"   => $arr1,
                );
            } else {
                return array(
                    "Status" => "Not Data Found.",
                    "Code"   => "07",
                );
            }
        } else {
            return array(
                "Status" => "Server Response Error.",
                "Code"   => "06",
            );
        }

    }

    public function GetBalance($id_proceso)
    {
        $sql = "SELECT I.CODIGO_INM CODIGO, MAX(F.TOTAL) BALANCE_PAGAR
                FROM SGC_TT_INMUEBLES I,SGC_TT_FACTURA F
                WHERE
                    I.CODIGO_INM = F.INMUEBLE AND
                    F.FACTURA_PAGADA = 'N' AND
                    I.ID_PROCESO = '$id_proceso'
                GROUP BY  I.CODIGO_INM";

        $data = oci_parse($this->_db, $sql);

        if (oci_execute($data)) {
            $result = oci_fetch_array($data);

            if (isset($result['CODIGO'])) {
                $arr1 = array();

                $arr = array(
                    "CODIGO" => oci_result($data, 'CODIGO'),
                    "BALANCE_PAGAR" => oci_result($data, 'BALANCE_PAGAR')
                );
                array_push($arr1, $arr);

                return array(
                    "Status" => "Success",
                    "Code" => "00",
                    "Data" => $arr1,
                );
            } else {
                return array(
                    "Status" => "Not Data Found.",
                    "Code" => "07",
                );
            }
        } else {
            return array(
                "Status" => "Server Response Error.",
                "Code" => "06",
            );
        }
    }
        public function GetDatosCliente($id_proceso){
            $sql = "SELECT (SELECT MAX(F.TOTAL)
FROM SGC_TT_INMUEBLES I1,SGC_TT_FACTURA F,SGC_TT_MEDIDOR_INMUEBLE MI
WHERE
    I1.CODIGO_INM = F.INMUEBLE AND
    F.FACTURA_PAGADA = 'N' AND
    I1.ID_PROCESO=I.ID_PROCESO)BALANCE_PAGAR,
    CLI.NOMBRE_CLI NOMBRE_CLIENTE,CLI.EMAIL, CLI.DOCUMENTO
    FROM SGC_TT_INMUEBLES I, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES CLI
WHERE
    I.CODIGO_INM = C.CODIGO_INM AND
    CLI.CODIGO_CLI = C.CODIGO_CLI AND
    I.ID_PROCESO = '$id_proceso'";

            $data = oci_parse($this->_db, $sql);

            if (oci_execute($data)) {
                $result = oci_fetch_array($data);

                if (isset($result['BALANCE_PAGAR'])) {
                    $arr1 = array();
                    $arr = array(
                        //"CODIGO"              => oci_result($data, 'CODIGO'),
                        "BALANCE_PAGAR"         => oci_result($data, 'BALANCE_PAGAR'),
                        "NOMBRE_CLIENTE"        => oci_result($data, 'NOMBRE_CLIENTE'),
                        "EMAIL"                 => oci_result($data, 'EMAIL'),
                        "DOCUMENTO"             => oci_result($data, 'DOCUMENTO'),
                        "ID_TRANSACCION"        => $this->getTransactionId()
                    );
                    array_push($arr1, $arr);

                    return array(
                        "Status" => "Success",
                        "Code"   => "00",
                        "Data"   => $arr1,
                    );
                } else {
                    return array(
                        "Status" => "Not Data Found.",
                        "Code"   => "07",
                    );
                }
            } else {
                return array(
                    "Status" => "Server Response Error.",
                    "Code"   => "06",
                );
            }
        }


    public function getTransactionId()
    {
        $sql = "SELECT EXTERNAL_ORDER_ID_SEQ.nextval orden_id FROM dual";

        $result = oci_parse($this->_db, $sql);
        $flag   = oci_execute($result);

        return oci_fetch_array($result)['ORDEN_ID'];
    }
}
