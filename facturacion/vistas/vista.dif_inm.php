<?php
session_start();
include '../../destruye_sesion.php';
$_SESSION['tiempo']=time();
$_POST['codigo']=$_SESSION['codigo'];
$inmueble=$_GET['inmueble'];
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

            url: './../datos/datos.detdif.php?inmueble=<?php echo $inmueble;?>',
            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:13,  align: 'center'},
                {display: 'Diferido', name: 'VALOR_DIFERIDO', width: 34, sortable: true, align: 'center'},
                {display: 'Cuotas', name: 'RANGO', width: 30, sortable: true, align: 'center'},
                {display: 'Cuotas pag.', name: 'CUTAS_PAG', width: 53, sortable: true, align: 'center'},
                {display: 'Valor pag.', name: 'VALOR_PAG', width: 43, sortable: true, align: 'center'},
                {display: 'Valor pend.', name: 'VALOR_PEND', width: 47, sortable: true, align: 'center'}
            ],

            sortname: "numero_cuotas",
            sortorder: "ASC",
            usepager: false,
            title: 'Diferidos',
            useRp: false,
            rp: 30,
            page: 1,
            showTableToggleBtn: false,
            width: 300,
            height: 40
        }
    );
</script>

</body>
</html>