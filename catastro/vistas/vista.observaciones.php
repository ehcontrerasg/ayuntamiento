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
        <link rel="stylesheet" href="../../flexigrid/style.css?<?php echo time();?>" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css?<?php echo time();?>">
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js?<?php echo time();?>"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js?<?php echo time();?>"></script>
        <script type="text/javascript" src="../js/observaciones.js?<?php echo time();?>"></script>
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css?<?php echo time();?>" />
        <link rel="stylesheet" type="text/css" href="../css/catastro.css?<?php echo time();?>" />
        <script type="text/javascript" src="../../js/sweetalert.min.js?<?php echo time();?>"></script>
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
    <form name="formCatObs" action="vista.observaciones.php?cod_inmueble=<?php echo $inmueble;?>" method="post"">
    <div class="flexigrid" style="width:100%">
        <div class="mDiv">
            <div><b>INCORPORACIÓN</b> >> Inmuebles >> medidores</div>

        </div>
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
                <th align="center">CONSECUTIVO</th>
                <th align="center">ASUNTO</th>
                <th align="center">CODIGO</th>
                <th align="center">DESCRIPCION</th>
                <th align="center">FECHA</th>
                <th align="center">USUARIO</th>
            </tr>
        </table>
        <table id="flex1" style="display:none">
        </table>
    </div>
    <div>
        <label>asunto:</label> <input type="text" id="inpCatObsAsunto" style="width: 300px"/>
        <label>Codigo:</label> <select id="selCatObsTipoObs" style="width: 300px" font-size: 2em;   > </select>
        <label>Descripcion:</label> <textarea type="text" id="inpCatObsDesc" style="width: 600px"/> </textarea>
        <button id="butCatObsGuardar">guardar</button>
    </div>

    <script type="text/javascript">
        obsInicio();
        flexyObs();

    </script>
    </body>
    </html>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

