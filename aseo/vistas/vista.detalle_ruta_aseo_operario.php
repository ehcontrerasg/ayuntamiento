<?php
session_start();
$coduser     = $_SESSION['codigo'];
$periodo_ini = ($_GET['periodoI']);
$periodo_fin = ($_GET['periodoF']);
//$operario = ($_GET['operario']);
$ruta = ($_GET['ruta']);
//$periodo = $_POST['periodo'];

//Conectamos con la base de datos
$Cnn  = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <!--<meta http-equiv="refresh" content="5; URL=rendimiento.php?periodo=<?php echo $periodo1; ?>&ruta=<?php echo $ruta1; ?>&proc=1" />-->
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
<body>
<form name="rendimiento" action="vista.detalle_ruta_aseo_operario.php" method="post" onsubmit="return rend();">
    <h3 style="font-family:Tahoma, Geneva, sans-serif; color:rgb(163,73,163); font-size:11px; font-weight:100">
        <b>MANTENIMIENTO</b> >> Detalle Rendimiento Operario Por Ruta
    </h3>
</form>
<?php

$sql = "SELECT COUNT(ID_OPERARIO), ID_USUARIO, NOM_USR, APE_USR
FROM SGC_TT_ASIGNACION A, SGC_TT_USUARIOS U
WHERE  A.ID_OPERARIO = U.ID_USUARIO AND
CONCAT (ID_SECTOR,ID_RUTA) = $ruta AND ID_PERIODO BETWEEN $periodo_ini AND $periodo_fin
GROUP BY ID_USUARIO, NOM_USR, APE_USR";
$stid = oci_parse($link, $sql);
oci_execute($stid, OCI_DEFAULT);
while (oci_fetch($stid)) {
    $cod_operario = oci_result($stid, 'ID_USUARIO');
    $nom_operario = oci_result($stid, 'NOM_USR');
    $ape_operario = oci_result($stid, 'APE_USR');
}
oci_free_statement($stid);
$nombre = $nom_operario . " " . $ape_operario;

//if($proc == 1){
$agno = substr($periodo_ini, 0, 4);
$mes  = substr($periodo_ini, 4, 2);
if ($mes == '01') {$mes = Enero;}if ($mes == '02') {$mes = Febrero;}if ($mes == '03') {$mes = Marzo;}if ($mes == '04') {$mes = Abril;}
if ($mes == '05') {$mes = Mayo;}if ($mes == '06') {$mes = Junio;}if ($mes == '07') {$mes = Julio;}if ($mes == '08') {$mes = Agosto;}
if ($mes == '09') {$mes = Septiembre;}if ($mes == '10') {$mes = Octubre;}if ($mes == '11') {$mes = Noviembre;}if ($mes == '12') {$mes = Diciembre;}
$nomrepo = 'Rendimiento ' . $nom_operario . ' ' . $ape_operario . ' - ';
?>
<form action="../../funciones/ficheroExcel.php?agno=<?echo $agno; ?>&mes=<?echo $mes; ?>&nomrepo=<?echo $nomrepo; ?>" method="post" target="_blank"  id="FormularioExportacion">
    <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
    <div class="flexigrid" style="width:100%">
        <div class="mDiv">
            <div>Exportar a:</div>
            <div style="background-color:rgb(255,255,255)">
                <img src="../../images/excel/xls.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="Excel"/>
                <img src="../../images/excel/pdf.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="PDF"/>
            </div>
        </div>
    </div>
</form>
<div>
    <table id="Exportar_a_Excel" style="display:none">
        <tr>
            <th align="center">No</th>
            <th align="center">COD SISTEMA</th>
            <th align="center">ID PROCESO</th>
            <th align="center">DIRECCI&Oacute;N</th>
            <th align="center">FECHA MANTENIMIENTO</th>
            <th align="center">FOTO</th>
            <th align="center">UBICACI&Oacute;N</th>
        </tr>
    </table>
    <table id="flex1" style="display:none">
    </table>
</div>

<script type="text/javascript">
    <!--
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flex1").flexigrid (
        {
            url: '../datos/datos.detalle_ruta_aseo.php?ruta=<?php echo $ruta; ?>&periodoI=<?php echo $periodo_ini; ?>&periodoF=<?php echo $periodo_fin; ?>&operario=<?php echo $cod_operario; ?>',
            dataType: 'json',
            colModel : [
                {display: 'No', name: 'numero', width: 30, sortable: true, align: 'center'},
                {display: 'Cod Sistema', name: 'codigo_inm', width: 80, sortable: true, align: 'center'},
                {display: 'Id Proceso', name: 'id_proceso', width: 200, sortable: true, align: 'center'},
                {display: 'Direcci&oacute;n', name: 'direccion', width: 250, sortable: true, align: 'center'},
                {display: 'Fecha Mantenimiento', name: 'fecha_mant', width: 150, sortable: true, align: 'center'},
                {display: 'Fotos', name: 'foto', width: 80, sortable: true, align: 'center'},
                {display: 'Ubicaci&oacute;n', name: 'ubicacion', width: 80, sortable: true, align: 'center'}
            ],

            buttons: [
                {name:'Mapa', bclass:'map', onpress: test}

            ],
            searchitems : [
                {display: 'Cod Sistema', name: 'codigo_inm',isdefault: true},
                {display: 'Id Proceso', name: 'id_proceso'}
            ],




            sortname: "I.ID_PROCESO",
            sortorder: "asc",
            usepager: true,
            title: 'Detalle Ruta <?php echo $ruta; ?> - Operario <?php echo $nombre; ?> - Periodo <?php echo $periodo_ini; ?>',
            useRp: true,
            rp: 10000,
            page: 1,
            showTableToggleBtn: true,
            width: 960,
            height: 358
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

    function fotos(id2,id3,id1) { // Traer la fila seleccionada
        popup("fotos_mantenimiento.php?cod_sistema="+id2+"&periodo="+id3+"&periodoF="+id1,1100,800,'yes');
    }
    //-->
    function ubicacion(id2) { // Traer la fila seleccionada
        popup("acea_google.php?cod_sistema="+id2,1100,800,'yes');
    }

    function test(com,grid)
    {
        if (com=='Mapa')
        {

            if($('.trSelected',grid).length>0)
            {
                var items = $('.trSelected',grid);
                var itemlist ='';

                for(i=0;i<items.length;i++)
                {
                    if(i<items.length-1 ){
                        itemlist+= items[i].id.substr(3)+",";
                    }else{
                        itemlist+= items[i].id.substr(3);
                    }
                }

                agrega(itemlist);
            }
        }

    }

    function agrega(codigos_inm) { // Traer la fila seleccionada
        window.open("/acea/catastro/vistas/vista.ruta_especifica.php?inmueble="+codigos_inm+"&periodo=<?php echo $periodo ?>" , "ventana1" , "width=770,height=6600,scrollbars=yes")
    }

</script>
<?php
//}
?>
</body>
</html>
<script type="text/javascript" language="javascript">




    function rend(){
        if (document.rendimiento.periodo_ini.value == "") {
            document.rendimiento.proc.value=0;
            return false;
        }
        else {
            document.rendimiento.proc.value = 1;
            return true;
        }
    }

    function recarga() {
        document.rendimiento.ruta.selectedIndex = 0;
        document.rendimiento.submit();
    }

</script>