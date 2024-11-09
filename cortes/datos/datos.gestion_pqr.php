<?php
session_start();

	$tip= $_REQUEST["tip"];

	if($tip=="getReporte"){
        $coduser = $_SESSION['codigo'];
        $tipo_pqr = $_GET['tipo_pqr'];
        $proyecto = $_GET['proyecto'];
        $secini = $_GET['secini'];
        $secfin = $_GET['secfin'];
        $rutini = $_GET['rutini'];
        $rutfin = $_GET['rutfin'];
        $fecini = $_GET['fecini'];
        $fecfin = $_GET['fecfin'];
        $cod_inmueble = $_GET['cod_inmueble'];
        $area_user = $_GET['area_user'];
        $page = $_POST['page'];
        $rp = $_POST['rp'];
        $sortname = $_POST['sortname'];
        $sortorder = $_POST['sortorder'];
        $query = $_POST['query'];
        $qtype = $_POST['qtype'];


        if (!$sortname) $sortname = "TO_DATE(TO_CHAR(P.FECHA_PQR,'DD/MM/YYYY HH24:MI:SS'),'DD/MM/YYYY hh24:mi:ss')";
        if (!$sortorder) $sortorder = "DESC";

        $sort = "ORDER BY $sortname $sortorder";

        if (!$page) $page = 1;
        if (!$rp) $rp = 30;

        $end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
        $start = ($page) * $rp;  // MIN_ROW_TO_FETCH
        $fname ="P.CODIGO_PQR";
        $tname="SGC_TT_PQRS P, SGC_TP_MOTIVO_RECLAMOS M, SGC_TP_AREAS A, SGC_TT_INMUEBLES I";


        if ($query) $where = "AND UPPER($qtype) LIKE UPPER('$query%') ";
        include_once '../../clases/class.pqr.php';

        $l=new Pqr();
        $valores=$l->getCantPqrByProTipSecRutFecCodAreFlexy($proyecto, $tipo_pqr, $secini, $secfin, $rutini, $rutfin, $fecini, $fecfin, $cod_inmueble, $where, '3');
        while (oci_fetch($valores)) {
            $total = oci_result($valores, 'CANTIDAD');
        }oci_free_statement($valores);

        $l=new Pqr();
        $registros=$l->getDatosPqrByProTipSecRutFecInmFlexy($proyecto, $tipo_pqr, $secini, $secfin, $rutini, $rutfin, $fecini, $fecfin, $cod_inmueble, $start, $end, $where, '3');


        $json = "";
        $json .= "{\n";
        $json .= "page: $page,\n";
        $json .= "total: $total,\n";
        $json .= "rows: [";
        $rc = false;
        while (oci_fetch($registros)) {
            $numero=oci_result($registros, 'RNUM');
            $codigo_pqr = oci_result($registros, 'CODIGO_PQR');
            $fecha_pqr = oci_result($registros, 'FECHAPQR');
            $cod_inm = oci_result($registros, 'COD_INMUEBLE');
            $motivo_pqr = oci_result($registros, 'MOTIVO');
            $entidad_pqr = oci_result($registros, 'COD_ENTIDAD');
            $user_pqr = oci_result($registros, 'LOGIN');
            $area_asig = oci_result($registros, 'DESC_AREA');
            $fecha_max = oci_result($registros, 'FECHAMAX');
            $proceso_inm = oci_result($registros, 'ID_PROCESO');
            $dias_faltan = oci_result($registros, 'PORVENCER');
            $dias_vencidos = oci_result($registros, 'VENCIDOS');

            if ($rc) $json .= ",";
            $json .= "\n{";
            $json .= "id:'".$codigo_pqr."',";
            $json .= "cell:['<b>" .$numero."</b>'";
            $json .= ",'".$codigo_pqr."'";
            $json .= ",'".$cod_inm."'";
            $json .= ",'".addslashes($fecha_pqr)."'";
            $json .= ",'".addslashes($motivo_pqr)."'";
            $json .= ",'".addslashes($entidad_pqr)."'";
            $json .= ",'".addslashes($user_pqr)."'";
            $json .= ",'".addslashes($fecha_max)."'";
            $json .= ",'".addslashes($proceso_inm)."'";
            $json .= ",'".addslashes($area_asig)."'";
            if($dias_faltan > 0)$json .= ",'<b><font color=#00CC00>".$dias_faltan."</font></b>'";
            else $json .= ",'<b><font color=#FF0000>".$dias_faltan."</font></b>'";
            $json .= ",'".$dias_vencidos."'";
            $json .= ",'<a href=javascript:edita_pqr($codigo_pqr);><img src=../../images/edit1.png width=15px height=15px title=Resoluci&oacute;n /></a>'";
            //$json .= ",'<a href=javascript:sigue_pqr($codigo_pqr);><img src=../../images/search1.png width=15px height=15px/></a>'";
            if($area_user == '9' || $area_user == '6'){
                $json .= ",'<a href=javascript:close_pqr($codigo_pqr);><img src=../../images/lock1.png width=15px height=15px title=Cierre /></a>'";
            }
            $json .= ",'<a href=javascript:documento_pqr($codigo_pqr);><img src=../../images/file1.png width=15px height=15px title=Documento /></a>'";
           /* if(explode('-',$motivo_pqr)[0]==49){
                $json .= ",'<button class=\"btnAsignar btn btn-primary\"  id=btnAsignar$numero onClick=asignar(this)>Asignar Corte</button>'";
            }else{
                $json .= ",''";
            }*/
            $json .= "]}";
            $rc = true;
        }
        $json .= "]\n";
        $json .= "}";
        echo $json;
	}

	if($tip=="getDatos"){
	    $cod_pqr= $_POST["cod_pqr"];
        include_once "../../clases/classPqrs.php";

        $d = new PQRs();
        $datos = $d->obtenerDatosInmueblePorPqr($cod_pqr);

        $data =[];
        while($row=oci_fetch_assoc($datos)){
            $inmueble = $row["CODIGO_INM"];
            $direccion = $row["DIRECCION"];
            $zona = $row["ID_ZONA"];
            $proyecto = $row["ID_PROYECTO"];
            $arr=[$inmueble,$direccion,$zona,$proyecto];
            array_push($data,$arr);
        }

        echo json_encode($data);

	}

	if($tip=="getOperarios"){

	    include_once "../../clases/class.usuario.php";

	    $proyecto = $_POST["proyecto"];
	    $u = new Usuario();
	    $usuarios = $u->getOperariosCorteByPro($proyecto);

	    $data = [];
	    while($row=oci_fetch_assoc($usuarios)){
	        $id_usuario = $row["CODIGO"];
	        $nobre_usuario = $row["NOMBRE"]." ".$row["APELLIDO"];

	        $arr = [$id_usuario,$nobre_usuario];
	        array_push($data,$arr);

        }oci_free_statement($usuarios);
        echo json_encode($data);
    }

    if($tip=="asignarOperario"){

        include_once ("../../clases/class.corte.php");

        session_start();
        $cod_pqr =$_POST["cod_pqr"];
        $usuario_asignador = $_SESSION["codigo"];
        $usuario_asignado = $_POST["operario"];
        $cod_inmueble = $_POST["cod_inmueble"];

        $c = new Corte();
        $asignacion_corte = $c->asignarCorteCancelacionContrato($cod_pqr,$usuario_asignado,$usuario_asignador,$cod_inmueble);

        echo $asignacion_corte?'true':'false';
    }

    if($tip=="corteAsignado"){
        include_once ("../../clases/class.corte.php");

        $cod_pqr = $_POST["cod_pqr"];

        $c = new Corte();
        $datos = $c->corteAsignado($cod_pqr);

        $operario="";
        while($row =oci_fetch_assoc($datos)){
            $operario = $row["OPERARIO"];
        }

       echo json_encode($operario);

    }

    if($tip=="actualizarAsignacionOperario") {
        include_once("../../clases/class.corte.php");

        session_start();
        $cod_pqr = $_POST["cod_pqr"];
        $usuario_asignador = $_SESSION["codigo"];
        $usuario_asignado = $_POST["operario"];

        $c = new Corte();
        $asignacion_corte = $c->actualizarCorteCancelacionContrato($cod_pqr, $usuario_asignado, $usuario_asignador);

        if($asignacion_corte==true){
            echo "true";
        }else{
            echo "false";
        }
        //echo $asignacion_corte;
        //echo $asignacion_corte?'true':'false';
    }

    if($tip=="eliminarAsignacion"){
        include_once("../../clases/class.corte.php");

        $cod_pqr = $_POST["cod_pqr"];
        $c = new Corte();
        $asignacion_corte = $c->eliminarAsignacion($cod_pqr);

        if($asignacion_corte==true){
            echo "true";
        }else{
            echo "false";
        }

    }
?>
