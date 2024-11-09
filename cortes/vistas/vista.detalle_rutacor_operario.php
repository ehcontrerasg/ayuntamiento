<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>

<?php
session_start();
include_once ('../../include.php');
include_once ('../../clases/class.usuario.php');
$coduser = $_SESSION['codigo'];

$cod_operario = $_GET['cod_operario'];
if($cod_operario != ''){
    $temporal=explode(" ",$cod_operario);
    $cod_ruta=$temporal[3];
    $fecini=$temporal[4];
    $fecfin=$temporal[5];
    $operario =$temporal[0];
}

else{
    $cod_ruta = $_GET['cod_ruta'];
    $fecini = $_GET['fecini'];
    $fecfin = $_GET['fecfin'];
    $operario = $_GET['operario'];
    $cod_ruta = substr($cod_ruta,2,2);
}
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
<body style="margin-left:-20px; margin-top:-10px;">

<form action="../../funciones/ficheroExcel.php" method="post" target="_blank"  id="FormularioExportacion">
    <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
    <div class="flexigrid" style="width:1400px">
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
            <th align="center">FECHA ENTREGA</th>
            <th align="center">FOTO</th>
            <th align="center">UBICACI&Oacute;N</th>
        </tr>
    </table>
    <table id="flex1" style="display:none">
    </table>
</div>
<?php
$i=new Usuario();
$nombre=$i->getNomOperByCod($operario);

//fechainicio=str_replace("-","/",$fechaini) ;;
//$fechafinal=str_replace("-","/",$fechafin) ;;

?>
<script type="text/javascript">
    <!--
        $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flex1").flexigrid (
        {
            url: '../datos/datos_detalle_rutacor.php?fecini=<?php echo $fecini;?>&fecfin=<?php echo $fecfin;?>&operario=<?php echo $operario;?>&ruta=<?php echo $cod_ruta;?> ',
            dataType: 'json',
            colModel : [
                {display: 'No', name: 'numero', width: 15, sortable: true, align: 'center'},
                {display: 'Cod Sistema', name: 'codigo_inm', width: 63, sortable: true, align: 'center'},
                {display: 'Id Proceso', name: 'id_proceso', width: 74, sortable: true, align: 'center'},
                {display: 'Id Inmueble', name: 'catastro', width: 114, sortable: true, align: 'center'},
                {display: 'Nombre', name: 'alias', width: 170, sortable: true, align: 'center'},
                {display: 'Direcci&oacute;n', name: 'direccion', width: 136, sortable: true, align: 'center'},
                {display: 'Serial', name: 'serial', width: 68, sortable: true, align: 'center'},
                {display: 'Lectura', name: 'lectura', width: 38, sortable: true, align: 'center'},
                {display: 'Tipo Corte', name: 'tipo_corte', width: 53, sortable: true, align: 'center'},
                {display: 'Impo. Corte', name: 'IMPO_CORTE', width: 55, sortable: true, align: 'center'},
                {display: 'Observacion', name: 'obs_generales', width: 235, sortable: true, align: 'center'},
                {display: 'Fecha Corte', name: 'fecha_mant', width: 108, sortable: true, align: 'center'},
                {display: 'Fotos', name: 'foto', width: 27, sortable: true, align: 'center'}
                //{display: 'Ubicaci&oacute;n', name: 'ubicacion', width: 45, sortable: true, align: 'center'}
            ],

            buttons: [
                {name:'Mapa', bclass:'map', onpress: test}
            ],
            searchitems : [
                {display: 'Cod Sistema', name: 'codigo_inm',isdefault: true},
                {display: 'Id Proceso', name: 'id_proceso'}
            ],




            sortname: "C.FECHA_EJE",
            sortorder: "asc",
            usepager: true,
            title: 'Detalle Operario <?php echo $operario?> Ruta <?php echo $cod_ruta?> Fecha <?php echo $fecini?> al <?php echo $fecfin?>',
            useRp: true,
            rp: 10000,
            page: 1,
            showTableToggleBtn: true,
            width: 1400,
            height: 258
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

    function fotos(id2,fecha,id4) { // Traer la fila seleccionada

        popup("vista.fotos_corte.php?cod_sistema="+id2+"&fecini="+fecha+"&fecfin="+id4,1100,800,'yes');
    }

    function ubicacion(id2,id3) { // Traer la fila seleccionada
        popup("vista.detcor_google.php?latitud="+id2+"&longitud="+id3,1100,800,'yes');
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
        window.open("/PRUEBAS/web-dominicana/cortes/vistas/vista.ruta_especifica.php?inmueble="+codigos_inm+"&fecini=<?php echo $fecini ?>"+"&fecfin=<?php echo $fecfin?>"+"&operario=<?php echo $operario?>" , "ventana1" , "width=770,height=6600,scrollbars=yes")
    }

</script>
<?php
//}
?>
</body>
</html>
<script type="text/javascript" language="javascript">




    function rend(){
        if (document.rendimiento.periodo.value == "") {
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


    <?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

</script>