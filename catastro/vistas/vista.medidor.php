<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();

    include '../../destruye_sesion.php';

    $_SESSION['tiempo']=time();
    $coduser = $_SESSION['codigo'];
    $inmueble = ($_GET['cod_inmueble']);

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
    <body>
    <form name="servicios" action="vista.servicios.php" method="post" onsubmit="return rend();">
        <div class="flexigrid" style="width:100%">
            <div class="mDiv">
                <div><b>INCORPORACIÓN</b> >> Inmuebles >> medidores</div>
                <!--<div style="background-color:rgb(255,255,255)">
                </div>-->
            </div>
    </form>
    <?php
    //if($proc == 1){
    ?>
    <form action="../../funciones/ficheroExcel.php" method="post" target="_blank"  id="FormularioExportacion">
        <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
        <div class="flexigrid" style="width:100%">
            <div class="mDiv">
                <div>Exportar a:</div>
                <div style="background-color:rgb(255,255,255)">
                    <img src="../../images/excel/xls.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="XLS"/>
                    <img src="../../images/excel/pdf.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="PDF"/>
                </div>
            </div>
        </div>
    </form>
    <div>
        <table id="Exportar_a_Excel" style="display:none">
            <tr>
                <th align="center">MEDIDOR</th>
                <th align="center">EMPLAZAMIENTO</th>
                <th align="center">CALIBRE</th>
                <th align="center">SERIAL</th>
                <th align="center">FECHA INSTALACION</th>
                <th align="center">FECHA BAJA</th>
                <th align="center">METODO SUMINISTRO</th>
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

                url: './../datos/datos.medidores.php?cod_inmueble=<?php echo $inmueble;?>',

                dataType: 'json',
                colModel : [
                    {display: 'No', name: 'rnum', width: 25,  align: 'center'},
                    {display: 'Cod Sistema', name: 'COD_INMUEBLE', width: 100, sortable: true, align: 'center'},
                    {display: 'Marca', name: 'DESC_MED', width: 100, sortable: true, align: 'center'},
                    {display: 'Emplazamiento', name: 'DESC_EMPLAZAMIENTO', width: 90, sortable: true, align: 'center'},
                    {display: 'Calibre', name: 'DESC_CALIBRE', width: 100, sortable: true, align: 'center'},
                    {display: 'Serial', name: 'SERIAL', width: 100, sortable: true, align: 'center'},
                    {display: 'Fecha Instalacion', name: 'FECHA_INSTALACION', width: 120, sortable: true, align: 'center'},
                    {display: 'fecha baja', name: 'FECHA_BAJA', width: 100, sortable: true, align: 'center'},
                    {display: 'Metodo Suministro', name: 'METODO_SUMINISTRO', width: 100, sortable: true, align: 'center'},
                    {display: 'Estado Medidor', name: 'DESCRIPCION', width: 100, sortable: true, align: 'center'}

                ],





                sortname: "MI.SERIAL",
                sortorder: "asc",
                usepager: true,
                title: 'Medidores',
                useRp: true,
                rp: 30,
                page: 1,
                showTableToggleBtn: true,
                width: 900,
                height: 250
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

    </script>
    </body>
    </html>




<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

