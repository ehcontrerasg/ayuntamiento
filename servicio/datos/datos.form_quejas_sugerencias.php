<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('MAX_EXECUTION_TIME', '-1');

error_reporting(E_ALL);//mb_internal_encoding("UTF-8");*/


session_start();
$tipo = $_POST['tip'];
$coduser = $_SESSION['codigo'];
$tipo_pqr=3; // variable que contiene el id del tipo pqr llamado QUEJA

if ($tipo == 'motiv') {
    $l         = new PQRs();
    $tipo_pqr      = $_POST['group'];
    $registros = $l->seleccionaMotivoPqrporTipo($tipo_pqr);
    $arr       = array();

    oci_fetch_all($registros, $motivo);
    echo json_encode($motivo);

}

if ($tipo == 'quejasSugerencias') {

    include_once '../../clases/classPqrs.php';
    include_once '../../clases/class.motivo.php';

    $fecini = $_POST['fechaIn'];
    $fecfin = $_POST['fechaFn'];
    $proyecto = $_POST['proyecto'];

    $pqr = new PQRs();
    $registros = $pqr->GetFormQuejasSugerencias('',$fecini, $fecfin,$proyecto);
    $data=array();
    $dataPregunta = array();
    $i=0;
    $codigos='';
    $p=new Motivo();

    while (oci_fetch($registros)) {

        $nombre = oci_result($registros, 'NOMBRE');
        $codigoPre = oci_result($registros, 'CODIGO_PREGUNTA');
        $fecha = oci_result($registros, 'FECHA');
        $pregunta = oci_result($registros, 'PREGUNTA');
        $respuesta = oci_result($registros, 'RESPUESTA');
        $codigoRep = oci_result($registros, 'CODIGO_RESPUESTA');
        $oficina = oci_result($registros, 'OFICINA');
        $queja = oci_result($registros, 'QUEJA');
        $sugerencia = oci_result($registros, 'SUGERENCIA');
        $inmueble = oci_result($registros, 'CODIGO_INMUEBLE');
        $cedula =  oci_result($registros, 'CEDULA');
        $celular =oci_result($registros, 'CELULAR');
        $telefono =  oci_result($registros, 'TELEFONO');
        $email =oci_result($registros, 'EMAIL');
        $direccion =  oci_result($registros, 'DIRECCION');
        $codFormulario = oci_result($registros, 'CODIGO_FORMULARIO');
        $gerencia = oci_result($registros, 'GERENCIA');
        $motivo_rec_id = oci_result($registros, 'MOTIVO_PQR');
        $proyecto =oci_result($registros, 'PROYECTO');

        $stid = $p->obtenermotivo($tipo_pqr,$gerencia);

        $htmlSelectTag='';
        if ($motivo_rec_id==null) {
            $htmlSelectTag .= '<select name="selectMotivo"  id="motivo' . $codFormulario . '" class="form-control"  style="width: 300px; font-size: 12px"><option value="" selected>Seleccione Motivo PQR...</option>';
            while (oci_fetch($stid)) {
                $cod_motivo = oci_result($stid, 'ID_MOTIVO_REC');
                $des_motivo = oci_result($stid, 'DESC_MOTIVO_REC');

                $htmlSelectTag .= '<option value="' . $cod_motivo . '">' . $cod_motivo . " - " . $des_motivo . '</option>';
            }
            oci_free_statement($stid);
            $htmlSelectTag .= '</select>';
        } else{
            while (oci_fetch($stid)) {
                $cod_motivo = oci_result($stid, 'ID_MOTIVO_REC');
                $des_motivo = oci_result($stid, 'DESC_MOTIVO_REC');
if ($motivo_rec_id==105) {
    $htmlSelectTag = '<select name="selectMotivo"  id="motivo' . $codFormulario . '" class="form-control"  style="width: 300px; font-size: 12px">
    <option value="' .$cod_motivo . '" selected>' . $cod_motivo . " - " . $des_motivo . '</option></select>';
}
            }
            oci_free_statement($stid);

        }

        $preguntas_registradas = $pqr->getCantPreguntas($codFormulario);

        if ( $i==0) {
            $arr = array($codFormulario,$inmueble, $direccion,$nombre,$cedula,$celular,$telefono,$email, $oficina, $fecha, $queja, $sugerencia,$gerencia, $pregunta, $respuesta,'<button  id="btnMostrar" tabindex="30" onclick="mostrarModal('.$codFormulario.')" class="btn btn-info glyphicon glyphicon-eye-open"></button>',
                $htmlSelectTag);
            array_push($data,$arr);
            $total=$preguntas_registradas;
        }
        else if ($i==$total ) {
            $arr = array('','', '', '', '', '', '', '', '','','','','','','','','');
            array_push($data,$arr);
            $arr = array('','', '', '', '', '', '', '', '','','','','','','','','');
            array_push($data,$arr);
            $arr = array($codFormulario,$inmueble, $direccion,$nombre,$cedula,$celular,$telefono,$email, $oficina, $fecha, $queja, $sugerencia,$gerencia, $pregunta, $respuesta,'<button  id="btnMostrar" tabindex="30" onclick="mostrarModal('.$codFormulario.')" class="btn btn-info glyphicon glyphicon-eye-open"></button>',
                $htmlSelectTag);
            $total+=$preguntas_registradas;
            array_push($data,$arr);
        } else {
            $arr = array('','', '', '', '', '', '', '', '','','','','',$pregunta,$respuesta,'','');
            array_push($data,$arr);

        }

        $htmlSelectTag='';
        $i++;
    }




    echo json_encode($data);

}

