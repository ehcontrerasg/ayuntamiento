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
        #rowTotFacturas{background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8)!important;}
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

            url: './../datos/datos.totalfactpend.php?factura=<?php echo $factura;?>',
            dataType: 'json',
            colModel : [
                {display: 'Lectura', name: 'LECTURA', width:34,  align: 'center'},
                {display: 'Observacion', name: 'OBSERVACION', width: 60, sortable: true, align: 'center'},
                {display: 'Lector', name: 'LECTOR', width: 76, sortable: true, align: 'center'},
                {display: 'Consumo', name: 'CONSUMO', width: 60, sortable: true, align: 'center'}
            ],

            sortname: "LOGIN",
            sortorder: "ASC",
            usepager: false,
            title: 'Detalle Lectura y Entrega de Factura',
            useRp: false,
            rp: 30,
            page: 1,
            showTableToggleBtn: false,
            width: 300,
            height: 170
        }
    );
</script>

</body>
</html>