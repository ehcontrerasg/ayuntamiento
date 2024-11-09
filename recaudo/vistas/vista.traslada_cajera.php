<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include '../clases/class.admin_pagos.php';
    include '../../destruye_sesion.php';
    $coduser = $_SESSION['codigo'];
    $caja_inicial=$_POST['caja_inicial'];
    $nueva_caja=$_POST['nueva_caja'];

    $tname="SGC_TP_ENTIDAD_PAGO EP, SGC_TP_PUNTO_PAGO PP, SGC_TP_CAJAS_PAGO CP, SGC_TT_USUARIOS U, SGC_TP_CARGOS CAR";
    $where="EP.COD_ENTIDAD(+) = PP.ENTIDAD_COD
                        AND CP.ID_USUARIO (+) = U.ID_USUARIO
                        AND PP.ID_PUNTO_PAGO(+) = CP.ID_PUNTO_PAGO
                        AND PP.ACTIVO  (+) = 'S'
                        AND CP.PRIVADA (+) IN ('S')
                        AND CP.TIPO_ATENCION (+) IN ('C','A')
                        AND U.ID_CARGO=CAR.ID_CARGO(+) AND
                        CAR.ID_AREA IN (9,4) and u.ID_USUARIO not in (
    select u.ID_USUARIO
    from SGC_TT_USUARIOS u , SGC_TP_CAJAS_PAGO c
    where u.ID_USUARIO=c.ID_USUARIO and c.PRIVADA='N')
    AND U.FEC_FIN IS NULL";
    $sort = "ORDER BY EP.COD_ENTIDAD, PP.ID_PUNTO_PAGO, CP.NUM_CAJA ASC";
    ?>

    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="../../js/bootstrap.min.js"></script>
        <link href="../../css/bootstrap.min.css" rel="stylesheet">

        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css?4" />
        <script type="text/javascript" src="../../js/sweetalert.min.js?4 "></script>
        <title>ACEASOFT</title>
    </head>


    <body>
    <div>
        <?php
        if (isset($_REQUEST["procesar"])){
            $datos = explode('&',$caja_inicial);
            $cajai = $datos[0];
            $usuarioi = $datos[1];
            $datos2 = explode('&',$nueva_caja);
            $cajaf = $datos2[0];
            $usuariof = $datos2[1];

            //echo $cajai.' * '.$usuarioi.' * '.$cajaf.' * '.$usuariof;
            $c = new AdminstraPagos();
            $bandera = $c->ActualizaCajeras($cajai, $usuarioi, $cajaf, $usuariof);
            if($bandera == false){
                $error=$c->getmsgresult();
                $coderror=$c->getcodresult();
                ?>
                <script>
                    swal({
                        title: "Error",
                        text: "No se realizo el cambio de cajera <br> Codigo: <?php echo $coderror.'.<br>'?> Error: <?php echo $error?>.",
                        type: "error",
                        showCancelButton: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "OK!",
                        cancelButtonText: "No!",
                        showLoaderOnConfirm: true,
                        closeOnConfirm: false,
                        closeOnCancel: false,
                        html: true
                    });
                </script>
            <?php
            }
            else if($bandera == true){
            ?>
                <script>
                    swal({
                            title: "Mensaje!!",
                            text: "Cambio Realizado",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonColor: "#009900",
                            confirmButtonText: "OK!",
                            cancelButtonText: "No!",
                            showLoaderOnConfirm: true,
                            closeOnConfirm: false,
                            closeOnCancel: false,
                            html: true
                        },
                        function(isConfirm)
                        {
                            if (isConfirm)
                            {
                                window.close();
                            }
                        });
                </script>
                <?php
            }
        }
        ?>
        <p>
            <form id='trasladacajera' action="vista.traslada_cajera.php" autocomplete="on" method="post" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="panel panel-primary" style="border-color:#FF8000">
                    <h3 class="panel-heading" style="background-color:#FF8000;border-color:#FF8000;font-size:18px;"><center>Traslado de Cajeras</center> </h3>
                    <div class="panel_mody" ><center></center></div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
        <p>
        <div class="input-group input-group-sm">
            <span class="input-group-addon" width="250" >Seleccione cajera 1</span>
            <span class="input-group-addon">
							<select name="caja_inicial" id="caja_inicial">
								<option value="0" selected>Seleccione cajera...</option>
                                <?php
                                $c = new AdminstraPagos();
                                $resultado = $c->listadoCajerasParaIntercambio();

                                while (oci_fetch($resultado)) {
                                    $idcaja = oci_result($resultado, 'ID_CAJA');
                                    $entidad = oci_result($resultado, 'COD_ENTIDAD');
                                    $punto = oci_result($resultado, 'COD_VIEJO');
                                    $caja = oci_result($resultado, 'NUM_CAJA');
                                    $cajera = oci_result($resultado, 'NOMBRE');
                                    $area = oci_result($resultado, 'AREA');
                                    $descripcion = oci_result($resultado, 'DESCRIPCION');
                                    $id_usuario = oci_result($resultado, 'ID_USUARIO');
                                    if($idcaja == $cajera_inicial) echo "<option value='$idcaja&$id_usuario' >$idcaja - $descripcion - CAJA $caja - $area - $cajera</option>\n";
                                    else echo "<option value='$idcaja&$id_usuario'>$idcaja - $descripcion - CAJA $caja - $area - $cajera</option>\n";
                                }oci_free_statement($resultado);
                                ?>
							</select>
                <!--input size='300' style='text-transform:uppercase' id='fecha' name='fecha' required type='date' class='form-control' value='<?php //echo $fecha?>'  width='14' height='10'-->
							</span>
        </div>
        </p>
        <p>
        <div class="input-group input-group-sm">
            <span class="input-group-addon" width="300" >Seleccione cajera 2</span>
            <span class="input-group-addon">
							<select name="nueva_caja" id="nueva_caja">
								<option value="0" selected>Seleccione cajera...</option>
                                <?php
                                $c = new AdminstraPagos();
                                $resultado = $c->listadoCajerasParaIntercambio();

                                while (oci_fetch($resultado)) {
                                    $idcaja2 = oci_result($resultado, 'ID_CAJA');
                                    $caja2 = oci_result($resultado, 'NUM_CAJA');
                                    $cajera2 = oci_result($resultado, 'NOMBRE');
                                    $area = oci_result($resultado, 'AREA');
                                    $descripcion2 = oci_result($resultado, 'DESCRIPCION');
                                    $id_usuario2 = oci_result($resultado, 'ID_USUARIO');
                                    if($idcaja2 == $nueva_caja) echo "<option value='$idcaja2&$id_usuario2' >$idcaja2 - $descripcion2 - CAJA $caja2 - $area- $cajera2</option>\n";
                                    else echo "<option value='$idcaja2&$id_usuario2'>$idcaja2 - $descripcion2 - CAJA $caja2 - $area - $cajera2</option>\n";
                                }oci_free_statement($resultado);
                                ?>
							</select>
                <!--input size='300' style='text-transform:uppercase' id='fecha' name='fecha' required type='date' class='form-control' value='<?php //echo $fecha?>'  width='14' height='10'-->
							</span>
        </div>
        </p>
    </div>
    <p>
    <center>
        <input type="submit" id="procesar" name="procesar" value="Actualizar" class="btn btn-primary btn-lg" style="background-color:#FF8000;border-color:#FF8000">
    </center>
    </p>
    </div>
    </div>
    </form>
    </p>
    </div>
    </body>
    </html>
    <script typ="text/javascript">
        opener.top.jobFrame.location.reload()
    </script>





<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