if ($tipo == 'generaVista') {

    include_once '../../clases/classPqrs.php';

    $codFormulario = $_POST['codFormulario'];


    $pqr = new PQRs();
    $registros = $pqr->GetFormQuejasSugerencias($codFormulario,'', '','');
    $data=array();
    $dataPregunta = array();

    $preguntaData = $pqr->getPregunta();
    $respuestaData = $pqr->getRespuesta();
    $cantidadPreguntas = count($preguntaData['CODIGO']);
    $cantidadRespuestas = count($respuestaData['CODIGO']);
    oci_fetch_all($registros, $datos);
    $cantRegistro=count($datos['CODIGO_FORMULARIO']);;

    for ($i0 = 0; $i0 < $cantRegistro; $i0++) {

     //   $nombre = oci_result($registros[$i], );

      //  $codigoPre = oci_result($registros, 'CODIGO_PREGUNTA');
        $nombre = $datos['NOMBRE'][$i0];
        $fecha = $datos['FECHA'][$i0];
        $pregunta = $datos['PREGUNTA'][$i0];
        $respuesta = $datos['RESPUESTA'][$i0];
        $oficina = $datos['OFICINA'][$i0];
        $queja = $datos['QUEJA'][$i0];
        $sugerencia = $datos['SUGERENCIA'][$i0];
        $inmueble = $datos['CODIGO_INMUEBLE'][$i0];
        $codFormulario = $datos['CODIGO_FORMULARIO'][$i0];
        $cedula = $datos['CEDULA'][$i0];
        $celular = $datos['CELULAR'][$i0];
        $telefono = $datos['TELEFONO'][$i0];
        $email = $datos['EMAIL'][$i0];
        $direccion = $datos['DIRECCION'][$i0];
        $proyecto = $datos['PROYECTO'][$i0];
        $codigoPre = $datos['CODIGO_PREGUNTA'][$i0];
        $codigoRep = $datos['CODIGO_RESPUESTA'][$i0];
        //$preguntasArray=array($codigoPre);
        $respuestasArray.=$codigoPre.$codigoRep.'-';

        $arr = array($inmueble,$nombre, $oficina, $fecha, $queja, $sugerencia, $pregunta, $respuesta);
        array_push($data,$arr);

        if ($proyecto=='SD') {
            $rutaImagen = '../../images/LogoCaasd.jpg"  width="15%';
            $siglas="(CAASD)";
        }
        else {
            $rutaImagen = '../../images/logo_coraabo.jpg"  width="30%';
            $siglas="(CORAABO)";
        }

        $html='       <div id="Encabezado">       
               <table align="center"  style="margin-bottom: 1%; width: 57%;">
<tbody>
<tr>
<td align="center">  <p>
<img src="'.$rutaImagen.'"></p>
 </td>
</tr>
<tr>
<td align="center">      
<p>                                    
       <strong> Corporacion de acuerductos y alcantarillado de Santo Domingo </strong></p>
</td>
</tr>
<tr>
<td align="center">                     
    <label style="font-weight: bold">'.$siglas.'</label>
</td>
</tr>
</tbody>
</table>     
      </div>      
      
 <div id="informacionCliente">                                  
  <strong> Formulario de Quejas y Sugerencias</strong> 
        <table  style="margin-bottom: 2%; width: 57%; margin-top: 10px  !important;">
        <tr>
<td align="center" colspan="2"> 
   
</td>
</tr>
            <tr>          
                <td>
                                      <label  style="font-size:  12px; font-weight: bold"> Oficina: </label>
  <input type="text"  name="oficina"  value="'.$oficina.'"  id="oficina" data-name="readonlyCustom"  class="form" style="border: 0  ;
    outline: 0 ;
    background: transparent ;
    border-bottom: 1px solid black ; width: 81%"  >
                </td>          
                <td>
                                    <label style="font-weight: bold" style="font-size:  12px; font-weight: bold"> Fecha: </label>
                    <input type="text"  value="'.$fecha.'" name="fecha"  id="fecha" class="form " data-name="readonlyCustom" style="border: 0;
    outline: 0 ;
    background: transparent ;
    border-bottom: 1px solid black ; width: 74%">
                    <input type="hidden"  value="'.$respuestasArray.'" name="codigoPR"  id="codigoPR" class="form " data-name="readonlyCustom" style="width: 74%">
                </td>
            <tr>

            <tr>         
                <td>
                                    <label style="font-size:  12px; font-weight: bold"> Codígo de sistema: </label>
                    <input type="text"  name="contrato" id="contrato"  class="form" style="border: 0  ;
    outline: 0 ;
    background: transparent ;
    border-bottom: 1px solid black ;" value="'.$inmueble.'" data-name="readonlyCustom" style="width: 57%">
                </td>            
                <td>
                                    <label style="font-size:  12px"> Cedula: </label>
                    <input type="text"  value="'.$cedula.'" name="cedula"  maxlength="13" id="cedula" class="form" style="border: 0  ;
    outline: 0 ;
    background: transparent ;
    border-bottom: 1px solid black ;" data-name="readonlyCustom" style="width: 72%">
                </td>
            </tr>

            <tr>            
                <td colspan="2">
                                    <label style="font-size:  12px; font-weight: bold"> Nombre: </label>
                    <input type="text" value="'.$nombre.'"  name="nombres" id="nombres" data-name="readonlyCustom"  class=" form" placeholder="Nombre" style="border: 0  ;
    outline: 0 ;
    background: transparent ;
    border-bottom: 1px solid black ; width: 87%" >
                </td>
            </tr>
            
            <tr>
                <td>
                                    <label style="font-size: 12px"> Celular: </label>
                    <input type="text"  value="'.$celular.'" name="celular" maxlength="14"   id="celular"  data-name="readonlyCustom"   class="form" style="border: 0;
    outline: 0 ;
    background: transparent ;
    border-bottom: 1px solid black ; width: 81%"  placeholder="Celular"  >
                </td>
                <td>
                                    <label style="font-size:  12px; font-weight: bold"> Teléfono: </label>
                    <input type="text" value="'.$telefono.'" name="telefono" maxlength="14"    id="telefono"  class="form" style="border: 0  ;
    outline: 0 ;
    background: transparent ;
    border-bottom: 1px solid black ;"   data-name="readonlyCustom" placeholder="Teléfono" style="width: 69%">
                </td>
            </tr>
            
            <tr>
                <td colspan="2">
                                    <label style="font-size:  12px"> Email: </label>
                    <input type="text" value="'.$email.'"  name="email" id="email"  class="form" style="border: 0  ;
    outline: 0 ;
    background: transparent ;
    border-bottom: 1px solid black ; width: 90%"  data-name="readonlyCustom" placeholder="Email" >
                </td>
            </tr>
            
            <tr>
                <td colspan="2">
                                    <label style="font-size:  12px; font-weight: bold"> Direccion: </label>
                    <input type="text"  value="'.$direccion.'" name="direccion" id="direccion" class="form" style="border: 0;
    outline: 0;
    background: transparent;
    border-bottom: 1px solid black; width: 86%;"   data-name="readonlyCustom"  placeholder="Direccion"><p>   
                </td>
            </tr>
            
        </table>
</div>';

    }
$HTML = "";
    $html .= '<label >¡Queremos mejorar! Ayúdenos respondiendo preguntas: </label>
<table id="PreguntasYrespuestas" style="margin-bottom: 3%; width: 57%; margin-top: 10px  !important;"><p>   
<th></th>';
    for ($i = 0; $i < $cantidadRespuestas; $i++) {

        $respuesta = $respuestaData['RESPUESTA'][$i];
        $html .= "<th>$respuesta</th>";
    }



    for ($i1 = 0; $i1 < $cantidadPreguntas; $i1++) {

        $pregunta = $preguntaData['PREGUNTA'][$i1];
        $codigoPregunta = $preguntaData['CODIGO'][$i1];

            $html .= "<tr><td  id='celdaPreguntas' >$pregunta </td>";

        for ($i2 = 0; $i2 < $cantidadRespuestas; $i2++) {

            $codigoRespuesta = $respuestaData['CODIGO'][$i2];
            $radioArray=array('pregunta'=>$codigoPregunta,'respuesta'=>$codigoRespuesta);


            $html .= "<td id='celdaRepuestas'>
            <input type='radio' name='radioRespuesta$i1' id='id$codigoPregunta$codigoRespuesta' disabled='disabled'/>
 </td>";


        }
        $html .= "</tr>";
    }



    $html .= '</table>';

    $html .= '<center>  
<strong>  ¡Ayúdenos a servirle mejor! Si nos deja conocer sus quejas y sugerencias<br>  nos permitirá
darle un mejor servicio, por favor escriba aquí: </strong>
</center>
<table  style="margin-bottom: 3%;   width: 57%; margin-top: 10px !important;">
<tbody>
<tr>
<td align="center"> </td>
</tr>
<tr>
<td align="left">
<div   style=" 
background-image: -webkit-linear-gradient(white, white 30px, #ccc 30px, #ccc 50px, white 31px) !important;
    background-image: -moz-linear-gradient(white, white 30px, #ccc 30px, #ccc 31px, white 31px) !important;
    background-image: -ms-linear-gradient(white, white 30px, #ccc 30px, #ccc 31px, white 31px) !important;
    background-image: -o-linear-gradient(white, white 30px, #ccc 30px, #ccc 31px, white 31px) !important;
    background-image: linear-gradient(white, white 30px, #070707  30px, #ccc 50px, white 31px) !important;
    background-size: 100% 31px !important;
    border: 0 solid #ffffff !important;
    border-radius: 0 !important;
    line-height: 31px !important;
    font-family: Arial, Helvetica, Sans-serif !important;
    width: 100% !important;
     padding-bottom: 0px;
     height: 200px !important;
        white-space: pre-wrap !important;       
   white-space: -moz-pre-wrap !important; 
   white-space: -o-pre-wrap !important; 
   word-wrap: break-word !important; 
   word-break: break-all !important;
  "><strong>Quejas: </strong>'.$queja.'
</div>

    </td>
</tr>
<tr><td align="left">
<div  style="
background-image: -webkit-linear-gradient(white, white 30px, #ccc 30px, #ccc 50px, white 31px) !important;
    background-image: -moz-linear-gradient(white, white 30px, #ccc 30px, #ccc 31px, white 31px) !important;
    background-image: -ms-linear-gradient(white, white 30px, #ccc 30px, #ccc 31px, white 31px) !important;
    background-image: -o-linear-gradient(white, white 30px, #ccc 30px, #ccc 31px, white 31px) !important;
    background-image: linear-gradient(white, white 30px, #070707  30px, #ccc 50px, white 31px) !important;
    background-size: 100% 31px !important;
    border: 0 solid #ffffff !important;
    border-radius: 0 !important;
    line-height: 31px !important;
    font-family: Arial, Helvetica, Sans-serif !important;
    width: 100% !important;
     padding-bottom: 0px;
     height: 200px !important;
        white-space: pre-wrap !important;       
   white-space: -moz-pre-wrap !important; 
   white-space: -o-pre-wrap !important; 
   word-wrap: break-word !important; 
   word-break: break-all !important;
    "><strong>Sugerencias: </strong>'.$sugerencia.'
    </div>
</td></tr>
</tbody>
</table>
<strong> ¡AGRADECEMOS SU COMENTARIOS! por favor deposite este volante<br> en el Bozón de sugerencias. </strong>';

    echo $html;

}



if ($tipo=='ingresaPqr')
{

    require '../clases/classPqrs.php';
    require '../../clases/class.areares.php';
    require '../../clases/class.fechares.php';

    $cod_form=$_POST['codigoFormulario'];
    $cod_inmueble=$_POST['cod_inmueble'];
    $direccion=$_POST['direccion'];
    $cod_zon=$_POST['cod_zon'];
    $uso_inm=$_POST['uso_inm'];
    $est_inm=$_POST['est_inm'];
    $act_inm=$_POST['act_inm'];
    $uni_inm=$_POST['uni_inm'];
    $cmo_min=$_POST['cmo_min'];
    $cod_pro=$_POST['cod_pro'];
    $des_pro=$_POST['des_pro'];
    $nom_cli=$_POST['nom_cli'];
    $ced_cli=$_POST['ced_cli'];
    $tel_cli=$_POST['tel_cli'];
    $mail_cli=$_POST['mail_cli'];
    $tel_cli_nuevo=$_POST['tel_cli'];
    $mail_cli_nuevo=$_POST['mail_cli'];
    $medio=$_POST['medio'];
    $motivo=$_POST['motivo'];
    $motivoDescripcion=$_POST['motivoDescription'];
    $fecha=$_POST['fecha'];
    $descripcion=$_POST['descripcion'];
    $ger_inm=$_POST['ger_inm'];

    $c = new PQRs();
    $p=new AreaRes();
    $f=new FechaRes();

    $resultado = $c->seleccionaUser($coduser);
    while (oci_fetch($resultado)) {
        $id_caja = oci_result($resultado, 'ID_CAJA');
        $num_caja = oci_result($resultado, 'NUM_CAJA');
        $id_punto = oci_result($resultado, 'ID_PUNTO_PAGO');
        $des_punto = oci_result($resultado, 'DESCRIPCION');
        $cod_ent = oci_result($resultado, 'COD_ENTIDAD');
        $des_ent = oci_result($resultado, 'DESC_ENTIDAD');
        $nom_usu = oci_result($resultado, 'NOMBRE');
    }oci_free_statement($resultado);


    $stid = $p->obtenerareares($motivo,$ger_inm);
    while (oci_fetch($stid)) {
        $cod_area= oci_result($stid, 'AREA_PERTENECE');
        $des_area= oci_result($stid, 'DESC_AREA');
    }oci_free_statement($stid);

    $stid = $f->obtenerfechares($motivo);
    while (oci_fetch($stid)) {
        $fecha_res= oci_result($stid, 'FEC_RESOL');
    }oci_free_statement($stid);


    $bandera = $c->IngresaPqr($fecha,$cod_inmueble,$nom_cli,$ced_cli,$direccion,$tel_cli,$mail_cli,$medio,$tipo_pqr,$motivo,$fecha_res,$descripcion,$cod_ent,$id_punto,$num_caja,$coduser,$ger_inm,$cod_area,$tel_cli_nuevo,$mail_cli_nuevo);

    if($bandera == false){
        $error=$c->getmsgresult();
        $coderror=$c->getcodresult();

        $mensaje= "No se registro la PQR, $error";

        $data=array('error'=>true,'mensaje'=>$mensaje);

    }
    else if($bandera == true){

        $error=$c->getmsgresult();
        $resultado = $c->actualizaQuejaSugerencia($cod_form,$error);

     if ($resultado===true) {
         $mensaje = "Se registro la PQR para el Inmueble $cod_inmueble, con el codigo pqr $error";
         $data = array('error' => false, 'mensaje' => "$mensaje");
     }else {
         $mensaje = "Se registro la PQR, pero se ha producido el siguiente error intentando actualizar la queja y sugerencia: $resultado";
         $data = array('error' => true, 'mensaje' => "$mensaje");
     }

    }

    echo json_encode($data);
}


?>
