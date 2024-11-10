<?php
namespace Models;

use App;

/**
 * Modelo para gestionar los datos de via.
 */
class ViaModel extends App\Conexion
{

    public function __construct()
    {
        parent::__construct();
    }

    public function GetData($inm)
    {
        $sql = "SELECT i.codigo_inm,
       C.CODIGO_CLI,
       REGEXP_REPLACE(C.ALIAS, '[^A-Za-z0-9ÁÉÍÓÚáéíóú ]', '') alias,
       I.DIRECCION || ' ' || U.DESC_URBANIZACION direccion,
       NVL(SUM(F.TOTAL - F.TOTAL_PAGADO), 0) DEUDA,
       COUNT(F.CONSEC_FACTURA) FACTPEN,
       TO_CHAR(MAX(F.FEC_EXPEDICION), 'DD/MM/YYYY') FEC_EXP,
       MAX(F.CONSEC_FACTURA) FACTURA,
       TO_CHAR(MAX(P.FECHA_PAGO), 'DD/MM/YYYY') FEC_MIN,
       MAX(P.ID_PAGO) FAC_MIN,
       DECODE(I.FACTURAR, 'D', 'S', 'P', 'N') METODO,
       DECODE(I.ID_ESTADO, 'SS', 'C', 'PC', 'C', 'N') CORTE,
       NVL((SELECT ROUND(TR.VALOR_TARIFA) VALOR_TARIFA
             FROM SGC_TP_TARIFAS_RECONEXION TR,
                  SGC_TT_REGISTRO_CORTES    RC,
                  SGC_TT_INMUEBLES          IM,
                  SGC_TT_MEDIDOR_INMUEBLE   MI,
                  SGC_TP_ACTIVIDADES        AC,
                  SGC_TP_MEDIDORES          M
            WHERE TR.CODIGO_DIAMETRO = IM.COD_DIAMETRO
              AND RC.ID_INMUEBLE = IM.CODIGO_INM
              AND IM.CODIGO_INM = I.CODIGO_INM
              AND MI.COD_INMUEBLE(+) = IM.CODIGO_INM
              AND M.CODIGO_MED(+) = MI.COD_MEDIDOR
              AND AC.SEC_ACTIVIDAD = IM.SEC_ACTIVIDAD
              AND MI.FECHA_BAJA(+) IS NULL
              AND TR.CODIGO_CALIBRE = NVL(MI.COD_CALIBRE, 0)
              AND TR.CODIGO_USO = AC.ID_USO
              AND TR.MEDIDOR = NVL(M.ESTADO_MED, 'N')
              AND RC.PERVENC = 'N'
              AND RC.REVERSADO = 'N'
              AND AC.ID_USO NOT IN ('L', 'O')
              AND IM.ID_ESTADO IN ('PC', 'SS')
              AND RC.FECHA_ACUERDO IS NULL
              AND TR.PROYECTO = IM.ID_PROYECTO),
           0) RECONEXION,
       NVL(SUM(F.TOTAL - F.TOTAL_PAGADO) +
           NVL((SELECT ROUND(TR.VALOR_TARIFA) VALOR_TARIFA
                 FROM SGC_TP_TARIFAS_RECONEXION TR,
                      SGC_TT_REGISTRO_CORTES    RC,
                      SGC_TT_INMUEBLES          IM,
                      SGC_TT_MEDIDOR_INMUEBLE   MI,
                      SGC_TP_ACTIVIDADES        AC,
                      SGC_TP_MEDIDORES          M
                WHERE TR.CODIGO_DIAMETRO = IM.COD_DIAMETRO
                  AND RC.ID_INMUEBLE = IM.CODIGO_INM
                  AND IM.CODIGO_INM = I.CODIGO_INM
                  AND MI.COD_INMUEBLE(+) = IM.CODIGO_INM
                  AND M.CODIGO_MED(+) = MI.COD_MEDIDOR
                  AND AC.SEC_ACTIVIDAD = IM.SEC_ACTIVIDAD
                  AND MI.FECHA_BAJA(+) IS NULL
                  AND TR.CODIGO_CALIBRE = NVL(MI.COD_CALIBRE, 0)
                  AND TR.CODIGO_USO = AC.ID_USO
                  AND IM.ID_ESTADO IN ('PC', 'SS')
                  AND TR.MEDIDOR = NVL(M.ESTADO_MED, 'N')
                  AND RC.PERVENC = 'N'
                  AND RC.REVERSADO = 'N'
                  AND AC.ID_USO NOT IN ('L', 'O')
                  AND RC.FECHA_ACUERDO IS NULL
                  AND TR.PROYECTO = IM.ID_PROYECTO),
               0),
           0) TOTAL_FACT
  from sgc_tt_inmuebles i,
       sgc_tt_contratos c,
       sgc_tp_urbanizaciones u,
       (SELECT fa.*
          from SGC_TT_FACTURA fa
         where fa.inmueble = $inm
           and fa.fec_expedicion is not null
           and fa.factura_pagada = 'N') f,
       (select P.ID_PAGO, P.FECHA_PAGO, P.INM_CODIGO
          from sgc_tt_pagos p
         where p.inm_codigo = $inm
           and p.estado in ('A', 'T')) p
 where i.codigo_inm = c.codigo_inm
   and i.codigo_inm = f.inmueble(+)
   and i.codigo_inm = p.inm_codigo(+)
   and i.codigo_inm = $inm
   AND P.ID_PAGO = (SELECT MAX(PP.ID_PAGO)
                      FROM SGC_TT_PAGOS PP
                     WHERE PP.INM_CODIGO = I.CODIGO_INM
                       AND PP.ESTADO IN ('T', 'A'))
   and i.consec_urb = u.consec_urb
   and c.fecha_fin is null
   and i.id_proyecto = 'SD'
 GROUP BY I.CODIGO_INM,
          C.CODIGO_CLI,
          C.ALIAS,
          I.DIRECCION || ' ' || U.DESC_URBANIZACION,
          I.FACTURAR,
          ID_ESTADO";

        $result = oci_parse($this->_db, $sql);

        if (oci_execute($result)) {
            $result = oci_fetch_array($result);

            $data = array(
                "Inmueble"       => $result['CODIGO_INM'],
                "No_Cliente"     => $result['CODIGO_CLI'],
                "Cliente"        => $result['ALIAS'],
                "Direccion"      => $result['DIRECCION'],
                "Deuda"          => number_format($result['DEUDA'], 2),
                "No_Facturas"    => $result['FACTPEN'],
                "Fec_Factura"    => $result["FEC_EXP"],
                "No_Ult_Factura" => $result['FACTURA'],
                "Fec_Ult_Pago"   => $result['FEC_MIN'],
                "No_Ult_Pago"    => $result['FAC_MIN'],
                "Tipo_Servicio"  => $result['METODO'],
                "Estado"         => $result['CORTE'],
                "Reconexion"     => number_format($result['RECONEXION'], 2),
                "Monto_Total"    => number_format($result['TOTAL_FACT'], 2),
            );

            if (isset($result['CODIGO_INM'])) {
                return array(
                    "Status" => "Success",
                    "Code"   => "00",
                    "Data"   => $data,
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

    public function SetData($inm, $monto)
    {
        $monto     = number_format($monto, 0);
        $formaPago = 'Efectivo';

        $sql = "BEGIN SGC_P_INGRESA_PAGO_VIA(:inmueble,:importe,:formaPago,:codPago,:puntoPago,:saldoF,:codErr,:msgErr); END;";

        $result = oci_parse($this->_db, $sql);
        oci_bind_by_name($result, ':inmueble', $inm, 8);
        oci_bind_by_name($result, ':importe', $monto, 10);
        oci_bind_by_name($result, ':formaPago', $formaPago, 8);
        oci_bind_by_name($result, ':codPago', $noPago, 50);
        oci_bind_by_name($result, ':puntoPago', $puntoPago, 50);
        oci_bind_by_name($result, ':saldoF', $sFavor, 10);
        oci_bind_by_name($result, ':codErr', $noErr, 50);
        oci_bind_by_name($result, ':msgErr', $msgErr, 50);

        if (oci_execute($result)) {
            if ($noErr == 0) {
                $data = array(
                    'Pagado'      => number_format($monto, 2),
                    'Comprobante' => $noPago,
                    'Saldo_Favor' => number_format($sFavor, 2),
                    'Inmueble'    => $inm,
                );
                return array(
                    "Status" => "Payment Registered",
                    "Code"   => "00",
                    "Data"   => $data,
                );
            } else {
                return array(
                    "Status" => "Payment Error.",
                    "Code"   => "08",
                );
            }
        } else {
            return array(
                "Status" => "Server Response Error.",
                "Code"   => "06",
            );
        }
    }

    public function ReversePayment($noPago)
    {
        $user = '11111';
        $desc = 'Pago reversado mediante la plataforma (GTECH VIA)';

        $sql    = "BEGIN SGC_P_REVERSAPAGO(:pagoCod,:description,:user,:msgErr,:codErr); END;";
        $result = oci_parse($this->_db, $sql);

        oci_bind_by_name($result, ':pagoCod', $noPago, 20);
        oci_bind_by_name($result, ':description', $desc, 100);
        oci_bind_by_name($result, ':user', $user, 20);
        oci_bind_by_name($result, ':msgErr', $msgErr, 50);
        oci_bind_by_name($result, ':codErr', $codErr, 20);

        if (oci_execute($result)) {
            if ($noErr == 0) {
                return array(
                    "Status" => "Reversed Payment.",
                    "Code"   => "00",
                );
            } else {
                return array(
                    "Status" => "Reverse Payment Error.",
                    "Code"   => "09",
                );
            }
        } else {
            return array(
                "Status" => "Server Response Error.",
                "Code"   => "06",
            );
        }
    }

    public function checkAmount($inm, $monto)
    {
        $monto  = number_format($monto, 2);
        $sql    = "SELECT MTOPENFACTDIGITAL($inm) DEUDA FROM DUAL";
        $result = oci_parse($this->_db, $sql);

        if (oci_execute($result)) {
            $result = oci_fetch_array($result);
            if (number_format($result['DEUDA'], 2) == $monto) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
