<?php
include_once "class.conexion.php";


class Factura extends ConexionClass{


    public function __construct()
    {
        parent::__construct();
    }

    public function getFacPendByInm($inm){
        $sql = "SELECT
                  COUNT(*) CANTIDAD
                FROM
                  SGC_TT_FACTURA F
                WHERE
                  F.INMUEBLE=$inm AND
                  F.FACTURA_PAGADA='N' AND
                  F.FEC_EXPEDICION IS NOT NULL AND
                  F.FECHA_PAGO IS NULL AND
                  F.TOTAL>0 AND
                  F.TOTAL_PAGADO<F.TOTAL  ";
        //echo $sql;
        $resultado = oci_parse($this->_db,$sql);
        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {
            if(oci_fetch($resultado)){
                $cant=oci_result($resultado,"CANTIDAD");
                return $cant;
            }else{
                return false;
            }
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


    public function datosFactura($factura){
        $sql = "SELECT
        TD.DESCRIPCION_TIPO_DOC TIPODOC, TO_CHAR(f.VENCIMIENTO_NCF,'DD/MM/YYYY') VENCE_NCF,i.ID_TIPO_CLIENTE, I.CODIGO_INM, 
        F.DOCUMENTO_CLIENTE DOCUMENTO,  CONCAT(N.ID_NCF,F.NCF_CONSEC)NCF, I.CATASTRO, F.NOMBRE_CLIENTE ALIAS , I.DIRECCION, U.DESC_URBANIZACION,
        I.ID_ZONA, TO_CHAR(F.FEC_EXPEDICION,'DD/MM/YYYY')FECEXP, F.PERIODO, I.ID_PROCESO, 
        (SELECT G.DESC_GERENCIA FROM SGC_TT_GERENCIA_ZONA Z, SGC_TP_GERENCIAS G
        WHERE G.ID_GERENCIA = Z.ID_GERENCIA AND Z.ID_ZONA = I.ID_ZONA) GERENCIA, E.DESC_MED, CA.DESC_CALIBRE, M.SERIAL, 
        TO_CHAR(F.FEC_VCTO,'DD/MM/YYYY')FEC_VCTO, TO_CHAR(FECHA_CORTE,'DD/MM/YYYY')FECCORTE, I.ID_PROYECTO, F.MSJ_NCF, F.MSJ_PERIODO, 
        F.MSJ_FACTURA, F.MSJ_ALERTA, F.MSJ_BURO
        FROM SGC_TT_FACTURA F, SGC_TT_INMUEBLES I, SGC_TP_NCF_USOS N,  SGC_TP_URBANIZACIONES U, SGC_TT_MEDIDOR_INMUEBLE M, SGC_TP_MEDIDORES E,
         SGC_TP_CALIBRES CA,SGC_TP_TIPODOC TD
        WHERE I.CODIGO_INM(+) = F.INMUEBLE
        AND CA.COD_CALIBRE(+) = M.COD_CALIBRE
        AND M.COD_INMUEBLE(+) = I.CODIGO_INM
        AND F.NCF_ID = N.ID_NCF_USO
        AND U.CONSEC_URB(+) = I.CONSEC_URB
        AND M.COD_MEDIDOR = E.CODIGO_MED(+)
        AND TD.ID_TIPO_DOC = F.TIPO_DOCUMENTO
        AND M.FECHA_BAJA(+) IS NULL
        AND F.CONSEC_FACTURA = '$factura'";
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


    public function facturaDigital($periodo, $zona){
        $sql = "SELECT F.CONSEC_FACTURA, C.EMAIL
		FROM SGC_TT_FACTURA F, SGC_TT_CONTRATOS C 
		WHERE F.INMUEBLE = C.CODIGO_INM
		AND C.FECHA_FIN (+) IS NULL
		AND F.PERIODO = $periodo
		AND F.ID_ZONA = '$zona'
		AND C.EMAIL IS NOT NULL
		ORDER BY F.CONSEC_FACTURA";

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

    public function zonasCerradas($hoy){
        $sql = "SELECT P.PERIODO, P.ID_ZONA
		FROM SGC_TP_PERIODO_ZONA P
		WHERE P.FEC_CIERRE BETWEEN TO_DATE('$hoy 00:00:00','DD/MM/YYYY HH24:MI:SS')
		AND TO_DATE('$hoy 23:59:59','DD/MM/YYYY HH24:MI:SS')";

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

    ////////////////////// VALOR RANGOS/////////////////////////////////////////
    public function valorRangosPdf ($codservicio,$rango,$codinm)
    {
        $sql="SELECT R.VALOR_METRO
        FROM SGC_TT_SERVICIOS_INMUEBLES S, SGC_TP_SERVICIOS C, SGC_TP_TARIFAS T, SGC_TP_USOS U, SGC_TP_RANGOS_TARIFAS R
        WHERE C.COD_SERVICIO  = S.COD_SERVICIO 
        AND S.CONSEC_TARIFA = T.CONSEC_TARIFA
        AND R.CONSEC_TARIFA = S.CONSEC_TARIFA
        AND T.COD_USO = U.ID_USO
        AND S.COD_INMUEBLE = '$codinm'
        AND C.COD_SERVICIO = $codservicio
        AND R.RANGO IN ($rango)
        AND S.ACTIVO = 'S'";
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

        /////////////VALOR DEUDA DE UN INMUEBLE////////
        public function valorDeuda ($codinm)
        {
            $sql="SELECT SUM(F.TOTAL - F.TOTAL_PAGADO + F.TOTAL_DEBITO - F.TOTAL_CREDITO) DEUDA
                    FROM SGC_TT_FACTURA F
                    WHERE F.FACTURA_PAGADA = 'N'
                    AND F.INMUEBLE = '$codinm'";
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
    
        /////////////CATEGORIA DE UN INMUEBLE////////
        public function categoriaInmueble ($codinm)
        {
            $sql="SELECT T.CATEGORIA
                    FROM SGC_TP_TARIFAS T, SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TT_INMUEBLES I
                    WHERE I.CODIGO_INM = SI.COD_INMUEBLE
                    AND SI.CONSEC_TARIFA = T.CONSEC_TARIFA
                    AND SI.COD_INMUEBLE = '$codinm'
                    AND SI.COD_SERVICIO IN (1,3)";
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

    public function getNCFbyRaizProPerZon($raiz,$periodo,$proyecto,$zona){

        $raiz = addslashes($raiz);
        $periodo      = addslashes($periodo);
        $proyecto      = addslashes($proyecto);
        $zona      = addslashes($zona);

         $sql = "SELECT
                  F.INMUEBLE,
                  F.TOTAL,
                  N.ID_NCF,
                  TD.DESCRIPCION_TIPO_DOC,
                  CL.DOCUMENTO,
                  NC.CONSECUTIVO,
                  F.ID_ZONA

                  FROM 
                    SGC_TT_FACTURA F, SGC_TP_NCF_USOS N, SGC_TT_CONTRATOS C,
                    SGC_TT_CLIENTES CL, SGC_TP_TIPODOC TD, SGC_TT_INMUEBLES I, SGC_TT_CONSE_NCF NC
                  where
                    f.NCF_ID=n.ID_NCF_USO and
                    I.CODIGO_INM=F.INMUEBLE AND
                    c.CODIGO_INM(+)=f.INMUEBLE AND
                    NC.PROYECTO=I.ID_PROYECTO AND 
                    NC.ID_NCF='$raiz' AND
                    C.FECHA_FIN (+)IS NULL AND
                    C.CODIGO_CLI=CL.CODIGO_CLI(+)  AND
                    N.ID_NCF='$raiz' AND
                    F.PERIODO=$periodo AND
                    I.ID_PROYECTO='$proyecto' AND
                    CL.TIPO_DOC=TD.ID_TIPO_DOC AND
                    F.ID_ZONA ='$zona'
                ORDER BY F.INMUEBLE ASC ";
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


    public function getDetFacrByFact ($factura)
    {
        $sql="SELECT S.DESC_SERVICIO CONCEPTO,RANGO,UNIDADES,VALOR,S.COD_SERVICIO
		FROM SGC_TT_DETALLE_FACTURA DF , SGC_TP_SERVICIOS S 
		WHERE  DF.CONCEPTO=S.COD_SERVICIO
		AND FACTURA='$factura'
		ORDER BY CONCEPTO, RANGO ASC";
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



    public function datosEnvioFacDig($factura, $periodo, $email, $enviado, $error, $acueducto){
        $sql="BEGIN SGC_P_INGRESA_FAC_DIGITAL('$factura','$periodo','$email','$enviado','$error','$acueducto',:PMSGRESULT,:PCODRESULT);
		COMMIT;END;";
        //echo $sql;
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->msgresult,1000);
        oci_bind_by_name($resultado,":PCODRESULT",$this->codresult);
        //oci_bind_by_name($resultado,":PROY",$VACIO);
        $bandera=oci_execute($resultado);

        if($bandera==TRUE){
            if( $this->codresult >0){
                return false;
            }else{
                return true;
            }
        }
        else{
            return false;
        }
    }



    public function verificarel ($factura)
    {
        $sql="select count (1) CANTIDAD from sgc_tt_factura_rel f where F.FACTURA=$factura ";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db,$sql
        );

        $resultado = oci_parse($this->_db,$sql);
        $banderas=oci_execute($resultado);
        if($banderas)
        {
            oci_fetch($resultado);
            $cantidad = oci_result($resultado, 'CANTIDAD');
            if($cantidad==0){
                return false;
            }else {
                return true;
            }
        }
        else
        {


            return false;
        }

    }

    public function getDetFacByFacFlexy ($where,$sort,$start,$end,$factura)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select S.DESC_SERVICIO CONCEPTO,RANGO,UNIDADES,VALOR,S.COD_SERVICIO
						from SGC_TT_DETALLE_FACTURA DF , SGC_TP_SERVICIOS S
						WHERE  DF.CONCEPTO=S.COD_SERVICIO  AND FACTURA='$factura'
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


    public function getValRangServByServRangInm ($codservicio,$rango,$codinm)
    {
        $sql="SELECT R.VALOR_METRO
        FROM SGC_TT_SERVICIOS_INMUEBLES S, SGC_TP_SERVICIOS C, SGC_TP_TARIFAS T, SGC_TP_USOS U, SGC_TP_RANGOS_TARIFAS R
        WHERE C.COD_SERVICIO  = S.COD_SERVICIO
        AND S.CONSEC_TARIFA = T.CONSEC_TARIFA
        AND R.CONSEC_TARIFA = S.CONSEC_TARIFA
        AND T.COD_USO = U.ID_USO
        AND S.COD_INMUEBLE = '$codinm'
        AND C.COD_SERVICIO = $codservicio
        AND R.RANGO IN ($rango)
        AND S.ACTIVO = 'S'";
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


    public function getValRangServByServRangInm2 ($codservicio,$rango,$codinm)
    {
        $sql="SELECT R.VALOR_METRO
        FROM SGC_TT_SERVICIOS_INMUEBLES S, SGC_TP_SERVICIOS C, SGC_TP_TARIFAS T, SGC_TP_USOS U, SGC_TP_RANGOS_TARIFAS R
        WHERE C.COD_SERVICIO  = S.COD_SERVICIO
        AND S.CONSEC_TARIFA = T.CONSEC_TARIFA
        AND R.CONSEC_TARIFA = S.CONSEC_TARIFA
        AND T.COD_USO = U.ID_USO
        AND S.COD_INMUEBLE = '$codinm'
        AND C.COD_SERVICIO = $codservicio
        AND R.RANGO IN ($rango)
        AND S.ACTIVO = 'S'";
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

    public function getDatEntreFactByFactFlexy($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select * from (
                          select EF.ENTREGADO,EF.FECHA_EJECUCION, nvl(OFA.DESCRIPCION,'Entregada') OBS_NOENTREGA,US.LOGIN  from sgc_tt_registro_entrega_fac ef,
                            sgc_tt_usuarios us,
                            SGC_TT_FACTURA f,
                            sgc_tp_observaciones_fac ofa where
                             F.INMUEBLE=EF.COD_INMUEBLE
                            and F.PERIODO=EF.PERIODO
                            and OFA.CODIGO(+)=EF.OBS_NOENTREGA
                            and US.ID_USUARIO=EF.USR_EJE
                            $where
                            $sort
                   )where  rownum<13
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

    public function getFacByInmAsc ($codinm)
    {
        $sql="SELECT TO_CHAR(F.FEC_EXPEDICION,'DD/MM/YYYY') FEC_EXPEDICION , to_char(F.FEC_EXPEDICION,'DD-MM-YYYY') FECHCOMP2 , to_char(F.FEC_EXPEDICION,'YYYYMMDD') FECHCOMP, 'fac '||F.CONSEC_FACTURA||' '||to_char(to_date(F.PERIODO,'YYYYMM'),'mon/yyyy') DESCRIPCION,F.TOTAL FROM SGC_TT_FACTURA F
              WHERE F.INMUEBLE='$codinm'
              and  F.FEC_EXPEDICION is not null
			  --AND F.FEC_EXPEDICION > TO_DATE('31/12/2010','DD/MM/YYYY')
              ORDER BY F.FEC_EXPEDICION ASC";
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

    public function getListFactPendByInm($inm){
        $sql="
        select * from(
        SELECT F.PERIODO,F.TOTAL-F.TOTAL_PAGADO DEUDA FROM SGC_TT_FACTURA F
        WHERE F.INMUEBLE=$inm
        AND F.FACTURA_PAGADA='N'
        AND F.TOTAL-F.TOTAL_PAGADO>0
        AND F.FEC_EXPEDICION IS NOT NULL
        ORDER BY F.PERIODO DESC) where rownum<20
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

    public function GetFactPendByProyPerGruZonDoc($proyecto,$periodo_inicial,$periodo_final,$grupo,$codigo_sistema,$zona,$documento){
        $proyecto           =   addslashes($proyecto);
        $periodo_inicial    =   addslashes($periodo_inicial);
        $periodo_final      =   addslashes($periodo_final);
        $grupo              =   addslashes($grupo);
        $codigo_sistema     =   addslashes($codigo_sistema);
        $zona               =   addslashes($zona);
        $documento          =   addslashes($documento);
        $where              =   '';

        if(trim($grupo)<>''){
            $where.=" AND CON.GRUPO='$grupo' ";
        }

        if(trim($codigo_sistema)<>''){
            $where.=" AND CON.CODIGO_INM='$codigo_sistema' ";
        }

        if(trim($zona)<>''){
            $where.=" AND FAC.ID_ZONA='$zona'";
        }

        if(trim($documento)<>''){
            $where.=" AND FAC.DOCUMENTO_CLIENTE='$documento'";
        }

        if(trim($periodo_final)==""){
            $periodo_final= $periodo_inicial;
        }
        $sql="
            SELECT
              INM.ID_PROCESO PROCESO,
              INM.CODIGO_INM,
              FAC.NOMBRE_CLIENTE NOMBRE,
              FAC.FEC_EXPEDICION,
              INM.DIRECCION,
              NU.ID_NCF||FAC.NCF_CONSEC NCF,
              CONSEC_FACTURA FACTURA,
              FAC.TOTAL    
            FROM 
              SGC_TT_INMUEBLES INM, 
              SGC_TT_FACTURA FAC,
              SGC_TP_NCF_USOS NU, 
              SGC_TT_CONTRATOS CON   
            WHERE
              fac.INMUEBLE=INM.CODIGO_INM AND
              NU.ID_NCF_USO=FAC.NCF_ID AND
              CON.CODIGO_INM(+)=INM.CODIGO_INM AND 
              CON.FECHA_FIN (+) IS NULL AND 
              FAC.FEC_EXPEDICION IS NOT NULL AND
              FAC.FACTURA_PAGADA='N' AND 
              INM.ID_PROYECTO='$proyecto' AND
              FAC.PERIODO BETWEEN '$periodo_inicial' AND '$periodo_final' 
              $where 
		      ORDER BY  INM.ID_PROCESO, CONSEC_FACTURA";
       // echo $sql;
        $resultado = oci_parse($this->_db,$sql);

        $banderas=oci_execute($resultado);
        if($banderas)
        {
            oci_close($this->_db);
            return $resultado;
        }
        else
        {
            oci_close($this->_db);
            return false;
        }

    }

    //FACTURAS PAGADAS

    public function GetFactPagadasByProyPerGruZonDoc($proyecto,$periodo_inicial,$periodo_final,$grupo,$codigo_sistema,$zona,$documento){
        $proyecto           =   addslashes($proyecto);
        $periodo_inicial    =   addslashes($periodo_inicial);
        $periodo_final      =   addslashes($periodo_final);
        $grupo              =   addslashes($grupo);
        $codigo_sistema     =   addslashes($codigo_sistema);
        $zona               =   addslashes($zona);
        $documento          =   addslashes($documento);
        $where              =   '';

        if(trim($grupo)<>''){
            $where.=" AND CON.GRUPO='$grupo' ";
        }

        if(trim($codigo_sistema)<>''){
            $where.=" AND CON.CODIGO_INM='$codigo_sistema' ";
        }

        if(trim($zona)<>''){
            $where.=" AND FAC.ID_ZONA='$zona'";
        }

        if(trim($documento)<>''){
            $where.=" AND FAC.DOCUMENTO_CLIENTE='$documento'";
        }

        if(trim($periodo_final)==""){
            $periodo_final= $periodo_inicial;
        }
        $sql="
            SELECT
              INM.ID_PROCESO PROCESO,
              INM.CODIGO_INM,
              FAC.NOMBRE_CLIENTE NOMBRE,
              FAC.FEC_EXPEDICION,
              INM.DIRECCION,
              NU.ID_NCF||FAC.NCF_CONSEC NCF,
              CONSEC_FACTURA FACTURA,
              FAC.TOTAL,
              FAC.FECHA_PAGO     
            FROM 
              SGC_TT_INMUEBLES INM, 
              SGC_TT_FACTURA FAC,
              SGC_TP_NCF_USOS NU, 
              SGC_TT_CONTRATOS CON   
            WHERE
              fac.INMUEBLE=INM.CODIGO_INM AND
              NU.ID_NCF_USO=FAC.NCF_ID AND
              CON.CODIGO_INM(+)=INM.CODIGO_INM AND 
              CON.FECHA_FIN (+) IS NULL AND 
              FAC.FEC_EXPEDICION IS NOT NULL AND
              FAC.FACTURA_PAGADA='S' AND 
              INM.ID_PROYECTO='$proyecto' AND
              FAC.PERIODO BETWEEN '$periodo_inicial' AND '$periodo_final' 
              $where 
		      ORDER BY  INM.ID_PROCESO, CONSEC_FACTURA";
        // echo $sql;
        $resultado = oci_parse($this->_db,$sql);

        $banderas=oci_execute($resultado);
        if($banderas)
        {
            oci_close($this->_db);
            return $resultado;
        }
        else
        {
            oci_close($this->_db);
            return false;
        }

    }



    public function getDeudConcByInm($inm){
        $sql="
        SELECT S.DESC_SERVICIO CONCEPTO, SUM(NVL(DF.VALOR-DF.VALOR_PAGADO,0)) VALOR FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_SERVICIOS S
        where
        F.FACTURA_PAGADA='N'
        AND F.PERIODO=DF.PERIODO
        AND F.INMUEBLE=DF.COD_INMUEBLE
        AND F.FEC_EXPEDICION IS NOT NULL
        AND S.COD_SERVICIO= DF.CONCEPTO
        and DF.COD_INMUEBLE=$inm
        GROUP BY S.DESC_SERVICIO
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


    public function getFacAplSalFavByPagFlexy ($id_pago, $sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						SELECT F.INMUEBLE, F.CONSEC_FACTURA, F.PERIODO, P.FECHA_PAGO, F.TOTAL,  M.IMPORTE, M.COMPROBANTE
						FROM SGC_TT_PAGOS P, SGC_TT_FACTURA F, SGC_TT_PAGO_FACTURAS M
						WHERE F.INMUEBLE = P.INM_CODIGO AND P.ID_PAGO = M.ID_PAGO AND M.FACTURA = F.CONSEC_FACTURA
						AND P.ID_PAGO = '$id_pago'
						  $sort
						)a WHERE  ROWNUM<=$start
					) WHERE rnum >= $end+1";
        // echo $sql;
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

    public function VerificarNota($factura)
    {
         $sql = "SELECT count(1) CANTIDAD
                FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F,  SGC_TT_NOTAS_FACTURAS NF, SGC_TP_NCF_USOS NCFU,
                SGC_TP_NCF_USOS NCFU2
                WHERE
                I.CODIGO_INM=F.INMUEBLE AND
                F.CONSEC_FACTURA=NF.FACTURA_APLICA AND
                NF.FACTURA_APLICA='$factura' AND
                NCFU.ID_NCF_USO = NF.ID_NCF AND
                NF.FECHA_ANULACION IS NULL AND
                NF.USR_ANULACION   IS NULL AND
                NCFU2.ID_NCF_USO = F.NCF_ID";


        $resultado = oci_parse($this->_db,$sql);
        $banderas=oci_execute($resultado);
        if($banderas)
        {
            oci_fetch($resultado);
            $cantidad = oci_result($resultado, 'CANTIDAD');
            if($cantidad==0){
                return false;
            }else {
                return true;
            }
        }
        else
        {


            return false;
        }


    }

    public function obtenerDeudaAcumuldaInm($codImm,$proyecto){

         $sql = "SELECT F.CONSEC_FACTURA, F.PERIODO, F.TOTAL,F.TOTAL_CREDITO,F.TOTAL_DEBITO, F.TOTAL_PAGADO
                FROM SGC_TT_FACTURA F, SGC_TP_ZONAS Z
                WHERE  F.ID_ZONA=Z.ID_ZONA
                AND F.INMUEBLE='$codImm'
                AND Z.ID_PROYECTO='$proyecto'
                AND F.FACTURA_PAGADA='N'
                ORDER BY F.PERIODO DESC";

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

    function getReporteFacturadosAdeudados($proyecto,$sector,$ruta='',$periodo)
    {
        $sql="";
       if($ruta!=''){
           $sql="SELECT
  NVL((SELECT SUM(F.TOTAL_ORI)
       FROM SGC_TT_FACTURA F
       WHERE F.PERIODO=$periodo AND
       F.ID_SECTOR=I.ID_SECTOR AND
      F.ID_RUTA = I.ID_RUTA AND
       F.FEC_EXPEDICION IS NOT NULL
      ),0) FACTURADO,
  (SELECT COUNT(F.TOTAL_ORI)
   FROM SGC_TT_FACTURA F
   WHERE F.PERIODO=$periodo AND
   F.ID_SECTOR=I.ID_SECTOR AND
   F.ID_RUTA = I.ID_RUTA
  ) USUARIOS_FACTURADOS,
  NVL((SELECT SUM(F.TOTAL-F.TOTAL_PAGADO+F.TOTAL_DEBITO-F.TOTAL_CREDITO)
       FROM SGC_TT_FACTURA F
       WHERE F.PERIODO<=$periodo AND
       F.ID_SECTOR=I.ID_SECTOR AND
             F.ID_RUTA = I.ID_RUTA AND
       F.FACTURA_PAGADA='N' AND
       F.FECHA_PAGO IS NULL AND
       F.FEC_EXPEDICION IS NOT NULL
      ),0) ADEUDADO,
  NVL((
        SELECT SUM(P.IMPORTE) AS IMPORTE_R
        FROM  SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO CP, SGC_TT_INMUEBLES I2
        WHERE
          I2.CODIGO_INM=P.INM_CODIGO AND
          I2.ID_PROYECTO='$proyecto' AND
          CP.ID_CAJA= P.ID_CAJA AND
          CP.VALIDA_REPORTES = 'S' AND
          TO_CHAR(P.FECHA_PAGO,'YYYYMM')=$periodo AND
        P.ESTADO <>'I' AND
        I2.ID_SECTOR=I.ID_SECTOR AND
        I2.ID_RUTA = I.ID_RUTA )  +
      NVL((SELECT SUM(P.IMPORTE) AS IMPORTE_R
       FROM  SGC_TT_OTROS_RECAUDOS P, SGC_TP_CAJAS_PAGO CP, SGC_TT_INMUEBLES I2
       WHERE
         I2.CODIGO_INM=P.INMUEBLE AND
         I2.ID_PROYECTO='$proyecto' AND
         CP.ID_CAJA= P.CAJA AND
         CP.VALIDA_REPORTES = 'S' AND
         TO_CHAR(P.FECHA_PAGO,'YYYYMM')=$periodo AND
                                         P.ESTADO <>'I' AND
                                         I2.ID_SECTOR=I.ID_SECTOR AND
                                        I2.ID_RUTA = I.ID_RUTA
      ),0),0) RECAUDOS,
  I.ID_SECTOR,I.ID_RUTA
from SGC_TT_INMUEBLES i where i.ID_PROYECTO='$proyecto'
                              AND I.ID_SECTOR=$sector AND I.ID_RUTA IN($ruta)
GROUP BY I.ID_SECTOR,I.ID_RUTA";

       }else{
           $sql="SELECT
  NVL((SELECT SUM(F.TOTAL_ORI)
       FROM SGC_TT_FACTURA F
       WHERE F.PERIODO=$periodo AND
       F.ID_SECTOR=I.ID_SECTOR AND
       F.FEC_EXPEDICION IS NOT NULL
      ),0) FACTURADO,
  (SELECT COUNT(F.TOTAL_ORI)
   FROM SGC_TT_FACTURA F
   WHERE F.PERIODO=$periodo AND
   F.ID_SECTOR=I.ID_SECTOR
  ) USUARIOS_FACTURADOS,
  NVL((SELECT SUM(F.TOTAL-F.TOTAL_PAGADO+F.TOTAL_DEBITO-F.TOTAL_CREDITO)
       FROM SGC_TT_FACTURA F
       WHERE F.PERIODO<=$periodo AND
       F.ID_SECTOR=I.ID_SECTOR AND
       F.FACTURA_PAGADA='N' AND
       F.FECHA_PAGO IS NULL AND
       F.FEC_EXPEDICION IS NOT NULL
      ),0) ADEUDADO,
  NVL((
        SELECT SUM(P.IMPORTE) AS IMPORTE_R
        FROM  SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO CP, SGC_TT_INMUEBLES I2
        WHERE
          I2.CODIGO_INM=P.INM_CODIGO AND
          I2.ID_PROYECTO='$proyecto' AND
          CP.ID_CAJA= P.ID_CAJA AND
          CP.VALIDA_REPORTES = 'S' AND
          TO_CHAR(P.FECHA_PAGO,'YYYYMM')=$periodo AND
        P.ESTADO <>'I' AND
        I2.ID_SECTOR=I.ID_SECTOR),0 )  +
      NVL((SELECT SUM(P.IMPORTE) AS IMPORTE_R
           FROM  SGC_TT_OTROS_RECAUDOS P, SGC_TP_CAJAS_PAGO CP, SGC_TT_INMUEBLES I2
           WHERE
             I2.CODIGO_INM=P.INMUEBLE AND
             I2.ID_PROYECTO='$proyecto' AND
             CP.ID_CAJA= P.CAJA AND
             CP.VALIDA_REPORTES = 'S' AND
             TO_CHAR(P.FECHA_PAGO,'YYYYMM')=$periodo AND
                                             P.ESTADO <>'I' AND
                                             I2.ID_SECTOR=I.ID_SECTOR
          ),0) RECAUDOS,
  I.ID_SECTOR
from SGC_TT_INMUEBLES i where i.ID_PROYECTO='$proyecto'
                              AND I.ID_SECTOR=$sector
GROUP BY I.ID_SECTOR";
    }
           /*echo $sql;*/
        $resultado = oci_parse($this->_db,$sql);

        $banderas=oci_execute($resultado);
        if($banderas==TRUE) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }

    public function getReporteFacturasSectorUso($periodo,$sector,$proyecto,$uso=''){

        if($uso!=""){
            $uso = "AND
            U.ID_USO = '$uso'";
        }

        $sql="SELECT NVL(SUM((SELECT F.TOTAL_ORI
                  FROM SGC_TT_FACTURA F
                    WHERE F.INMUEBLE = I.CODIGO_INM AND
                          F.FEC_EXPEDICION IS NOT NULL AND
                          F.ID_SECTOR = I.ID_SECTOR AND
                          F.PERIODO = $periodo
                  )),0) FACTURADO,
                  COUNT((SELECT F.TOTAL_ORI
                      FROM SGC_TT_FACTURA F
                      WHERE F.INMUEBLE = I.CODIGO_INM AND
                            F.FEC_EXPEDICION IS NOT NULL AND
                            F.ID_SECTOR = I.ID_SECTOR AND
                            F.PERIODO = $periodo
                  )) POLIZAS_AFECTADAS,U.ID_USO,U.DESC_USO
                  FROM SGC_TT_INMUEBLES I,SGC_TP_USOS U, SGC_TP_ACTIVIDADES AC
                  WHERE I.ID_SECTOR=$sector AND
                        I.ID_PROYECTO='$proyecto' AND
                        I.SEC_ACTIVIDAD = AC.SEC_ACTIVIDAD AND
                        AC.ID_USO = U.ID_USO ".$uso."
                  GROUP BY U.ID_USO,U.DESC_USO";

            /*echo $sql;*/
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

    public function getReportePagosSectorUso($periodo,$sector,$uso){

        $sql="SELECT TO_CHAR(P.FECHA_PAGO,'YYYYMM') PERIODO, round(SUM(P.IMPORTE),0) IMPORTE,count(1) POLIZAS_AFECTADAS,U.ID_USO
              FROM  SGC_TT_PAGOS P , SGC_TP_CAJAS_PAGO CP, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES AC,SGC_TP_USOS U
              WHERE CP.ID_CAJA=P.ID_CAJA AND
                    CP.VALIDA_REPORTES='S' AND
                    P.ESTADO<>'I' and
                    TO_CHAR(p.FECHA_PAGO,'YYYYMM')=$periodo and
                    i.CODIGO_INM=p.INM_CODIGO and
                    i.ID_SECTOR in ($sector) AND
                    I.SEC_ACTIVIDAD = AC.SEC_ACTIVIDAD AND
                    AC.ID_USO = U.ID_USO AND 
                    U.ID_USO = '$uso'
              GROUP BY TO_CHAR(P.FECHA_PAGO,'YYYYMM'),U.ID_USO";

        /*echo $sql;*/
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
    public function getReporteRecaudoSectorUso($periodo,$sector,$uso){

        $sql="SELECT  TO_CHAR(P.FECHA_PAGO,'YYYYMM') ,round(SUM(P.IMPORTE),0) IMPORTE,count(1) POLIZAS_AFECTADAS,U.ID_USO 
              FROM SGC_TT_OTROS_RECAUDOS P , SGC_TP_CAJAS_PAGO CP, SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES AC,SGC_TP_USOS U
              WHERE CP.ID_CAJA=P.CAJA AND
                    CP.VALIDA_REPORTES='S' AND
                    P.ESTADO<>'I' and
                    TO_CHAR(p.FECHA_PAGO,'YYYYMM')=$periodo  and
                    i.CODIGO_INM=p.INMUEBLE and
                    i.ID_SECTOR in ($sector) AND
                    I.SEC_ACTIVIDAD = AC.SEC_ACTIVIDAD AND
                    AC.ID_USO = U.ID_USO AND
                    U.ID_USO = '$uso'
              GROUP BY TO_CHAR(P.FECHA_PAGO,'YYYYMM'),U.ID_USO";
        /*echo $sql;*/
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

    public function getReporteFechaVencimientoPorZona($periodo, $proyecto, $zona=""){

        $where ="";
        if($zona !=''){
            $where = " AND f.ID_ZONA IN ('$zona') ";
        }
        $sql="SELECT F.ID_ZONA, F.ID_SECTOR,TO_CHAR(F.FEC_VCTO,'DD/MM/YYYY') FECHA_VENCIMIENTO,TO_CHAR(F.FECHA_CORTE,'DD/MM/YYYY') FECHA_CORTE
              FROM SGC_TT_FACTURA F, SGC_TP_PERIODO_ZONA PZ
              WHERE PZ.ID_ZONA = F.ID_ZONA
              AND   PZ.PERIODO = '$periodo'
              AND   TO_CHAR(F.FEC_EXPEDICION,'YYYYMM') = '$periodo'
              AND   PZ.FEC_CIERRE IS NOT NULL
              AND   PZ.CODIGO_PROYECTO = '$proyecto' "
            .$where.
            " group by F.ID_ZONA,F.ID_SECTOR,TO_CHAR(F.FEC_VCTO,'DD/MM/YYYY'),TO_CHAR(F.FECHA_CORTE,'DD/MM/YYYY')";

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

    public function getDatCalendarioFacturacionCAASD(){
        $periodo = date('Ym');

        $sql="SELECT C.ID_ZONA, TO_CHAR(C.FEC_APERTURA,'DD/MM/YYYY')FEC_APERTURA, TO_CHAR(P.FEC_APERTURA,'DD/MM/YYYY') FEC_APE_REAL,
        TO_CHAR(C.FEC_FIN_LEC,'DD/MM/YYYY')FEC_MAX_LEC, 
        (SELECT MAX(TO_CHAR(R.FECHA_LECTURA_ORI,'DD/MM/YYYY')) FROM SGC_TT_REGISTRO_LECTURAS R WHERE R.ID_ZONA = C.ID_ZONA AND R.PERIODO = $periodo) FEC_LEC_REAL,
        TO_CHAR(C.FEC_DIF_MORA,'DD/MM/YYYY')FEC_MORA, TO_CHAR(P.FEC_DIFE,'DD/MM/YYYY')FEC_MORA_REAL,
        TO_CHAR(C.FEC_DIF_MORA,'DD/MM/YYYY')FEC_CIERRE, TO_CHAR(P.FEC_CIERRE,'DD/MM/YYYY')FEC_CIERRE_REAL,
        TO_CHAR(C.FEC_MAX_DISTR,'DD/MM/YYYY')FEC_DISTR, 
        (SELECT MAX(TO_CHAR(E.FECHA_EJECUCION,'DD/MM/YYYY')) FROM SGC_TT_REGISTRO_ENTREGA_FAC E WHERE E.ID_ZONA = C.ID_ZONA AND E.PERIODO = $periodo) FEC_DISTR_REAL,
        TO_CHAR(C.FEC_VENC_FACT,'DD/MM/YYYY')FEC_VCTO, 
        (SELECT MAX(TO_CHAR(F.FEC_VCTO,'DD/MM/YYYY')) FROM SGC_TT_FACTURA F WHERE F.ID_ZONA = C.ID_ZONA AND F.PERIODO = $periodo)FEC_VCTO_REAL,
        TO_CHAR(C.FEC_CORTE_SER,'DD/MM/YYYY')FEC_CORTE, TO_CHAR(P.FEC_LOTCORTE,'DD/MM/YYYY')FEC_CORTE_REAL
        FROM SGC_TP_CAL_FACT C, SGC_TP_PERIODO_ZONA P
        WHERE C.ID_ZONA = P.ID_ZONA(+)
        AND C.PERIODO = P.PERIODO(+)
        AND C.PERIODO = $periodo
        AND C.CODIGO_PROYECTO = 'SD'
        ORDER BY 2, 1 ASC";

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

    public function getDatCalendarioFacturacionCORAABO(){
        $periodo = date('Ym');

        $sql="SELECT C.ID_ZONA, TO_CHAR(C.FEC_APERTURA,'DD/MM/YYYY')FEC_APERTURA, TO_CHAR(P.FEC_APERTURA,'DD/MM/YYYY') FEC_APE_REAL,
        TO_CHAR(C.FEC_FIN_LEC,'DD/MM/YYYY')FEC_MAX_LEC, 
        (SELECT MAX(TO_CHAR(R.FECHA_LECTURA_ORI,'DD/MM/YYYY')) FROM SGC_TT_REGISTRO_LECTURAS R WHERE R.ID_ZONA = C.ID_ZONA AND R.PERIODO = $periodo) FEC_LEC_REAL,
        TO_CHAR(C.FEC_DIF_MORA,'DD/MM/YYYY')FEC_MORA, TO_CHAR(P.FEC_DIFE,'DD/MM/YYYY')FEC_MORA_REAL,
        TO_CHAR(C.FEC_DIF_MORA,'DD/MM/YYYY')FEC_CIERRE, TO_CHAR(P.FEC_CIERRE,'DD/MM/YYYY')FEC_CIERRE_REAL,
        TO_CHAR(C.FEC_MAX_DISTR,'DD/MM/YYYY')FEC_DISTR, 
        (SELECT MAX(TO_CHAR(E.FECHA_EJECUCION,'DD/MM/YYYY')) FROM SGC_TT_REGISTRO_ENTREGA_FAC E WHERE E.ID_ZONA = C.ID_ZONA AND E.PERIODO = $periodo) FEC_DISTR_REAL,
        TO_CHAR(C.FEC_VENC_FACT,'DD/MM/YYYY')FEC_VCTO, 
        (SELECT MAX(TO_CHAR(F.FEC_VCTO,'DD/MM/YYYY')) FROM SGC_TT_FACTURA F WHERE F.ID_ZONA = C.ID_ZONA AND F.PERIODO = $periodo)FEC_VCTO_REAL,
        TO_CHAR(C.FEC_CORTE_SER,'DD/MM/YYYY')FEC_CORTE, TO_CHAR(P.FEC_LOTCORTE,'DD/MM/YYYY')FEC_CORTE_REAL
        FROM SGC_TP_CAL_FACT C, SGC_TP_PERIODO_ZONA P
        WHERE C.ID_ZONA = P.ID_ZONA(+)
        AND C.PERIODO = P.PERIODO(+)
        AND C.PERIODO = $periodo
        AND C.CODIGO_PROYECTO = 'BC'
        ORDER BY 2, 1 ASC";

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




    /////DATOS CONSUMO FACTURADO
    public function getDatosConsumoFactPdf($factura){
        $sql = "SELECT SUM(DF.UNIDADES) CONSUMO
        FROM SGC_TT_DETALLE_FACTURA DF
        WHERE DF.FACTURA = $factura
        AND DF.CONCEPTO IN (1,3)";
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

    /**  
        *@param string $fecha Fecha la cual se desea saber la cantidad de correos enviados. Si no se envía el parámetro se toma la fecha actual.
        *@return json.
    */
    public function intentosFacturasPorCorreo($fecha = ''){
        /* Función para obtener la cantidad de facturas enviadas por correo en el día actual. */

        try {
            if ($fecha == "" ) $fecha = date('Ymd');

            $sql = "SELECT COUNT(*) INTENTOS
                    FROM SGC_TT_ENVIO_FACDIGITAL EFD
                    WHERE TO_CHAR(EFD.FECHA_ENVIO, 'YYYYMMDD') = '$fecha'";

            $statement = oci_parse($this->_db, $sql);
            $executed = oci_execute($statement);
            $error = oci_error($statement);

            if($executed){
                $json = array();
                while($fila = oci_fetch_assoc($statement)){ $json = array('intentos'=>$fila["INTENTOS"]); }
                return json_encode(array('code'=>$error["code"], 'mensaje'=>$error["message"], 'payload'=>$json));
            }else{
                return json_encode(array('code'=>$error["code"], 'mensaje'=>$error["message"], 'payload'=>array()));
            }
        } catch (Exception $e) {
            return json_encode(array('code'=>$e->getCode(), 'mensaje'=>$e->getMessage(), 'payload'=>array()));
        }
        
    }

    public function facDigitalPendientes(){
        $sql = "SELECT E.CONSEC_FACTURA, E.PERIODO, E.EMAIL, E.ACUEDUCTO, E.INTENTOS_ENVIO, F.INMUEBLE, C.ALIAS
                FROM SGC_TT_ENVIO_FACDIGITAL E, SGC_TT_FACTURA F, SGC_TT_CONTRATOS C
                WHERE E.CONSEC_FACTURA = F.CONSEC_FACTURA
                  AND C.CODIGO_INM = F.INMUEBLE
                      AND ENVIADO = 'N'
                AND C.FECHA_FIN IS NULL
                ORDER BY CONSEC_FACTURA ASC";

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

    public function datosEnvioFacDigPend($factura, $periodo, $enviado, $error, $intentos){
        $sql="BEGIN SGC_P_ACTUALIZA_FAC_DIGITAL('$factura','$periodo','$enviado','$error','$intentos',:PMSGRESULT,:PCODRESULT);
		COMMIT;END;";
        //echo $sql;
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->msgresult,1000);
        oci_bind_by_name($resultado,":PCODRESULT",$this->codresult);
        //oci_bind_by_name($resultado,":PROY",$VACIO);
        $bandera=oci_execute($resultado);

        if($bandera==TRUE){
            if( $this->codresult >0){
                return false;
            }else{
                return true;
            }
        }
        else{
            return false;
        }
    }

    public function getDifDet($proyecto,$periodo){

        $periodo      = addslashes($periodo);
        $proyecto      = addslashes($proyecto);

        $sql = "SELECT D.INMUEBLE, S.DESC_SERVICIO, D.VALOR_DIFERIDO
FROM SGC_TT_DIFERIDOS D, SGC_TP_SERVICIOS S, SGC_TT_INMUEBLES I
WHERE D.CONCEPTO = S.COD_SERVICIO
AND D.INMUEBLE = I.CODIGO_INM
AND D.PER_INI = $periodo
AND I.ID_PROYECTO = '$proyecto'
AND FECHA_REVERSION IS NULL
AND USUARIO_REVERSION IS NULL";
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

    public function getDifRes($proyecto,$periodo){

        $periodo      = addslashes($periodo);
        $proyecto      = addslashes($proyecto);

        $sql = "SELECT S.DESC_SERVICIO, SUM (D.VALOR_DIFERIDO) TOTAL
FROM SGC_TT_DIFERIDOS D, SGC_TP_SERVICIOS S, SGC_TT_INMUEBLES I
WHERE D.CONCEPTO = S.COD_SERVICIO
  AND D.INMUEBLE = I.CODIGO_INM
  AND D.PER_INI = $periodo
  AND I.ID_PROYECTO = '$proyecto'
AND FECHA_REVERSION IS NULL
AND USUARIO_REVERSION IS NULL
GROUP BY D.CONCEPTO, S.DESC_SERVICIO";
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

    public function getDetNotB04($proyecto,$fecini, $fecfin){

        $proyecto      = addslashes($proyecto);
        $fecini      = addslashes($fecini);
        $fecfin      = addslashes($fecfin);

        $sql = "SELECT P.CODIGO_PQR, TO_CHAR(P.FECHA_PQR,'DD/MM/YYYY') FECHA_PQR,'NCR' TIPO_NOTA, I.CODIGO_INM, (SELECT C1.ALIAS FROM SGC_TT_CONTRATOS C1 WHERE C1.CODIGO_INM = I.CODIGO_INM AND C1.FECHA_FIN IS NULL) NOMBRE,
        NF.FACTURA_APLICA, F.TOTAL_ORI VALOR_FACTURA, CONCAT(NU.ID_NCF, F.NCF_CONSEC) NCF_ORI, CONCAT(DECODE (NF.ID_NCF, 42, 'B04', 45, 'B99', 49, 'B98') , NF.NCF_CONSEC) NCF_NOTA,
        NF.TOTAL_NOTA, TO_CHAR(NF.FECHA_EMISION,'DD/MM/YYYY')FEC_REL, P.DESCRIPCION
        FROM SGC_TT_FACTURA F, SGC_TT_NOTAS_FACTURAS NF, SGC_TT_INMUEBLES I, SGC_TT_PQRS P, SGC_TP_NCF_USOS NU
        WHERE I.CODIGO_INM = F.INMUEBLE
        AND P.COD_INMUEBLE = I.CODIGO_INM
        AND P.MOTIVO_PQR IN (1,2,7,10,17,23,25,27,47,49,54,66,100,108)
        AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS') AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND F.NCF_ID = NU.ID_NCF_USO
        AND F.CONSEC_FACTURA = NF.FACTURA_APLICA
        AND NF.COD_INMUEBLE IS NOT NULL
        AND NF.FECHA_EMISION BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS') AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
        AND I.ID_PROYECTO = '$proyecto'
        AND NF.FECHA_ANULACION IS NULL
        AND NF.ANULADA = 'N'
        AND NF.ID_NCF IN (42,45)
        GROUP BY  P.CODIGO_PQR, TO_CHAR(P.FECHA_PQR,'DD/MM/YYYY'), I.CODIGO_INM, NF.FACTURA_APLICA, F.TOTAL_ORI, NF.ID_NCF, NF.NCF_CONSEC,  NF.FECHA_EMISION, F.NCF_CONSEC, NU.ID_NCF, NF.TOTAL_NOTA, P.DESCRIPCION
        ORDER BY I.CODIGO_INM, NF.FACTURA_APLICA";
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

    public function getDetNotB99($proyecto,$fecini, $fecfin){

        $proyecto      = addslashes($proyecto);
        $fecini      = addslashes($fecini);
        $fecfin      = addslashes($fecfin);

        $sql = "SELECT I.CODIGO_INM, (SELECT C1.ALIAS FROM SGC_TT_CONTRATOS C1 WHERE C1.CODIGO_INM = I.CODIGO_INM AND C1.FECHA_FIN IS NULL) NOMBRE,
       NF.FACTURA_APLICA, F.TOTAL_ORI VALOR_FACTURA, DECODE (NF.ID_NCF, 42, 'B04', 45, 'B99', 49, 'B98') NCF, NF.NCF_CONSEC,
       SUM (NF.TOTAL_NOTA) TOTAL_NOTA, O.DESCRIPCION OBSERVACION
FROM SGC_TT_FACTURA F, SGC_TT_NOTAS_FACTURAS NF, SGC_TT_INMUEBLES I, SGC_TT_OBSERVACIONES_INM O
WHERE I.CODIGO_INM = F.INMUEBLE
  AND F.CONSEC_FACTURA = NF.FACTURA_APLICA
  AND O.INM_CODIGO = I.CODIGO_INM
  AND O.CODIGO_OBS = 'ANC'
  AND NF.REL_AGRUPADA = O.DESC_NOTA
  AND NF.COD_INMUEBLE IS NOT NULL
  AND NF.FECHA_EMISION BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS') AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
  AND I.ID_PROYECTO = '$proyecto'
  AND NF.FECHA_ANULACION IS NULL
  AND NF.ANULADA = 'N'
  AND NF.ID_NCF = 45
GROUP BY I.CODIGO_INM, NF.FACTURA_APLICA, F.TOTAL_ORI, NF.ID_NCF, NF.NCF_CONSEC, O.DESCRIPCION
ORDER BY I.CODIGO_INM";
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

    public function getDetNotB98($proyecto,$fecini, $fecfin){

        $proyecto      = addslashes($proyecto);
        $fecini      = addslashes($fecini);
        $fecfin      = addslashes($fecfin);

        $sql = "SELECT I.CODIGO_INM, (SELECT C1.ALIAS FROM SGC_TT_CONTRATOS C1 WHERE C1.CODIGO_INM = I.CODIGO_INM AND C1.FECHA_FIN IS NULL) NOMBRE,
       NF.FACTURA_APLICA, F.TOTAL_ORI VALOR_FACTURA, DECODE (NF.ID_NCF, 42, 'B04', 45, 'B99', 49, 'B98') NCF, NF.NCF_CONSEC,
       SUM (NF.TOTAL_NOTA) TOTAL_NOTA, O.DESCRIPCION OBSERVACION
FROM SGC_TT_FACTURA F, SGC_TT_NOTAS_FACTURAS NF, SGC_TT_INMUEBLES I, SGC_TT_OBSERVACIONES_INM O
WHERE I.CODIGO_INM = F.INMUEBLE
  AND F.CONSEC_FACTURA = NF.FACTURA_APLICA
  AND O.INM_CODIGO = I.CODIGO_INM
  AND O.CODIGO_OBS = 'ANC'
  AND NF.REL_AGRUPADA = O.DESC_NOTA
  AND NF.COD_INMUEBLE IS NOT NULL
  AND NF.FECHA_EMISION BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS') AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
  AND I.ID_PROYECTO = '$proyecto'
  AND NF.FECHA_ANULACION IS NULL
  AND NF.ANULADA = 'N'
  AND NF.ID_NCF = 49
GROUP BY I.CODIGO_INM, NF.FACTURA_APLICA, F.TOTAL_ORI, NF.ID_NCF, NF.NCF_CONSEC, O.DESCRIPCION
ORDER BY I.CODIGO_INM";
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

    public function getSolNcfB04($proyecto,$fecini,$fecfin){

        //$proyecto      = addslashes($proyecto);

        $sql = "SELECT 'NCR' TIPO_NOTA, I.CODIGO_INM, P.CODIGO_PQR, P.FECHA_PQR, C.ALIAS, F.TOTAL_ORI TOTAL_FACTURA, CONCAT(NU.ID_NCF,F.NCF_CONSEC)NCF, F.TOTAL_ORI MONTO_NOTA,
       'NOTA POR '||MR.DESC_MOTIVO_REC  MOTIVO, P.DESCRIPCION, U.NOM_USR||' '||U.APE_USR AUXILIAR
FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TP_NCF_USOS NU, SGC_TT_PQRS P, SGC_TP_MOTIVO_RECLAMOS MR, SGC_TT_CONTRATOS C, SGC_TT_USUARIOS U
WHERE  F.INMUEBLE = I.CODIGO_INM AND
        NU.ID_NCF_USO = F.NCF_ID AND
        P.COD_INMUEBLE = F.INMUEBLE AND
        P.MOTIVO_PQR = MR.ID_MOTIVO_REC AND
        C.CODIGO_INM = I.CODIGO_INM AND
        P.USER_RECIBIO_PQR = U.ID_USUARIO AND
        C.FECHA_FIN IS NULL AND
        F.FACTURA_PAGADA = 'N' AND
        I.ID_PROYECTO = '$proyecto' AND
        P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
            TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS') AND
        MR.GERENCIA = 'E' AND
        P.MOTIVO_PQR IN (1,2,7,10,17,23,25,27,47,49,54,66,100,108) AND
        P.CERRADO = 'N'
ORDER BY I.CODIGO_INM";
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


    public function getRelacionFacturacionMesAct($periodo,$inmueble){

        //$proyecto      = addslashes($proyecto);

        $sql = "SELECT F.INMUEBLE, F.PERIODO, F.TOTAL
FROM SGC_TT_FACTURA F
WHERE TO_DATE(F.PERIODO,'YYYYMM') =  ADD_MONTHS (TO_DATE($periodo, 'YYYYMM'),0)
  AND F.INMUEBLE = $inmueble";
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

    public function getRelacionFacturacionMesAnt($periodo,$inmueble){

        //$proyecto      = addslashes($proyecto);

        $sql = "SELECT F.INMUEBLE, F.PERIODO, F.TOTAL
FROM SGC_TT_FACTURA F
WHERE TO_DATE(F.PERIODO,'YYYYMM') =  ADD_MONTHS (TO_DATE($periodo, 'YYYYMM'),-1)
  AND F.INMUEBLE = $inmueble";
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


    public function getRelacionFacturacionAnoAnt($periodo,$inmueble){

        //$proyecto      = addslashes($proyecto);

        $sql = "SELECT F.INMUEBLE, F.PERIODO, F.TOTAL
FROM SGC_TT_FACTURA F
WHERE TO_DATE(F.PERIODO,'YYYYMM') =  ADD_MONTHS (TO_DATE($periodo, 'YYYYMM'),-12)
  AND F.INMUEBLE = $inmueble";
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

    public function getRelacionMetrosMesAct($periodo,$inmueble){

        //$proyecto      = addslashes($proyecto);

        $sql = "SELECT F.COD_INMUEBLE, F.PERIODO, SUM(F.UNIDADES)UNIDADES
FROM SGC_TT_DETALLE_FACTURA F
WHERE TO_DATE(F.PERIODO,'YYYYMM') =  ADD_MONTHS (TO_DATE($periodo, 'YYYYMM'),0)
  AND F.COD_INMUEBLE = $inmueble
  AND F.CONCEPTO IN (1,3)
GROUP BY  F.COD_INMUEBLE, F.PERIODO";
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

    public function getRelacionMetrosMesAnt($periodo,$inmueble){

        //$proyecto      = addslashes($proyecto);

        $sql = "SELECT F.COD_INMUEBLE, F.PERIODO, SUM(F.UNIDADES)UNIDADES
FROM SGC_TT_DETALLE_FACTURA F
WHERE TO_DATE(F.PERIODO,'YYYYMM') =  ADD_MONTHS (TO_DATE($periodo, 'YYYYMM'),-1)
  AND F.COD_INMUEBLE = $inmueble
  AND F.CONCEPTO IN (1,3)
GROUP BY  F.COD_INMUEBLE, F.PERIODO";
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

    public function getRelacionMetrosAnoAnt($periodo,$inmueble){

        //$proyecto      = addslashes($proyecto);

        $sql = "SELECT F.COD_INMUEBLE, F.PERIODO, SUM(F.UNIDADES)UNIDADES
FROM SGC_TT_DETALLE_FACTURA F
WHERE TO_DATE(F.PERIODO,'YYYYMM') =  ADD_MONTHS (TO_DATE($periodo, 'YYYYMM'),-12)
  AND F.COD_INMUEBLE = $inmueble
  AND F.CONCEPTO IN (1,3)
GROUP BY  F.COD_INMUEBLE, F.PERIODO";
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

    public function getDetFacByFacFlexyAseo ($where,$sort,$start,$end,$factura)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select S.DESC_SERVICIO CONCEPTO,VALOR,S.COD_SERVICIO
						from SGC_TT_DETALLE_FACTURA_ASEO DF , SGC_TP_SERVICIOS S
						WHERE  DF.CONCEPTO=S.COD_SERVICIO  AND FACTURA='$factura'
						$where
						  ORDER BY CONCEPTO ASC
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

}
