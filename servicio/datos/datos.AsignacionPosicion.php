<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/
extract($_POST);

if($tip == "getPosicionesAtencion"){

    require_once "../../clases/class.caja.php";

    $caja = new Caja();
    $datosAtencion = $caja->GetCajasAtencion();

    $data = [];
    while ($fila = oci_fetch_assoc($datosAtencion)){
        $idCaja            = $fila["ID_CAJA"];
        $numeroPosicion    = $fila["NUMERO_POSICION"];
        $usuario           = $fila["USUARIO"];
        $codigoPunto       = $fila["CODIGO_PUNTO"];
        $descripcion       = $fila["DESCRIPCION"];
        $descipcionEntidad = $fila["DESC_ENTIDAD"];

        //BOTONES
        $btnDejarDisponible = "<button class='btnDejarDisponible btn' id='btnDejarDisponible_".$idCaja."'>Dejar disponible</button>";
        $btnAsignarPosicion = "<div class='btn-group dropleft'>
                                  <button type='button' class='btn btn-secondary dropdown-toggle btnAsignarPosicion' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' id='btnAsignarPosicion_".$idCaja."'>
                                    Asignar usuario
                                  </button>
                                  <div class='dropdown-menu' x-placement='left-start'>
                                    <div class='dvAsignarPosicion'>
                                        <h5>$numeroPosicion</h5>
                                        <hr/>
                                        <select class='slcUsuarios form-control' id='slcUsuarios".$idCaja."'></select>
                                        <button class='btn btn-danger' class='btnNoAsignar'>No</button>
                                        <button class='btnAsignar btn btn-success' id='btnAsignar_".$idCaja."'>Sí</button>
                                    </div>
                                  </div>
                              </div>";

        if($usuario == " "){$usuario = "-";}

        $arr = [$idCaja,$numeroPosicion,$usuario,$descripcion,$btnAsignarPosicion];
        /*if($usuario != '-'){
            array_push($arr,$btnDejarDisponible);
        }else{
            array_push($arr,$btnAsignarPosicion);
        }*/
        array_push($data,$arr);
    }
    echo json_encode($data);
}

if($tip == "getUsuarioPorCargo"){
    require_once "../../clases/class.usuario.php";

    $in = '300,301,200,601,3';
    $usuario = new Usuario();
    $dataUsuarios = $usuario->GetUsuariosSinPosicion($in);

    $data = [];
    while($fila = oci_fetch_assoc($dataUsuarios)){

        $idUsuario     = $fila["ID_USUARIO"];
        $nombreUsuario = $fila["NOMBRE_USUARIO"];

        $arr=["ID_USUARIO"=>$idUsuario,"NOMBRE_USUARIO"=>$nombreUsuario];
        array_push($data,$arr);
    }
    echo json_encode($data);
}

if($tip == "asignarPosicion"){
    require_once "../../clases/class.caja.php";

    $caja          = new Caja();
    $datosAsignacion = $caja->AsignarPosicion($idUsuario,$idCaja);

    echo json_encode($datosAsignacion);

}

if($tip == "desasignarPosicion"){
    require_once "../../clases/class.caja.php";

    $caja               = new Caja();
    $datosDesasignacion = $caja->DesasignarPosicion($idCaja);

    echo json_encode($datosDesasignacion);

}