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
        .valores { color: red }

        #rowTotFacturas{
			background: #fafafa url(../../flexigrid/css/images/fhbg.gif) repeat-x bottom;
			border:1px solid #ccc;
            border-left:1px solid #ccc;
		}
	.th{
            background: #fafafa url(../../flexigrid/css/images/fhbg.gif) repeat-x bottom;
            height:24px;
            border:0px solid #ccc;
            border-left:1px solid #ccc;
            font-size:11px;
            font-weight:normal;
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>
<body style="padding: 1px">
    <div style="display:block;float: left">

        <table id="flex1" style="display:block;float: left; ">
        </table>
    </div>
    <script type="text/javascript">
        $('.flexme1').flexigrid();
        $('.flexme2').flexigrid({height:'auto',striped:false});
        $("#flex1").flexigrid	( 
        {
            url: './../datos/datos.estadoconcepto.php?inmueble=<?php echo $inmueble;?>',
            dataType: 'json',
            colModel : [
                {display: 'N&deg;', name: 'rnum', width:64,  align: 'center'},
                {display: 'Concepto', name: 'CONCEPTO', width: 124, sortable: true, align: 'center'},
                {display: 'N&deg; Facturas', name: 'NUMFAC', width: 115, sortable: true, align: 'center'},
                {display: 'Valor', name: 'VALOR', width: 80, sortable: true, align: 'center'}
            ],
            ///sortname: "CONCEPTO",
            //sortorder: "ASC",
            usepager: false,
            title: 'Estado Por Concepto',
            useRp: false,
            rp: 30,
            page: 1,
            showTableToggleBtn: false,
            width: 559,
            height: 150
        }
    );
</script>

</body>
</html>