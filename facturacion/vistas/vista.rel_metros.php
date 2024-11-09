<?php
include '../clases/class.reliquidacion.php';
include'../clases/class.facturas.php';
session_start();
$factura = $_GET["factura"];
$coduser = $_SESSION['codigo'];

if(isset($_REQUEST['procesar'])){
    $metros=$_POST["nuevosmetros"];
    $factura=$_POST["factura"];
    $obs=$_POST["observacion"];
    $p=new reliquidacion();
        $resultado=$p->relpormetros($metros,$factura,$obs,$coduser);
        $error=$p->getmsgresult();
        if(!$resultado){
            echo "
    <script type='text/javascript'>
    alert('error $error' );
   	</script>";
            echo 'error';
        }else{
            echo 'exito';
            echo "
    <script type='text/javascript'>
    opener.location.reload();
    window.close();
   	</script>";
        }

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
<?php
    $i=new facturas();
    $registros = $i->detfact2($factura);
    $contador =0;
    while (oci_fetch($registros)) {
        $contador++;
        $valor=oci_result($registros, 'VALOR');
        $periodo=oci_result($registros, 'PERIODO');
        $consumo = oci_result($registros, 'CONSUMO');
	?>
		<div id="content" class="flexigrid" >
		<form  id="FMRelMet" onSubmit="return validacampos();" name="FMRelMet" action="vista.rel_metros.php" method="post">
		<div class="panel panel-primary" style="border-color:#336699">
		<h3 class="panel-heading" style="background-color:#336699;border-color:#336699"><center>Reliquidaci&oacute;n por Metros</center> </h3>
		<div class="panel_mody" ><center></center></div>
		<h3 style="background-color:#336699; font-size:13px; color:white; text-align:center">&nbsp;&nbsp;&nbsp;Datos De La Reliquidaci&oacute;n</h3>
        <table cellpadding="0" cellspacing="0" width="54.8%" align="center" class="table">
            <tr>
                <th class="th">Periodo</th>
                <th class="th">Consumo facturado</th>
                <th class="th">Valor facturado</th>
                <th class="th">Nuevos metros</th>
                <th class="th">Observacion</th>
            </tr>
            
            <tr>
                <td class="tda" align="center">
                    <label class="num" name="periodo"   value="<?php echo $periodo?>" size="25"/>
					<?php echo $periodo?></label>
				</td>
                <td class="tda" align="center">
                    <label class="input" name="consumo" value="<?php echo $consumo?>" size="15" maxlength="13" />
					<?php echo $consumo?></label>
				</td>
                <td class="tda" align="center">
                    <label class="input" type="text" name="valor" value="<?php echo $valor?>" size="15" maxlength="13" >
					<?php echo "<b style='color:#FF0000'>RD$ ".$valor."</b>"?></label>
				</td>
                <td class="tda" align="center">
                    <input class="input" type="float" min="0"  required name="nuevosmetros"  style="width: 150px" placeholder="Ingrese el nuevo consumo"/>
				</td>
                <td class="tda" align="center">
                    <input class="input" type="text" min="0"  required name="observacion"  style="width: 150px" placeholder="Ingrese la observacion"/>
                </td>
            </tr>

            <?php
            }
            ?>
        </table>

        <table cellpadding="0" cellspacing="0" width="54.8%" align="center" class="table">
            <tr>
                <td class="tda" align="center" colspan="4">
                    <button type="submit" name="procesar" id="procesar"
                            style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#336699; color:#336699" class="btn btn btn-INFO">
                        <b>&nbsp;&nbsp;Procesar Reliquidación</b></button></td>
            </tr>
        </table>

        <input type="hidden" name="factura" value="<?php echo $factura?>" />
		</div>
    </form>



</div>
</body>
</html>