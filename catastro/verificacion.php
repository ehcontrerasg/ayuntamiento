<?
include_once ('../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>

    <?php
    session_start();
    include('../destruye_sesion.php');
    include_once ('../include.php');

    $coduser = $_SESSION['codigo'];
    $proyecto = $_POST['proyecto'];
    $periodo = $_POST['periodo'];
    $operario = $_POST['operario'];
    $proc = $_POST['proc'];

//Conectamos con la base de datos
    $Cnn = new OracleConn(UserGeneral, PassGeneral);
    $link = $Cnn->link;
    ?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="refresh" content="100000; URL=verificacion.php?periodo=<?php echo $periodo;?>&operario=<?php echo $operario;?>&proc=1" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" media="screen" href="../css/Static_Tabstrip.css">
        <link rel="stylesheet" href="../flexigrid/style.css" />
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
    <form name="verificacion_mant" id="verificacion_mant" action="verificacion.php" method="post" onsubmit="return verifica_mant();">
        <div class="flexigrid" style="width:100%">
            <div class="mDiv">
                <div><b>MANTENIMIENTO</b> >> Validaci&oacute;n Inmuebles >> Parametros de Busqueda</div>
                <div style="background-color:rgb(255,255,255)">
                    <table width="100%">
                        <tr>
                            <td>Proyecto:</td>
                            <td>
                                <select name="proyecto" class="textbox" onChange="recarga();" required><option></option>
                                    <?php
                                    $sql = "SELECT PR.ID_PROYECTO, PR.SIGLA_PROYECTO FROM SGC_TP_PROYECTOS PR, SGC_TT_PERMISOS_USUARIO PU
						WHERE PR.ID_PROYECTO = PU.ID_PROYECTO AND PU.ID_USUARIO = '$coduser'";
                                    $stid = oci_parse($link, $sql);
                                    oci_execute($stid, OCI_DEFAULT);
                                    while (oci_fetch($stid)) {
                                        $cod_proyecto = oci_result($stid, 'ID_PROYECTO') ;
                                        $sigla_proyecto = oci_result($stid, 'SIGLA_PROYECTO') ;
                                        if($cod_proyecto == $proyecto) echo "<option value='$cod_proyecto' selected>$sigla_proyecto</option>\n";
                                        else echo "<option value='$cod_proyecto'>$sigla_proyecto</option>\n";
                                    }oci_free_statement($stid);
                                    ?>
                                </select>
                            </td>
                            <td>Periodo:</td>
                            <td>
                                <select name="periodo" class="textbox" onChange="recarga();" required><option></option>
                                    <?php
                                    $sql = "SELECT DISTINCT  M.ID_PERIODO FROM SGC_TT_MANTENIMIENTO M, SGC_Tt_INMUEBLES I WHERE M.ACTUALIZADO ='N'
                              AND I.CODIGO_INM=M.ID_INMUEBLE
                              AND I.ID_PROYECTO='$proyecto'
                            ORDER BY(M.ID_PERIODO)";
                                    $stid = oci_parse($link, $sql);
                                    oci_execute($stid, OCI_DEFAULT);
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
                                    $sql = "SELECT DISTINCT A.ID_OPERARIO, U.NOM_USR, U.APE_USR 
                        FROM SGC_TT_ASIGNACION A, SGC_TT_INMUEBLES I, SGC_TT_USUARIOS U, SGC_TT_MANTENIMIENTO MAN
						WHERE A.ID_INMUEBLE = I.CODIGO_INM AND U.ID_USUARIO = A.ID_OPERARIO
						AND I.ID_PROYECTO = '$proyecto' AND A.ID_PERIODO = $periodo
						AND MAN.ID_INMUEBLE=I.CODIGO_INM
						AND MAN.ACTUALIZADO='N'
						AND MAN.ID_PERIODO=A.ID_PERIODO";
                                    $stid = oci_parse($link, $sql);
                                    oci_execute($stid, OCI_DEFAULT);
                                    while (oci_fetch($stid)) {
                                        $cod_operario = oci_result($stid, 'ID_OPERARIO') ;
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
                                <input type="submit" value="Buscar" name="Buscar" class="boton">
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
                    <th align="center">COD SISTEMA</th>
                    <th align="center">SECTOR</th>
                    <th align="center">RUTA</th>
                    <th align="center">URBANIZACI&Oacute;N</th>
                    <th align="center">DIRECCI&Oacute;N</th>
                    <th align="center">CLIENTE</th>
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
            <!--
            $('.flexme1').flexigrid();
            $('.flexme2').flexigrid({height:'auto',striped:false});
            $("#flex1").flexigrid	(
                {
                    url: 'datos_verificacion.php?periodo=<?php echo $periodo;?>&operario=<?php echo $operario;?>',
                    dataType: 'json',
                    colModel : [
                        {display: 'No', name: 'numero', width: 20, sortable: true, align: 'center'},
                        {display: 'Cod Sistema', name: 'id_inmueble', width: 60, sortable: true, align: 'center'},
                        {display: 'Sector', name: 'id_sector', width: 60, sortable: true, align: 'center'},
                        {display: 'Ruta', name: 'id_ruta', width: 60, sortable: true, align: 'center'},
                        {display: 'Urbanizaci\xf3n', name: 'desc_urbanizacion', width: 100, sortable: true, align: 'center'},
                        {display: 'Direcci\xf3n', name: 'direccion', width: 120, sortable: true, align: 'center'},
                        {display: 'Cliente', name: 'nombre_cli', width: 155, sortable: true, align: 'center'},
                        {display: 'Id Proceso', name: 'id_proceso', width: 80, sortable: true, align: 'center'},
                        {display: 'Uso', name: 'id_uso', width: 30, sortable: true, align: 'center'},
                        {display: 'Actividad', name: 'id_actividad', width: 45, sortable: true, align: 'center'},
                        {display: 'Unidades', name: 'total_unidades', width: 45, sortable: true, align: 'center'},
                        {display: 'Estado', name: 'id_estado', width: 40, sortable: true, align: 'center'}
                    ],
                    searchitems : [
                        {display: 'Cliente', name: 'nombre_cli'},
                        {display: 'Uso', name: 'id_uso'},
                        {display: 'Cod Sistema', name: 'id_inmueble',isdefault: true}
                    ],
                    sortname: "id_proceso",
                    sortorder: "asc",
                    usepager: true,
                    title: 'LISTADO INMUEBLES A VERIFICAR - INSPECTOR <? echo $operario;?>',
                    useRp: true,
                    rp: 100,
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

            function upCliente(id2,id3) { // Traer la fila seleccionada
                popup("vistas/vista.infomantenimiento.php?cod="+id2+"&per="+id3,1200,700,'yes');
            }
            //-->
        </script>
        <?php
    }
    ?>
    </body>
    </html>
    <script type="text/javascript" language="javascript">


        function verifica_mant(){
            if (document.verificacion_mant.proyecto.value == "") {
                document.verificacion_mant.proc.value=0;
                return false;
            }
            if (document.verificacion_mant.periodo.value == "") {
                document.verificacion_mant.proc.value=0;
                return false;
            }
            if (document.verificacion_mant.operario.value == "") {
                document.verificacion_mant.proc.value=0;
                return false;
            }
            else {
                document.verificacion_mant.proc.value = 1;
                return true;
            }
        }

        function recarga() {
            document.verificacion_mant.submit();
        }
    </script>

<?php endif;
if ($verificarPermisos==false):
    include "../general/vistas/vista.PlantillaError.php";
endif; ?>

