<?php
session_start();
include_once ('../../include.php');
require'../clases/classAperturaZona.php';
require'../clases/class.facturas.php';
include('../../destruye_sesion.php');
$coduser = $_SESSION['codigo'];
$proyecto=$_POST['proyecto'];
$zona=$_POST['zona'];
$periodo=$_POST['periodo'];
//Conectamos con la base de datos
$Cnn = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;
   ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <script src="../../js/jquery.min.js"></script>
    <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js"></script>
	<link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="screen" href="../../css/Static_Tabstrip.css">
    <link href="../../css/css.css" rel="stylesheet" type="text/css" />
    <script>

            $(function() {
                $("#zona").autocomplete({
                    source: "../datos/datos.zona.php?proyecto=<? echo $proyecto?>",
                    minLength: 1,
                    html: true,
                    open: function(event, ui)
                    {
                        $(".ui-autocomplete").css("z-index", 1000);
                    }
                });

            });

            function recarga() {
                //document.rendimiento.ruta.selectedIndex = 0;
                document.FMCierraZona.submit();
            }

            function desabilita() {
                $("#Procesar").hide();
            }

            function habilita() {
                $("#Procesar").show();
            }
	</script>
</head>
<body style="margin-top:-5px" >
<div id="content" style="padding:0px; width:1120px; margin-left:20px">
    <form name="FMCierraZona" action="vista.cierre_periodo.php" autocomplete="on" method="post">
	<h3 class="panel-heading" style=" background-color:#336699; color:#FFFFFF; font-size:18px; width:1120px" align="center">Cierre Zona de Facturaci&oacute;n</h3>
       <div style="text-align:center; width:1120px;">
	    <table width="100%" border="1" bordercolor="#CCCCCC" align="center">
    		<tr>
    			<td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="25%"><b>Acueducto:&nbsp;</b></td>
			    <td align="left" width="25%" bgcolor="#EBEBEB">
				<select name="proyecto" id="proyecto" class="btn btn-default btn-sm dropdown-toggle" required onChange="recarga();" tabindex="1"><option></option>
				<?php
				$sql = "SELECT PR.ID_PROYECTO, PR.SIGLA_PROYECTO FROM SGC_TP_PROYECTOS PR, SGC_TT_PERMISOS_USUARIO PU
				WHERE PR.ID_PROYECTO = PU.ID_PROYECTO AND PU.ID_USUARIO = '$coduser' ORDER BY 2";
				$stid = oci_parse($link, $sql);
				oci_execute($stid, OCI_DEFAULT);
				while (oci_fetch($stid)) {
					$cod_proyecto = oci_result($stid, 'ID_PROYECTO') ;	
					$sigla_proyecto = oci_result($stid, 'SIGLA_PROYECTO') ;
					if($cod_proyecto == $proyecto) echo "<option value='$cod_proyecto' selected>$sigla_proyecto</option>\n";
					else echo "<option value='$cod_proyecto'>$sigla_proyecto</option>\n";
				}oci_free_statement($stid);
				?>									
				</select>
				<?php
				$sql = "SELECT PR.DESC_PROYECTO FROM SGC_TP_PROYECTOS PR
				WHERE PR.ID_PROYECTO = '$proyecto'";
				$stid = oci_parse($link, $sql);
				oci_execute($stid, OCI_DEFAULT);
				while (oci_fetch($stid)) {
					$desc_proyecto = oci_result($stid, 'DESC_PROYECTO') ;	
				}oci_free_statement($stid);
				?>
				</td>
				
				<td width="50%" bgcolor="#EBEBEB" align="left">
				<input type="text" readonly name="desc_proyecto" required value="<?php echo $desc_proyecto?>" class="form-control"/>
				</td>
			</tr>
			<tr>
    			<td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="25%"><b>Zona:&nbsp;</b></td>
				<td align="left" width="25%" bgcolor="#EBEBEB">
				<input type="text" id="zona" name="zona" required value="<? echo $zona?>" class="form-control" style="background-color:#FFFFFF" maxlength="3" onblur="recarga();" tabindex="2">
				</td>
				<?php
				if ($zona != ""){
					$desc_zona = 'Zona '.$zona;
					$sql = "SELECT TO_CHAR(ADD_MONTHS(TO_DATE(MAX(PERIODO),'YYYYMM'),1),'YYYYMM')MAXPER FROM SGC_TP_PERIODO_ZONA
					WHERE ID_ZONA = '$zona' AND FEC_CIERRE IS NOT NULL";
					$stid = oci_parse($link, $sql);
					oci_execute($stid, OCI_DEFAULT);
					while (oci_fetch($stid)) {
						$maxperiodo = oci_result($stid, 'MAXPER') ;	
					}oci_free_statement($stid);
					$periodo = $maxperiodo ;
					$agno = substr($periodo,0,4);
					$mes = substr($periodo,4,2);
					if($mes == '01'){$mes = Enero;} if($mes == '02'){$mes = Febrero;} if($mes == '03'){$mes = Marzo;} if($mes == '04'){$mes = Abril;}
					if($mes == '05'){$mes = Mayo;} if($mes == '06'){$mes = Junio;} if($mes == '07'){$mes = Julio;} if($mes == '08'){$mes = Agosto;}
					if($mes == '09'){$mes = Septiembre;} if($mes == '10'){$mes = Octubre;} if($mes == '11'){$mes = Noviembre;} if($mes == '12'){$mes = Diciembre;}
					$desc_periodo = $mes." ".$agno
					?>
					<td width="50%" bgcolor="#EBEBEB" align="left">
					<input type="text" required readonly name="desc_zona" value="<? echo $desc_zona?>" class="form-control" >
					</td>
					<?
				}
				else{
					?>
					<td width="50%" bgcolor="#EBEBEB" align="left">
						<input type="text" required readonly name="desc_zona" value="<? echo $desc_zona?>" class="form-control" >
					</td>
					<?php
				}
				?>
			</tr>
			<tr>
				<td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="25%"><b>Periodo:&nbsp;</b></td>
				<td align="left" width="25%" bgcolor="#EBEBEB">
					<input type="text" required  name="periodo" value="<? echo $periodo?>" class="form-control" maxlength="6" tabindex="3">
				</td>
				<td width="50%" bgcolor="#EBEBEB" align="left">
					<input type="text" readonly required name="desc_periodo" value="<?php echo $desc_periodo?>" class="form-control" >
				</td>
			</tr>
		</table>
	 
  	<p><center>
	<input type="submit" onclick="desabilita();" value="Procesar" name="Procesar" id="Procesar" class="btn btn-primary btn-lg" style="background-color:#336699;border-color:#336699"></center></p>
	<input type="hidden" name="proc" value="<?php echo $proc;?>">	
	</div>
   	</form>
</div>
</body>	
</html>

<?php
if (isset($_REQUEST["Procesar"])){
	echo "
    <script type='text/javascript'>
     desabilita();
   	</script>";

	    $i = new facturas();
                $result = $i->cierrezona($zona,$periodo);
                $mserror=$i->getmsgresult();
                IF($result==false){
                	
                	echo "
                	<script type='text/javascript'>
                	if(confirm('$mserror'))
                	{
                	habilita();
                	window.close();
                	}else
                	{
                    habilita();
                	window.close();
                	}
                	
                	</script>";
                }
                else{
                    echo "
                    <script type='text/javascript'>
                        if(confirm('se ha cerrado satifactoriamente la zona'))
                        {
                        habilita();
                            window.close();
                        }else
                        {
                            habilita();
                            window.close();
                        }

                    </script>";


            }
        }
?>