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
        b { color: red }
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

            url: './../datos/datos.detfactpend.php?factura=<?php echo $factura;?>',
            dataType: 'json',
            colModel : [
                {display: 'N&deg;', name: 'rnum', width:15,  align: 'center'},
                {display: 'Concepto', name: 'CONCEPTO', width: 117, sortable: true, align: 'center'},
                {display: 'Rango', name: 'RANGO', width: 87, sortable: true, align: 'center'},
                {display: 'Unidades', name: 'UNIDADES', width: 86, sortable: true, align: 'center'},
                {display: 'Valor', name: 'VALOR', width: 70, sortable: true, align: 'center'}
            ],

            sortname: "CONCEPTO, RANGO",
            sortorder: "ASC",
            usepager: false,
            title: 'Detalle Factura N&deg; <?php echo $factura;?>',
            useRp: false,
            rp: 30,
            page: 1,
            showTableToggleBtn: false,
            width: 560,
            height: 150
        }
    );
</script>

</body>
</html>