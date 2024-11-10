<?php
error_reporting(E_ERROR);
ini_set("display_errors", "1");
include_once ('../clases/class.PermisosURL.php');
include_once ('../clases/class.asignacion.php');
include_once ('../clases/class.proyecto.php');
include_once ('../clases/class.sector.php');
include_once ('../clases/class.ruta.php');
include_once ('../clases/class.periodo.php');
include_once ('../clases/class.usuario.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>

    <?php
    session_start();
    include('../destruye_sesion.php');
    $coduser = $_SESSION['codigo'];
    $proyecto = $_POST['proyecto'];
    $sector = $_POST['sector'];
    $ruta = $_POST['ruta'];
    $periodo = $_POST['periodo'];
    $operario = $_POST['operario'];
    $proc = $_POST['proc'];

    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" media="screen" href="../css/Static_Tabstrip.css">
        <link rel="stylesheet" href="../flexigrid/style.css?<?echo time();?>" />
        <link rel="stylesheet" type="text/css" href="../flexigrid/css/flexigrid/flexigrid.css">
        <script type="text/javascript" src="../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../flexigrid/flexigrid.js"></script>
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
    <?php

    if($proc == 1){
        $pendientes=0;
        $a=new Asignacion();
        $stid=$a->getCantidadBySectorRuta($sector,$ruta);
        while (oci_fetch($stid)) {
            $pendientes = oci_result($stid, 'CANTIDAD');
        }oci_free_statement($stid);


        if($pendientes==0){
            $stid=$a->getInmXAsignarBySectorRuta($sector,$ruta);
            //echo $sql;
            while (oci_fetch($stid)) {
                $zona_inm = oci_result($stid, 'ID_ZONA');
                $codigo_inm = oci_result($stid, 'CODIGO_INM');
                $direccion_inm = oci_result($stid, 'DIRECCION');
                $urbanizacion = oci_result($stid, 'DESC_URBANIZACION');
                $codigo_cli = oci_result($stid, 'CODIGO_CLI');
                $nombre_cli = oci_result($stid, 'NOMBRE_CLI');
                $codigo_doc = oci_result($stid, 'TIPO_DOC');
                $documento_cli = oci_result($stid, 'DOCUMENTO');
                $telefono_cli = oci_result($stid, 'TELEFONO');
                $proceso = oci_result($stid, 'ID_PROCESO');
                $catastro = oci_result($stid, 'CATASTRO');
                $uso_inm = oci_result($stid, 'ID_USO');
                $actividad_inm = oci_result($stid, 'ID_ACTIVIDAD');
                $unidades_inm = oci_result($stid, 'TOTAL_UNIDADES');
                $tipo_cliente = oci_result($stid, 'ID_TIPO_CLIENTE');
                $estado_inm = oci_result($stid, 'ID_ESTADO');
                $id_proyecto = oci_result($stid, 'ID_PROYECTO');
                if($unidades_inm == ""){
                    $unidades_inm = '0';
                }
                $a->insertaAsignacion($codigo_inm,$operario,$sector,$ruta,$periodo,$coduser);
            }oci_free_statement($stid);
        }else{
            echo '<script language="javascript">alert("Error en la asignacion, esta ruta aun tiene pendientes ';
            echo $pendientes;
            echo ' predios por realizar  mantenimiento");</script>';
        }
    }
    ?>
    <form name="asignacion_mant" action="asignacion.php" method="post" onsubmit="return verifica_asig();">
        <div class="flexigrid" style="width:100%">
            <div class="mDiv">
                <div><b>MANTENIMIENTO</b> >> Asignaci&oacute;n Rutas >> Parametros de Asignaci&oacute;n</div>
                <div style="background-color:rgb(255,255,255)">
                    <table width="100%">
                        <tr>
                            <td>Proyecto:</td>
                            <td>
                                <select name="proyecto" class="textbox" onChange="recarga();" required><option></option>
                                    <?php
                                    $a= new Proyecto();
                                    $stid=$a->obtenerProyecto($coduser);
                                    while (oci_fetch($stid)) {
                                        $cod_proyecto = oci_result($stid, 'CODIGO') ;
                                        $sigla_proyecto = oci_result($stid, 'DESCRIPCION') ;
                                        if($cod_proyecto == $proyecto) echo "<option value='$cod_proyecto' selected>$sigla_proyecto</option>\n";
                                        else echo "<option value='$cod_proyecto'>$sigla_proyecto</option>\n";
                                    }oci_free_statement($stid);
                                    ?>
                                </select>
                            </td>
                            <td>Sector:</td>
                            <td>
                                <select name="sector" class="textbox" onChange="recarga();" required><option></option>
                                    <?php
                                    $a= new Sector();
                                    $stid=$a->getSecMantCorByPro($proyecto);
                                    while (oci_fetch($stid)) {
                                        $cod_sector = oci_result($stid, 'CODIGO') ;
                                        if($cod_sector == $sector) echo "<option value='$cod_sector' selected>$cod_sector</option>\n";
                                        else echo "<option value='$cod_sector'>$cod_sector</option>\n";
                                    }oci_free_statement($stid);
                                    ?>
                                </select>
                            </td>
                            <td>Ruta:</td>
                            <td>
                                <select name="ruta" class="textbox" onChange="recarga();" required><option></option>
                                    <?php
                                    $a= new Ruta();
                                    $stid=$a->getRutaBySectorProyecto($sector,$proyecto);
                                    while (oci_fetch($stid)) {
                                        $cod_ruta = oci_result($stid, 'ID_RUTA') ;
                                        if($cod_ruta == $ruta) echo "<option value='$cod_ruta' selected>$cod_ruta</option>\n";
                                        else echo "<option value='$cod_ruta'>$cod_ruta</option>\n";
                                    }oci_free_statement($stid);
                                    ?>
                                </select>
                            </td>
                            <td>Periodo:</td>
                            <td>
                                <select name="periodo" class="textbox" required><option></option>
                                    <?php
                                    $a=new Periodo();
                                    $stid=$a->getPeriodoAsig();
                                    while (oci_fetch($stid)) {
                                        $cod_periodo = oci_result($stid, 'ID_PERIODO') ;
                                        if($cod_periodo == $periodo) echo "<option value='$cod_periodo' selected>$cod_periodo</option>\n";
                                        else echo "<option value='$cod_periodo'>$cod_periodo</option>\n";
                                    }oci_free_statement($stid);
                                    ?>
                                </select>
                            </td>
                            <td>Inspector:</td>
                            <td>
                                <select name="operario" class="textbox" required><option></option>
                                    <?php
                                    $a= new Usuario();
                                    $stid=$a->getUsrasigByProyect($proyecto);
                                    while (oci_fetch($stid)) {
                                        $cod_operario = oci_result($stid, 'ID_USUARIO') ;
                                        $nom_operario = oci_result($stid, 'NOM_USR') ;
                                        $ape_operario = oci_result($stid, 'APE_USR') ;
                                        $nom_completo = $nom_operario." ".$ape_operario;
                                        if($cod_operario == $operario) echo "<option value='$cod_operario' selected>$nom_completo</option>\n";
                                        else echo "<option value='$cod_operario'>$nom_completo</option>\n";
                                    }//unset($cod_periodo);
                                    oci_free_statement($stid);
                                    ?>
                                </select>
                            </td>
                            <td>
                                <input type="submit" value="Asignar" name="Asignar" class="boton">
                                <input type="hidden" name="proc" value="<?php echo $proc;?>">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </form>
    <?php
    if($proc == 1){
        ?>
        <form action="../funciones/ficheroExcel.php" method="post" target="_blank"  id="FormularioExportacion">
            <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
            <div class="flexigrid" style="width:100%">
                <div class="mDiv">
                    <div>Exportar a:</div>
                    <div style="background-color:rgb(255,255,255)">
                        <img src="../images/excel/xls.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="Excel"/>
                        <img src="../images/excel/pdf.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="PDF"/>
                    </div>
                </div>
            </div>
        </form>
        <div>
            <table id="Exportar_a_Excel" style="display:none">
                <tr>
                    <th align="center">No</th>
                    <th align="center">ZONA</th>
                    <th align="center">COD SISTEMA</th>
                    <th align="center">CONTRATO</th>
                    <th align="center">DIRECCI&Oacute;N</th>
                    <th align="center">URBANIZACI&Oacute;N</th>
                    <th align="center">CLIENTE</th>
                    <th align="center">TIPO DOCUMENTO</th>
                    <th align="center">DOCUMENTO</th>
                    <th align="center">TELEFONO</th>
                    <th align="center">ID PROCESO</th>
                    <th align="center">CATASTRO</th>
                    <th align="center">USO</th>
                    <th align="center">ACTIVIDAD</th>
                    <th align="center">UNIDADES</th>
                    <th align="center">ESTADO</th>
                </tr>
            </table>
            <table id="flex1" style="display:none">
            </table>
        </div>
        <script type="text/javascript">            
            $('.flexme1').flexigrid();
            $('.flexme2').flexigrid({height:'auto',striped:false});
            $("#flex1").flexigrid	(
                {
                    url: 'datos_asignacion.php?proyecto=<?php echo $proyecto;?>&sector=<?php echo $sector;?>&ruta=<?php echo $ruta;?>&periodo=<?php echo $periodo;?>&operario=<?php echo $operario;?>',
                    dataType: 'json',
                    colModel : [
                        {display: 'No', name: 'numero', width: 20, sortable: false, align: 'center'},
                        {display: 'Zona', name: 'id_zona', width: 25, sortable: true, align: 'center'},
                        {display: 'Cod Sistema', name: 'codigo_inm', width: 60, sortable: true, align: 'center'},
                        {display: 'Contrato', name: 'id_contrato', width: 70, sortable: true, align: 'center'},
                        {display: 'Direcci\xf3n', name: 'direccion', width: 120, sortable: true, align: 'center'},
                        {display: 'Urbanizaci\xf3n', name: 'desc_urbanizacion', width: 100, sortable: true, align: 'center'},
                        {display: 'Cliente', name: 'nombre_cli', width: 150, sortable: true, align: 'center'},
                        {display: 'Tipo Doc', name: 'tipo_doc', width: 80, sortable: true, align: 'center'},
                        {display: 'Documento', name: 'documento', width: 80, sortable: true, align: 'center'},
                        {display: 'Telefono', name: 'telefono', width: 70, sortable: true, align: 'center'},
                        {display: 'Id Proceso', name: 'id_proceso', width: 80, sortable: true, align: 'center'},
                        {display: 'Catastro', name: 'catastro', width: 105, sortable: true, align: 'center'},
                        {display: 'Uso', name: 'id_uso', width: 30, sortable: true, align: 'center'},
                        {display: 'Actividad', name: 'id_actividad', width: 45, sortable: true, align: 'center'},
                        {display: 'Unidades', name: 'total_unidades', width: 45, sortable: true, align: 'center'},
                        {display: 'Estado', name: 'id_estado', width: 40, sortable: true, align: 'center'}
                    ],
                    searchitems : [
                        {display: 'Cliente', name: 'nombre_cli'},
                        {display: 'Contrato', name: 'id_contrato'},
                        {display: 'Id Proceso',name: 'id_proceso'},
                        {display: 'Uso', name: 'id_uso'},
                        {display: 'Cod Sistema', name: 'codigo_inm', isdefault: true}
                    ],
                    sortname: "id_proceso",
                    sortorder: "asc",
                    usepager: true,
                    title: 'Listado Inmuebles Mantenimiento',
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

            function upCliente(id2) { // Traer la fila seleccionada
                popup("infoanalisis.php?id="+id2,1100,800,'yes');
            }

            //-->
        </script>
        <?php
    }
    ?>
    </body>
    </html>
    <script type="text/javascript" language="javascript">


        function verifica_asig(){
            if (document.asignacion_mant.proyecto.value == "") {
                document.asignacion_mant.proc.value=0;
                return false;
            }
            if (document.asignacion_mant.sector.value == "") {
                document.asignacion_mant.proc.value=0;
                return false;
            }
            if (document.asignacion_mant.ruta.value == "") {
                document.asignacion_mant.proc.value=0;
                return false;
            }
            if (document.asignacion_mant.periodo.value == "") {
                document.asignacion_mant.proc.value=0;
                return false;
            }
            if (document.asignacion_mant.operario.value == "") {
                document.asignacion_mant.proc.value=0;
                return false;
            }
            else {
                document.asignacion_mant.proc.value = 1;
                return true;
            }
        }

        function recarga() {
            //document.asignacion_mant.operario.selectedIndex = 0;
            document.asignacion_mant.submit();
        }
    </script>


<?php endif;
if ($verificarPermisos==false):
    include "../general/vistas/vista.PlantillaError.php";
endif; ?>

