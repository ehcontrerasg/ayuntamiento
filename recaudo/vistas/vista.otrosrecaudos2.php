<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    require'../clases/classPagos.php';
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
    $banco=$_POST['banco'];
    $cheque=$_POST['cheque'];
    $importe2=$_POST['importe2'];
    $tarjeta=$_POST['tarjeta'];
    $numcard=$_POST['numcard'];
    $importe3=$_POST['importe3'];
    $numaproba=$_POST['numaproba'];
    $est_inm=$_POST['est_inm'];
    $concepto=$_POST['concepto'];
    $nom_cli=$_POST['nom_cli'];
    $id_caja=$_POST['id_caja'];
    $num_caja=$_POST['num_caja'];
    $id_punto=$_POST['id_punto'];
    $des_punto=$_POST['des_punto'];
    $cod_ent=$_POST['cod_ent'];
    $des_ent=$_POST['des_ent'];
    $nom_usu=$_POST['nom_cajero'];
    $per_usu=$_POST['per_usu'];
    $fecha=$_POST['fecha'];
    $min=$_POST['min'];
    $cod_punto = $_POST['cod_punto'];
    $pago_fac = $_POST['pago_fac'];
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
        <script type="text/javascript" src="../../js/sweetalert.min.js "></script>

        <link href="../../css/bootstrap.min.css?2" rel="stylesheet">
        <link rel="stylesheet" href="../../flexigrid/style.css?2" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css?2">
        <script src="../../js/ajax.js?2"></script>
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js?2"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js?2"></script>
        <link href="../css/pagosgen.css?2" rel="stylesheet" />
        <script type="text/javascript" src="../js/formOtrosRec.js?2"></script>
        <link href="../../font-awesome/css/font-awesome.min.css?2" rel="stylesheet" />
        <script type="text/javascript" src="../../js/jquery.min.js?2"></script>
    </head>
    <body style="margin-top:-25px" onload="document.getElementById('medio').focus();">
    <div id="content" style="padding:0px; width:1120px; margin-left:0px">
        <?php
        $c = new Pagos();
        $resultado = $c->seleccionaUser($coduser);
        while (oci_fetch($resultado)) {
            $id_caja = oci_result($resultado, 'ID_CAJA');
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

            $valores = explode(" ", $concepto);
            $a1 = $valores[0]; // valores1
            $c = new Pagos();
            $resultado = $c->obtenerDescServicio($a1);
            while (oci_fetch($resultado)) {
                $servicio = oci_result($resultado, 'DESC_SERVICIO');
            }oci_free_statement($resultado);
        if($medio == 1){
            $importe = $importe1;
        if($monto > 0 && $importe > 0){
            $referencia = "";
            $c = new Pagos();
            $bandera = $c->IngresaOtroRecaudo($cod_pro,$a1,$importe,$coduser,$referencia,$cod_inmueble,$id_caja,$fecha);
        if($bandera == false){
            $error=$c->getmsgresult();
            $coderror=$c->getcodresult();
            ?>
            <script>
                swal({
                    title: "Error",
                    text: "No se registro el recaudo para el inmueble <?php echo $cod_inmueble.'.<br>'?> Codigo: <?php echo $coderror.'.<br>'?> Error: <?php echo $error?>.",
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
            $c = new Pagos();
            $resultado = $c->seleccionaIdRec($cod_inmueble);
            while (oci_fetch($resultado)) {
                $id_rec = oci_result($resultado, 'ID_REC');
            }oci_free_statement($resultado);

            $c = new Pagos();
            $resultado = $c->IngresaMedioRecaudo($medio,$importe,$id_rec);
            echo "
					<script type='text/javascript'>
					window.open('vista.imprime_recibo_rec.php?cod_inmueble=$cod_inmueble&id_rec=$id_rec&num_caja=$num_caja&des_punto=$des_punto&des_ent=$des_ent&nom_cli=$nom_cli&fecha=$fecha&monto=$monto&vuelta=$vuelta&medio=$medio&concepto=$concepto&importe1=$importe1&importe2=$importe2&importe3=$importe3');
					window.location='vista.inc_fianzas.php';
					</script>";
        }
        //despues de enviar los datos limpiamos las variables
        unset($monto,$importe1,$importe);
        $medio = 0;
        }
        }
        if($medio == 2){
        $importe = $importe2;
        if($importe > 0){
        $referencia = "";
        $c = new Pagos();
        $bandera = $c->IngresaOtroRecaudo($cod_pro,$a1,$importe,$coduser,$referencia,$cod_inmueble,$id_caja,$fecha);
        if($bandera == false){
        $error=$c->getmsgresult();
        $coderror=$c->getcodresult();
        ?>
            <script>
                swal({
                    title: "Error",
                    text: "No se registro el recaudo para el inmueble <?php echo $cod_inmueble.'.<br>'?> Codigo: <?php echo $coderror.'.<br>'?> Error: <?php echo $error?>.",
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
            $c = new Pagos();
            $resultado = $c->seleccionaIdRec($cod_inmueble);
            while (oci_fetch($resultado)) {
                $id_rec = oci_result($resultado, 'ID_REC');
            }oci_free_statement($resultado);
            $c = new Pagos();
            $resultado = $c->IngresaMedioRecaudo($medio,$importe,$id_rec);
            $c = new Pagos();
            $resultado = $c->seleccionaIdMedRec($id_rec);
            while (oci_fetch($resultado)) {
                $id_med_rec = oci_result($resultado, 'ID_MEDIO_RECAUDO');
            }oci_free_statement($resultado);
            $c = new Pagos();
            $resultado = $c->IngresaDetMedioRecaudo('1',$banco,$id_med_rec);
            $c = new Pagos();
            $resultado = $c->IngresaDetMedioRecaudo('4',$cheque,$id_med_rec);
            echo "
					<script type='text/javascript'>
					window.open('vista.imprime_recibo_rec.php?cod_inmueble=$cod_inmueble&id_rec=$id_rec&num_caja=$num_caja&des_punto=$des_punto&des_ent=$des_ent&nom_cli=$nom_cli&fecha=$fecha&monto=$monto&vuelta=$vuelta&medio=$medio&concepto=$concepto&importe1=$importe1&importe2=$importe2&importe3=$importe3');
					window.location='vista.inc_fianzas.php';
					</script>";
        }
        //despues de enviar los datos limpiamos las variables
        unset($cheque,$importe2,$importe);
        $medio = 0; $banco = 0;
        }
        }
        if($medio == 3){
        $importe = $importe3;
        if($importe > 0){
        $referencia = "";
        $c = new Pagos();
        $bandera = $c->IngresaOtroRecaudo($cod_pro,$a1,$importe,$coduser,$referencia,$cod_inmueble,$id_caja,$fecha);
        if($bandera == false){
        $error=$c->getmsgresult();
        $coderror=$c->getcodresult();
        ?>
            <script>
                swal({
                    title: "Error",
                    text: "No se registro el recaudo para el inmueble <?php echo $cod_inmueble.'.<br>'?> Codigo: <?php echo $coderror.'.<br>'?> Error: <?php echo $error?>.",
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
            $c = new Pagos();
            $resultado = $c->seleccionaIdRec($cod_inmueble);
            while (oci_fetch($resultado)) {
                $id_rec = oci_result($resultado, 'ID_REC');
            }oci_free_statement($resultado);
            $c = new Pagos();
            $resultado = $c->IngresaMedioRecaudo($medio,$importe,$id_rec);
            $c = new Pagos();
            $resultado = $c->seleccionaIdMedRec($id_rec);
            while (oci_fetch($resultado)) {
                $id_med_rec = oci_result($resultado, 'ID_MEDIO_RECAUDO');
            }oci_free_statement($resultado);
            $c = new Pagos();
            $resultado = $c->IngresaDetMedioRecaudo('3',$numcard,$id_med_rec);
            $c = new Pagos();
            $resultado = $c->IngresaDetMedioRecaudo('6',$tarjeta,$id_med_rec);
            $c = new Pagos();
            $resultado = $c->IngresaDetMedioRecaudo('7',$numaproba,$id_med_rec);
            echo "
					<script type='text/javascript'>
					window.open('vista.imprime_recibo_rec.php?cod_inmueble=$cod_inmueble&id_rec=$id_rec&num_caja=$num_caja&des_punto=$des_punto&des_ent=$des_ent&nom_cli=$nom_cli&fecha=$fecha&monto=$monto&vuelta=$vuelta&medio=$medio&concepto=$concepto&importe1=$importe1&importe2=$importe2&importe3=$importe3');
					window.location='vista.inc_fianzas.php';
					</script>";
        }
        //despues de enviar los datos limpiamos las variables
        unset($monto,$importe3,$importe,$numcard,$numaproba);
        $medio = 0; $tarjeta = 0;
        }
        }
        if($medio == 4){
        }
        if($medio == 5){
        $importe = ($importe1 + $importe3);
        if($importe > 0){
        $referencia = "";
        $c = new Pagos();
        $bandera = $c->IngresaOtroRecaudo($cod_pro,$a1,$importe,$coduser,$referencia,$cod_inmueble,$id_caja,$fecha);
        if($bandera == false){
        $error=$c->getmsgresult();
        $coderror=$c->getcodresult();
        ?>
            <script>
                swal({
                    title: "Error",
                    text: "No se registro el recaudo para el inmueble <?php echo $cod_inmueble.'.<br>'?> Codigo: <?php echo $coderror.'.<br>'?> Error: <?php echo $error?>.",
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
            $c = new Pagos();
            $resultado = $c->seleccionaIdRec($cod_inmueble);
            while (oci_fetch($resultado)) {
                $id_rec = oci_result($resultado, 'ID_REC');
            }oci_free_statement($resultado);
            $c = new Pagos();
            $resultado = $c->IngresaMedioRecaudo('1',$importe1,$id_rec);
            $c = new Pagos();
            $resultado = $c->IngresaMedioRecaudo('3',$importe3,$id_rec);
            $c = new Pagos();
            $resultado = $c->seleccionaIdMedRec($id_rec);
            while (oci_fetch($resultado)) {
                $id_med_rec = oci_result($resultado, 'ID_MEDIO_RECAUDO');
            }oci_free_statement($resultado);
            $c = new Pagos();
            $resultado = $c->IngresaDetMedioRecaudo('3',$numcard,$id_med_rec);
            $c = new Pagos();
            $resultado = $c->IngresaDetMedioRecaudo('6',$tarjeta,$id_med_rec);
            $c = new Pagos();
            $resultado = $c->IngresaDetMedioRecaudo('7',$numaproba,$id_med_rec);
            echo "
					<script type='text/javascript'>
					window.open('vista.imprime_recibo_rec.php?cod_inmueble=$cod_inmueble&id_rec=$id_rec&num_caja=$num_caja&des_punto=$des_punto&des_ent=$des_ent&nom_cli=$nom_cli&fecha=$fecha&monto=$monto&vuelta=$vuelta&medio=$medio&concepto=$concepto&importe1=$importe1&importe2=$importe2&importe3=$importe3');
					window.location='vista.inc_fianzas.php';
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
        $referencia = "";
        $c = new Pagos();
        $bandera = $c->IngresaOtroRecaudo($cod_pro,$a1,$importe,$coduser,$referencia,$cod_inmueble,$id_caja,$fecha);
        if($bandera == false){
        $error=$c->getmsgresult();
        $coderror=$c->getcodresult();
        ?>
            <script>
                swal({
                    title: "Error",
                    text: "No se registro el recaudo para el inmueble <?php echo $cod_inmueble.'.<br>'?> Codigo: <?php echo $coderror.'.<br>'?> Error: <?php echo $error?>.",
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
            $c = new Pagos();
            $resultado = $c->seleccionaIdRec($cod_inmueble);
            while (oci_fetch($resultado)) {
                $id_rec = oci_result($resultado, 'ID_REC');
            }oci_free_statement($resultado);
            $c = new Pagos();
            $resultado = $c->IngresaMedioRecaudo('1',$importe1,$id_rec);
            $c = new Pagos();
            $resultado = $c->IngresaMedioRecaudo('2',$importe2,$id_rec);
            $c = new Pagos();
            $resultado = $c->seleccionaIdMedRec($id_rec);
            while (oci_fetch($resultado)) {
                $id_med_rec = oci_result($resultado, 'ID_MEDIO_RECAUDO');
            }oci_free_statement($resultado);
            $c = new Pagos();
            $resultado = $c->IngresaDetMedioRecaudo('1',$banco,$id_med_rec);
            $c = new Pagos();
            $resultado = $c->IngresaDetMedioRecaudo('4',$cheque,$id_med_rec);
            echo "
					<script type='text/javascript'>
					window.open('vista.imprime_recibo_rec.php?cod_inmueble=$cod_inmueble&id_rec=$id_rec&num_caja=$num_caja&des_punto=$des_punto&des_ent=$des_ent&nom_cli=$nom_cli&fecha=$fecha&monto=$monto&vuelta=$vuelta&medio=$medio&concepto=$concepto&importe1=$importe1&importe2=$importe2&importe3=$importe3');
					window.location='vista.inc_fianzas.php';
					</script>";
        }
            //despues de enviar los datos limpiamos las variables
            unset($monto,$importe1,$importe2,$importe,$cheque);
            $medio = 0; $banco = 0;
        }
        }
        }


        ?>
        <h3 class="panel-heading" style=" background-color:#FF8000; color:#FFFFFF; font-size:18px; width:1120px" align="center">Ingreso de Otros Recaudos</h3>
        <form name="pagos" action="vista.otrosrecaudos2.php" method="post" onKeyPress="return anular(event)">
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
                                <td width="150">
                                    Fecha Pago<br />
                                    <input type="date" required name="fecha" value="<?php echo $fecha;?>" size="15" class="input" style="height:20px" min="<?php echo $nuevafecha?>" max="<?php echo $fecha?>"/>
                                </td>
                                <td width="220">
                                    Entidad<br />
                                    <input type="text" required name="cod_ent" id="cod_ent" value="<?php echo $cod_ent;?>" size="2"  class="input" />
                                    <input type="text" required name="des_ent" id="des_ent" value="<?php echo $des_ent;?>" size="20" readonly class="input" />
                                </td>
                                <td width="220">
                                    Punto<br />
                                    <input type="text" required name="id_punto" id="id_punto" value="<?php echo $id_punto;?>" size="2"  class="input" />
                                    <input type="text" required name="des_punto" id="des_punto" value="<?php echo $des_punto;?>" size="20" readonly class="input" />
                                </td>
                                <td width="330">
                                    Caja&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cajero<br />
                                    <input type="text" required name="num_caja" id="num_caja" value="<?php echo $num_caja;?>" size="2" class="input" />
                                    <input type="text" required name="nom_cajero" id="nom_cajero" value="<?php echo $nom_usu;?>" size="40" class="input" readonly />
                                </td>
                                <?php
                                $c = new Pagos();
                                $resultado = $c->obtenerDatosCliente($cod_inmueble);
                                while (oci_fetch($resultado)) {
                                    $cod_inm = oci_result($resultado, 'CODIGO_INM');
                                    $dir_inm = oci_result($resultado, 'DIRECCION');
                                    $urb_inm = oci_result($resultado, 'DESC_URBANIZACION');
                                    $cod_cli = oci_result($resultado, 'CODIGO_CLI');
                                    $nom_cli = oci_result($resultado, 'ALIAS');
                                    $est_inm = oci_result($resultado, 'ID_ESTADO');
                                    $sec_act = oci_result($resultado, 'DESC_ACTIVIDAD');
                                    $des_uso = oci_result($resultado, 'ID_USO');
                                    $cod_pro = oci_result($resultado, 'ID_PROYECTO');
                                    $des_pro = oci_result($resultado, 'DESC_PROYECTO');
                                    $ali_cli = oci_result($resultado, 'NOMBRE_CLI');
                                }oci_free_statement($resultado);
                                $direccion = $urb_inm.' '.$dir_inm;
                                if($nom_cli == '') $nom_cli = $ali_cli;
                                $c = new Pagos();
                                $resultado = $c->obtenerDatosDiametro($cod_inmueble);
                                while (oci_fetch($resultado)) {
                                    $diametro = oci_result($resultado, 'COD_DIAMETRO');
                                }oci_free_statement($resultado);
                                $c = new Pagos();
                                $resultado = $c->obtenerDatosCalibre($cod_inmueble);
                                while (oci_fetch($resultado)) {
                                    $calibre = oci_result($resultado, 'COD_CALIBRE');
                                }oci_free_statement($resultado);
                                if($calibre == '') $calibre = 0;

                                $c = new Pagos();
                                $resultado = $c->obtenerValorReconex($calibre, $diametro, $des_uso);
                                while (oci_fetch($resultado)) {
                                    $tarifa_reco = oci_result($resultado, 'VALOR_TARIFA');
                                }oci_free_statement($resultado);
                                ?>
                                <td width="150">
                                    Acueducto<br />
                                    <input type="text" name="cod_pro" value="<?php echo $cod_pro;?>" size="2" readonly class="input" id="cod_pro" />
                                    <input type="text" name="des_pro" value="<?php echo $des_pro;?>" size="10" readonly class="input" />
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
                                <td width="163">

                                    C&oacute;digo Sistema<br />
                                    <input readonly class="input" type="text" value="<?php echo $cod_inmueble ?>"  name="cod_inmueble" id="cod_inmueble" placeholder="Ingrese el Inmueble" />

                                </td>

                                <td width="214"><input type="hidden" id="tarifa_reco" name="tarifa_reco" value="<?php echo $tarifa_reco;?>" />
                                    Direcci&oacute;n<br />
                                    <input type="text" name="direccion" id="direccion" value="<?php echo $direccion;?>" size="30" readonly class="input"/>
                                </td>
                                <td width="418">
                                    Cliente<br />
                                    <input type="text" name="cod_cli" value="<?php echo $cod_cli;?>" size="10" readonly class="input" />
                                    <input type="text" name="nom_cli" value="<?php echo $nom_cli;?>" size="50" readonly class="input"/>
                                </td>
                                <td width="95">
                                    Estado<br />
                                    <input type="text" id="est_inm" name="est_inm" value="<?php echo $est_inm;?>" size="10" readonly class="input" />
                                </td>
                                <td width="70">
                                    Uso<br />
                                    <input type="text" name="des_uso" id="des_uso" value="<?php echo $des_uso;?>" size="5" readonly class="input"/>
                                </td>


                                <td width="322">
                                    Acueducto<br />
                                    <input type="text" name="des_pro" value="<?php echo $des_pro;?>" size="9" readonly class="input"/>
                                </td>
                            </tr>
                            <?php
                            // if($est_inm == 'SS' || $est_inm == 'PC'){
                            ?>
                            <!--tr>
						  	<td colspan="6">
							<font size="3px" color="#FF0000">Valor Reconexion RD$ <?php echo $tarifa_reco?></font>
						  </tr-->
                            <?php
                            //}
                            ?>
                        </table>

                    </div>
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
                                <td width="350">
                                    Medio de Pago<br />
                                    <select name='medio' id="medio" class='input' onblur="oculta();habframe();" tabindex="1">
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
                                </td>
                                <td width="300" style="display:none" id="tdconcepto">
                                    Concepto<br />
                                    <!--select id="concepto" onChange="load(this.value)" name="concepto" class="input"-->
                                    <select id="concepto" name="concepto" class="input" onchange="valida_conceptos();" tabindex="2">
                                        <option value="0" selected>Seleccione concepto...</option>
                                        <?php
                                        $c = new Pagos();
                                        $resultado = $c->seleccionaConcepto2();
                                        while (oci_fetch($resultado)) {
                                            $cod_servicio = oci_result($resultado, 'COD_SERVICIO') ;
                                            $des_servicio = oci_result($resultado, 'DESC_SERVICIO') ;
                                            if($cod_servicio == $concepto) echo "<option value='$cod_servicio $des_uso $cod_pro' selected>$des_servicio</option>\n";
                                            else echo "<option value='$cod_servicio $des_uso $cod_pro'>$des_servicio</option>\n";
                                        }oci_free_statement($resultado);
                                        ?>
                                    </select>
                                </td>
                                <td width="300">
                                    <!--div id="myDiv">
                                    </div-->
                                </td>
                                <td align="right" style="display:none;" id="botonpago" >
                                    <button style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#FF8000; color:#FF8000; display:none" type="submit"
                                            name="procesar" id="procesar"class="btn btn btn-INFO"><i class="fa fa-refresh"></i><b>&nbsp;Ingresar Recaudo</b>
                                    </button>

                                </td>
                            </tr>
                            <tr class="person">
                                <!--si es efectivo-->
                                <td style="display:none; width:450px" id="tdmonto">
                                    Precio <font color="#FF0000">(RD$)</font><br />
                                    <input type="text" name="monto" id="monto" value="<?php echo $monto;?>" size="15" maxlength="9" placeholder="Ingrese monto" class="input" onchange="habframe()" onblur="calculavuelto()" tabindex="3" />
                                </td>
                                <td style="display:none; width:300px" id="tdimporte1">
                                    Importe <font color="#FF0000">(RD$)</font><br />
                                    <input type="text" name="importe1" id="importe1" value="<?php echo $importe1;?>" size="15" maxlength="9" placeholder="Ingrese importe" class="input" onchange="habframe()" onblur="calculavuelto()" tabindex="4"/>
                                </td>
                                <td style="display:none; width:300px" id="tdvuelta">
                                    De vuelta <font color="#FF0000">(RD$)</font><br />
                                    <input type="text" name="vuelta" id="vuelta" value="<?php echo $vuelta;?>" size="15" maxlength="9" readonly class="input" />
                                </td>
                            </tr>
                            <tr class="person">
                                <!--si es cheque-->
                                <td style="display:none; width:450px" id="tdbanco">
                                    Banco Emisor<br />
                                    <select name='banco' id="banco" class='input' onchange=" habframe();" tabindex="3">
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
                                <td style="display:none; width:300px" id="tdcheque">
                                    N&deg; Cheque<br />
                                    <input type="text" id="cheque" name="cheque" value="<?php echo $cheque;?>" size="20" maxlength="18" placeholder="Ingrese N&deg; cheque" class="input" onchange=" habframe();" tabindex="4"/>
                                </td>
                                <td style="display:none; width:300px" id="tdimporte2" >
                                    Importe <font color="#FF0000">(RD$)</font><br />
                                    <input type="text" name="importe2" id="importe2" value="<?php echo $importe2;?>" size="15" maxlength="9" placeholder="Ingrese importe" class="input"onchange=" habframe();" onblur="calculavuelto()" tabindex="5"/>
                                </td>
                            </tr>
                            <tr class="person">
                                <!--si es tarjeta-->
                                <td style="display:none; width:350px" id="tdtipo">
                                    Tipo Tarjeta<br />
                                    <select name='tarjeta' id="tarjeta" class='input' onchange=" habframe();" tabindex="3">
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
                                <td style="display:none; width:300px" id="tdnumero">
                                    N&deg; Tarjeta<br />
                                    <input type="text" id="numcard" name="numcard" value="<?php echo $numcard;?>" size="20" maxlength="16" placeholder="Ingrese N&deg; tarjeta" class="input" onchange=" habframe();" tabindex="4" />
                                </td>
                                <td style="display:none; width:300px" id="tdimporte3">
                                    Importe <font color="#FF0000">(RD$)</font><br />
                                    <input type="text" name="importe3" id="importe3" value="<?php echo $importe3;?>" size="15" maxlength="9" placeholder="Ingrese importe" class="input" onchange="habframe();" onblur="calculavuelto()" tabindex="5" />
                                </td>
                                <td  style="display:none; width:300px" id="tdaproba">
                                    N&deg; Aprobaci&oacute;n<br />
                                    <input type="text" id="numaproba" name="numaproba" value="<?php echo $numaproba;?>" size="20" maxlength="18" placeholder="Ingrese N&deg; aprobaci&oacute;n" class="input" onchange=" habframe();" tabindex="6"/>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <input type="hidden" id="per_usu" name="per_usu" value="<?php echo $per_usu;?>"/>
        </form>

        <!--iframe id="ifdetfactpend" style="display:none; border-left:0px solid #ccc; border-right:0px solid #ccc; margin-top:-1px" class="iframe"></iframe><br /></div-->
        <script type="text/javascript">
            inicioFormOtrosRec();
        </script>
    </body>
    </html>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

