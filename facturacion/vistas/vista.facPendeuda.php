<?php
$coduser = $_SESSION['codigo'];
$codinmueble = $_GET["codinmueble"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
	<link rel="stylesheet" href="../../flexigrid/style.css" />
    <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
</head>
<body style="padding: 0px">
	 <div style="display:block;float: left">
		<table id="flex2" style="display:block; float:left">
		</table>
		</div>
		<script type="text/javascript">
		<!--
		$('.flexme1').flexigrid();
		$('.flexme2').flexigrid({height:'auto',striped:false});
		$("#flex2").flexigrid	(
			{

            url: './../datos/datos.facturasPendientesDeuda.php?codinmueble=<?php echo $codinmueble;?>',

            dataType: 'json',
            colModel : [
                {display: 'Item', name: 'rnum', width:18,  align: 'center'},
                {display: 'N&deg; Factura', name: 'CONSEC_FACTURA', width: 65, sortable: true, align: 'center'},
                {display: 'Periodo', name: 'Periodo', width: 47, sortable: true, align: 'center'},
                {display: 'Expedici\xF3n', name: 'FEC_EXPEDICION', width: 60, sortable: true, align: 'center'},
                {display: 'Vencimiento', name: 'NCF', width: 60, sortable: true, align: 'center'},
                {display: 'Total', name: 'TOTAL', width: 70, sortable: true, align: 'center'}
            ],

            sortname: "PERIODO",
            sortorder: "asc",
            usepager: false,
            title: 'Facturas Pendientes Por Pagar',
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            width: 480,
            height: 261
			}
		);
		</script>
	</body>

</html>
