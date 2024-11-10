
<?php
session_start();
include('../../destruye_sesion.php');
//recibimos variables via get
$coduser = $_SESSION['codigo'];
$proyecto = $_GET['proyecto'];
$codinmueble = $_GET['codinmueble'];
$procini = $_GET['procini'];
$procfin = $_GET['procfin'];
$estado = $_GET['estado'];
$estado_inm = $_GET['estado_inm'];
$zona = $_GET['zona'];
$codcliente = $_GET['codcliente'];
$nomcliente = $_GET['nomcliente'];
$numdoc = $_GET['numdoc'];
$grupo = $_GET['grupo'];
$tipocli = $_GET['tipocli'];
$nummed = $_GET['nummed'];
$fecins = $_GET['fecins'];
$mora = $_GET['mora'];
$totalizador = $_GET['totalizador'];
$numfac = $_GET['numfac'];
$tipofac = $_GET['tipofac']; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Consulta General de Inmuebles</title>	
	<script type="text/javascript" src="../../alertas/dialog_box.js"></script>
	<script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
	<link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
	<link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
</head>
<body>
<form name="FormDetalle" action="vista.detalle_consulta.php" method="get">
	<table id="flex1" style="display:none">
    </table>
</form>
<script type="text/javascript">
    <!--
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flex1").flexigrid	(
        {
            url: './../datos/datos.listado_inmuebles.php?proyecto=<?php echo $proyecto;?>&codinmueble=<?php echo $codinmueble;?>&procini=<?php echo $procini;?>
			&procfin=<?php echo $procfin;?>&estado=<?php echo $estado;?>&estado_inm=<?php echo $estado_inm;?>&zona=<?php echo $zona?>&codcliente=<?php echo $codcliente?>
			&nomcliente=<?php echo $nomcliente?>&numdoc=<?php echo $numdoc;?>&grupo=<?php echo $grupo;?>&tipocli=<?php echo $tipocli;?>&nummed=<?php echo $nummed;?>
			&fecins=<?php echo $fecins;?>&mora=<?php echo $mora;?>&numfac=<?php echo $numfac;?>&tipofac=<?php echo $tipofac;?>',

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:40,  align: 'center'},
                {display: 'Cod Sistema', name: 'CODIGO_INM', width: 60, sortable: true, align: 'center'},
                {display: 'Zona', name: 'ID_ZONA', width: 30, sortable: true, align: 'center'},
                {display: 'Urbanizaci\u00F3n', name: 'DESC_URBANIZACION', width: 62, sortable: true, align: 'center'},
                {display: 'Direcci\u00F3n', name: 'DIRECCION', width: 127, sortable: true, align: 'center'},
				{display: 'Estado', name: 'ID_ESTADO', width: 32, sortable: true, align: 'center'},
				{display: 'Catastro', name: 'CATASTRO', width: 120, sortable: true, align: 'center'},
				{display: 'Proceso', name: 'ID_PROCESO', width: 65, sortable: true, align: 'center'},
				{display: 'Cliente', name: 'CODIGO_CLI', width: 40, sortable: true, align: 'center'},
				{display: 'Nombre', name: 'ALIAS', width: 260, sortable: true, align: 'center'},
				{display: 'C\u00E9dula', name: 'DOCUMENTO', width: 80, sortable: true, align: 'center'},
				{display: 'Medidor', name: 'SERIAL', width: 60, sortable: true, align: 'center'},
				{display: 'Fecha Inst.', name: 'FECHA_INSTALACION', width: 60, sortable: true, align: 'center'},
				{display: 'Suministro', name: 'METODO_SUMINISTRO', width: 50, sortable: true, align: 'center'}
            ],
			
            sortname: "CODIGO_INM",
            sortorder: "ASC",
            usepager: true,
            title: 'Listado Inmuebles',
            useRp: false,
            rp: 500,
            page: 10,
            showTableToggleBtn: false,
            width: 1300,
            height: 100,
			pagestat: 'Mostrando del {from} al {to} de {total} registros',
			procmsg: 'Procesando, un momento por favor ...',
			nomsg: 'No existen registros para su consulta'
        }
    );
</script>
<iframe id="datosinmueble" width="1298px" height="380px" style="display:block;float:left;border:solid;border-width:1px;border-color:#CCCCCC" src="vista.datos_inmueble.php">
</iframe>
</body>
</html>
