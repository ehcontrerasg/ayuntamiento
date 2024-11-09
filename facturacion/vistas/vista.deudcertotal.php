<?php
session_start();
include '../../destruye_sesion.php';
$_SESSION['tiempo']=time();
$_POST['codigo']=$_SESSION['codigo'];
$codinmueble=$_GET['codinmueble'];
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
        .iframedeucer{
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
        <table id="flex6" style="display:none">
        </table>
    </div>
	<iframe id="IFCuotasDeuCer" class="iframedeucer" src="vista.opc_deudacero.php?codinmueble=<?php echo $codinmueble;?>"></iframe>
	<script type="text/javascript">
    <!--
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flex6").flexigrid	(
        {

            url: './../datos/datos.deudcerotot.php?codinmueble=<?php echo $codinmueble;?>',

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:15,  align: 'center'},
                {display: 'C\xF3digo <br/> Deuda Cero', name: 'CODIGO', width: 60, sortable: true, align: 'center'},
                {display: 'Per\xEDodo <br/> Inicial', name: 'PER_INI', width: 60, sortable: true, align: 'center'},
                {display: 'N\xFAmero  <br/> Cuotas', name: 'NUM_CUOTAS', width: 40, sortable: true, align: 'center'},
                {display: 'Fecha <br/> \xDAltimo Pago', name: 'FECHA_ULTPAGO', width: 76, sortable: true, align: 'center'},
                {display: 'Activo', name: 'ACTIVO', width: 30, sortable: true, align: 'center'},
                {display: 'Cuotas <br/> Pagadas', name: 'CUOTAS_PAGADAS', width: 45, sortable: true, align: 'center'},
                {display: 'Total <br/> Diferido ', name: 'TOT_DIF', width: 48, sortable: true, align: 'center'},
                {display: 'Total <br/> Mora ', name: 'TOT_MORA', width: 48, sortable: true, align: 'center'},
                {display: 'Cliente <br/> Acuerdo', name: 'CLIENTE', width: 200, sortable: true, align: 'center'},
                {display: 'Per\xEDodo <br/> Reversi\xF3n', name: 'PERREV', width: 60, sortable: true, align: 'center'}
            ],
            sortname: "PERIODO_INI",
            sortorder: "desc",
            usepager: false,
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            width: 980,
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
