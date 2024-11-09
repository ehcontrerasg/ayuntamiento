
<?php
session_start();
$segundos = 1800; //si pasa este tiempo se detecta la inactividad del usuario en el sitio 
if(($_SESSION['tiempo']+$segundos) < time()) {
	session_destroy();
   	echo'<script type="text/javascript">alert("Su sesion ha expirado por inactividad'; 
   	echo', vuelva a logearse para continuar");window.close();</script>';     
}
else $_SESSION['tiempo']=time(); 
//include '../../destruye_sesion.php';
//recibimos variables via get
$coduser = $_SESSION['codigo'];
$proyecto = $_GET['proyecto'];
$codinmueble = $_GET['codinmueble'];
$secini = $_GET['secini'];
$secfin = $_GET['secfin'];
$zonini = $_GET['zonini'];
$zonfin = $_GET['zonfin'];
$procini = $_GET['procini'];
$procfin = $_GET['procfin'];
$urbaniza = $_GET['urbaniza'];
$tipovia = $_GET['tipovia'];
$nomvia = $_GET['nomvia'];
$numcasa = $_GET['numcasa'];
$estado = $_GET['estado'];
$estado_inm = $_GET['estado_inm'];
$codcliente = $_GET['codcliente'];
$nomcliente = $_GET['nomcliente'];
$numdoc = $_GET['numdoc'];
$grupo = $_GET['grupo'];
$tipocli = $_GET['tipocli'];
$numcon = $_GET['numcon'];
$fecinicon = $_GET['fecinicon'];
$fecfincon = $_GET['fecfincon'];
$marca = $_GET['marca'];
$serial = $_GET['serial'];
$emplaza = $_GET['emplaza'];
$metodo = $_GET['metodo'];
$fecinsini = $_GET['fecinsini'];
$fecinsfin = $_GET['fecinsfin'];
$mora = $_GET['mora'];
$totalizador = $_GET['totalizador'];
$concepto = $_GET['concepto'];
$uso = $_GET['uso'];
$actividad = $_GET['actividad'];
$tarifa = $_GET['tarifa'];
$numfac = $_GET['numfac'];
$tipofac = $_GET['tipofac'];
$fecinipag = $_GET['fecinipag'];
$fecfinpag = $_GET['fecfinpag'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Consulta General de Inmuebles</title>	
	<script type="text/javascript" src="../../alertas/dialog_box.js"></script>
	<script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
	<script language="JavaScript" type="text/javascript" src="../js/datInm.js?12 "></script>
	<link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
	<link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
    <link rel="stylesheet" type="text/css" href="../css/datpago.css ">
	<link href="../../css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<form name="FormDetalle" action="vista.detalle_consulta.php" method="get">
	<table id="flex1" style="display:none">
    </table>

<script type="text/javascript">

    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flex1").flexigrid	(
        {
            url: './../datos/datos.listado_inmuebles.php?proyecto=<?php echo $proyecto;?>&codinmueble=<?php echo $codinmueble;?>&secini=<?php echo $secini;?>
			&secfin=<?php echo $secfin;?>&zonini=<?php echo $zonini;?>&zonfin=<?php echo $zonfin;?>&procini=<?php echo $procini;?>&procfin=<?php echo $procfin;?>
			&urbaniza=<?php echo $urbaniza;?>&tipovia=<?php echo $tipovia;?>&nomvia=<?php echo $nomvia;?>&estado=<?php echo $estado;?>&estado_inm=<?php echo $estado_inm;?>
			&codcliente=<?php echo $codcliente?>&nomcliente=<?php echo $nomcliente?>&numdoc=<?php echo $numdoc;?>&grupo=<?php echo $grupo;?>&tipocli=<?php echo $tipocli;?>
			&numcon=<?php echo $numcon;?>&fecinicon=<?php echo $fecinicon;?>&fecfincon=<?php echo $fecfincon;?>&marca=<?php echo $marca;?>&serial=<?php echo $serial;?>
			&emplaza=<?php echo $emplaza;?>&metodo=<?php echo $metodo;?>&fecinsini=<?php echo $fecinsini;?>&fecinsfin=<?php echo $fecinsfin;?>&mora=<?php echo $mora;?>
			&totalizador=<?php echo $totalizador;?>&concepto=<?php echo $concepto;?>&uso=<?php echo $uso;?>&actividad=<?php echo $actividad;?>&tarifa=<?php echo $tarifa;?>
			&numfac=<?php echo $numfac;?>&tipofac=<?php echo $tipofac;?>&fecinipag=<?php echo $fecinipag;?>&fecfinpag=<?php echo $fecfinpag;?>&numcasa=<?php echo $numcasa;?>',
        dataType: 'json',
        colModel : [
        {display: 'No', name: 'rnum', width:40,  align: 'center'},
        {display: 'Acueducto', name: 'ID_PROYECTO', width: 50, sortable: true, align: 'center'},
        {display: 'Cod Sistema', name: 'codigo_inm', width: 60, sortable: true, align: 'center'},
        {display: 'Zona', name: 'ID_ZONA', width: 30, sortable: true, align: 'center'},
        {display: 'Urbanizaci\u00F3n', name: 'DESC_URBANIZACION', width: 82, sortable: true, align: 'center'},
        {display: 'Direcci\u00F3n', name: 'DIRECCION', width: 127, sortable: true, align: 'center'},
        {display: 'Estado', name: 'ID_ESTADO', width: 32, sortable: true, align: 'center'},
        {display: 'Catastro', name: 'CATASTRO', width: 120, sortable: true, align: 'center'},
        {display: 'Proceso', name: 'ID_PROCESO', width: 65, sortable: true, align: 'center'},
        {display: 'Cliente', name: 'CODIGO_CLI', width: 40, sortable: true, align: 'center'},
        {display: 'Nombre', name: 'ALIAS', width: 260, sortable: true, align: 'center'},
        {display: 'C\u00E9dula', name: 'DOCUMENTO', width: 80, sortable: true, align: 'center'},
        {display: 'Medidor', name: 'SERIAL', width: 60, sortable: true, align: 'center'},
        {display: 'Calibre', name: 'DESC_CALIBRE', width: 60, sortable: true, align: 'center'},
        {display: 'Fecha Inst.', name: 'FECHA_INSTALACION', width: 60, sortable: true, align: 'center'},
        {display: 'Fecha Alta', name: 'FEC_ALTA', width: 60, sortable: true, align: 'center'},
        {display: 'Suministro', name: 'METODO_SUMINISTRO', width: 50, sortable: true, align: 'center'},
        {display: 'Uso', name: 'ID_USO', width: 50, sortable: true, align: 'center'}
    ],
        //searchitems : [
        //{display: ''}
   // ],
        sortname: "ID_SECTOR, ID_ZONA, CODIGO_INM",
        sortorder: "ASC",
        usepager: true,
        title: 'Listado Inmuebles',
        useRp: false,
        rp: 500,
        page: 1,
        showTableToggleBtn: true,
        height: 250,
        pagestat: 'Mostrando del {from} al {to} de {total} registros',
        procmsg: 'Procesando, un momento por favor ...',
        nomsg: 'No existen registros para su consulta'
        }
    );
</script>

<!--div id="datosinmueble" style="display:block;float:left;border:solid;border-width:1px;border-color:#CCCCCC; width:1350px; height:460px"></div-->
<iframe id="datosinmueble" style="display:block;border:solid;border-width:1px;border-color:#CCCCCC; width:1350px; height:460px" src="vista.datos_inmueble.php">
</iframe>
</form>
</body>
</html>
