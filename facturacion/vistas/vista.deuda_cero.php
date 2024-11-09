<?php
session_start();
$codinmueble = $_GET["codinmueble"];
$servicios = $_GET["servicios"];
$total = $_GET["total"];
$mora = $_GET["mora"];
$cuotas = $_GET["cuotas"];
$coduser = $_SESSION['codigo'];
$valCuota= $_GET["deuCerInpValCuo"];
$numCuotas=$_GET["deuCerInpNumCuo"];
$totApl=   $_GET["deuCerInpApo"];
?>

<!DOCTYPE html>
<head>
    <html lang="es">
    <meta  charset=UTF-8" />
    <script type="text/javascript" src="../../alertas/dialog_box.js"></script>
    <script type="text/javascript" src="../js/deuda_cero.js?3"></script>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
    <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
    <link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        iframe{
            border-color: #666666;
            border:solid;
            border-width:0px ;
            display: block;
            float: left;
            width: 530px;
            height: 60px;
        }

        #compl_pdc{
            width: 540px;
            height: 170px;
            float:right;
        }
        .table{
            border:1px solid #ccc;
            border-left:0px solid #ccc;
        }
        .th{
            background: #fafafa url(../../flexigrid/css/images/fhbg.gif) repeat-x bottom;
            height:24px;
            border:0px solid #ccc;
            border-left:1px solid #ccc;
            font-size:11px;
            font-weight:normal;
            font-family: Arial, Helvetica, sans-serif;
        }
        .tda{

            height:24px;
            border:0px solid #ccc;
            border-left:1px solid #ccc;
            padding:0px;
            font-size:11px;
            font-weight:normal;
            font-family: Arial, Helvetica, sans-serif;
        }
        .input{
            border:0px solid #ccc;
            font-family: Arial, Helvetica, sans-serif;
            font-size:11px;
            font-weight:normal;
        }
        .select{
            border:0px solid #ccc;
            font-family: Arial, Helvetica, sans-serif;
            font-size:11px;
            font-weight:normal;
        }
        .btn-info{
            color:#fff;
            background-color:#5bc0de;
            border-color:#46b8da;
        }
        .btn{
            display:inline-block;
            padding:6px 12px;
            margin-bottom:0;
            font-size:14px;
            font-weight:400;
            line-height:1.42857143;
            text-align:center;
            white-space:nowrap;
            vertical-align:middle;
            cursor:pointer;
            -webkit-user-select:none;
            -moz-user-select:none;
            -ms-user-select:none;
            user-select:none;
            background-image:none;
            border:1px solid transparent;
            border-radius:4px
        }
    </style>

</head>
<body>

