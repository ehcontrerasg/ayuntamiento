<?php
session_start();

include '../../destruye_sesion.php';
$_SESSION['tiempo']=time();
$_POST['codigo']=$_SESSION['codigo'];
$diferido=$_GET['diferido'];


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
        .titulo1 { color: red }

        body{
            margin: 0px;
            padding: 0px;
        }
    </style>


</head>
<body>


<div style="display: block;float: left">

    <table id="flex1" style="display:none">
    </table>
</div>
<script type="text/javascript">
    <!--
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flex1").flexigrid	(
        {

            url: './../datos/datos.cuotasdif.php?diferido=<?php echo $diferido;?>',

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:10,  align: 'center'},
                {display: 'Numero<br/>cuota', name: 'CUOTA', width: 37, sortable: true, align: 'center'},
                {display: 'Valor<br/>cuota', name: 'VAL_CUOTA', width: 80, sortable: true, align: 'center'},
                {display: 'Valor<br/>pagado', name: 'VAL_PAGADO', width: 60, sortable: true, align: 'center'},
                {display: 'Periodo', name: 'PERIODO', width: 35, sortable: true, align: 'center'},


            ],
            sortname: "DD.PERIODO",
            sortorder: "desc",
            usepager: false,
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            width: 300,
            height: 269
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


    //-->




</script>


</body>
</html>
