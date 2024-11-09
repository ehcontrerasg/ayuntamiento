<?php
session_start();
include '../../destruye_sesion.php';
$_SESSION['tiempo']=time();
$_POST['codigo']=$_SESSION['codigo'];
$inmueble=$_GET['cod_inmueble'];
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

        .iframedif{
            border-color: #666666;
            border:solid;
            border-width:0px ;
            display: block;
            float: left;
            width: 380px;
            height: 213px;
        }
		.iframeopc{
            border-color: #666666;
            border:solid;
            border-width:0px ;
            display: block;
            float: left;
            width: 250px;
            height: 213px;
        }
    </style>
</head>
<body>
<div class="flexigrid" >
    <div class="mDiv">
    </div>

    <div style="display: block;float: left">
        <table id="flex5" style="display:none">
        </table>
    </div>
<iframe id="IFCuotasDif" class="iframedif" src="vista.cuotasdif.php?coddif=<?php echo $diferido;?>"></iframe>
<iframe id="IFOpcDif" class="iframeopc" src="vista.opc_diferido.php?codinmueble=<?php echo $codinmueble;?>&coddif=<?php echo $diferido;?>"></iframe>
<script type="text/javascript">
    <!--
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flex5").flexigrid	(
        {

            url: './../datos/datos.diferidostotal.php?inmueble=<?php echo  $codinmueble;?>',

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:25,  align: 'center'},
                {display: 'C\xF3digo<br>Diferido', name: 'DIFERIDO', width: 60, sortable: true, align: 'center'},
                {display: 'Concepto', name: 'CONCEPTO', width: 100, sortable: true, align: 'center'},
				{display: 'Tipo<br>Diferido', name: 'FEC_APERTURA', width: 55, sortable: true, align: 'center'},
                {display: 'Fecha<br>Apertura', name: 'FEC_APERTURA', width: 70, sortable: true, align: 'center'},
                {display: 'Valor<br>Diferido', name: 'VAL_DIF', width: 50, sortable: true, align: 'center'},
                {display: 'Activo', name: 'ACTIVO', width: 30, sortable: true, align: 'center'},
                {display: 'Cuotas<br>Pagas', name: 'CUOTAS_PAGADAS', width: 45, sortable: true, align: 'center'},
                {display: 'Valor<br>Pagado ', name: 'VAL_PAGO', width: 50, sortable: true, align: 'center'},
                {display: 'Numero<br>Cuotas ', name: 'NUMERO_CUOTAS', width: 40, sortable: true, align: 'center'},
                {display: 'Periodo<br>Inicio', name: 'PERIODO_INI', width: 50, sortable: true, align: 'center'}

            ],
            sortname: "PER_INI",
            sortorder: "desc",
            usepager: false,
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            width: 600,
            height: 171
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
</div>
</body>
</html>