<form action="vista.deuda_cero.php" method="post">
<script language="javascript">
	function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}
	</script>
	<div id="content" class="flexigrid" >
		<?php
		if (isset($_REQUEST["procesar"])){
			include '../clases/class.deuda_cero.php';
		
			$f=new deuda_cero();
			$respdc=$f->aplicaPDC($codinmueble,$servicios,$mora,$cuotas,$coduser,$totApl,$numCuotas);
			if($respdc==true)
			{
				?>
				<meta http-equiv="Refresh" content="5 ; URL=vista.consulta.php">
				<?php echo "
				<script type='text/javascript'>
				showDialog('PDC Generado Satisfactoriamente','Se ha generado el PDC para el inmueble $codinmueble','success',6);
				</script>";
			}
			else{
				if($codinmueble == '') $mserror .= 'El codigo del inmueble no puede ser vacio.<br>';
				if($mora == '') $mserror .= 'El valor de la mora no puede ser vacio.<br>';
				if($servicios == '') $mserror .= 'El valor de los servicios no puede ser vacio.';
				echo "
				<script type='text/javascript'>
				swal({
                            title: 'Error Generando PDC',
                            text: '1 El inmueble N°; $codinmueble no cumple con los requisitos para aplicar el PDC.<br> $mserror<br><br> Por favor comuniquese con el área de facturación.<br>',
                            confirmButtonColor: '#DD6B55',
                            confirmButtonText: 'Ok!',
                            type: 'error',
                            html: true,
                            showLoaderOnConfirm: true,
                            closeOnConfirm: false},
            function(isConfirm){
                if (isConfirm) {
                    location.reload();
                }
            });

				</script>";
			}
		}
		else{
			$i=new deuda_cero();
			$bandera=$i->comprobarDeudaCero($codinmueble);
			if($bandera==false){
				$mserror=$i->getmsgresult();

				echo "
				<script type='text/javascript'>
				swal({
                            title: 'Error Generando PDC',
                            text: '1 El inmueble N°; $codinmueble no cumple con los requisitos para aplicar el PDC.<br> $mserror<br><br> Por favor comuniquese con el área de facturación.<br>',
                            confirmButtonColor: '#DD6B55',
                            confirmButtonText: 'Ok!',
                            showLoaderOnConfirm: true,
                            type: 'error',
                            html: true,
                            closeOnConfirm: false},
            function(isConfirm){
                if (isConfirm) {
                    location.reload();
                }
            });
				</script>";
			}
			else{
				$i=new deuda_cero();
				$mora=$i->obtiene_mora($codinmueble);
				$servicios=$i->obtiene_servicios($codinmueble);
				$total=$mora+$servicios;
				$cuotas=24;
				?>
				<div class="mDiv">
				</div>
				<div style="display: block;float: left">
					<table id="flex1" style="display:none">
					</table>
				</div>
				<script type="text/javascript">
				<!--
				$('.flexme1').flexigrid();
				$('.flexme2').flexigrid({height:'auto',striped:false});
				$("#flex1").flexigrid	(
				{
					url: '../datos/datos.datos_pdc.php?codinmueble=<?php echo $codinmueble;?>',
					
					dataType: 'json',
					colModel : [
						{display: 'No', name: 'rnum', width:20,  align: 'center'},
						{display: 'Mora', name: 'mora', width: 80, sortable: true, align: 'center'},
						{display: 'Servicios', name: 'servicios', width: 80, sortable: true, align: 'center'},
						{display: 'Total', name: 'total', width: 80, sortable: true, align: 'center'},
						{display: 'Cuotas', name: 'cuotas', width: 80, sortable: true, align: 'center'},
					],
					width: 420,
					height: 87
				});
				//-->
				</script>

        </table>
        <table cellpadding="0" cellspacing="0" width="54.8%" align="right" class="table">
            <tr>
                <th class="th">Valor Aporte</th>
                <th class="th">Número Cuotas</th>
                <th class="th">Valor Cuota</th>
            </tr>
            <tr>
                <td class="tda" align="center">
                    <input class="input" type="text" id="deuCerInpApo" name="deuCerInpApo"  size="25" value="<?php echo $totApl?>"  maxlength="70" placeholder="Ingrese el  Aporte"/></td>
                <td class="tda" align="center">
                    <input class="input" type="text" id="deuCerInpNumCuo" readonly name="deuCerInpNumCuo" value="<?php echo $numCuotas?>"   size="15" maxlength="13" placeholder="Ingrese El numero de cuotas"/></td>
                <td class="tda" align="center">
                    <input class="input" type="text" id="deuCerInpValCuo" readonly name="deuCerInpValCuo" value="<?php echo $valCuota?>"  size="12" maxlength="12" placeholder="Ingrese el valor de la cuota"/></td>
            </tr>
        </table>

            <table cellpadding="0" cellspacing="0" width="54.8%" align="right" class="table">
                <tr>
                    <th class="th">Nombre</th>
                    <th class="th">C&eacute;dula</th>
                    <th class="th">En calidad de</th>
                    <th class="th">Tel&eacute;fono</th>
                </tr>
                <tr>
                    <td class="tda" align="center">
                        <input class="input" type="text" name="nompdc" value="<?php echo $nompdc?>" size="25" maxlength="70" placeholder="Ingrese Nombre"/></td>
                    <td class="tda" align="center">
                        <input class="input" type="text" name="cedpdc" value="<?php echo $cedpdc?>" size="15" maxlength="13" placeholder="Ingrese C&eacute;dula"/></td>
                    <td class="tda" align="center">
                        <select name='calpdc' class="select" >
                            <option value="" style="color:#CCCCCC;" disabled selected>Seleccione En Calidad...</option>
                            <option value="I">Inquilino</option>
                            <option value="R">Representante</option>
                        </select></td>
                    <td class="tda" align="center">
                        <input class="input" type="text" name="telpdc" value="<?php echo $telpdc?>" size="12" maxlength="12" placeholder="Ingrese Tel&eacute;fono"/></td>
                </tr>

				<table cellpadding="0" cellspacing="0" width="54.8%" align="right" class="table">
					<tr>
						<td class="tda" align="center" colspan="4" style="color:#FF0000">
							<b>NOTA: Diligencie estos campos, solo si el cliente que firma el PDC, es una persona diferente a <?php echo $nom_cli?>.</b></td>
					</tr>
				</table>
				<table cellpadding="0" cellspacing="0" width="54.8%" align="right" class="table">
					<tr>
						<td class="tda" align="center" colspan="4">
						<input type="submit" value="Generar PDC" name="procesar" id="procesar"
						style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#336699; color:#336699" class="btn btn btn-INFO"						class="fa fa-upload" /></td>
					</tr>
				</table>
				<input type="hidden" name="codinmueble" value="<?php echo $codinmueble?>" />
				<input type="hidden" name="servicios" value="<?php echo $servicios?>" />
				<input type="hidden" name="mora" value="<?php echo $mora?>" />
				<input type="hidden" name="total" value="<?php echo $total?>" />
				<input type="hidden" name="cuotas" value="<?php echo $cuotas?>" />


>

				<?php
			}
		}
		?>
	</div>

<script>
    deuCerInicio();
</script>
   </form>
</body>
</html>