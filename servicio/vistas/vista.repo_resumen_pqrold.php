<?php
session_start();
include '../clases/classPqrs.php';
include '../../destruye_sesion.php';

$coduser = $_SESSION['codigo'];
$proyecto = $_POST['proyecto'];
$zonini = $_POST['zonini'];
$zonfin = $_POST['zonfin'];
$secini = $_POST['secini'];
$secfin = $_POST['secfin'];
$rutini = $_POST['rutini'];
$rutfin = $_POST['rutfin'];
$tipo_resol = $_POST['tipo_resol'];
$tipo_estado = $_POST['tipo_estado'];
$ofiini = $_POST['ofiini'];
$ofifin = $_POST['ofifin'];
$motivo = $_POST['motivo'];
$fecinirad = $_POST['fecinirad'];
$fecfinrad = $_POST['fecfinrad'];
$fecinires = $_POST['fecinires'];
$fecfinres = $_POST['fecfinres'];
$recini = $_POST['recini'];
$recfin = $_POST['recfin'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" media="screen" href="../../css/Static_Tabstrip.css?0">
<link rel="stylesheet" href="../../flexigrid/style.css?0" />
<link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css?0">
<link rel="stylesheet" href="../../css/tablas_catastro.css?0">
<link href="../../css/bootstrap.min.css?0" rel="stylesheet">
<link href="../../css/css.css?0" rel="stylesheet" type="text/css" />
<link href="../../font-awesome/css/font-awesome.min.css?0" rel="stylesheet" />
<script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js?0"></script>
<script type="text/javascript" src="../../flexigrid/flexigrid.js?0"></script>
<style type="text/css">
.input{
	border:1px solid #ccc;
    font-family: Arial, Helvetica, sans-serif;
    font-size:11px;
	height:16px;
    font-weight:normal;
}
.inputn{
	border:1px solid #ccc;
    font-family: Arial, Helvetica, sans-serif;
    font-size:11px;
	height:16px;
	width:40px;
    font-weight:normal;
}
.inputm{
	border:1px solid #ccc;
    font-family: Arial, Helvetica, sans-serif;
    font-size:11px;
	height:16px;
	width:80px;
    font-weight:normal;
}
</style>

</head>
<body>
<form name="reporte_hisfac" action="vista.repo_resumen_pqr.php" method="post" >
<h3 class="panel-heading" style=" background-color:#000040; color:#FFFFFF; font-size:18px; width:1120px; margin-top:-5px" align="center">Reporte Resumen PQRs</h3>
<div class="flexigrid" style="width:1120px">
	<div class="mDiv">
    	<div>Filtros de B&uacute;squeda Reporte Resumen PQRs</div>
        <div style="background-color:rgb(255,255,255)">
        	<table width="100%">
    			<tr>
    				<td width="15%" style=" border:1px solid #EEEEEE; text-align:center">Acueducto<br />
						<select name="proyecto" class="input"><option></option>
						<?php
						$l=new PQRs();
						$registros=$l->seleccionaAcueducto();
						while (oci_fetch($registros)) {
							$cod_proyecto = oci_result($registros, 'ID_PROYECTO') ;	
							$sigla_proyecto = oci_result($registros, 'SIGLA_PROYECTO') ;	
							if($cod_proyecto == $proyecto) echo "<option value='$cod_proyecto' selected>$sigla_proyecto</option>\n";
							else echo "<option value='$cod_proyecto'>$sigla_proyecto</option>\n";
						}oci_free_statement($registros);
						?>									
						</select>
   		  	  	  </td>
					<td width="22%" style=" border:1px solid #EEEEEE; text-align:center">Zona<br />
						Desde:&nbsp;&nbsp;<input type="text" name="zonini" id="zonini" value="<?php echo $zonini;?>" class="inputn" />
						&nbsp;&nbsp;&nbsp;&nbsp;
						Hasta:&nbsp;&nbsp;<input type="text" name="zonfin" id="zonfin" value="<?php echo $zonfin;?>" class="inputn" />
		  	  	  </td>
					<td width="22%" style=" border:1px solid #EEEEEE; text-align:center">Sector<br />
						Desde:&nbsp;&nbsp;<input type="number" name="secini" id="secini" value="<?php echo $secini;?>" class="inputn" min ="0" max="99" />
						&nbsp;&nbsp;&nbsp;&nbsp;
						Hasta:&nbsp;&nbsp;<input type="number" name="secfin" id="secfin" value="<?php echo $secfin;?>" class="inputn" min ="0" max="99" />
		  	  	  </td>
					<td width="22%" style=" border:1px solid #EEEEEE; text-align:center">Ruta<br />
						Desde:&nbsp;&nbsp;<input type="number" name="rutini" id="rutini" value="<?php echo $rutini;?>" class="inputn" min ="0" max="99" />
						&nbsp;&nbsp;&nbsp;&nbsp;
						Hasta:&nbsp;&nbsp;<input type="number" name="rutfin" id="rutfin" value="<?php echo $rutfin;?>" class="inputn" min ="0" max="99" />
		  	  	  </td>
				  	<td width="19%" style=" border:1px solid #EEEEEE" rowspan="2" align="center">
                       	<button style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#000040; color:#000040; display:block" type="submit" 
										name="Buscar" id="Buscar"class="btn btn btn-INFO"><i class="fa fa-search"></i><b>&nbsp;Consultar</b>
					  </button>
               	  </td>
				</tr>
			</table>
			<table width="100%">
				<tr>
					<td width="27%" style=" border:1px solid #EEEEEE; text-align:center">Tipo Resoluci&oacute;n<br />
						<input type="radio" name="tipo_resol" value="1" >&nbsp;Procedente&nbsp;&nbsp;
						<input type="radio" name="tipo_resol" value="2" >&nbsp;No Procedente&nbsp;&nbsp;
						<input type="radio" name="tipo_resol" value="3" >&nbsp;Todos
   		  	  	  	</td>
				  	<td width="27%" style=" border:1px solid #EEEEEE; text-align:center">Estado<br />
						<input type="radio" name="tipo_estado" value="1" >&nbsp;Pendientes&nbsp;&nbsp;
						<input type="radio" name="tipo_estado" value="2" >&nbsp;Realizadas&nbsp;&nbsp;
						<input type="radio" name="tipo_estado" value="3" >&nbsp;Todos
   		  	  	  	</td>
					<td width="22%" style=" border:1px solid #EEEEEE; text-align:center">Oficina<br />
						Desde:&nbsp;&nbsp;<input type="number" name="ofiini" id="ofiini" value="<?php echo $ofiini;?>" class="inputn" min ="0" max="999" />
						&nbsp;&nbsp;&nbsp;&nbsp;
						Hasta:&nbsp;&nbsp;<input type="number" name="ofifin" id="ofifin" value="<?php echo $ofifin;?>" class="inputn" min ="0" max="999" />
		  	  	  </td>
					<td width="15%" style=" border:1px solid #EEEEEE; text-align:center">Motivo PQR<br />
						<input type="text" id="motivo" name="motivo" value="<? echo $motivo?>" class="input">
   		  	  	  </td>
				</tr>
			</table>
			<table width="100%">
				<tr>
					<td style=" border:1px solid #EEEEEE; text-align:center" colspan="2">Fecha Radicaci&oacute;n<br />
						Desde:&nbsp;&nbsp;<input type="date" name="fecinirad" id="fecinirad" value="<?php echo $fecinirad;?>" class="input"/>
						&nbsp;&nbsp;&nbsp;&nbsp;
						Hasta:&nbsp;&nbsp;<input type="date" name="fecfinrad" id="fecfinrad" value="<?php echo $fecfinrad;?>" class="input"/>
		  	  	  	</td>
					<td style=" border:1px solid #EEEEEE; text-align:center" colspan="2">Fecha Resoluci&oacute;n<br />
						Desde:&nbsp;&nbsp;<input type="date" name="fecinires" id="fecinires" value="<?php echo $fecinires;?>" class="input"/>
						&nbsp;&nbsp;&nbsp;&nbsp;
						Hasta:&nbsp;&nbsp;<input type="date" name="fecfinres" id="fecfinres" value="<?php echo $fecfinres;?>" class="input"/>
		  	  	  	</td>
					<td width="27%" style=" border:1px solid #EEEEEE; text-align:center">N&deg; Reclamo<br />
						Desde:&nbsp;&nbsp;<input type="number" name="recini" id="recini" value="<?php echo $recini;?>" class="inputm" />
						&nbsp;&nbsp;&nbsp;&nbsp;
						Hasta:&nbsp;&nbsp;<input type="number" name="recfin" id="recfin" value="<?php echo $recfin;?>" class="inputm" />
		  	  	  </td>
				</tr>
       	  </table>
        </div>
    </div>
</div>
</form>
<?php
if(isset($_REQUEST["Buscar"])){
$l=new PQRs();
$registros=$l->obtieneAreaUsuario ($coduser);
while (oci_fetch($registros)) {
	$area_user = oci_result($registros, 'ID_AREA') ;	
}oci_free_statement($registros);
?>				

<table id="flex1" style="display:none">
</table>
</div>
<script type="text/javascript">
<!--
 $('.flexme1').flexigrid();
  $('.flexme2').flexigrid({height:'auto',striped:false});
  $("#flex1").flexigrid	(
	{
			url: '../datos/datos.repo_resumen_pqr.php?proyecto=<?php echo $proyecto;?>&zonini=<?php echo $zonini;?>&zonfin=<?php echo $zonfin;?>&secini=<?php echo $secini;?>&secfin=<?php echo $secfin;?>&rutini=<?php echo $rutini;?>&rutfin=<?php echo $rutfin;?>&tipo_resol=<?php echo $tipo_resol;?>&tipo_estado=<?php echo $tipo_estado;?>&ofiini=<?php echo $ofiini;?>&ofifin=<?php echo $ofifin;?>&motivo=<?php echo $motivo;?>&fecinirad=<?php echo $fecinirad;?>&fecfinrad=<?php echo $fecfinrad;?>&fecinires=<?php echo $fecinires;?>&fecfinres=<?php echo $fecfinres;?>&recini=<?php echo $recini;?>&recfin=<?php echo $recfin;?>',
			dataType: 'json',
			colModel : [
				  {display: 'N&deg;', name: 'rnum', width: 25,  align: 'left'},
				  {display: 'C&oacute;digo<br>PQR', name: 'codigo_pqr', width: 60, sortable: true, align: 'left'},
				  {display: 'Fecha<br>Radicaci&oacute;n', name: 'fecrad', width: 70, sortable: true, align: 'left'},
				  {display: 'Inmueble', name: 'cod_inmueble', width: 60, sortable: true, align: 'left'},
			      {display: 'Cliente', name: 'nom_cliente', width: 180, sortable: true, align: 'left'},
				  {display: 'Medio<br>Recepci&oacute;n', name: 'medio_rec_pqr', width: 60, sortable: true, align: 'left'},
				  {display: 'Zona', name: 'id_zona', width: 35, sortable: true, align: 'left'},
				  {display: 'Gerencia', name: 'gerencia', width: 55, sortable: true, align: 'left'},
			      {display: 'Oficina', name: 'cod_viejo', width: 55, sortable: true, align: 'left'},
				  {display: 'Descripci&oacute;n', name: 'descripcion', width: 200, sortable: true, align: 'left'},
				  {display: 'Fecha<br>Diagnostico', name: 'fecdiag', width: 70, sortable: true, align: 'left'},
				  {display: 'Diagnostico', name: 'diagnostico', width: 100, sortable: true, align: 'left'},
				  {display: 'Fecha<br>Resol.', name: 'fecresol', width: 70, sortable: true, align: 'left'},
				  {display: 'Tipo', name: 'desc_motivo_rec', width: 130, sortable: true, align: 'left'},
				  {display: 'Resol.', name: 'resolucion', width: 100, sortable: true, align: 'left'},
				  {display: 'Descripci&oacute;n<br>Resoluci&oacute;n', name: 'respuesta', width: 200, sortable: true, align: 'left'}
				  ],	
			/*searchitems : [	
				 // {display: 'C&oacute;digo PQR', name: 'codigo_pqr', isdefault: true},
				  {display: 'Inmueble', name: 'cod_inm'},
				  {display: 'Oficina', name: 'entidad_pqr'},
				  {display: 'Usuario', name: 'user_pqr'},
				  //{display: 'Dias Faltantes', name: 'dias_faltan'}  	
				  ],*/
			sortname: "I.ID_PROCESO",
			sortorder: "DESC",
			usepager: true,
			title: 'Listado PQRs',
			useRp: true,
			rp: 30,
			page: 1,			
			//showTableToggleBtn: true,
			width: 1120,
			height: 280
			}
			);


  //FUNCION PARA ABRIR UN POPUP
  var popped = null;
  function popup(uri, awid, ahei, scrollbar) {
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
              
   </script>
   <script src="../../js/jquery.min.js?1"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js?1"></script>
<script type="text/javascript">        
//-->       
  function edita_pqr(id) { // Traer la fila seleccionada
      popup("vista.edita_pqr.php?codigo_pqr="+id,800,600,'no');
  }  

  function sigue_pqr(id) { // Traer la fila seleccionada
      popup("vista.sigue_pqr.php?codigo_pqr="+id,800,600,'yes');
  }

  function close_pqr(id) { // Traer la fila seleccionada
      popup("vista.close_pqr.php?codigo_pqr="+id,800,600,'yes');
  }

  function documento_pqr(id) { // Traer la fila seleccionada
      popup("vista.documento_pqr.php?codigo_pqr="+id,800,600,'yes');
  }
//-->	
	$(function() {
    	$("#zonini").autocomplete({
        	source: "../datos/datos.zona2.php",
            minLength: 1,
            html: true,
            open: function(event, ui)
            {
            	$(".ui-autocomplete").css("z-index", 1000);
            }
		});
	});
	$(function() {
       	$("#zonfin").autocomplete({
           	source: "../datos/datos.zona2.php",
            minLength: 1,
            html: true,
            open: function(event, ui)
            {
               	$(".ui-autocomplete").css("z-index", 1000);
            }
        });
	});
	$(function() {
		$("#motivo").autocomplete({
    		source: "../datos/datos.motivos.php",
			minLength: 1,
			html: true,
			open: function(event, ui) 
			{
				$(".ui-autocomplete").css("z-index", 1000);
			}
		});
	});
	
</script>
<?php
}
?>
</body>
</html>