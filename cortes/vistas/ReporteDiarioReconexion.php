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
$contratista = $_POST['selContr'];
$acueducto = $_POST['selAcue'];
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
    <script type="text/javascript" src="../js/reporteDiario.js?1"></script>
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
<form name="repdiario" action="ReporteDiarioReconexion.php" method="post" >
    <div class="flexigrid" style="width:100%">
        <div class="mDiv">
            <div><b>Reconexión</b> >> Reporte diario >> Parametros de Busqueda</div>
            <div style="background-color:rgb(255,255,255)">
                <table width="100%">
                    <tr>
                        <td>Fecha Inicial:</td>
                        <td>
                            <input type="date" name="fecini" required id="fecini" value="<?php echo $fecini;?>">
                        </td>
                        <td>Fecha Final:</td>
                        <td>
                            <input type="date" name="fecfin" required id="fecfin" value="<?php echo $fecfin;?>">
                        </td>
                        <td width="24%" style=" border:1px solid #EEEEEE; text-align:center">Acueducto<br/>
                            <select name="selAcue" required class="input" id="selAcue" >
                            </select>
                        </td>
                        <td width="24%" style=" border:1px solid #EEEEEE; text-align:center">Contratista<br/>
                            <select name="selContr"  required class="input" id="selContr" >
                            </select>
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
                <th align="center">C&Oacute;DIGO</th>
                <th align="center">NOMBRE</th>
                <th align="center">CEDULA</th>
                <th align="center">SECTOR</th>
                <th align="center">CANTIDAD</th>

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
                url: '../datos/datos.reporteDiariorReconexion.php?fecini=<?php echo $fecini;?>&fecfin=<?php echo $fecfin;?>&contratista=<?php echo $contratista;?>&acueducto=<?php echo $acueducto;?>&tip=flexy',
                dataType: 'json',
                colModel : [
                    {display: 'No', name: 'numero', width: 20, sortable: true, align: 'center'},
                    {display: 'C\xf3digo', name: 'id_usuario', width: 75, sortable: true, align: 'center'},
                    {display: 'Nombre Inspector', name: 'nom_completo', width: 180, sortable: true, align: 'center'},
                    {display: 'Cedula', name: 'cedula', width: 40, sortable: true, align: 'center'},
                    {display: 'Ruta', name: 'uta', width: 100, sortable: true, align: 'center'},
                    {display: 'Inicio', name: 'fecha_ini', width: 100, sortable: true, align: 'center'},
                    {display: 'Final', name: 'fecha_fin', width: 100, sortable: true, align: 'center'},
                    {display: 'Total', name: 'Total', width: 100, sortable: true, align: 'center'}
                ],
                searchitems : [
                    {display: 'C\xf3digo', name: 'id_usuario',isdefault: true},
                ],
                sortname: "RC.USR_EJE",
                sortorder: "DESC",
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
      /*  var popped = null;
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
             }
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
       function upCliente(ruta,fecini,fecfin,usr) { // Traer la fila seleccionada
            popup("vista.detalle_rutarec_operario.php?fecini="+fecini+"&fecfin="+fecfin+"&operario="+usr+"&ruta="+ruta,1100,800,'yes');
        }
        function ruteo(ruta,inicio,fin,usr) { // Traer la fila seleccionada
            variable=""+usr;

            popup2("vista.detalle_rutarec_google.php?cod_ruta="+ruta+"&fecini="+inicio+"&fecfin="+fin+"&operario="+usr,1100,800,'yes');
        }*/

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

    function selectAcu(){
        selecionarAcu("<?php echo $acueducto;?>");
    }

    <?php endif;
    if ($verificarPermisos==false):
        include "../../general/vistas/vista.PlantillaError.php";
    endif; ?>


</script>