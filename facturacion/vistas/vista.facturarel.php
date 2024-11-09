<?php
session_start();
include '../../destruye_sesion.php';
$_SESSION['tiempo']=time();
$_POST['codigo']=$_SESSION['codigo'];
$factura=$_GET['factura'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="../../flexigrid/style.css" />
    <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
    <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
    <style type="text/css">
        .valores { color: red }

        #rowTotTacturas{background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8) !important;}

    </style>
</head>
<body style="padding: 1px">
<div style="display:block;float: left">

    <table id="flex1" style="display:block;float: left">
    </table>
</div>
<script type="text/javascript">
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flex1").flexigrid	(
        {

            url: './../datos/datos.facturasrel.php?factura=<?php echo $factura;?>',
            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:32,  align: 'center'},
                {display: 'Version', name: 'VERSION_FACTURA', width: 32, sortable: true, align: 'center'},
                {display: 'Periodo', name: 'PERIODO', width: 48, sortable: true, align: 'center'},
                {display: 'Fecha Exp', name: 'FEC_EXPEDICION', width: 64, sortable: true, align: 'center'},
                {display: 'NCF', name: 'NCF', width: 64, sortable: true, align: 'center'},
                {display: 'Descripcion', name: 'DESCRICPION', width: 118, sortable: true, align: 'center'},
                {display: 'Tipo Ajuste', name: 'AJUSTE', width: 56, sortable: true, align: 'center'},
                {display: 'Valor', name: 'VALOR', width: 46, sortable: true, align: 'center'}
            ],

            sortname: "VERSION_FACTURA",
            sortorder: "ASC",
            usepager: false,
            title: 'Facturas Ateriores',
            useRp: false,
            rp: 30,
            page: 1,
            showTableToggleBtn: false,
            width: 600,
            height: 300
        }
    );
</script>

</body>
</html>