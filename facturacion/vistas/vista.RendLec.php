<?php
session_start();
include_once ('../../include.php');
include '../../destruye_sesion.php';

$coduser = $_SESSION['codigo'];
 $fecini = $_POST['fecini'];
 $fecfin = $_POST['fecfin'];
$proyecto = $_POST['proyecto'];

//Conectamos con la base de datos
$Cnn = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" media="screen" href="../../css/Static_Tabstrip.css">
<link rel="stylesheet" href="../../flexigrid/style.css" />
<link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
<script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
<script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
<script language="javascript">
	$(document).ready(function() {
		$(".botonExcel").click(function(event) {
			$("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).append( $("#flex1").eq(0).clone()).html());
			$("#FormularioExportacion").submit();
	});
	});
</script>
</head>
<body>
<form name="repdiario" action="vista.RendLec.php" method="post">
<div class="flexigrid" style="width:100%">
	<div class="mDiv">
    	<div><b>Facturacion</b> >> Reporte Lecturas >> Parametros de Busqueda</div>
        	<div style="background-color:rgb(255,255,255)">
        		<table width="100%">
    				<tr>
    					<td>Fecha Inicial:</td>
            			<td>
	            			<input type="date" name="fecini" id="fecini" value="<?php echo $fecini;?>">
            			</td>
                        <td>Fecha Final:</td>
            			<td>
	            			<input type="date" name="fecfin" id="fecfin" value="<?php echo $fecfin;?>">
            			</td>

                        <td>
                            <select name="proyecto" class='btn btn-default btn-sm dropdown-toggle'  onChange='recarga();'>
                                <option value="" selected>Seleccione acueducto...</option>
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
                        </td>
            			
    					<td>
                        	<input type="submit" value="Buscar" name="Buscar" class="boton">
                        </td>
					</tr>
                </table>
        </div>
    </div>
</div>
</form>
<?php
if(isset($_REQUEST['Buscar'])){
?>
<form action="../../funciones/ficheroExcel.php?agno=<? echo $fecfin;?>&mes=<? echo $fecini;?>&nomrepo=<? echo 'facturacion';?>" method="post" target="_blank"  id="FormularioExportacion">
	<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
    <div class="flexigrid" style="width:100%">
		<div class="mDiv">
    		<div>Exportar a:</div>
            <div style="background-color:rgb(255,255,255)">
            	<img src="../../images/excel/xls.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="Excel"/>
                <img src="../../images/excel/pdf.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="PDF"/>
            </div>    	
    	</div>
	</div>  	
</form>
<div>
<table id="Exportar_a_Excel" style="display:none">
	<tr>
        <th align="center">No</th>
        <th align="center">USUARIO</th>
		<th align="center">LOGIN</th>
		<th align="center">RUTA</th>
        <th align="center">INICIO</th>
        <th align="center">FINAL</th>
        <th align="center">CANTIDAD</th>
        
    </tr>
</table>
<table id="flex1" style="display:none">
</table>
</div>
<script type="text/javascript">
<!--
  $('.flexme1').flexigrid();
  $('.flexme2').flexigrid({height:'auto',striped:false});
  $("#flex1").flexigrid	(
	{
			url: '../datos/datos.RendLec.php?proyecto=<?php echo $proyecto;?>&fecini=<?php echo $fecini;?>&fecfin=<?php echo $fecfin;?>',
			dataType: 'json',
			colModel : [
				{display: 'No', name: 'numero', width: 79, sortable: true, align: 'center'},
				{display: 'Usuario', name: 'sector', width: 131, sortable: true, align: 'center'},
				{display: 'Login', name: 'ruta', width: 166, sortable: true, align: 'center'},
				{display: 'Ruta', name: 'nombre', width: 102, sortable: true, align: 'center'},
				{display: 'Inicio', name: 'documento', width: 171, sortable: true, align: 'center'},
				{display: 'Final', name: 'telfono', width:154 , sortable: true, align: 'center'},
				{display: 'Cantidad', name: 'fecha', width: 53, sortable: true, align: 'center'}
				],	
			searchitems : [	
				{display: 'C\xf3digo', name: 'id_usuario',isdefault: true},
				],
			sortname: "USR.LOGIN",
			sortorder: "ASC",
			usepager: true,
			title: 'Listado Operarios Por Ruta',
			useRp: true,
			rp: 1000,
			page: 1,			
			showTableToggleBtn: true,
			width: 960,
			height: 358
			}
			);
			//FUNCION PARA ABRIR UN POPUP
			var popped = null;
			function popup(uri, awid, ahei, scrollbar) {
				popped = window.open(uri);
				/*var params;
				if (uri != "") {
					if (popped && !popped.closed) {
						popped.location.href = uri;
						popped.focus();
					} 
					else {
						params = "toolbar=no,width=" + awid + ",height="+ ahei +",directories=no,status=no,scrollbars=" + scrollbar + ",menubar=no,resizable=no,location=no";								
						popped = window.open(uri, "popup", params);
					}
				}*/
			}
				
			 function popup2(uri, awid, ahei, scrollbar) {
                var params;
                if (uri != "") {
                    if (popped && !popped.closed) {
                        popped.location.href = uri;
                        popped.focus();
                    } 
                    else {
                        params = "toolbar=no,width=" + awid + ",height="+ ahei +",directories=no,status=no,scrollbars=" + scrollbar + ",menubar=no,resizable=no,location=no";                               
                        popped = window.open(uri, "popup", params);
                    }
                }
            }	
			function upCliente(ruta,inicio,fin,usr) { // Traer la fila seleccionada
			  	popup("vista.detalle_rutalec_operario.php?cod_ruta="+ruta+"&fecini="+inicio+"&fecfin="+fin+"&usuario="+usr,1100,800,'yes');
			}		
			function ruteo(ruta,inicio,fin,usr) { // Traer la fila seleccionada
				variable=""+usr;
	
			  	popup2("vista.detalle_rutalec_google.php?cod_ruta="+ruta+"&fecini="+inicio+"&fecfin="+fin+"&usuario="+usr,1100,800,'yes');
			}		
//-->		
</script>
<?php
}
?>
</body>
</html>
<script type="text/javascript" language="javascript">	


function rend(){
	if (document.rendimiento.periodo.value == "") {
		document.rendimiento.proc.value=0;
		return false;	
	}
	else { 
		document.rendimiento.proc.value = 1; 
		return true; 
	}
}

function recarga() {
    //document.rendimiento.ruta.selectedIndex = 0;
    document.rendimiento.submit();
  }

</script>