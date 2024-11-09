<?php
/**
 * Created by PhpStorm.
 * User: Ehcontrerasg
 * Date: 7/18/2016
 * Time: 12:14 PM
 */

/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/

$tipo = $_POST['tip'];
session_start();
$cod=$_SESSION['codigo'];

if($tipo=='sess'){
    $segundos = 1800; //si pasa este tiempo se detecta la inactividad del usuario en el sitio
    if(($_SESSION['tiempo']+$segundos) < time()) {
        session_destroy();
        echo "false";
    }else{
        $_SESSION['tiempo']=time();
        echo "true";
    }
}

if($tipo=='obtDat'){
    include_once '../../clases/class.inmueble.php';
    $contrato=$_POST["contrato"];
    $l=new Inmueble();
    $datos = $l->getInmuByContrato($contrato);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


if($tipo=='cambUsu') {
    include_once'../../clases/class.contrato.php';
    $documento =    $_POST['documento'];
    $alias =   $_POST['alias'];
    $inmueble =   $_POST['inmueble'];
    $contrato =   $_POST['contrato'];

    $i = new Contrato();
    $result = $i->cambiaUsuContrato($contrato,$documento,$cod,$alias) ;
    if($result){
        $miArray=array("error"=>$i->getMesrror(), "cod"=>$i->getCoderror(),"res"=>"true");
    }else{
        $miArray=array("error"=>$i->getMesrror(), "cod"=>$i->getCoderror(),"res"=>"false");
    }
    echo json_encode($miArray);

}

if ($tipo == "getDatosCliente") {
    require_once "../../clases/class.cliente.php";
    $idContrato = $_POST["id_contrato"];
    $classCliente = new Cliente();
    $statement = $classCliente->getDatosClientePorIdContrato($idContrato);
    
    $json = array();
    while ($fila = oci_fetch_assoc($statement)) {
        array_push($json, array(
            'inmueble' => $fila['INMUEBLE'],
            'codigo_cliente' => $fila['CODIGO_CLIENTE'],
            'email' => $fila['EMAIL'],
            'email2' => $fila['EMAIL2'],
            'nombre_cliente' => $fila['NOMBRE_CLIENTE'],
            'telefono' => $fila['TELEFONO'],
            'celular' => $fila['CELULAR'],
            'documento' => $fila['DOCUMENTO'],
            'id_proyecto' => $fila['ID_PROYECTO'],
            'direccion' => $fila['DIRECCION'],
            'alias' => $fila['ALIAS'],
            'tipo_documento' => $fila['TIPO_DOCUMENTO'],
            'contribuyente_dgii' => $fila['CONTRIBUYENTE_DGII'],
            'grupo' => $fila['GRUPO'],
            'correspondencia' => $fila['CORRESPONDENCIA'],
            'dir_correspondencia' => $fila['DIR_CORRESPONDENCIA']
        ));
    }

    echo json_encode($json);
}

if ($tipo == "getTiposDocumentos") {
    require_once '../clases/class.tipodoc.php';
    $classDocumento = new Documento();
    $statement = $classDocumento->Todos();
    $json = array();
    while ($fila = oci_fetch_assoc($statement)) {
        array_push($json,array(
            'id_tipo_doc' => $fila['ID_TIPO_DOC'],
            'descripcion_tipo_doc' => $fila['DESCRIPCION_TIPO_DOC']
        ));
    }
    echo json_encode($json);
}

if ($tipo == "getGrupos") {
    require_once '../clases/class.grupo.php';
    $classGrupo = new Grupo();
    $statement = $classGrupo->Todos();
    $json = array();
    while ($fila = oci_fetch_assoc($statement)) {
        array_push($json,array(
            'cod_grupo' => $fila["COD_GRUPO"],
            'desc_grupo' => $fila["DESC_GRUPO"]
        ));
    }

    echo json_encode($json);
}

if ($tipo == "getClientePorDocumento") {
    require_once "../../clases/class.cliente.php";
    $documento = $_POST["documento"];
    $codigoInmueble = $_POST["codigo_inmueble"];
    $classCliente = new Cliente();
    $statement = $classCliente->getDatosClientePorDocumento($documento, $codigoInmueble);

    $json = array();
    while ($fila = oci_fetch_assoc($statement)) {
        array_push($json, array(
            'inmueble' => $fila["INMUEBLE"],
            'codigo_cliente' => $fila["CODIGO_CLIENTE"],
            'email' => $fila["EMAIL"],
            'email2' => $fila["EMAIL2"],
            'nombre_cliente' => $fila["NOMBRE_CLIENTE"],
            'sigla_proyecto' => $fila["SIGLA_PROYECTO"],
            'telefono' => $fila["TELEFONO"],
            'celular' => $fila["CELULAR"],
            'documento' => $fila["DOCUMENTO"],
            'id_proyecto' => $fila["ID_PROYECTO"],
            'direccion' => $fila["DIRECCION"],
            'alias' => $fila["ALIAS"],
            'tipo_documento' => $fila["TIPO_DOCUMENTO"],
            'contribuyente_dgii' => $fila["CONTRIBUYENTE_DGII"],
            'grupo' => $fila["GRUPO"],
            'correspondencia' => $fila["CORRESPONDENCIA"],
            'dir_correspondencia' => $fila["DIR_CORRESPONDENCIA"]
        ));        
    }
    echo json_encode($json);
}

if ($tipo == "cambioNombreContrato") {
    extract($_POST);
    require_once '../../clases/class.contrato.php';
    $classContrato = new Contrato();
    $codigo = null;
    $mensaje = null;

    $classContrato->cambioNombreContrato($id_contrato, $cod, $alias, $nombre, $direccion, $telefono, $email,$tipo_documento, $documento,$grupo, $direccion_corresponcencia, $correspondencia, $contribuyente_dgii,$codigo, $mensaje);
    
    $respuesta = array(
        'codigo' => $codigo,
        'mensaje' => $mensaje
    );

    echo json_encode($respuesta);
}
