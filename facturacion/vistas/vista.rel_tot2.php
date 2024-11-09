<?php

include '../clases/class.reliquidacion.php';
include'../clases/class.facturas.php';
session_start();
$coduser = $_SESSION['codigo'];
if($_GET["codinmueble"]){
    $codinmueble = $_GET["codinmueble"];
    $p=new facturas();
    $perini = $p->minper($codinmueble);
    $perfin = $p->maxper($codinmueble);
}
if($_POST['codinmueble']){
    $codinmueble = $_POST["codinmueble"];
    $perini = $_POST["perini"];
    $perfin = $_POST["perfin"];
    $obs=$_POST["observacion"];
}
if(isset($_REQUEST['procesar'])){
    $p=new reliquidacion();
	$result=$p->reltot2($codinmueble,$obs,$coduser);
    $mserror=$p->getmsgresult();
    if(!$result){
    	echo "
    	<script type='text/javascript'>
    	alert('error $mserror' );
   		</script>";
    }
    echo "
    <script type='text/javascript'>
    opener.location.reload();
    window.close();
   	</script>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/html">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link href="../../css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="../../alertas/dialog_box.js"></script>
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
			text-align:center;
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
			text-align:center;
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
        tr{
            border-width: 1px;
        }
    </style>
</head>
<body>
<div id="content" class="flexigrid" >
    <form  id="FMRelConc" name="FMRelConc" action="vista.rel_tot2.php" method="post" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="panel panel-primary" style="border-color:#336699">
			<h3 class="panel-heading" style="background-color:#336699;border-color:#336699"><center>Reliquidaci&oacute;n a Cero</center> </h3>
			<div class="panel_mody" ><center></center></div>
			<h3 style="background-color:#336699; font-size:13px; color:white; text-align:center">&nbsp;&nbsp;&nbsp;Datos De La Reliquidaci&oacute;n</h3>
        	<table cellpadding="0" cellspacing="0" width="54.8%" align="center" class="table">
				<tr>
                	<th class="th">Periodo Inicio</th>
                	<th class="th">Periodo Fin</th>
            	</tr>
            	<tr>
                	<td class="tda" align="center">
                    <input class="input" type="number" min="0" readonly  max="<?php echo $perfin?>" onChange="javascript:FMRelConc.submit();" value="<?php echo $perini?>"  name="perini"  style="width: 150px" placeholder="Ingrese el valor a reliquidar"/></td>
					<td class="tda" align="center">
                    <input class="input" type="number" readonly min="<?php echo $perini?>" onChange="javascript:FMRelConc.submit();" value="<?php echo $perfin?>"  name="perfin"  style="width: 150px" placeholder="Ingrese el valor a reliquidar"/></td>
            	</tr>
        	</table>

        	<table cellpadding="0" cellspacing="0" width="54.8%" align="center" class="table">
				<tr>
					<th class="th">Concepto</th>
					<th class="th">Número  facturas</th>
					<th class="th">Deuda</th>
				</tr>
				<tr>
				<?php
				$i=new facturas();
				$registros = $i->estcon2($perini,$perfin,$codinmueble);
				$contador =0;
				$totaldeuda=0;
				while (oci_fetch($registros)) {
					$valor = oci_result($registros, 'VALOR');
					if ($valor>0) {
	
						$concept = oci_result($registros, 'CONCEPTO');
						$numfact = oci_result($registros, 'NUMFAC');
						$idconc = oci_result($registros, 'COD_SERVICIO');
						$totaldeuda += $valor;
						$contador++;
	
						?>
                        <td class="tda" align="center">
                            <label class="num" name="<?php echo 'con' . $contador?>" value="<?php echo $concept?>" size="25" maxlength="70" placeholder="Ingrese Nombre"/>
								<?php echo $concept?>
							</label>
						</td>
                        <td class="tda" align="center">
                            <label class="input" type="text" name="<?php echo 'numf' . $contador?>" size="15" maxlength="13"/>
								<?php echo $numfact?>
							</label>
						</td>
                        <td class="tda" align="center">
                            <label class="input" type="text" name="<?php echo 'valt' . $contador?>" value="<?php echo $valor?>" size="15" maxlength="13">
								<?php echo "<b style='color:#FF0000'>RD$ ".$valor."</b>"?>
							</label>
						</td>
                    </tr>
                <?php
                }
            }
            ?>
        </table>

        <table cellpadding="0" cellspacing="0" width="54.8%" align="center" class="table">
            <tr>
                <th class="th"><b>Total Deuda</b></th>
                <th class="th"><?php echo "<b style='color:#FF0000'>RD$ ".$totaldeuda."</b>"?></th>
            </tr>
            <tr>
                <th class="th"><b>Observcion</b></th>
                <th class="th"><?php echo "<input class=\"input\" type=\"text\" min=\"0\"  required name=\"observacion\"  style=\"width: 150px\" placeholder=\"Ingrese la observacion\"/>"?></th>
            </tr>
        </table>

        <table cellpadding="0" cellspacing="0" width="54.8%" align="center" class="table">
            <tr>
                <td class="tda" align="center" colspan="4">
                    <button type="submit" name="procesar" id="procesar"
                            style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#336699; color:#336699" class="btn btn btn-INFO">
                        <b>&nbsp;&nbsp;Procesar Reliquidación</b></button></td>
            </tr>
        </table>
        <input type="hidden" name="codinmueble" value="<?php echo $codinmueble?>" />
		</div>
    </form>



</div>
</body>
</html>