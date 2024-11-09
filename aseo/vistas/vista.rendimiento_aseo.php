<?php
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>

    <?php
    session_start();
    include_once '../../include.php';
    include '../../destruye_sesion.php';

    $coduser     = $_SESSION['codigo'];
    $proyecto    = $_POST['proyecto'];
    $periodo     = $_POST['periodo_ini'];
    $periodo_fin = $_POST['periodo_fin'];
    $sector      = $_POST['sector'];
//$ruta = $_POST['ruta'];
    $proc = $_POST['proc'];

//Conectamos con la base de datos
    $Cnn  = new OracleConn(UserGeneral, PassGeneral);
    $link = $Cnn->link;
    ?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <!--<meta http-equiv="refresh" content="5; URL=rendimiento.php?periodo=<?php echo $periodo1; ?>&ruta=<?php echo $ruta1; ?>&proc=1" />-->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" media="screen" href="../../css/Static_Tabstrip.css">
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
        <style type="text/css">
            .flexigrid{width: 960px;}
        </style>

    </head>
    <body>
    <form name="rendimiento" action="vista.rendimiento_aseo.php" method="post" onsubmit="return rend();">
        <div class="flexigrid" style="width:960px">
            <div class="mDiv">
                <div><b>MANTENIMIENTO</b> >> Rendimiento Operarios >> Parametros de Busqueda</div>
                <div style="background-color:rgb(255,255,255)">
                    <table width="960px">
                        <tr>
                            <td>Ayuntamiento:</td>
                            <td>
                                <select name="proyecto" class="textbox" required><option></option>
                                    <?php
                                    $sql = "SELECT PR.ID_PROYECTO, PR.SIGLA_PROYECTO FROM SGC_TP_PROYECTOS PR, SGC_TT_PERMISOS_USUARIO PU
							WHERE PR.ID_PROYECTO = PU.ID_PROYECTO AND PU.ID_USUARIO = '$coduser'";
                                    $stid = oci_parse($link, $sql);
                                    oci_execute($stid, OCI_DEFAULT);
                                    while (oci_fetch($stid)) {
                                        $cod_proyecto   = oci_result($stid, 'ID_PROYECTO');
                                        $sigla_proyecto = oci_result($stid, 'SIGLA_PROYECTO');
                                        if($sigla_proyecto == "CAASD"){
                                            $sigla_proyecto = "Santo Domingo";
                                        } else{
                                            $sigla_proyecto = "Bocachica";
                                        }
                                        if ($cod_proyecto == $proyecto) {
                                            echo "<option value='$cod_proyecto' selected>$sigla_proyecto</option>\n";
                                        } else {
                                            echo "<option value='$cod_proyecto'>$sigla_proyecto</option>\n";
                                        }

                                    }
                                    oci_free_statement($stid);
                                    ?>
                                </select>
                            </td>
                            <td>Periodo ini:</td>
                            <td>
                                <select name="periodo_ini" class="textbox" onChange="recarga();" required><option></option>
                                    <?php
                                    $sql  = "SELECT ID_PERIODO FROM SGC_TT_ASIGNACION GROUP BY ID_PERIODO ORDER BY 1 DESC";
                                    $stid = oci_parse($link, $sql);
                                    oci_execute($stid, OCI_DEFAULT);
                                    while (oci_fetch($stid)) {
                                        $cod_periodo = oci_result($stid, 'ID_PERIODO');
                                        if ($cod_periodo == $periodo) {
                                            echo "<option value='$cod_periodo' selected>$cod_periodo</option>\n";
                                        } else {
                                            echo "<option value='$cod_periodo'>$cod_periodo</option>\n";
                                        }

                                    }
                                    oci_free_statement($stid);
                                    ?>
                                </select>
                            </td>
                            <td>Periodo fin:</td>
                            <td>
                                <select name="periodo_fin" class="textbox" onChange="recarga();" required><option></option>
                                    <?php
                                    $sql  = "SELECT ID_PERIODO FROM SGC_TT_ASIGNACION GROUP BY ID_PERIODO ORDER BY 1 DESC";
                                    $stid = oci_parse($link, $sql);
                                    oci_execute($stid, OCI_DEFAULT);
                                    while (oci_fetch($stid)) {
                                        $cod_periodo = oci_result($stid, 'ID_PERIODO');
                                        if ($cod_periodo == $periodo_fin) {
                                            echo "<option value='$cod_periodo' selected>$cod_periodo</option>\n";
                                        } else {
                                            echo "<option value='$cod_periodo'>$cod_periodo</option>\n";
                                        }

                                    }
                                    oci_free_statement($stid);
                                    ?>
                                </select>
                            </td>
                            <td>Sector:</td>
                            <td>
                                <select name="sector" class="textbox"><option></option>
                                    <?php
                                    $sql = "SELECT A.ID_SECTOR FROM SGC_TT_ASIGNACION A,SGC_TT_INMUEBLES I  WHERE A.ID_PERIODO BETWEEN '$periodo' and '$periodo_fin'
                                    AND I.CODIGO_INM=A.ID_INMUEBLE
                                    AND I.ID_PROYECTO='$proyecto'
                                    GROUP BY A.ID_SECTOR ORDER BY 1 ASC";
                                    $stid = oci_parse($link, $sql);
                                    oci_execute($stid, OCI_DEFAULT);
                                    while (oci_fetch($stid)) {
                                        $cod_sector = oci_result($stid, 'ID_SECTOR');
                                        if ($cod_sector == $sector) {
                                            echo "<option value='$cod_sector' selected>$cod_sector</option>\n";
                                        } else {
                                            echo "<option value='$cod_sector'>$cod_sector</option>\n";
                                        }

                                    }
                                    oci_free_statement($stid);
                                    ?>
                                </select>
                            </td>
                            <td>
                                <input type="submit" value="Buscar" name="Buscar" class="boton">
                                <input type="hidden" name="proc" value="<?php echo $proc; ?>">

                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </form>
    <?php
    if ($proc == 1) {
        $agno = substr($periodo, 0, 4);
        $mes  = substr($periodo, 4, 2);
        if ($mes == '01') {$mes = Enero;}if ($mes == '02') {$mes = Febrero;}if ($mes == '03') {$mes = Marzo;}if ($mes == '04') {$mes = Abril;}
        if ($mes == '05') {$mes = Mayo;}if ($mes == '06') {$mes = Junio;}if ($mes == '07') {$mes = Julio;}if ($mes == '08') {$mes = Agosto;}
        if ($mes == '09') {$mes = Septiembre;}if ($mes == '10') {$mes = Octubre;}if ($mes == '11') {$mes = Noviembre;}if ($mes == '12') {$mes = Diciembre;}
        $nomrepo = 'Rendimiento Operarios Catastro - ';
        ?>
        <form action="../../funciones/ficheroExcel.php?agno=<?echo $agno; ?>&mes=<?echo $mes; ?>&nomrepo=<?echo $nomrepo; ?>" method="post" target="_blank"  id="FormularioExportacion">
            <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
            <div class="flexigrid" style="960px">
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
                    <th align="center">RUTA</th>
                    <th align="center">FECHA<br/>ASIGNACI&Oacute;N</th>
                    <th align="center">FECHA<br/>INICIO</th>
                    <th align="center">FECHA<br/>FINAL</th>
                    <th align="center">TOTAL<br/>PREDIOS</th>
                    <th align="center">TOTAL<br/>LEIDOS</th>
                    <th align="center">PORCENTAJE<br/>LEIDOS</th>
                    <th align="center">TOTAL<br/>NO LEIDOS</th>
                    <th align="center">PORCENTAJE<br/>NO LEIDOS</th>
                    <th align="center">TIEMPO TOTAL<br/>RECORRIDO</th>
                    <th align="center">TIEMPO PROMEDIO<br/>PREDIO</th>
                    <th align="center">PREDIOS PROMEDIO<br/>POR HORA</th>
                    <th align="center">ANULADO</th>
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
                    url: '../datos/datos.rendimiento_aseo.php?proyecto=<?php echo $proyecto; ?>&periodoI=<?php echo $periodo; ?>&periodoF=<?php echo $periodo_fin; ?>&sector=<?php echo $sector; ?>',
                    dataType: 'json',
                    colModel : [
                        {display: 'No', name: 'numero', width: 24, sortable: true, align: 'center'},
                        {display: 'C\xf3digo', name: 'id_usuario', width: 68, sortable: true, align: 'center'},
                        {display: 'Nombre Inspector', name: 'nom_completo', width: 137, sortable: true, align: 'center'},
                        {display: 'Ruta', name: 'ruta', width: 25, sortable: true, align: 'center'},
                        {display: 'Fecha<br>Asignaci\xf3n', name: 'fecha_asig', width: 50, sortable: true, align: 'center'},
                        {display: 'Fecha<br>Inicio', name: 'fecinicio', width: 93, sortable: true, align: 'center'},
                        {display: 'Fecha<br>Final', name: 'fecfinal', width: 93, sortable: true, align: 'center'},
                        {display: 'Total<br>Predios', name: 'total', width: 32, sortable: true, align: 'center'},
                        {display: 'Predios<br>Leidos', name: 'predio_leido', width: 30, sortable: false, align: 'center'},
                        {display: 'Porcentaje<br>Leidos', name: 'porc_leidos', width: 46, sortable: false, align: 'center'},
                        {display: 'Predios<br>No Leidos', name: 'noleidos', width: 43, sortable: true, align: 'center'},
                        {display: 'Porcentaje<br>No Leidos', name: 'porc_noleido', width: 46, sortable: false, align: 'center'},
                        {display: 'Tiempo Total<br>Recorrido', name: 'hora', width: 58, sortable: false, align: 'center'},
                        {display: 'Tiempo Promedio<br>Por Predio', name: 'min_prom', width: 79, sortable: false, align: 'center'},
                        {display: 'Predios Promedio<br>Por Hora', name: 'predio_promedio_hora', width: 80, sortable: false, align: 'center'},
                        {display: 'Anulado', name: 'anulado', width: 80, sortable: false, align: 'center'}
                    ],
                    searchitems : [
                        {display: 'C\xf3digo', name: 'id_usuario',isdefault: true},
                        {display: 'Ruta', name: 'ruta'},
                        {display: 'Fecha', name: 'fecha_asig'}
                    ],
                    sortname: "TO_CHAR(A.FECHA_ASIG,'DD/MM/YYYY')",
                    sortorder: "DESC",
                    usepager: true,
                    title: 'Listado Operarios Por Ruta',
                    useRp: true,
                    rp: 1000,
                    page: 1,
                    showTableToggleBtn: true,
                    width: 1000,
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
            function upCliente(id2,id1,id3) { // Traer la fila seleccionada
                popup("vista.detalle_ruta_aseo_operario.php?periodoI="+id2+"&periodoF="+id1+"&ruta="+id3,1100,800,'yes');
            }
            function ruteo(id3,id4,id2) { // Traer la fila seleccionada
                popup2("vista.detalle_ruta_google.php?cod_ruta="+id3+"&periodoI="+id4+"&periodoF="+id2,1100,800,'yes');
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
            //document.rendimiento.ruta.selectedIndex = 0;
            document.rendimiento.submit();
        }

    </script>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

