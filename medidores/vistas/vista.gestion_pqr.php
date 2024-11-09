<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include '../../clases/class.proyecto.php';
    include '../../clases/class.pqr.php';
    include '../../clases/class.usuario.php';
    include '../../destruye_sesion.php';

    $coduser = $_SESSION['codigo'];
    $proyecto = $_POST['proyecto'];
    $tipo_pqr = $_POST['tipo_pqr'];
    $secini = $_POST['secini'];
    $secfin = $_POST['secfin'];
    $rutini = $_POST['rutini'];
    $rutfin = $_POST['rutfin'];
    $fecini = $_POST['fecini'];
    $fecfin = $_POST['fecfin'];
    $cod_inmueble = $_POST['cod_inmueble'];
//$area_user= $_POST['area_user'];
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" media="screen" href="../../css/Static_Tabstrip.css">
        <link rel="stylesheet" href="../../css/tablas_catastro.css">
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <link href="../../css/css.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="../../flexigrid/style.css" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
        <link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
        <!--script src="../../js/jquery.min.js"></script>
        <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js"></script-->



        <style type="text/css">
            .input{
                border:1px solid #ccc;
                font-family: Arial, Helvetica, sans-serif;
                font-size:11px;
                height:16px;
                font-weight:normal;
            }
            .inputn{
                border:1px solid #ccc;
                font-family: Arial, Helvetica, sans-serif;
                font-size:11px;
                height:16px;
                width:40px;
                font-weight:normal;
            }
        </style>

    </head>
    <body>
    <form name="reporte_hisfac" action="vista.gestion_pqr.php" method="post" >
        <h3 class="panel-heading" style=" background-color:#7F1B3F; color:#FFFFFF; font-size:18px; width:1120px; margin-top:-5px" align="center">Gesti&oacute;n Solicitudes, Reclamos y Consultas</h3>
        <div class="flexigrid" style="width:1120px">
            <div class="mDiv">
                <div>Filtros de B&uacute;squeda Solicitudes, Reclamos y Consultas</div>
                <div style="background-color:rgb(255,255,255)">
                    <table width="100%">
                        <tr>
                            <td width="17%" style=" border:1px solid #EEEEEE; text-align:center">Acueducto<br />
                                <select name="proyecto" class="input"><option></option>
                                    <?php
                                    $l=new Proyecto();
                                    $registros=$l->obtenerProyecto($coduser);
                                    while (oci_fetch($registros)) {
                                        $cod_proyecto = oci_result($registros, 'CODIGO') ;
                                        $sigla_proyecto = oci_result($registros, 'DESCRIPCION') ;
                                        if($cod_proyecto == $proyecto) echo "<option value='$cod_proyecto' selected>$sigla_proyecto</option>\n";
                                        else echo "<option value='$cod_proyecto'>$sigla_proyecto</option>\n";
                                    }oci_free_statement($registros);
                                    ?>
                                </select>
                            </td>
                            <td width="17%" style=" border:1px solid #EEEEEE; text-align:center">Tipo PQR<br />
                                <select name="tipo_pqr" class="input"><option></option>
                                    <?php
                                    $l=new Pqr();
                                    $registros=$l->getTipPqr();
                                    while (oci_fetch($registros)) {
                                        $cod_tipo = oci_result($registros, 'ID_TIPO_RECLAMO') ;
                                        $des_tipo = oci_result($registros, 'DESC_TIPO_RECLAMO') ;
                                        if($cod_tipo == $tipo_pqr) echo "<option value='$cod_tipo' selected>$des_tipo</option>\n";
                                        else echo "<option value='$cod_tipo'>$des_tipo</option>\n";
                                    }oci_free_statement($registros);
                                    ?>
                                </select>
                            </td>
                            <td width="25%" style=" border:1px solid #EEEEEE; text-align:center">Sector<br />
                                Desde:&nbsp;&nbsp;<input type="number" name="secini" id="secini" value="<?php echo $secini;?>" class="inputn" min ="0" max="99" />
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                Hasta:&nbsp;&nbsp;<input type="number" name="secfin" id="secfin" value="<?php echo $secfin;?>" class="inputn" min ="0" max="99" />
                            </td>
                            <td width="25%" style=" border:1px solid #EEEEEE; text-align:center">Ruta<br />
                                Desde:&nbsp;&nbsp;<input type="number" name="rutini" id="rutini" value="<?php echo $rutini;?>" class="inputn" min ="0" max="99" />
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                Hasta:&nbsp;&nbsp;<input type="number" name="rutfin" id="rutfin" value="<?php echo $rutfin;?>" class="inputn" min ="0" max="99" />
                            </td>
                            <td width="22%" style=" border:1px solid #EEEEEE" rowspan="2" align="center">
                                <button style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#7F1B3F; color:#7F1B3F; display:block" type="submit"
                                        name="Buscar" id="Buscar"class="btn btn btn-INFO"><i class="fa fa-search"></i><b>&nbsp;Buscar</b>
                                </button>
                                <button style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#7F1B3F; color:#7F1B3F; display:block" type="submit"
                                        name="Imprimir" id="Imprimir"class="btn btn btn-INFO"><i class="fa fa-print fa-lg"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td width="18%" style=" border:1px solid #EEEEEE; text-align:center">Inmueble<br />
                                <input type="number" name="cod_inmueble" id="cod_inmueble" value="<?php echo $cod_inmueble;?>" class="input" style="width:60px" />
                            </td>
                            <td width="25%" style=" border:1px solid #EEEEEE; text-align:center" colspan="2">Fecha Generaci&oacute;n<br />
                                Desde:&nbsp;&nbsp;<input type="date" name="fecini" id="fecini" value="<?php echo $fecini;?>" class="input"/>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                Hasta:&nbsp;&nbsp;<input type="date" name="fecfin" id="fecfin" value="<?php echo $fecfin;?>" class="input"/>
                            </td>
                            <td style=" border:1px solid #EEEEEE; text-align:center">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </form>
    <?php
    $l=new Usuario();
    $registros=$l->getAreaUsuByUsu($coduser);
    while (oci_fetch($registros)) {
        $area_user = oci_result($registros, 'ID_AREA') ;
    }oci_free_statement($registros);
    if(isset($_REQUEST["Imprimir"])){
        ?>
        <script type="text/javascript">window.open('vista.docmasivo_pqr.php?tipo_pqr=<?php echo $tipo_pqr;?>&proyecto=<?php echo $proyecto;?>&secini=<?php echo $secini;?>&secfin=<?php echo $secfin;?>&rutini=<?php echo $rutini;?>&rutfin=<?php echo $rutfin;?>&fecini=<?php echo $fecini;?>&fecfin=<?php echo $fecfin;?>&cod_inmueble=<?php echo $cod_inmueble;?>&area_user=<?php echo 10;?>')</script>
        <?php
    }
    if(isset($_REQUEST["Buscar"])){

        ?>
        <table id="flex1" style="display:none">
        </table>
        </div>
        <script type="text/javascript">
            <!--
            $('.flexme1').flexigrid();
            $('.flexme2').flexigrid({height:'auto',striped:false});
            $("#flex1").flexigrid	(
                {
                    url: './../datos/datos.gestion_pqr.php?tipo_pqr=<?php echo $tipo_pqr;?>&proyecto=<?php echo $proyecto;?>&secini=<?php echo $secini;?>&secfin=<?php echo $secfin;?>&rutini=<?php echo $rutini;?>&rutfin=<?php echo $rutfin;?>&fecini=<?php echo $fecini;?>&fecfin=<?php echo $fecfin;?>&cod_inmueble=<?php echo $cod_inmueble;?>&area_user=<?php echo $area_user;?>',
                    dataType: 'json',
                    colModel : [
                        {display: 'N&deg;', name: 'rnum', width: 25,  align: 'left'},
                        {display: 'C&oacute;digo<br>PQR', name: 'codigo_pqr', width: 60, sortable: true, align: 'left'},
                        {display: 'Inmueble', name: 'cod_inm', width: 60, sortable: true, align: 'left'},
                        {display: 'Fecha<br>Entrada', name: 'fecha_pqr', width: 110, sortable: true, align: 'left'},
                        {display: 'Motivo', name: 'motivo_pqr', width: 250, sortable: true, align: 'left'},
                        {display: 'Oficina', name: 'entidad_pqr', width: 40, sortable: true, align: 'left'},
                        {display: 'Usuario', name: 'user_pqr', width: 90, sortable: true, align: 'left'},
                        {display: 'Fecha Max<br>Resoluci&oacute;n', name: 'fecha_max', width: 80, sortable: true, align: 'left'},
                        {display: 'Proceso', name: 'proceso_inm', width: 80, sortable: true, align: 'left'},
                        {display: 'Depto', name: 'area_asig', width: 90, sortable: true, align: 'left'},
                        {display: 'Dias<br>Faltantes', name: 'dias_faltan', width: 50, sortable: true, align: 'left'},
                        {display: 'Dias<br>Vencidos', name: 'dias_vencidos', width: 50, sortable: true, align: 'left'},
                        {display: 'R', name: 'respuesta', width: 30, sortable: true, align: 'center'},
                        //{display: 'S', name: 'seguimiento', width: 30, sortable: true, align: 'center'},
                        <?php
                        if($area_user == '9' || $area_user == '6'){
                        ?>
                        {display: 'C', name: 'cierre', width: 30, sortable: true, align: 'center'},
                        <?php
                        }
                        ?>
                        {display: 'D', name: 'documento', width: 30, sortable: true, align: 'center'},
                    ],
                    /*searchitems : [
                     // {display: 'C&oacute;digo PQR', name: 'codigo_pqr', isdefault: true},
                     {display: 'Inmueble', name: 'cod_inm'},
                     {display: 'Oficina', name: 'entidad_pqr'},
                     {display: 'Usuario', name: 'user_pqr'},
                     //{display: 'Dias Faltantes', name: 'dias_faltan'}
                     ],*/
                    sortname: "TO_DATE(TO_CHAR(P.FECHA_PQR,'DD/MM/YYYY HH24:MI:SS'),'DD/MM/YYYY hh24:mi:ss')",
                    sortorder: "DESC",
                    usepager: true,
                    title: 'Listado PQRs',
                    useRp: true,
                    rp: 30,
                    page: 1,
                    //showTableToggleBtn: true,
                    width: 1120,
                    height: 280
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


            //-->
            function edita_pqr(id) { // Traer la fila seleccionada
                popup("vista.edita_pqr.php?codigo_pqr="+id,800,600,'no');
            }

            function sigue_pqr(id) { // Traer la fila seleccionada
                popup("vista.sigue_pqr.php?codigo_pqr="+id,800,600,'yes');
            }

            function close_pqr(id) { // Traer la fila seleccionada
                popup("vista.close_pqr.php?codigo_pqr="+id,800,600,'yes');
            }

            function documento_pqr(id) { // Traer la fila seleccionada
                popup("vista.documento_pqr.php?codigo_pqr="+id,800,600,'yes');
            }
            function docmasivo_pqr() { // Traer la fila seleccionada
                popup("vista.docmasivo_pqr.php",800,600,'yes');
            }
            //-->
        </script>
        <?php
    }
    ?>
    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

