<?php
include_once '../../clases/class.conexion.php';
class ReportesFacturacion extends ConexionClass{
	private $id_proyecto;
	private $id_zonini;
	private $id_zonfin;
	private $id_perfin;
	private $id_perini;
	
	public function __construct()
	{
		parent::__construct();
		$this->id_proyecto="";
		$this->id_zonini="";
		$this->id_zonfin="";
		$this->id_perfin="";
		$this->id_perini="";
	}
	
	public function CantidadLeidos($perini,$zonini,$zonfin,$perfin,$proyecto){
        /// ya optimizada
		$sql = "
          SELECT COUNT(F.CONSEC_FACTURA) CANTIDAD,
               SUM((SELECT SUM(DF.UNIDADES)
                       FROM SGC_TT_DETALLE_FACTURA DF
                      WHERE DF.FACTURA = F.CONSEC_FACTURA
                        AND DF.CONCEPTO IN(1, 3))) CONSUMO,
               SUM(F.TOTAL) FACTURADO,
               SUM(F.TOTAL_PAGADO) RECAUDADO,
               SUM(F.TOTAL) - SUM(F.TOTAL_PAGADO) PENDIENTE
          FROM SGC_TT_FACTURA F,
               SGC_TT_INMUEBLES I
          WHERE F.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')
            AND I.CODIGO_INM = F.INMUEBLE + 0
            AND I.ID_PROYECTO || '' = '$proyecto'
            AND F.PERIODO BETWEEN '$perini' AND '$perfin'
            AND F.METODO_CALCULO IN ('D')";
		//echo $sql;
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
	
	public function CantidadPromedios($perini,$zonini,$zonfin,$perfin,$proyecto){
        /// ya optimizada
		$sql = "SELECT COUNT(F.CONSEC_FACTURA) CANTIDAD,
       SUM((SELECT SUM(DF.UNIDADES)
               FROM SGC_TT_DETALLE_FACTURA DF
              WHERE DF.FACTURA = F.CONSEC_FACTURA
                AND DF.CONCEPTO IN(1, 3))) CONSUMO,
       SUM(F.TOTAL) FACTURADO,
       SUM(F.TOTAL_PAGADO) RECAUDADO,
       SUM(F.TOTAL) - SUM(F.TOTAL_PAGADO) PENDIENTE
  FROM SGC_TT_FACTURA F,
       SGC_TT_INMUEBLES I
 WHERE F.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')
   AND I.CODIGO_INM = F.INMUEBLE + 0
   AND I.ID_PROYECTO || '' = '$proyecto'
   AND F.PERIODO BETWEEN '$perini' AND '$perfin'
   AND F.METODO_CALCULO IN ('P')";
		//echo $sql;
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

	
	public function CantidadConceptos($perini,$zonini,$zonfin,$perfin,$proyecto){
		$sql = "SELECT D.CONCEPTO, S.DESC_SERVICIO
                FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA D, SGC_TP_SERVICIOS S, SGC_TT_INMUEBLES I
                WHERE F.INMUEBLE = I.CODIGO_INM
                AND F.CONSEC_FACTURA=D.FACTURA
                AND D.CONCEPTO = S.COD_SERVICIO
                AND F.PERIODO BETWEEN '$perini' AND '$perfin'
                AND F.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')
                AND I.ID_PROYECTO = '$proyecto'
                AND D.CONCEPTO IN (1,2,3,4,11)
                GROUP BY D.CONCEPTO, S.DESC_SERVICIO
        ORDER BY  S.DESC_SERVICIO";
		//echo $sql;
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

	public function CantidadConceptosOptimizado($perini,$zonini,$zonfin,$perfin,$proyecto){
		$sql = " SELECT * FROM (
                SELECT D.CONCEPTO, S.DESC_SERVICIO
                FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA D, SGC_TP_SERVICIOS S, SGC_TT_INMUEBLES I
                WHERE F.INMUEBLE = I.CODIGO_INM
                AND F.CONSEC_FACTURA=D.FACTURA
                AND D.CONCEPTO = S.COD_SERVICIO
                AND F.PERIODO BETWEEN '$perini' AND '$perfin'
                AND F.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')
                AND I.ID_PROYECTO = '$proyecto'
                --AND D.CONCEPTO IN (1,2,3,4,11)
                GROUP BY D.CONCEPTO, S.DESC_SERVICIO
                ORDER BY  S.DESC_SERVICIO
                /*UNION
                SELECT D.CONCEPTO,
                       S.DESC_SERVICIO
                  FROM SGC_TT_INMUEBLES I,
                       SGC_TT_FACTURA F,
                       SGC_TT_DETALLE_FACTURA D,
                       SGC_TT_SERVICIOS_INMUEBLES SI,
                       SGC_TP_ACTIVIDADES A,
                       SGC_TP_SERVICIOS S,
                       SGC_TP_USOS U,
                       SGC_TP_TARIFAS T
                 WHERE F.INMUEBLE = I.CODIGO_INM
                   AND F.CONSEC_FACTURA = D.FACTURA
                   AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD
                   AND A.ID_USO = U.ID_USO
                   AND S.COD_SERVICIO = D.CONCEPTO
                   AND SI.CONSEC_TARIFA = T.CONSEC_TARIFA
                   AND SI.COD_INMUEBLE = F.INMUEBLE
                   AND T.COD_USO = U.ID_USO
                   AND SI.COD_INMUEBLE = I.CODIGO_INM
                   AND F.PERIODO BETWEEN $perini AND $perfin
                   AND F.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')
                   AND I.ID_PROYECTO = '$proyecto'
                   AND D.CONCEPTO NOT IN (1, 2, 3, 4, 11)
                 GROUP BY D.CONCEPTO, S.DESC_SERVICIO*/
                )";

		//echo $sql;
		$resultado = oci_parse($this->_db,$sql);

		$banderas=oci_execute($resultado);
		if($banderas==TRUE)
		{
			oci_close($this->_db);
			//echo true;
			return $resultado;
		}
		else
		{
			oci_close($this->_db);
			echo "false";
			return false;
		}

	}  

	public function CantidadUsos($perini,$zonini,$zonfin,$perfin,$proyecto,$concepto){
		$sql="SELECT /*+ USE_MERGE(U,S,A) ORDERED */ D.CONCEPTO,
                   S.DESC_SERVICIO,
                   U.DESC_USO
              FROM SGC_TT_INMUEBLES I,
                   SGC_TT_FACTURA F,
                   SGC_TT_DETALLE_FACTURA D,
                   SGC_TP_ACTIVIDADES A,
                   SGC_TP_SERVICIOS S,
                   SGC_TP_USOS U
             WHERE F.INMUEBLE = I.CODIGO_INM
               AND F.CONSEC_FACTURA = D.FACTURA
               AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD
               AND A.ID_USO = U.ID_USO
               AND I.ID_PROYECTO = '$proyecto'
               AND S.COD_SERVICIO = D.CONCEPTO
               AND F.PERIODO BETWEEN $perini AND $perfin
               AND S.DESC_SERVICIO = '$concepto'
               AND I.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')
             GROUP BY D.CONCEPTO, S.DESC_SERVICIO, U.DESC_USO
             ORDER BY D.CONCEPTO, U.DESC_USO";
		//echo $sql;
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


	public function CantidadUsosOptimizado($perini,$zonini,$zonfin,$perfin,$proyecto/*,$concepto*/){
		$sql="SELECT * FROM (SELECT D.CONCEPTO,
                                  S.DESC_SERVICIO,
                                  U.DESC_USO
                             FROM SGC_TT_INMUEBLES I,
                                  SGC_TT_FACTURA F,
                                  SGC_TT_DETALLE_FACTURA D,
                                  SGC_TP_ACTIVIDADES A,
                                  SGC_TP_SERVICIOS S,
                                  SGC_TP_USOS U
                            WHERE F.INMUEBLE = I.CODIGO_INM
                              AND F.CONSEC_FACTURA = D.FACTURA
                              AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD
                              AND A.ID_USO = U.ID_USO
                              AND I.ID_PROYECTO = '$proyecto'
                              AND S.COD_SERVICIO = D.CONCEPTO
                              AND F.PERIODO BETWEEN $perini AND $perfin
                              --AND S.DESC_SERVICIO = '$concepto'
                              AND I.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')
                            GROUP BY D.CONCEPTO, S.DESC_SERVICIO, U.DESC_USO
                            /*UNION
                            SELECT D.CONCEPTO, U.DESC_USO
                            FROM SGC_TT_DETALLE_FACTURA D,
                                 SGC_TT_FACTURA F,
                                 SGC_TP_USOS U,
                                 SGC_TP_ACTIVIDADES A,
                                 SGC_TT_INMUEBLES I
                           WHERE I.CODIGO_INM = F.INMUEBLE + 0
                             AND F.CONSEC_FACTURA = D.FACTURA + 0
                             AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD
                             AND A.ID_USO = U.ID_USO
                             AND F.PERIODO + 0 >= $perini
                             AND F.PERIODO + 0 <= $perfin
                             AND F.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')
                             --AND D.CONCEPTO = '$otroconcepto'
                             AND I.ID_PROYECTO || '' = '$proyecto'
                           GROUP BY D.CONCEPTO,U.DESC_USO*/
                           )
             
             ";
		$sql = "SELECT *
        FROM (SELECT U.DESC_USO, F.ID_ZONA, D.CONCEPTO
              FROM SGC_TT_INMUEBLES I,
                   SGC_TT_FACTURA F,
                   SGC_TT_DETALLE_FACTURA D,
                   SGC_TP_ACTIVIDADES A,
                   SGC_TP_USOS U
              WHERE I.CODIGO_INM = F.INMUEBLE
                AND F.CONSEC_FACTURA = D.FACTURA
                AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
                AND U.ID_USO = A.ID_USO
                AND I.ID_PROYECTO = '$proyecto'
                AND F.PERIODO BETWEEN $perini AND $perfin
              GROUP BY U.DESC_USO, F.ID_ZONA, D.CONCEPTO)
        WHERE ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')
        ";
		
		$sql = "SELECT DISTINCT U.DESC_USO, /*F.ID_ZONA,*/ D.CONCEPTO
      FROM SGC_TT_INMUEBLES I,
           SGC_TT_FACTURA F,
           SGC_TT_DETALLE_FACTURA D,
           SGC_TP_ACTIVIDADES A,
           SGC_TP_USOS U
      WHERE I.CODIGO_INM = F.INMUEBLE
        AND F.CONSEC_FACTURA = D.FACTURA
        AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
        AND U.ID_USO = A.ID_USO
        AND I.ID_PROYECTO = '$proyecto'
        AND F.PERIODO BETWEEN $perini AND $perfin
      GROUP BY U.DESC_USO, F.ID_ZONA, D.CONCEPTO";
		//echo $sql;
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
  
	public function CantidadConceptoPorUso($perini,$zonini,$zonfin,$perfin,$proyecto,$codconcepto,$desuso){
		/// ya optimizado
        $sql = "
		SELECT U.DESC_USO,
           T.CODIGO_TARIFA,
           T.DESC_TARIFA,
           SUM(SI.CONSUMO_MINIMO) CONSUMO,
           COUNT(I.CODIGO_INM) CANTIDAD,
           SUM(SI.UNIDADES_TOT) UNIDADES,
           SUM(D.VALOR_ORI) FACTURADO,
           SUM(D.VALOR_PAGADO) RECAUDADO,
           SUM(D.VALOR) - SUM(D.VALOR_PAGADO) PENDIENTE
      FROM SGC_TT_INMUEBLES I,
           SGC_TT_SERVICIOS_INMUEBLES SI,
           SGC_TT_FACTURA F,
           SGC_TT_DETALLE_FACTURA D,
           SGC_TP_ACTIVIDADES A,
           SGC_TP_SERVICIOS S,
           SGC_TP_USOS U,
           SGC_TP_TARIFAS T
     WHERE I.CODIGO_INM = F.INMUEBLE
       AND D.FACTURA = F.CONSEC_FACTURA
       AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
       AND U.ID_USO = A.ID_USO
       AND D.CONCEPTO = S.COD_SERVICIO
       AND T.CONSEC_TARIFA = SI.CONSEC_TARIFA
       AND F.INMUEBLE = SI.COD_INMUEBLE
       AND U.ID_USO = T.COD_USO
       AND I.CODIGO_INM = SI.COD_INMUEBLE
       AND D.CONCEPTO = SI.COD_SERVICIO
       AND '$proyecto' = I.ID_PROYECTO
       AND $perini + UID * 0 <= F.PERIODO
       AND $perfin >= F.PERIODO
       AND I.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')
       AND $codconcepto = D.CONCEPTO
       AND '$desuso' = U.DESC_USO
       AND A.ID_USO = T.COD_USO
       AND S.COD_SERVICIO = SI.COD_SERVICIO
       AND S.COD_SERVICIO = $codconcepto
       AND SI.COD_SERVICIO = $codconcepto
     GROUP BY U.DESC_USO, T.DESC_TARIFA, T.CODIGO_TARIFA
     ORDER BY U.DESC_USO
";
		//echo $sql;
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

	public function CantidadConceptoPorUsoOptimizado($perini,$zonini,$zonfin,$perfin,$proyecto/*,$codconcepto,$desuso*/){

        $sql = "SELECT * FROM (SELECT U.DESC_USO,
                                      T.CODIGO_TARIFA,
                                      T.DESC_TARIFA,
                                      SUM(SI.CONSUMO_MINIMO) CONSUMO,
                                      COUNT(I.CODIGO_INM) CANTIDAD,
                                      SUM(SI.UNIDADES_TOT) UNIDADES,
                                      SUM(D.VALOR_ORI) FACTURADO,
                                      SUM(D.VALOR_PAGADO) RECAUDADO,
                                      SUM(D.VALOR) - SUM(D.VALOR_PAGADO) PENDIENTE,
                                      D.CONCEPTO
                               FROM SGC_TT_INMUEBLES I,
                                    SGC_TT_SERVICIOS_INMUEBLES SI,
                                    SGC_TT_FACTURA F,
                                    SGC_TT_DETALLE_FACTURA D,
                                    SGC_TP_ACTIVIDADES A,
                                    SGC_TP_SERVICIOS S,
                                    SGC_TP_USOS U,
                                    SGC_TP_TARIFAS T
                               WHERE I.CODIGO_INM = F.INMUEBLE
                                 AND D.FACTURA = F.CONSEC_FACTURA
                                 AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
                                 AND U.ID_USO = A.ID_USO
                                 AND D.CONCEPTO = S.COD_SERVICIO
                                 AND T.CONSEC_TARIFA = SI.CONSEC_TARIFA
                                 AND F.INMUEBLE = SI.COD_INMUEBLE
                                 AND U.ID_USO = T.COD_USO
                                 AND I.CODIGO_INM = SI.COD_INMUEBLE
                                 AND D.CONCEPTO = SI.COD_SERVICIO
                                 AND '$proyecto' = I.ID_PROYECTO
                                  AND $perini + UID * 0 <= F.PERIODO
                                   AND $perfin >= F.PERIODO
                                   AND I.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')
                                   AND A.ID_USO = T.COD_USO
                                   AND S.COD_SERVICIO = SI.COD_SERVICIO
                                   AND CONCEPTO  IN (1, 2, 3, 4, 11)
                                   GROUP BY U.DESC_USO, T.DESC_TARIFA, T.CODIGO_TARIFA,D.CONCEPTO
                                   /*ORDER BY U.DESC_USO*/
                                   UNION
                                   SELECT DESC_USO,CODIGO_TARIFA,DESC_TARIFA,CONSUMO,CANTIDAD, UNIDADES,FACTURADO, RECAUDADO, PENDIENTE,CONCEPTO
                                      FROM (SELECT U.DESC_USO, 0 CODIGO_TARIFA,'NO APLICA' DESC_TARIFA,F.ID_ZONA, D.CONCEPTO,SUM(D.UNIDADES_ORI) CONSUMO,
                                                   COUNT(F.INMUEBLE) CANTIDAD,
                                                   SUM(D.VALOR_ORI) FACTURADO,
                                                   SUM(I.UNIDADES_HAB) UNIDADES,
                                                   SUM(D.VALOR_PAGADO)  RECAUDADO,
                                                   SUM(D.VALOR) - SUM(D.VALOR_PAGADO) PENDIENTE
                                      FROM    SGC_TT_INMUEBLES I,
                                              SGC_TT_FACTURA F,
                                              SGC_TT_DETALLE_FACTURA D,
                                              SGC_TP_ACTIVIDADES A,
                                              SGC_TP_USOS U
                                      WHERE   I.CODIGO_INM = F.INMUEBLE
                                          AND F.CONSEC_FACTURA = D.FACTURA
                                          AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
                                          AND U.ID_USO = A.ID_USO
                                          AND I.ID_PROYECTO = '$proyecto'
                                          AND F.PERIODO BETWEEN $perini AND $perfin
                                        AND CONCEPTO NOT IN (1, 2, 3, 4, 11)
                                      GROUP BY U.DESC_USO, F.ID_ZONA, D.CONCEPTO)
                                      WHERE ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')
                                    )
                                   ";

        /*$sql = "SELECT
                        T.CODIGO_TARIFA,
                        T.DESC_TARIFA,
                        U.DESC_USO,
                        DF.CONCEPTO,
                        SUM(DF.UNIDADES_ORI) CONSUMO,
                        COUNT(F.INMUEBLE) CANTIDAD,
                        SUM(DF.VALOR_ORI) FACTURADO,
                        SUM(INM.UNIDADES_HAB) UNIDADES,
                        SUM(DF.VALOR_PAGADO) RECAUDADO,
                        SUM(DF.VALOR) - SUM(DF.VALOR_PAGADO) PENDIENTE
                        FROM SGC_TT_FACTURA F, SGC_TT_INMUEBLES INM, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES ACT, SGC_TP_USOS U,SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TP_TARIFAS T
                        WHERE INM.CODIGO_INM = F.INMUEBLE
                        AND   INM.ID_PROYECTO = '$proyecto'
                        AND   F.CONSEC_FACTURA = DF.FACTURA
                        AND   F.PERIODO BETWEEN $perini AND $perfin
                        AND   F.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')
                        AND   INM.SEC_ACTIVIDAD = ACT.SEC_ACTIVIDAD
                        AND   U.ID_USO = ACT.ID_USO
                        AND     T.CONSEC_TARIFA = SI.CONSEC_TARIFA
                        AND     U.ID_USO = T.COD_USO
                        AND SI.COD_INMUEBLE = INM.CODIGO_INM
                        GROUP BY   T.CODIGO_TARIFA,T.DESC_TARIFA,U.DESC_USO,DF.CONCEPTO";*/


        /*$sql = "SELECT U.DESC_USO,
       SUM(D.UNIDADES_ORI) CONSUMO,
       COUNT(I.CODIGO_INM) CANTIDAD,
       SUM(I.UNIDADES_HAB) UNIDADES,
       SUM(D.VALOR_ORI) FACTURADO,
       SUM(D.VALOR_PAGADO) RECAUDADO,
       SUM(D.VALOR) - SUM(D.VALOR_PAGADO) PENDIENTE,
       D.CONCEPTO
FROM SGC_TT_INMUEBLES I,
     SGC_TT_FACTURA F,
     SGC_TT_DETALLE_FACTURA D,
     SGC_TP_ACTIVIDADES A,
     SGC_TP_SERVICIOS S,
     SGC_TP_USOS U
WHERE I.CODIGO_INM = F.INMUEBLE
  AND D.FACTURA = F.CONSEC_FACTURA
  AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
  AND U.ID_USO = A.ID_USO
  AND D.CONCEPTO = S.COD_SERVICIO
  AND 'SD' = I.ID_PROYECTO
  AND 202005 + UID * 0 <= F.PERIODO
  AND 202005 >= F.PERIODO
  AND I.ID_ZONA BETWEEN UPPER('16E') AND UPPER('16E')
GROUP BY U.DESC_USO, D.CONCEPTO";*/

        /*$sql = "SELECT U.DESC_USO,
                 D.CONCEPTO,
                 SUM(D.UNIDADES_ORI) CONSUMO,
                 COUNT(F.INMUEBLE) CANTIDAD,
                 SUM(D.VALOR_ORI) FACTURADO,
                 SUM(I.UNIDADES_HAB) UNIDADES,
                 SUM(D.VALOR_PAGADO) RECAUDADO,
                 SUM(D.VALOR) - SUM(D.VALOR_PAGADO) PENDIENTE
                 FROM SGC_TT_DETALLE_FACTURA D,
                 SGC_TT_FACTURA F,
                 SGC_TP_USOS U,
                 SGC_TP_SERVICIOS S,
                 SGC_TP_ACTIVIDADES A,
                 SGC_TT_INMUEBLES I
                 WHERE I.CODIGO_INM = F.INMUEBLE + 0
                 AND F.CONSEC_FACTURA = D.FACTURA + 0
                 AND S.COD_SERVICIO = D.CONCEPTO + 0
                 AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD
                 AND A.ID_USO = U.ID_USO
                 AND I.ID_PROYECTO || '' = '$proyecto'
                 AND F.PERIODO + 0 >= $perini
                 AND F.PERIODO + 0 <= $perfin
                 AND F.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')
                 GROUP BY U.DESC_USO, D.CONCEPTO";*/
        //($perini,$zonini,$zonfin,$perfin,$proyecto/*,$codconcepto,$desuso*/){

        /*$sql = "SELECT
       '-' CODIGO_TARIFA,
       '-' DESC_TARIFA,
       U.DESC_USO,
       DF.CONCEPTO,
       SUM(DF.UNIDADES_ORI) CONSUMO,
       COUNT(F.INMUEBLE) CANTIDAD,
       SUM(DF.VALOR_ORI) FACTURADO,
       SUM(INM.UNIDADES_HAB) UNIDADES,
       SUM(DF.VALOR_PAGADO) RECAUDADO,
       SUM(DF.VALOR) - SUM(DF.VALOR_PAGADO) PENDIENTE
FROM SGC_TT_FACTURA F, SGC_TT_INMUEBLES INM, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES ACT, SGC_TP_USOS U--,SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TP_TARIFAS T
WHERE INM.CODIGO_INM = F.INMUEBLE
  AND   INM.ID_PROYECTO = '$proyecto'
  AND   F.CONSEC_FACTURA = DF.FACTURA
  AND   F.PERIODO BETWEEN $perini AND $perfin
    AND   F.ID_ZONA BETWEEN UPPER('$zonfin') AND UPPER('$zonfin')
    AND   INM.SEC_ACTIVIDAD = ACT.SEC_ACTIVIDAD
    AND   U.ID_USO = ACT.ID_USO
    GROUP BY U.DESC_USO,DF.CONCEPTO";*/
		//echo $sql;
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

	
	public function CantidadOtrosConceptos($perini,$zonini,$zonfin,$perfin,$proyecto){
        //ya optimizado
			$sql = "
                  SELECT D.CONCEPTO,
                       S.DESC_SERVICIO
                  FROM SGC_TT_INMUEBLES I,
                       SGC_TT_FACTURA F,
                       SGC_TT_DETALLE_FACTURA D,
                       SGC_TT_SERVICIOS_INMUEBLES SI,
                       SGC_TP_ACTIVIDADES A,
                       SGC_TP_SERVICIOS S,
                       SGC_TP_USOS U,
                       SGC_TP_TARIFAS T
                 WHERE F.INMUEBLE = I.CODIGO_INM
                   AND F.CONSEC_FACTURA = D.FACTURA
                   AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD
                   AND A.ID_USO = U.ID_USO
                   AND S.COD_SERVICIO = D.CONCEPTO
                   AND SI.CONSEC_TARIFA = T.CONSEC_TARIFA
                   AND SI.COD_INMUEBLE = F.INMUEBLE
                   AND T.COD_USO = U.ID_USO
                   AND SI.COD_INMUEBLE = I.CODIGO_INM
                   AND F.PERIODO BETWEEN $perini AND $perfin
                   AND F.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')
                   AND I.ID_PROYECTO = '$proyecto'
                   AND D.CONCEPTO NOT IN (1, 2, 3, 4, 11)
                 GROUP BY D.CONCEPTO, S.DESC_SERVICIO
                 ORDER BY D.CONCEPTO, S.DESC_SERVICIO";
		//echo $sql;
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
	
	
	public function CantidadOtrosUsos($perini,$zonini,$zonfin,$perfin,$otroconcepto,$proyecto){
//		ya optimizado
			$sql = "
              SELECT /*+ USE_NL(I,A) ORDERED */ U.DESC_USO
              FROM SGC_TT_DETALLE_FACTURA D,
                   SGC_TT_FACTURA F,
                   SGC_TP_USOS U,
                   SGC_TP_ACTIVIDADES A,
                   SGC_TT_INMUEBLES I
             WHERE I.CODIGO_INM = F.INMUEBLE + 0
               AND F.CONSEC_FACTURA = D.FACTURA + 0
               AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD
               AND A.ID_USO = U.ID_USO
               AND F.PERIODO + 0 >= $perini
               AND F.PERIODO + 0 <= $perfin
               AND F.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')
               AND D.CONCEPTO = '$otroconcepto'
               AND I.ID_PROYECTO || '' = '$proyecto'
             GROUP BY U.DESC_USO
             ORDER BY U.DESC_USO";
		//echo $sql;
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
	
	
	public function DetalleOtrosConceptos($perini,$zonini,$zonfin,$perfin,$proyecto,$otrouso,$otroconcepto){
//		ya optimizado
		  $sql = "
                SELECT /*+ ORDERED */ U.DESC_USO,
                       D.CONCEPTO,
                       SUM(D.UNIDADES_ORI) CONSUMO,
                       COUNT(F.INMUEBLE) CANTIDAD,
                       SUM(D.VALOR_ORI) FACTURADO,
                       SUM(I.UNIDADES_HAB) UNIDADES,
                       SUM(D.VALOR_PAGADO) RECAUDADO,
                       SUM(D.VALOR) - SUM(D.VALOR_PAGADO) PENDIENTE
                  FROM SGC_TT_DETALLE_FACTURA D,
                       SGC_TT_FACTURA F,
                       SGC_TP_USOS U,
                       SGC_TP_SERVICIOS S,
                       SGC_TP_ACTIVIDADES A,
                       SGC_TT_INMUEBLES I
                 WHERE I.CODIGO_INM = F.INMUEBLE + 0
                   AND F.CONSEC_FACTURA = D.FACTURA + 0
                   AND S.COD_SERVICIO = D.CONCEPTO + 0
                   AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD
                   AND A.ID_USO = U.ID_USO
                   AND I.ID_PROYECTO || '' = '$proyecto'
                   AND F.PERIODO + 0 >= $perini
                   AND F.PERIODO + 0 <= $perfin
                   AND F.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')
                   AND D.CONCEPTO = '$otroconcepto'
                   AND U.DESC_USO = '$otrouso'
                 GROUP BY U.DESC_USO, D.CONCEPTO
            ";
		//echo $sql;
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
