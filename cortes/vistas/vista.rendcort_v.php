<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include_once ('../../include.php');
    include('../../destruye_sesion.php');

    $coduser = $_SESSION['codigo'];
    $proyecto = $_POST['proyecto'];
    $periodo = $_POST['periodo'];
    $sector = $_POST['sector'];
    $fecini = $_POST['fecini'];
    $fecfin = $_POST['fecfin'];

//Conectamos con la base de datos
    $Cnn = new OracleConn(UserGeneral, PassGeneral);
    $link = $Cnn->link;
    ?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <!--<meta http-equiv="refresh" content="5; URL=rendimiento.php?periodo=<?php echo $periodo1;?>&ruta=<?php echo $ruta1;?>&proc=1" />-->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" href="../../flexigrid/style.css" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
        <script type="text/javascript" src="../../alertas/dialog_box.js"></script>
        <link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" media="screen" href="../../css/Static_Tabstrip.css">

        <script language="javascript">
            $(document).ready(function() {
                $(".botonExcel").click(function(event) {
                    $("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).append( $("#flex1").eq(0).clone()).html());
                    $("#FormularioExportacion").submit();
                });
            });
        </script>
        <style type="text/css">
            iframe{
                margin-top:-10px;
                border-color: #666666;
                border:1px solid #ccc;
                display:none;
                width: 560px;
                height: 400px;
                float:left;
            }
        </style>
    </head>
    <body style="margin-top:-25px">
    <div id="content" style="padding:0px; width:1120px;">
        <form name="rendimiento" action="vista.rendcort.php" method="post" onsubmit="return rend();">
            <?php
            /*if($proyecto != '' && $periodo == '' && $sector == '' && $fecini == '' && $fecfin == ''){
                echo"
                <script type='text/javascript'>
                showDialog('Advertencia','Seleccione un periodo y sector o bien, seleccione un rango de fechas','warning',2);
                </script>";
            }*/
            if($proyecto == '' && $fecini != '' && $fecfin != ''){
                echo"
	<script type='text/javascript'>
	showDialog('Advertencia','Seleccione el Acueducto','warning',3);
	</script>";
            }
            ?>

            <h3 class="panel-heading" style=" background-color:#88A247; color:#FFFFFF; font-size:18px; width:1120px;" align="center">Rendimiento de Cortes</h3>
            <div style="text-align:center; width:1120px; margin-left:0px">
                <table width="100%" border="1" bordercolor="#CCCCCC" align="center">
                    <tr>
                        <td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="15%">Acueducto:&nbsp;</td>
                        <td align="left" width="15%" bgcolor="#EBEBEB">
                            <select name="proyecto" class='btn btn-default btn-sm dropdown-toggle'  onChange='recarga();'>
                                <option value="" selected>Seleccione acueducto...</option>
                                <?php
                                $sql = "SELECT PR.ID_PROYECTO, PR.SIGLA_PROYECTO FROM SGC_TP_PROYECTOS PR, SGC_TT_PERMISOS_USUARIO PU
						WHERE PR.ID_PROYECTO = PU.ID_PROYECTO AND PU.ID_USUARIO = '$coduser' ORDER BY 2";
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
                        <td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="15%">Periodo:&nbsp;</td>
                        <td align="left" width="15%" bgcolor="#EBEBEB">
                            <select id="periodo" name='periodo' class='btn btn-default btn-sm dropdown-toggle' onChange='recarga();'>
                                <option value="" selected>Seleccione periodo...</option>
                                <?php
                                $sql = "SELECT L.ID_PERIODO PERIODO
                        FROM SGC_TT_REGISTRO_CORTES L, SGC_TP_ZONAS Z, SGC_TT_INMUEBLES I
                        WHERE I.ID_ZONA = Z.ID_ZONA AND Z.ID_PROYECTO = '$proyecto'
                        AND I.CODIGO_INM=L.ID_INMUEBLE
                        AND L.ID_PERIODO>TO_CHAR(ADD_MONTHS(SYSDATE,-8),'YYYYMM')
                        GROUP BY L.ID_PERIODO ORDER BY L.ID_PERIODO DESC";
                                $stid = oci_parse($link, $sql);
                                oci_execute($stid, OCI_DEFAULT);
                                while (oci_fetch($stid)) {
                                    $cod_periodo = oci_result($stid, 'PERIODO') ;
                                    if($cod_periodo == $periodo) echo "<option value='$cod_periodo' selected>$cod_periodo</option>\n";
                                    else echo "<option value='$cod_periodo'>$cod_periodo</option>\n";
                                }oci_free_statement($stid);
                                ?>
                            </select>
                        </td>
                        <td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="15%">Sector:&nbsp;</td>
                        <td align="left" width="15%" bgcolor="#EBEBEB">
                            <select name='sector' id='sector' class='btn btn-default btn-sm dropdown-toggle' onChange='recarga();'>
                                <option value="" selected>Seleccione sector...</option>
                                <?php
                                $sql = "SELECT I.ID_SECTOR ZONA
                        FROM SGC_TT_REGISTRO_CORTES C, SGC_TP_SECTORES S, SGC_TT_INMUEBLES I
                        WHERE
                        C.ID_INMUEBLE=I.CODIGO_INM AND
                        I.ID_SECTOR=S.ID_SECTOR AND
                        C.ID_PERIODO= $periodo AND
                        S.ID_PROYECTO = '$proyecto'
                        AND C.FECHA_EJE IS NOT NULL
                        GROUP BY I.ID_SECTOR ORDER BY 1 ASC";
                                $stid = oci_parse($link, $sql);
                                oci_execute($stid, OCI_DEFAULT);
                                while (oci_fetch($stid)) {
                                    $cod_sector = oci_result($stid, 'ZONA') ;
                                    if($cod_sector == $sector) echo "<option value='$cod_sector' selected>$cod_sector</option>\n";
                                    else echo "<option value='$cod_sector'>$cod_sector</option>\n";
                                }oci_free_statement($stid);
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:13px" bgcolor="#EBEBEB" align="right" >Fecha Inicial:&nbsp;</td>
                        <td align="left" bgcolor="#EBEBEB">
                            <input class='btn btn-default btn-sm dropdown-toggle' style="height:27px" type="date" name="fecini" id="fecini" value="<?php echo $fecini;?>" onChange='recarga();'>
                        </td>
                        <td style="font-size:13px" bgcolor="#EBEBEB" align="right" >Fecha Final:&nbsp;</td>
                        <td align="left" bgcolor="#EBEBEB">
                            <input class='btn btn-default btn-sm dropdown-toggle' style="height:27px" type="date" name="fecfin" id="fecfin" value="<?php echo $fecfin;?>" onChange='recarga();'>
                        </td>
                        <td style="font-size:13px" bgcolor="#EBEBEB" align="right" width="15%" colspan="2"></td>

                    </tr>
                </table>
            </div>

            <?php
            if (($sector != "") || ($proyecto != "" && $fecini != "" && $fecfin != "") ){
                $agno = substr($periodo,0,4);
                $mes = substr($periodo,4,2);
                if($mes == '01'){$mes = Enero;} if($mes == '02'){$mes = Febrero;} if($mes == '03'){$mes = Marzo;} if($mes == '04'){$mes = Abril;}
                if($mes == '05'){$mes = Mayo;} if($mes == '06'){$mes = Junio;} if($mes == '07'){$mes = Julio;} if($mes == '08'){$mes = Agosto;}
                if($mes == '09'){$mes = Septiembre;} if($mes == '10'){$mes = Octubre;} if($mes == '11'){$mes = Noviembre;} if($mes == '12'){$mes = Diciembre;}
                $nomrepo = 'Rendimiento Operarios Corte - ';
                //echo "../datos/datos.rendcort.php?proyecto=$proyecto&periodo=$periodo&sector=$sector&fecini=$fecini&fecfin=$fecfin";
                ?>
                <!--form action="../../funciones/ficheroExcel.php?agno=<? echo $agno;?>&mes=<? echo $mes;?>&nomrepo=<? echo $nomrepo;?>" method="post" target="_blank"  id="FormularioExportacion">
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
</form-->
                <br />

                <div style="margin-top:-10px">
                    <table id="flex1" style="display:block">
                    </table>
                </div>
                <script type="text/javascript">
                    <!--
                    $('.flexme1').flexigrid();
                    $('.flexme2').flexigrid({height:'auto',striped:false});
                    $("#flex1").flexigrid	(
                        {
                            url: '../datos/datos.rendcort.php?proyecto=<?php echo $proyecto;?>&periodo=<?php echo $periodo;?>&sector=<?php echo $sector;?>&fecini=<?php echo $fecini;?>&fecfin=<?php echo $fecfin;?>',

                            dataType: 'json',
                            colModel : [
                                {display: 'N&deg;', name: 'numero', width: 30, sortable: true, align: 'center'},
                                {display: 'C\xf3digo', name: 'id_usuario', width: 90, sortable: true, align: 'center'},
                                {display: 'Nombre Lector', name: 'nom_completo', width: 180, sortable: true, align: 'center'},
                                {display: 'Ruta', name: 'ruta', width: 50, sortable: true, align: 'center'},
                                {display: 'Fecha<br>Asignaci\xf3n', name: 'fecha_asig', width: 80, sortable: true, align: 'center'},
                                {display: 'Total<br>Predios', name: 'total', width: 70, sortable: true, align: 'center'},
                                {display: 'Predios<br>Leidos', name: 'leidos', width: 70, sortable: true, align: 'center'},
                                {display: 'Porcentaje<br>Leidos', name: 'porc_leidos', width: 70, sortable: true, align: 'center'},
                                {display: 'Predios<br>No Leidos', name: 'no_leidos', width: 70, sortable: true, align: 'center'},
                                {display: 'Porcentaje<br>No Leidos', name: 'porc_noleido', width: 70, sortable: true, align: 'center'},
                                {display: 'Tiempo Total<br>Recorrido', name: 'hora', width: 80, sortable: true, align: 'center'},
                                {display: 'Tiempo Promedio<br>Por Predio', name: 'min_prom', width: 100, sortable: true, align: 'center'},
                                {display: 'Predios Promedio<br>Por Hora', name: 'predio_promedio_hora', width: 100, sortable: true, align: 'center'}
                            ],
                            /*searchitems : [
                             {display: 'C\xf3digo', name: 'id_usuario',isdefault: true},
                             {display: 'Zona', name: 'zona'},
                             {display: 'Fecha', name: 'fecha_asig'}
                             ],*/
                            sortname: "TO_CHAR(C.FECHA_PLANIFICACION,'DD/MM/YYYY')",
                            sortorder: "ASC",
                            usepager: true,
                            title: 'Listado Operarios Por Ruta',
                            useRp: true,
                            rp: 1000,
                            page: 1,
                            //showTableToggleBtn: true,
                            width: 1120,
                            height: 250
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
                        popup("vista.detalle_ruta_operario.php?periodo="+id2+"&ruta="+id3,1100,800,'yes');
                    }
                    function ruteo(id2,id3) { // Traer la fila seleccionada
                        popup2("vista.detalle_ruta_google.php?cod_ruta="+id3+"&periodo="+id2,1100,800,'yes');
                    }
                    //-->
                </script>

                <iframe id="detallerutaopecort" style="border-left:0px solid #ccc; border-right:0px solid #ccc"></iframe>
                <iframe id="rutageocort" style="border-left:0px solid #ccc; border-left:0px solid #ccc"></iframe>
                <?php
            }
            ?>
        </form>
    </div>
    </body>
    </html>
    <script type="text/javascript" language="javascript">
        function recarga() {
            document.rendimiento.submit();
        }
    </script>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

