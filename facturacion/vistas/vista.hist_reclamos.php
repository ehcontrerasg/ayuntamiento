<?php
session_start();

include '../../destruye_sesion_cierra.php';

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
    <script language="javascript">
        $(document).ready(function() {
            $(".botonExcel").click(function(event) {
                $("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).append( $("#flex1").eq(0).clone()).html());
                $("#FormularioExportacion").submit();
            });
        });
    </script>
</head>
<body style="width: 100%">
<form name="FMTarifas" action="vista.hist_reclamos.php" method="post" onsubmit="return rend();">
    <div class="flexigrid" style="width:100%">
    	<table id="flexreclamos" style="display:none">
    	</table>
	</div>
</form>
<script type="text/javascript">
    <!--
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexreclamos").flexigrid	(
        {

            url: '../datos/datos.histreclamos.php?codinmueble=<?php echo $codinmueble;?>',

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:25,  align: 'center'},
                {display: 'Código PQR', name: 'CODIGO_PQR', width: 70, sortable: true, align: 'center'},
                {display: 'Fecha PQR', name: 'FECHA_PQR', width: 120, sortable: true, align: 'center'},
                {display: 'Tipo PQR', name: 'DESC_TIPO_RECLAMO', width: 100, sortable: true, align: 'center'},
                {display: 'Motivo PQR', name: 'DESC_MOTIVO_REC', width: 400, sortable: true, align: 'center'},
				{display: 'Estado', name: 'CERRADO', width: 60, sortable: true, align: 'center'},
				{display: 'Diagnostico', name: 'DIAGNOSTICO', width: 80, sortable: true, align: 'center'},
				{display: 'PDF', name: 'foto', width: 40, sortable: true, align: 'center'}
            ],


            sortname: "CODIGO_PQR",
            sortorder: "DESC",
          
            title: 'Historico de Reclamos',
          
            rp: 1000,
            page: 1,
       
            width: 1000,
            height: 300
        }
    );


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
			popped = window.open(uri, "pdf", params);
        }
	}
}	
 function reclamoPdf(id) { // Traer la fila seleccionada
      popup("vista.documento_pqr.php?codigo_pqr="+id,1100,800,'yes','pop2');
  }


    //-->
</script>
</body>
</html>
