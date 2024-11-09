<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
$verificarPermisos = $permisos->VerificaPermisos();
//if ($verificarPermisos==true):
if (true==true): ?>
    <?php
    session_start();
    require'../../clases/classPagos.php';
    include('../../destruye_sesion.php');
//pasamos variables por post
    $coduser = $_SESSION['codigo'];
    $cod_inmueble=$_GET['cod_inmueble'];
    $direccion=$_POST['direccion'];
    $importe1=$_POST['importe1'];
    $monto=$_POST['monto'];
    $medio=$_POST['medio'];
    $vuelta=$_POST['vuelta'];
    $cod_pro=$_POST['cod_pro'];
    $des_pro=$_POST['des_pro'];
    $banco=$_POST['banco'];
    $cheque=$_POST['cheque'];
    $importe2=$_POST['importe2'];
    $tarjeta=$_POST['tarjeta'];
    $numcard=$_POST['numcard'];
    $importe3=$_POST['importe3'];
    $numaproba=$_POST['numaproba'];
    $nom_cli=$_POST['nom_cli'];
    $deuda=$_POST['deuda'];
    $monto=$_POST['monto'];
    $vuelta=$_POST['vuelta'];
    $id_caja=$_POST['id_caja'];
    $num_caja=$_POST['num_caja'];
    $id_punto=$_POST['id_punto'];
    $des_punto=$_POST['des_punto'];
    $cod_ent=$_POST['cod_ent'];
    $des_ent=$_POST['des_ent'];
    $nom_usu=$_POST['nom_cajero'];
    $per_usu=$_POST['per_usu'];
    $favor=$_POST['favor'];
    $fecha=$_POST['fecha'];
    $min=$_POST['min'];
    $observacion=$_POST['observacion'];
    $cod_punto = $_POST['cod_punto'];
    $pago_fac = $_POST['pago_fac'];
    $importe4=$_POST['importe4'];
    ?>
    <!DOCTYPE html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css?4" />
        <script type="text/javascript" src="../../js/sweetalert.min.js?4 "></script>

        <link href="../../css/bootstrap.min.css?4" rel="stylesheet">
        <link rel="stylesheet" href="../../flexigrid/style.css?4" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css?4">
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js?4"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js?4"></script>
        <script type="text/javascript" src="../js/formpag.js?30"></script>
        <link href="../../font-awesome/css/font-awesome.min.css?4" rel="stylesheet" />
        <link href="../css/general.css?4" rel="stylesheet" />
        <script type="text/javascript" src="../../js/jquery.min.js?4>"></script>
    </head>
    <body style="margin-top:-25px" onLoad="document.getElementById('medio').focus();">
    <div id="content" style="padding:0px; width:1120px; margin-left:0px">
        <?php
        $c = new Pagos();
        $resultado = $c->seleccionaUser($coduser);
        while (oci_fetch($resultado)) {
            $num_caja = oci_result($resultado, 'NUM_CAJA');
            $id_punto = oci_result($resultado, 'COD_VIEJO');
            $des_punto = oci_result($resultado, 'DESCRIPCION');
            $cod_ent = oci_result($resultado, 'COD_ENTIDAD');
            $des_ent = oci_result($resultado, 'DESC_ENTIDAD');
            $nom_usu = oci_result($resultado, 'NOMBRE');
            $per_usu = oci_result($resultado, 'PERF');
            $max_dia = oci_result($resultado, 'MAX_DIAS');
            $cod_punto = oci_result($resultado, 'ID_PUNTO_PAGO');
            $pago_fac = oci_result($resultado, 'PAGO_X_FAC');
        }oci_free_statement($resultado);

        if($per_usu == 'AD'){
            $c = new Pagos();
            $resultado = $c->seleccionaUltimaCaja($cod_inmueble);
            while (oci_fetch($resultado)) {
                $num_caja = oci_result($resultado, 'NUM_CAJA');
                $id_punto = oci_result($resultado, 'COD_VIEJO');
                $des_punto = oci_result($resultado, 'DESCRIPCION');
                $cod_ent = oci_result($resultado, 'ENTIDAD_COD');
                $des_ent = oci_result($resultado, 'DESC_ENTIDAD');
            }oci_free_statement($resultado);
        }

        if($fecha == ''){
            //este codigo si no esta cuadrada la hora en el server de php
            $dt = new DateTime();
            $dt->modify("-6 hour");
            $fecha = $dt->format('Y-m-d');
            //////////////////////////////
            //$fecha = date('Y-m-d');
            //$min = date('H:i:s');
            $nuevafecha = strtotime ( '-'.$max_dia.' day' , strtotime ( $fecha ) ) ;
            $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
        }

        if (isset($_REQUEST["procesar"])){
            $cod_inmueble=$_POST['cod_inmueble'];
            $num_caja=$_POST['num_caja'];
            $id_punto=$_POST['id_punto'];
            $des_punto=$_POST['des_punto'];
            $cod_ent=$_POST['cod_ent'];
            $des_ent=$_POST['des_ent'];

            $c= new Pagos();
            $resultado=$c->seleccionaIdCaja($num_caja,$id_punto,$cod_ent);
            if(oci_fetch($resultado)){
                $id_caja=oci_result($resultado, 'ID_CAJA');
            }oci_free_statement($resultado);

            if($medio == 1){
                $importe = $importe1;
                if($monto > 0 && $importe > 0){
                    $referencia = "Pago manual aplicado al inmueble No. ".$cod_inmueble;
                    $origen = 'P';
                    $c = new Pagos();
                    echo $importe1.' - '.$importe2.' - '.$importe3.' - '.$importe4;
                    $bandera = $c->IngresaPago($importe1,$referencia,$id_caja,$cod_inmueble,$origen,$coduser,$monto,$cod_pro,$deuda,$fecha,$pago_fac,$medio,$importe2,$importe3,$banco,$cheque,$numcard,$tarjeta,$numaproba,$observacion);
                    if($bandera == false){
                        $error=$c->getmsgresult();
                        $coderror=$c->getcodresult();
                        ?>
                        <script>
                            swal({
                                title: "Error",
                                text: "No se registro el pago para el inmueble <?php echo $cod_inmueble.'.<br>'?> Codigo: <?php echo $coderror.'.<br>'?> Error: <?php echo $error?>.",
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
                        echo "
                                <script type='text/javascript'>
                                window.open('vista.imprime_recibo.php?cod_inmueble=$cod_inmueble&num_caja=$num_caja&des_punto=$des_punto&des_ent=$des_ent&nom_cli=$nom_cli&fecha=$fecha&deuda=$deuda&monto=$monto&vuelta=$vuelta&medio=$medio&favor=$favor');
                                window.location='vista.tipopagos.php';
                                </script>";
                    }
                    //despues de enviar los datos limpiamos las variables
                    unset($monto,$importe1,$importe,$vuelta,$favor);
                    $medio = 0;
                }
            }
            if($medio == 2){
                $importe = $importe2;
                if($importe > 0){
                    $monto = $importe;
                    $referencia = "Pago manual aplicado al inmueble No. ".$cod_inmueble;
                    $origen = 'P';
                    $c = new Pagos();
                    $bandera = $c->IngresaPago($importe1,$referencia,$id_caja,$cod_inmueble,$origen,$coduser,$monto,$cod_pro,$deuda,$fecha,$pago_fac,$medio,$importe2,$importe3,$banco,$cheque,$numcard,$tarjeta,$numaproba,$observacion);
                    if($bandera == false){
                        $error=$c->getmsgresult();
                        $coderror=$c->getcodresult();
                        ?>
                            <script>
                                swal({
                                    title: "Error",
                                    text: "No se registro el pago para el inmueble <?php echo $cod_inmueble.'.<br>'?> Codigo: <?php echo $coderror.'.<br>'?> Error: <?php echo $error?>.",
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
                        echo "
                                <script type='text/javascript'>
                                window.open('vista.imprime_recibo.php?cod_inmueble=$cod_inmueble&num_caja=$num_caja&des_punto=$des_punto&des_ent=$des_ent&nom_cli=$nom_cli&fecha=$fecha&deuda=$deuda&monto=$monto&vuelta=$vuelta&medio=$medio&favor=$favor');
                                window.location='vista.tipopagos.php';
                                </script>";
                    }
                    //despues de enviar los datos limpiamos las variables
                    unset($monto,$cheque,$importe2,$importe);
                    $medio = 0; $banco = 0;
                }
            }
            if($medio == 3){
                $importe = $importe3;
                if($importe > 0){
                    $monto = $importe;
                    $referencia = "Pago manual aplicado al inmueble No. ".$cod_inmueble;
                    $origen = 'P';
                    $c = new Pagos();
                    $bandera = $c->IngresaPago($importe1,$referencia,$id_caja,$cod_inmueble,$origen,$coduser,$monto,$cod_pro,$deuda,$fecha,$pago_fac,$medio,$importe2,$importe3,$banco,$cheque,$numcard,$tarjeta,$numaproba,$observacion);
                    if($bandera == false){
                        $error=$c->getmsgresult();
                        $coderror=$c->getcodresult();
                        ?>
                        <script>
                                swal({
                                    title: "Error",
                                    text: "No se registro el pago para el inmueble <?php echo $cod_inmueble.'.<br>'?> Codigo: <?php echo $coderror.'.<br>'?> Error: <?php echo $error?>.",
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
                        echo "
                                <script type='text/javascript'>
                                window.open('vista.imprime_recibo.php?cod_inmueble=$cod_inmueble&num_caja=$num_caja&des_punto=$des_punto&des_ent=$des_ent&nom_cli=$nom_cli&fecha=$fecha&deuda=$deuda&monto=$monto&vuelta=$vuelta&medio=$medio&favor=$favor');
                                window.location='vista.tipopagos.php';
                                </script>";
                    }
                    //despues de enviar los datos limpiamos las variables
                    unset($monto,$importe3,$importe,$numcard,$numaproba);
                    $medio = 0; $tarjeta = 0;
                }
            }
            if($medio == 4){
                $importe = $importe4;
                if($importe > 0){
                    $monto = $importe;
                    $referencia = "Pago manual aplicado al inmueble No. ".$cod_inmueble;
                    $origen = 'P';
                    $c = new Pagos();
                    $bandera = $c->IngresaPago($importe4,$referencia,$id_caja,$cod_inmueble,$origen,$coduser,$monto,$cod_pro,$deuda,$fecha,$pago_fac,$medio,$importe2,$importe3,$banco,$cheque,$numcard,$tarjeta,$numaproba,$observacion);
                    if($bandera == false){
                        $error=$c->getmsgresult();
                        $coderror=$c->getcodresult();
                        ?>
                        <script>
                            swal({
                                title: "Error",
                                text: "No se registro el pago para el inmueble <?php echo $cod_inmueble.'.<br>'?> Codigo: <?php echo $coderror.'.<br>'?> Error: <?php echo $error?>.",
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
                        echo "
                               <script type='text/javascript'>
                               window.open('vista.imprime_recibo.php?cod_inmueble=$cod_inmueble&num_caja=$num_caja&des_punto=$des_punto&des_ent=$des_ent&nom_cli=$nom_cli&fecha=$fecha&deuda=$deuda&monto=$monto&vuelta=$vuelta&medio=$medio&favor=$favor');
                               window.location='vista.tipopagos.php';
                               </script>";
                    }
                    //despues de enviar los datos limpiamos las variables
                    unset($monto,$importe4,$importe,$vuelta,$favor);
                    $medio = 0;
                }
            }
            if($medio == 5){
                $importe = ($importe1 + $importe3);
                if($importe > 0){
                    $monto = ($monto + $importe3);
                    $referencia = "Pago manual aplicado al inmueble No. ".$cod_inmueble;
                    $origen = 'P';
                    $c = new Pagos();
                    $bandera = $c->IngresaPago($importe1,$referencia,$id_caja,$cod_inmueble,$origen,$coduser,$monto,$cod_pro,$deuda,$fecha,$pago_fac,$medio,$importe2,$importe3,$banco,$cheque,$numcard,$tarjeta,$numaproba,$observacion);
                    if($bandera == false){
                        $error=$c->getmsgresult();
                        $coderror=$c->getcodresult();
                        ?>
                            <script>
                                swal({
                                    title: "Error",
                                    text: "No se registro el pago para el inmueble <?php echo $cod_inmueble.'.<br>'?> Codigo: <?php echo $coderror.'.<br>'?> Error: <?php echo $error?>.",
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
                        echo "
                                <script type='text/javascript'>
                                window.open('vista.imprime_recibo.php?cod_inmueble=$cod_inmueble&num_caja=$num_caja&des_punto=$des_punto&des_ent=$des_ent&nom_cli=$nom_cli&fecha=$fecha&deuda=$deuda&monto=$monto&vuelta=$vuelta&medio=$medio&favor=$favor');
                                window.location='vista.tipopagos.php';
                                </script>";
                    }
                    //despues de enviar los datos limpiamos las variables
                    unset($monto,$importe1,$importe3,$importe,$numcard,$numaproba);
                    $medio = 0; $tarjeta = 0;
                }
            }
            if($medio == 6){
                $importe = ($importe1 + $importe2);
                if($importe > 0){
                    $monto = ($monto + $importe2);
                    $referencia = "Pago manual aplicado al inmueble No. ".$cod_inmueble;
                    $origen = 'P';
                    $c = new Pagos();
                    $bandera = $c->IngresaPago($importe1,$referencia,$id_caja,$cod_inmueble,$origen,$coduser,$monto,$cod_pro,$deuda,$fecha,$pago_fac,$medio,$importe2,$importe3,$banco,$cheque,$numcard,$tarjeta,$numaproba,$observacion);
                    if($bandera == false){
                        $error=$c->getmsgresult();
                        $coderror=$c->getcodresult();
                        ?>
                        <script>
                            swal({
                                title: "Error",
                                text: "No se registro el pago para el inmueble <?php echo $cod_inmueble.'.<br>'?> Codigo: <?php echo $coderror.'.<br>'?> Error: <?php echo $error?>.",
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
                        echo "
                                <script type='text/javascript'>
                                window.open('vista.imprime_recibo.php?cod_inmueble=$cod_inmueble&num_caja=$num_caja&des_punto=$des_punto&des_ent=$des_ent&nom_cli=$nom_cli&fecha=$fecha&deuda=$deuda&monto=$monto&vuelta=$vuelta&medio=$medio&favor=$favor');
                                window.location='vista.tipopagos.php';
                                </script>";
                    }
                    //despues de enviar los datos limpiamos las variables
                    unset($monto,$importe1,$importe2,$importe,$cheque);
                    $medio = 0; $banco = 0;
                }
            }
        }
        ?>
        <h3 class="panel-heading" style=" background-color:#996833; color:#FFFFFF; font-size:18px; width:1120px" align="center">Ingreso de Pagos</h3>
        <form name="pagos" action="vista.pagos2.php" method="post" onKeyPress="return anular(event)">
            <div class="flexigrid" style="width:100%">
                <div class="mDiv">
                    <div align="left"><b>Datos Oficina De Pago</b></div>
                </div>
            </div>
            <div class="flexigrid" style="width:100%">
                <div class="mDiv">
                    <div style="background: linear-gradient(#F7F7F7,#F7F7F7); border-top:1px solid #ccc;" align="left">
                        <table>
                            <tr>
                                <?php
                                $diaDeLaSemana = strftime("%A");
                                ?>
                                <?php
                                $c = new Pagos();
                                $resultado = $c->obtenerDatosCliente($cod_inmueble);
                                while (oci_fetch($resultado)) {
                                    $cod_inm = oci_result($resultado, 'CODIGO_INM');
                                    $dir_inm = oci_result($resultado, 'DIRECCION');
                                    $urb_inm = oci_result($resultado, 'DESC_URBANIZACION');
                                    $cod_cli = oci_result($resultado, 'CODIGO_CLI');
                                    $nom_cli = oci_result($resultado, 'ALIAS');
                                    $cod_pro = oci_result($resultado, 'ID_PROYECTO');
                                    $des_pro = oci_result($resultado, 'DESC_PROYECTO');
                                    $est_inm = oci_result($resultado, 'ID_ESTADO');
                                    $ali_cli = oci_result($resultado, 'NOMBRE_CLI');
                                    $tip_cli = oci_result($resultado, 'ID_TIPO_CLIENTE');
                                }oci_free_statement($resultado);
                                $direccion = $urb_inm.' '.$dir_inm;
                                if($nom_cli == '') $nom_cli = $ali_cli;
                                if($coduser == '001-1124931-8' || $coduser == '001-0010486-8' || $coduser == '001-1125913-1'){
                                    if($cod_pro == 'SD'){
                                        $bgcolor = '#FF0000';
                                        $color = '#FFFFFF';
                                    }
                                }
                                ?>
                                <td width="150">
                                    Acueducto<br />
                                    <input type="text" id="cod_pro" name="cod_pro" value="<?php echo $cod_pro;?>" size="2" readonly class="input" style=" color:<?php echo $color;?>;background-color:<?php echo $bgcolor;?>" />
                                    <input type="text" name="des_pro" value="<?php echo $des_pro;?>" size="10" readonly class="input" style=" color:<?php echo $color;?>;background-color:<?php echo $bgcolor;?>"/>
                                </td>
                                <td width="150">
                                    Fecha Pago<br />
                                    <input type="date" required name="fecha" value="<?php echo $fecha;?>" size="15" class="input" style="height:20px" min="<?php echo $nuevafecha?>" max="<?php echo $fecha?>"/>
                                </td>
                                <td width="220">
                                    Entidad<br />
                                    <input type="text" required id="cod_ent" name="cod_ent" value="<?php echo $cod_ent;?>" size="2" class="input" />
                                    <input type="text" required id="des_ent" name="des_ent" value="<?php echo $des_ent;?>" size="20" readonly class="input" />
                                </td>
                                <td width="220">
                                    Punto<br />
                                    <input type="text" required id="id_punto" name="id_punto" value="<?php echo $id_punto;?>" size="2" class="input" />
                                    <input type="text" required  id="des_punto" name="des_punto" value="<?php echo $des_punto;?>" size="20" readonly class="input" />
                                </td>
                                <td width="330">
                                    Caja&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cajero<br />
                                    <input type="text" required id="num_caja" name="num_caja" value="<?php echo $num_caja;?>" size="2" class="input"/>
                                    <input type="text" required id="nom_cajero" name="nom_cajero" value="<?php echo $nom_usu;?>" size="40" class="input" readonly />
                                </td>


                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="flexigrid" style="width:100%">
                <div class="mDiv">
                    <div align="left"><b>Datos Inmueble</b></div>
                </div>
            </div>

            <div class="flexigrid" style="width:100%">
                <div class="mDiv">
                    <div style="background: linear-gradient(#F7F7F7,#F7F7F7); border-top:1px solid #ccc;" align="left">
                        <table width="1231">
                            <tr>
                                <td width="80">
                                    C&oacute;digo Sistema<br />
                                    <input readonly class="input" type="text" value="<?php echo $cod_inmueble ?>"  name="cod_inmueble" id="cod_inmueble" placeholder="Ingrese el Inmueble" size="10"/>
                                </td>
                                <td width="160">
                                    Direcci&oacute;n<br />
                                    <input type="text" name="direccion" id="direccion" value="<?php echo $direccion;?>" size="25" readonly class="input"/>
                                </td>
                                <td width="350">
                                    Cliente<br />
                                    <input type="text" name="cod_cli" value="<?php echo $cod_cli;?>" size="7" readonly class="input" />
                                    <input type="text" name="nom_cli" value="<?php echo $nom_cli;?>" size="40" readonly class="input"/>
                                </td>
                                <td width="70">
                                    Acueducto<br />
                                    <input type="text" name="des_pro" value="<?php echo $des_pro;?>" size="9" readonly class="input"/>
                                </td>
                                <td width="72">
                                    Estado<br />
                                    <input type="text" id="est_inm" name="est_inm" value="<?php echo $est_inm;?>" size="5" readonly class="input" />
                                </td>
                                <td width="130">
                                    <?php
                                    $c = new Pagos();
                                    $resultado = $c->obtieneDeuda($cod_inmueble);
                                    while (oci_fetch($resultado)) {
                                        $deuda = oci_result($resultado, 'DEUDA') ;
                                        $facpend = oci_result($resultado, 'FACPEND') ;
                                    }oci_free_statement($resultado);


                                    /*
                                    $c = new Pagos();
                                    $resultado = $c->obtieneDiferidoCorte($cod_inmueble);
                                    while (oci_fetch($resultado)) {
                                        $deuda+= oci_result($resultado, 'VALOR');
                                    }oci_free_statement($resultado);*/

                                    ?>
                                    Total Deuda <font color="#FF0000">(RD$)</font><br />
                                    <input type="text" name="deuda" id="deuda" value="<?php echo $deuda;?>" size="10" maxlength="9" class="input" style="color:#FF0000" onBlur="calculavuelto()"/>
                                </td>
                                <td width="226">
                                    Fact Pendientes<br />
                                    <input type="text" name="facpend" id="facpend" value="<?php echo $facpend;?>" size="5"  readonly class="input" style="color:#FF0000"  />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="flexigrid" style="width:100%; display:block" id="divformapago">
                <div class="mDiv">

                    <iframe id="ifdetfactpend" style="display:block; border-left:-1px solid #ccc; border-right:0px solid #ccc; margin-top:-1px" class="iframe"></iframe><br />

                </div>
            </div>
            <div class="flexigrid" style="width:100%; display:block" id="divformapago">
                <div class="mDiv">
                    <div align="left"><b>Forma de Pago</b></div>
                </div>
            </div>
            <div class="flexigrid" style="width:100%; display:block" id="divdatosformapago">
                <div class="mDiv">
                    <div style="background: linear-gradient(#F7F7F7,#F7F7F7); border-top:1px solid #ccc;" align="left">
                        <table>
                            <tr class="person">
                                <?php
                                $where = " AND INMUEBLE='$cod_inmueble'";
                                $sort = "ORDER BY PERIODO ASC";
                                $h = new Pagos();
                                $resultado = $h->facpend ($where,$sort,1000,0);
                                while (oci_fetch($resultado)) {
                                    $val_fact = oci_result($resultado, 'PENDIENTE') ;

                                    $total_deuda = $total_deuda.$val_fact.' ';

                                }oci_free_statement($resultado);
                                $total_deuda = $total_deuda.'0';
                                ?>
                                <td id="tdarreglofact"  style="display: none">
                                    <input type="text" id="arreglofact" name="arreglofact" value="<?php echo $total_deuda;?>">
                                </td>
                                <td id="tdtipocliente"  style="display: none">
                                    <input type="text" id="tipocliente" name="tipocliente" value="<?php echo $tip_cli;?>">
                                </td>
                                <td id="tdproyecto"  style="display: none">
                                    <input type="text" id="proyecto" name="proyecto" value="<?php echo $cod_pro;?>">
                                </td>
                                <td colspan="1">
                                    Medio de Pago<br />
                                    <?php
                                    if($cod_ent == '901' && $id_punto == '440'){
                                        ?>
                                        <select name='medio' id="medio" class='input' onBlur="oculta();" tabindex="1" >
                                        <!--option value="0" selected>Seleccione medio...</option-->
                                        <?php
                                        $c = new Pagos();
                                        $resultado = $c->seleccionaMedioPagoTeleAgua();
                                        while (oci_fetch($resultado)) {
                                            $cod_medio = oci_result($resultado, 'CODIGO') ;
                                            $des_medio = oci_result($resultado, 'DESCRIPCION') ;
                                            if($cod_medio == $medio) echo "<option value='$cod_medio' selected>$des_medio</option>\n";
                                            else echo "<option value='$cod_medio'>$des_medio</option>\n";
                                        }oci_free_statement($resultado);
                                        ?>
                                        </select>
                                        <?php
                                    }
                                    else{
                                    ?>
                                    <select name='medio' id="medio" class='input' onBlur="oculta();" tabindex="1" >
                                        <option value="0" selected>Seleccione medio...</option>
                                        <?php
                                        $c = new Pagos();
                                        $resultado = $c->seleccionaMedioPago();
                                        while (oci_fetch($resultado)) {
                                            $cod_medio = oci_result($resultado, 'CODIGO') ;
                                            $des_medio = oci_result($resultado, 'DESCRIPCION') ;
                                            if($cod_medio == $medio) echo "<option value='$cod_medio' selected>$des_medio</option>\n";
                                            else echo "<option value='$cod_medio'>$des_medio</option>\n";
                                        }oci_free_statement($resultado);
                                        ?>
                                    </select>
                                    <?php
                                    }
                                    ?>
                                </td>
                                <td style="display:none; width:300px" id="tdpendiente" colspan="1">
                                    Saldo Por Pagar <font color="#FF0000">(RD$)</font><br />
                                    <input type="text" name="pendiente" id="pendiente" value="<?php echo $pendiente;?>" size="15" maxlength="9" readonly class="input" onBlur="calculavuelto()" />
                                </td>
                                <td style="display:none; width:300px" id="tdfavor" colspan="2">
                                    Saldo a Favor <font color="#FF0000">(RD$)</font><br />
                                    <input type="text" name="favor" id="favor" value="<?php echo $favor;?>" size="15" maxlength="9" readonly class="input" />
                                </td>
                                <td align="right" style="display:none;" id="botonpago" >
                                    <button style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#FF8000; color:#FF8000; display:none" type="submit"
                                            name="procesar" id="procesar"class="btn btn btn-INFO"><i class="fa fa-refresh"></i><b>&nbsp;Generar Pago</b>
                                    </button>

                                </td>
                            </tr>
                            <?php $importe1 = $pendiente; ?>
                            <tr class="person">
                                <!--si es efectivo-->
                                <td style="display:none; width:450px" id="tdmonto">
                                    Monto En efectivo <font color="#FF0000">(RD$)</font><br />
                                    <input type="text" name="monto" id="monto" value="<?php echo $monto;?>" size="15" maxlength="9" placeholder="Ingrese monto" class="input"  onChange="habframe()" tabindex="2" />
                                </td>
                                <td style="display:none; width:300px" id="tdimporte1">
                                    Importe En efectivo  <font color="#FF0000">(RD$)</font><br />
                                    <input type="text" name="importe1" id="importe1" value="<?php echo $importe1;?>" size="15" maxlength="9"  class="input" onBlur="calculavuelto()" onChange="habframe()" tabindex="3" />
                                </td>
                                <td style="display:none; width:300px" id="tdvuelta">
                                    De vuelta En efectivo  <font color="#FF0000">(RD$)</font><br />
                                    <input type="text" name="vuelta" id="vuelta" value="<?php echo $vuelta;?>" size="15" maxlength="9" readonly class="input" />
                                </td>
                            </tr>
                            <tr class="person">
                                <!--si es cheque-->
                                <td style="display:none; width:450px" id="tdbanco">
                                    Banco Emisor<br />
                                    <select name='banco' id="banco" class='input' onChange=" habframe();" tabindex="4">
                                        <option value="0" selected>Seleccione banco...</option>
                                        <?php
                                        $c = new Pagos();
                                        $resultado = $c->seleccionaBanco();
                                        while (oci_fetch($resultado)) {
                                            $cod_banco = oci_result($resultado, 'CODIGO');
                                            $des_banco = oci_result($resultado, 'DESCRIPCION');
                                            if($cod_banco == $banco) echo "<option value='$cod_banco' selected>$des_banco</option>\n";
                                            else echo "<option value='$cod_banco'>$des_banco</option>\n";
                                        }oci_free_statement($resultado);
                                        ?>
                                    </select>
                                </td>
                                </td>
                                <td style="display:none; width:300px" id="tdimporte2" >
                                    Importe en cheque <font color="#FF0000">(RD$)</font><br />
                                    <input type="text" name="importe2" id="importe2" value="<?php echo $importe2;?>" size="15" maxlength="9" placeholder="Ingrese importe" class="input"onchange=" habframe();" onBlur="calculavuelto()" tabindex="6"/>
                                </td>
                            </tr>
                            <tr class="person">
                                <!--si es tarjeta-->
                                <td style="display:none; width:350px" id="tdtipo">
                                    Tipo Tarjeta<br />
                                    <select name='tarjeta' id="tarjeta" class='input' onChange=" habframe();" tabindex="4">
                                        <option value="0" selected>Seleccione tarjeta...</option>
                                        <?php
                                        $c = new Pagos();
                                        $resultado = $c->seleccionaTarjeta();
                                        while (oci_fetch($resultado)) {
                                            $cod_tarjeta = oci_result($resultado, 'CODIGO');
                                            $des_tarjeta = oci_result($resultado, 'DESCRIPCION');
                                            if($cod_banco == $banco) echo "<option value='$cod_tarjeta' selected>$des_tarjeta</option>\n";
                                            else echo "<option value='$cod_tarjeta'>$des_tarjeta</option>\n";
                                        }oci_free_statement($resultado);
                                        ?>
                                    </select>
                                </td>
                                <td style="display:none; width:300px" id="tdaproba">
                                    N&deg; Aprobaci&oacute;n<br />
                                    <input type="text" id="numaproba" name="numaproba" value="<?php echo $numaproba;?>" size="10" maxlength="6" placeholder="Ingrese N&deg; aprobaci&oacute;n" class="input" onChange=" habframe();" onBlur="calculavuelto()" tabindex="6"/>
                                </td>
                                <td style="display:none; width:300px" id="tdimporte3">
                                    Importe en tarjeta <font color="#FF0000">(RD$)</font><br />
                                    <input type="number" name="importe3" id="importe3" value="<?php echo $importe3;?>" size="15" maxlength="9" placeholder="Ingrese importe" class="input"onchange=" habframe();" onBlur="calculavuelto()" tabindex="7"/>
                                </td>
                            </tr>
                            <tr class="person">
                                <!--si es transferencia-->
                                <!--td style="display:none; width:450px" id="tdmonto">
                                    Monto En efectivo <font color="#FF0000">(RD$)</font><br />
                                    <input type="text" name="monto" id="monto" value="<?php echo $monto;?>" size="15" maxlength="9" placeholder="Ingrese monto" class="input"  onChange="habframe()" tabindex="2" />
                                </td-->
                                <td style="display:none; width:300px" id="tdimporte4">
                                    Importe En efectivo  <font color="#FF0000">(RD$)</font><br />
                                    <input type="text" name="importe4" id="importe4" value="<?php echo $importe4;?>" size="15" maxlength="9"  class="input" onBlur="calculavuelto()" onChange="habframe()" tabindex="3" />
                                </td>
                                <!--td style="display:none; width:300px" id="tdvuelta">
                                    De vuelta En efectivo  <font color="#FF0000">(RD$)</font><br />
                                    <input type="text" name="vuelta" id="vuelta" value="<?php echo $vuelta;?>" size="15" maxlength="9" readonly class="input" />
                                </td-->
                            </tr>
                            <tr class="person">
                                <td style="display:none; width:300px" id="tdobservacion" colspan="3">
                                    <?php
                                    if($per_usu == 'AD' or $per_usu == 'US'){
                                        ?>
                                        Observaci&oacute;n<br />
                                        <textarea name="observacion" id="observacion" cols="160" rows="4"><?php $observacion;?></textarea>

                                        <?php
                                    }
                                    else{
                                        ?>
                                        Observaci&oacute;n<br />
                                        <textarea name="observacion" id="observacion" cols="160" rows="4" readonly="readonly"><?php $observacion;?></textarea>
                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <input type="hidden" id="per_usu" name="per_usu" value="<?php echo $per_usu;?>"/>
        </form>
    </div>
    <script type="text/javascript">
        inicioFormPago();
    </script>
    </body>
    </html>


<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

