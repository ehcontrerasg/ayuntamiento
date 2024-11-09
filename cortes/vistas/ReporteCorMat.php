<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
<?php
session_start();
include '../../destruye_sesion.php';

$coduser = $_SESSION['codigo'];
$fecini = $_POST['fecini'];
$fecfin = $_POST['fecfin'];
$contratista = $_POST['selCon'];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" media="screen" href="../../css/Static_Tabstrip.css">
    <link rel="stylesheet" href="../../flexigrid/style.css" />
    <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
    <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
    <script type="text/javascript" src="../js/ReporteCorMat.js"></script>
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
<form name="repdiario" action="ReporteCorMat.php" method="post">
    <div class="flexigrid" style="width:100%">
        <div class="mDiv">
            <div><b>Cortes</b> >> Reporte diario >> Parametros de Busqueda</div>
            <div style="background-color:rgb(255,255,255)">
                <table width="100%">
                    <tr>
                        <td>Fecha Inicial:</td>
                        <td>
                            <input type="date" name="fecini" id="fecini" value="<?php echo $fecini;?>">
                        </td>
                        <td>Fecha Final:</td>
                        <td>
                            <input type="date" name="fecfin" id="fecfin" value="<?php echo $fecfin;?>">
                        </td>
                        <td>Contratista:</td>
                        <td>
                            <select tabindex="2" name="selCon" required select id="selCon"></select>
                        </td>
                        <td>
                            <input type="submit" value="Buscar" name="Buscar" class="boton">
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</form>
<?php
if(isset($_REQUEST['Buscar'])){
    ?>
    <form action="../../funciones/ficheroExcel.php?agno=<? echo $fecfin;?>&mes=<? echo $fecini;?>&nomrepo=<? echo 'cortes';?>" method="post" target="_blank"  id="FormularioExportacion">
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
                <th align="center">SECTOR</th>
                <th align="center">RUTA</th>
                <th align="center">CATASTRO</th>
                <th align="center">NOMBRE</th>
                <th align="center">DOCUMENTO</th>
                <th align="center">TELEFONO</th>
                <th align="center">FECHA</th>
                <th align="center">OPERARIO</th>
                <th align="center">TIPO</th>
                <th align="center">OBSERVACION</th>
                <th align="center">ARN</th>
                <th align="center">CMG</th>
                <th align="center">PCT</th>
                <th align="center">PMG</th>
                <th align="center">PMP</th>
                <th align="center">TA1</th>
                <th align="center">TA112</th>
                <th align="center">TA12</th>
                <th align="center">TA2</th>
                <th align="center">TA34</th>
                <th align="center">TAN</th>

            </tr>
        </table>
        <table id="flex1" style="display:none">
        </table>
    </div>
    <script type="text/javascript">
        <!--
        $('.flexme1').flexigrid();
        $('.flexme2').flexigrid({height:'auto',striped:false});
        $("#flex1").flexigrid	(
            {
                url: '../datos/datos.ReporteMaterialesCorte.php?fecini=<?php echo $fecini;?>&fecfin=<?php echo $fecfin;?>',
                dataType: 'json',
                colModel : [
                    {display: 'No', name: 'numero', width: 15, sortable: true, align: 'center'},
                    {display: 'Sector', name: 'sector', width: 20, sortable: true, align: 'center'},
                    {display: 'Ruta', name: 'ruta', width: 18, sortable: true, align: 'center'},
                    {display: 'Catastro', name: 'ruta', width: 102, sortable: true, align: 'center'},
                    {display: 'Nombre', name: 'nombre', width: 102, sortable: true, align: 'center'},
                    {display: 'Documento', name: 'documento', width: 70, sortable: true, align: 'center'},
                    {display: 'Telefono', name: 'telfono', width: 65, sortable: true, align: 'center'},
                    {display: 'Fecha', name: 'fecha', width: 53, sortable: true, align: 'center'},
                    {display: 'Operario', name: 'operario', width: 159, sortable: true, align: 'center'},
                    {display: 'Tipo Corte', name: 'fecha_ini', width: 176, sortable: true, align: 'center'},
                    {display: 'Observacion', name: 'obs', width: 268, sortable: true, align: 'center'},
                    {display: 'ARN', name: 'arn', width: 20, sortable: true, align: 'center'},
                    {display: 'CMG', name: 'cmg', width: 20, sortable: true, align: 'center'},
                    {display: 'PCT', name: 'pct', width: 20, sortable: true, align: 'center'},
                    {display: 'PMG', name: 'pmg', width: 20, sortable: true, align: 'center'},
                    {display: 'PMP', name: 'pmp', width: 20, sortable: true, align: 'center'},
                    {display: 'TA1', name: 'ta1', width: 20, sortable: true, align: 'center'},
                    {display: 'TA112', name: 'ta112', width: 20, sortable: true, align: 'center'},
                    {display: 'TA12', name: 'ta12', width: 20, sortable: true, align: 'center'},
                    {display: 'TA2', name: 'ta2', width: 20, sortable: true, align: 'center'},
                    {display: 'TA34', name: 'ta34', width: 20, sortable: true, align: 'center'},
                    {display: 'TAN', name: 'tan', width: 20, sortable: true, align: 'center'}
                ],
                searchitems : [
                    {display: 'C\xf3digo', name: 'id_usuario',isdefault: true},
                ],
                sortname: "COR.ORDEN",
                sortorder: "ASC",
                usepager: true,
                title: 'Listado Operarios Por Ruta',
                useRp: true,
                rp: 1000,
                page: 1,
                showTableToggleBtn: true,
                width: 960,
                height: 358
            }
        );
        //FUNCION PARA ABRIR UN POPUP
        var popped = null;
        function popup(uri, awid, ahei, scrollbar) {
            popped = window.open(uri);
            /*var params;
             if (uri != "") {
             if (popped && !popped.closed) {
             popped.location.href = uri;
             popped.focus();
             }
             else {
             params = "toolbar=no,width=" + awid + ",height="+ ahei +",directories=no,status=no,scrollbars=" + scrollbar + ",menubar=no,resizable=no,location=no";
             popped = window.open(uri, "popup", params);
             }
             }*/
        }

        function popup2(uri, awid, ahei, scrollbar) {
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
        function upCliente(id2,id3) { // Traer la fila seleccionada
            popup("detalle_ruta_operario.php?periodo="+id2+"&ruta="+id3,1100,800,'yes');
        }
        function ruteo(id3,id4) { // Traer la fila seleccionada
            popup2("detalle_ruta_google.php?cod_ruta="+id3+"&periodo="+id4,1100,800,'yes');
        }
        //-->
    </script>
    <?php
}
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
        //document.rendimiento.ruta.selectedIndex = 0;
        document.rendimiento.submit();
    }


    function select(){
        selecionar("<?php echo $contratista;?>");
    }


    <?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>



</script>