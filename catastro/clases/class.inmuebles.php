<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/20/2015
 * Time: 12:14 PM
 */
include_once 'class.conexion.php';

class inmuebles extends ConexionClass {
    public function __construct()
    {
        parent::__construct();
    }
////////////////// datos generales ///////////////////////

    public function datgen ($where,$sort,$start,$end,$from)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						SELECT I.CODIGO_INM, I.ID_ZONA, U.DESC_URBANIZACION, I.DIRECCION, I.ID_TIPO_CLIENTE, I.ID_ESTADO, I.CATASTRO, I.ID_PROCESO, C.ID_CONTRATO, C.CODIGO_CLI, 
						C.ALIAS, L.NOMBRE_CLI, L.DOCUMENTO, L.TELEFONO, M.SERIAL, M.COD_CALIBRE, M.COD_EMPLAZAMIENTO, TO_CHAR(M.FECHA_INSTALACION,'DD/MM/YYYY')FECHA_INSTALACION, 
						M.METODO_SUMINISTRO, A.ID_USO, I.ID_PROYECTO, TO_CHAR(I.FEC_ALTA,'DD/MM/YYYY')FEC_ALTA, D.DESC_CALIBRE
						FROM SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES L, SGC_TT_MEDIDOR_INMUEBLE M, SGC_TP_ESTADOS_INMUEBLES E, 
						SGC_TP_GRUPOS G, SGC_TP_PROYECTOS P, SGC_TP_TIPO_CLIENTE T, SGC_TP_ACTIVIDADES A, SGC_TP_CALIBRES D $from
						WHERE U.CONSEC_URB(+) = I.CONSEC_URB 
                        AND C.CODIGO_INM(+) = I.CODIGO_INM 
                        AND L.CODIGO_CLI(+) = C.CODIGO_CLI 
                        AND M.COD_INMUEBLE(+) = I.CODIGO_INM
                        AND E.ID_ESTADO(+) = I.ID_ESTADO 
                        AND P.ID_PROYECTO(+) = I.ID_PROYECTO 
                        AND T.ID_TIPO_CLIENTE(+) = I.ID_TIPO_CLIENTE
                        AND G.COD_GRUPO(+) = L.COD_GRUPO 
						AND A.SEC_ACTIVIDAD(+) = I.SEC_ACTIVIDAD
						AND D.COD_CALIBRE(+) = M.COD_CALIBRE
						AND C.FECHA_FIN(+) IS NULL
						$where 
						  $sort
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





////////////////////// cantidad datos/////////////////////////////////////////
 
public function countRec ($where,$from)
    {
        $sql="SELECT COUNT(*) CANTIDAD
			FROM SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES L, SGC_TT_MEDIDOR_INMUEBLE M, SGC_TP_ESTADOS_INMUEBLES E, 
			SGC_TP_GRUPOS G, SGC_TP_PROYECTOS P, SGC_TP_TIPO_CLIENTE T, SGC_TP_ACTIVIDADES A, SGC_TP_CALIBRES D $from
			WHERE U.CONSEC_URB(+) = I.CONSEC_URB 
            AND C.CODIGO_INM(+) = I.CODIGO_INM 
            AND L.CODIGO_CLI(+) = C.CODIGO_CLI 
            AND M.COD_INMUEBLE(+) = I.CODIGO_INM
            AND E.ID_ESTADO(+) = I.ID_ESTADO 
            AND P.ID_PROYECTO(+) = I.ID_PROYECTO 
            AND T.ID_TIPO_CLIENTE(+) = I.ID_TIPO_CLIENTE
            AND G.COD_GRUPO(+) = L.COD_GRUPO 
			AND A.SEC_ACTIVIDAD(+) = I.SEC_ACTIVIDAD
			AND D.COD_CALIBRE(+) = M.COD_CALIBRE
			AND C.FECHA_FIN(+) IS NULL
            $where";
        //echo '<br>'.$sql.'<br>';
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
	
public function datosInmueble ($where)
    {
        $sql="SELECT I.ID_ZONA, U.DESC_URBANIZACION, I.DIRECCION, TO_CHAR(I.FEC_ALTA,'DD/MM/YYYY')FEC_ALTA,  I.ID_ESTADO, I.CATASTRO, I.ID_PROCESO, C.CODIGO_CLI, C.ID_CONTRATO,
			C.ALIAS, L.DOCUMENTO, L.TELEFONO, M.SERIAL
			FROM SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES L, SGC_TT_MEDIDOR_INMUEBLE M
			WHERE U.CONSEC_URB(+) = I.CONSEC_URB 
			AND M.COD_INMUEBLE(+) = I.CODIGO_INM 
            AND C.CODIGO_INM(+) = I.CODIGO_INM 
            AND L.CODIGO_CLI(+) = C.CODIGO_CLI 
            $where";
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
	
//////////////////////datos Medidor/////////////////////////////////////////	
	
public function datosMedidor ($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						SELECT I.DESC_MED, E.DESC_EMPLAZAMIENTO, C.DESC_CALIBRE, M.SERIAL, TO_CHAR(M.FECHA_INSTALACION,'DD/MM/YYYY')FECHA, S.DESC_SUMINISTRO, ME.DESCRIPCION, 
						M.LECTURA_INSTALACION
						FROM SGC_TT_MEDIDOR_INMUEBLE M, SGC_TP_MEDIDORES I, SGC_TP_CALIBRES C, SGC_TP_EMPLAZAMIENTO E, SGC_TP_ESTADOS_MEDIDOR ME, SGC_TP_MET_SUMINISTRO S
						WHERE M.COD_MEDIDOR = I.CODIGO_MED
						AND C.COD_CALIBRE = M.COD_CALIBRE
						AND E.COD_EMPLAZAMIENTO = M.COD_EMPLAZAMIENTO
						AND ME.CODIGO = M.ESTADO_MEDIDOR
						AND S.COD_SUMINISTRO = M.METODO_SUMINISTRO
						$where
						  $sort
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
	
	//////////////////////datos Lectura/////////////////////////////////////////	
	
public function datosLectura ($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						SELECT R.PERIODO, R.LECTURA_ACTUAL, TO_CHAR(R.FECHA_LECTURA_ORI,'DD/MM/YYYY')FECLEC, R.OBSERVACION_ACTUAL, R.COD_LECTOR, R.CONSUMO
						FROM SGC_TT_REGISTRO_LECTURAS R
						$where
						  $sort
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
	
	//////////////////////datos Servicios/////////////////////////////////////////	
	
public function datosServicios ($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						SELECT S.COD_SERVICIO, O.DESC_SERVICIO, T.DESC_TARIFA, S.UNIDADES_TOT, S.UNIDADES_HAB, S.UNIDADES_DESH, S.CUPO_BASICO, S.PROMEDIO, S.CONSUMO_MINIMO, 
						C.DESC_CALCULO
						FROM SGC_TT_SERVICIOS_INMUEBLES S, SGC_TP_SERVICIOS O, SGC_TP_TARIFAS T, SGC_TP_CALCULO C
						WHERE S.COD_SERVICIO = O.COD_SERVICIO
						AND T.CONSEC_TARIFA = S.CONSEC_TARIFA
						AND S.CALCULO = C.COD_CALCULO 
						$where
						  $sort
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
	
	
	public function datosContratos ($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						SELECT ID_CONTRATO, TO_CHAR(FECHA_INICIO,'DD/MM/YYYY')FECHA_INICIO, TO_CHAR(FECHA_FIN,'DD/MM/YYYY')FECHA_FIN, CODIGO_CLI, ALIAS, 
						TO_CHAR(FECHA_SOLICITUD,'DD/MM/YYYY')FECHA_SOLICITUD
						FROM SGC_TT_CONTRATOS
						WHERE $where
						  $sort
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


    public function obtenerEstado()
    {
        $sql="SELECT EI.INDICADOR_ESTADO CODIGO, 
EI.INDICADOR_ESTADO DESCRIPCION
              FROM  SGC_TP_ESTADOS_INMUEBLES EI
              WHERE EI.ID_ESTADO='AC' OR EI.ID_ESTADO='CT'
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

    public function getEstadosInmueblesII($proyecto,$estado)
    {
        $sql="SELECT *
            from (SELECT  U.DESC_USO USO, S.ID_GERENCIA
            from SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, 
            SGC_TP_SECTORES S,
             SGC_TP_ESTADOS_INMUEBLES EI, 
             SGC_TP_USOS U
            WHERE I.ID_SECTOR=S.ID_SECTOR
            AND A.ID_USO=U.ID_USO
            AND I.SEC_ACTIVIDAD=A.SEC_ACTIVIDAD
            AND I.ID_ESTADO =  EI.ID_ESTADO
            AND EI.INDICADOR_ESTADO='$estado'
            AND I.ID_PROYECTO= '$proyecto'
) pivot(count(ID_GERENCIA) for ID_GERENCIA in
('E', 'N'))";


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

    public function getEstadosInmuebles($proyecto,$gerencia,$indicador_estado){

        $sql="SELECT count(I1.CODIGO_INM) NORTE ,U.DESC_USO
              FROM SGC_TT_INMUEBLES I1,
                  SGC_TP_USOS U,SGC_TP_ESTADOS_INMUEBLES EI1,
                  SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES S1
              WHERE I1.ID_SECTOR = S1.ID_SECTOR AND
                    I1.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD AND
                    U.ID_USO = A.ID_USO AND
                    i1.ID_ESTADO = ei1.ID_ESTADO and
                    I1.ID_PROYECTO = '$proyecto' AND
                    S1.ID_GERENCIA = '$gerencia' AND
                    EI1.INDICADOR_ESTADO = '$indicador_estado'
              GROUP BY  U.DESC_USO";

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

    public function getUnidadesPorUso($proyecto,$indicador_estado){
        $sql="SELECT  U.DESC_USO USO, SUM(SI.UNIDADES_TOT) UNIDADES
              from SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES S, SGC_TP_ESTADOS_INMUEBLES EI, SGC_TT_SERVICIOS_INMUEBLES SI,   SGC_TP_USOS U
              WHERE I.ID_SECTOR=S.ID_SECTOR
                AND I.SEC_ACTIVIDAD=A.SEC_ACTIVIDAD
                AND I.ID_ESTADO =  EI.ID_ESTADO
                AND I.CODIGO_INM= SI. COD_INMUEBLE
                AND A.ID_USO=U.ID_USO
                AND I.ID_PROYECTO= '$proyecto'
                AND EI.INDICADOR_ESTADO='$indicador_estado'
              GROUP BY  U.DESC_USO
              ORDER BY  U.DESC_USO";

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





}

