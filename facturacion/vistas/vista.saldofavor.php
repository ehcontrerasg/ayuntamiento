<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title></title>	
	<script type="text/javascript" src="../../alertas/dialog_box.js"></script>
	<script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
	<link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
	<link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../flexigrid/style.css" />
    <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
</head>
<body>
<form name="FormSaldoFavor" action="vista.saldofavor.php" method="get">
	<table id="flexisaldos" style="display:none">
    </table>
</form>
	<script type="text/javascript">
    
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexisaldos").flexigrid	(
        {

            url: './../datos/datos.saldofavor.php?codinmueble=<?php echo $codinmueble;?>',

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'RNUM', width:25,  sortable: true, align: 'center'},
                {display: 'C\xF3digo', name: 'CODIGO', width: 70, sortable: true, align: 'center'},
                {display: 'Fecha', name: 'FECHA', width: 120, sortable: true, align: 'center'},
                {display: 'Importe', name: 'IMPORTE', width: 75, sortable: true, align: 'center'},
                {display: 'Aplicado', name: 'VALOR_APLICADO', width: 75, sortable: true, align: 'center'},
                {display: 'Por Aplicar', name: 'PENDIENTE', width: 75, sortable: true, align: 'center'},
				{display: 'Motivo', name: 'MOTIVO', width: 400, sortable: true, align: 'center'}
            ],
            sortname: "CODIGO",
            sortorder: "DESC",
            usepager: false,
            useRp: false,
            rp: 100,
            page: 1,
            showTableToggleBtn: false,
            width: 980,
            height: 171
        }
    );

	</script>
</body>
</html>
