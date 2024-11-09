<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();
    include '../clases/classPqrs.php';
    include '../../destruye_sesion.php';

    $coduser = $_SESSION['codigo'];
    $proyecto = $_POST['proyecto'];
    $ofiini = $_POST['ofiini'];
    $ofifin = $_POST['ofifin'];
    $fecinirad = $_POST['fecinirad'];
    $fecfinrad = $_POST['fecfinrad'];
    $login = $_POST['login'];

    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" media="screen" href="../../css/Static_Tabstrip.css">
        <link rel="stylesheet" href="../../css/tablas_catastro.css">
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <link href="../../css/css.css?1" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="../../flexigrid/style.css" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
        <link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
        <script type="text/javascript" src="../js/repo_interacciones.js?5"></script>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>




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
    <form name="formRepInt" id="formRepInt" action="vista.repo_interacciones.php" method="post" >
        <h3 class="panel-heading" style=" background-color:#000040; color:#FFFFFF; font-size:18px; width:1120px; margin-top:-5px" align="center">Reporte Interacciones Cliente</h3>
        <div class="flexigrid" style="width:1120px">
            <div class="mDiv">
                <div>Filtros de B&uacute;squeda Reporte Interacciones Cliente</div>
                <div style="background-color:rgb(255,255,255)">
                    <table width="100%">
                        <tr>
                            <td width="34%" style=" border:1px solid #EEEEEE; text-align:center">Acueducto<br />
                                <select  required name="proyecto" class="input"><option></option>
                                    <?php
                                    $l=new PQRs();
                                    $registros=$l->seleccionaAcueducto();
                                    while (oci_fetch($registros)) {
                                        $cod_proyecto = oci_result($registros, 'ID_PROYECTO') ;
                                        $sigla_proyecto = oci_result($registros, 'SIGLA_PROYECTO') ;
                                        if($cod_proyecto == $proyecto) echo "<option value='$cod_proyecto' selected>$sigla_proyecto</option>\n";
                                        else echo "<option value='$cod_proyecto'>$sigla_proyecto</option>\n";
                                    }oci_free_statement($registros);
                                    ?>
                                </select>
                            </td>
                            <td style=" border:1px solid #EEEEEE; text-align:center" colspan="2">Fecha Interacci&oacute;n<br />
                                Desde:&nbsp;&nbsp;<input type="date" name="fecinirad" id="fecinirad" value="<?php echo $fecinirad;?>" class="input"/>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                Hasta:&nbsp;&nbsp;<input type="date" name="fecfinrad" id="fecfinrad" value="<?php echo $fecfinrad;?>" class="input"/>
                            </td>
                            <td width="21%" style=" border:1px solid #EEEEEE" rowspan="2" align="center">
                                <button style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#000040; color:#000040; display:block" type="submit"
                                        name="Buscar" id="Buscar"class="btn btn btn-INFO"><i class="fa fa-search"></i><b>&nbsp;Consultar</b>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td width="34%" style=" border:1px solid #EEEEEE; text-align:center">Oficina<br />
                                Desde:&nbsp;&nbsp;<input type="number" name="ofiini" id="ofiini" value="<?php echo $ofiini;?>" class="inputn" min ="0" max="999" />
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                Hasta:&nbsp;&nbsp;<input type="number" name="ofifin" id="ofifin" value="<?php echo $ofifin;?>" class="inputn" min ="0" max="999" />
                            </td>
                            <td colspan="2" style=" border:1px solid #EEEEEE; text-align:center">Usuario<br />
                                <input type="text" name="login" id="login" value="<?php echo $login;?>" class="input"/>

                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </form>
    <?php
    if(isset($_REQUEST["Buscar"])){

        ?>
        <div class="contenedor" style="padding:0px 109px 9px 109px">
            <form onsubmit="return false" id="genExc">
                <button  type="submit"  title="Descargar en Excel" class="butExcel"><i class="fa fa-file-excel-o fa-2x"></i></button>
            </form>
            <form onsubmit="return false" id="genPdf">
                <button  type="submit" title="Descargar en PDF" class="butPdf"><i class="fa fa-file-pdf-o fa-2x"></i></button>
            </form>
        </div>
        <table id="flex1" style="display:none">
        </table>
        </div>
        <script type="text/javascript">

            $('.flexme1').flexigrid();
            $('.flexme2').flexigrid({height:'auto',striped:false});
            $("#flex1").flexigrid	(
                {
                    url: './../datos/datos.repo_interacciones.php?proyecto=<?php echo $proyecto;?>&login=<?php echo $login;?>&ofiini=<?php echo $ofiini;?>&ofifin=<?php echo $ofifin;?>&fecinirad=<?php echo $fecinirad;?>&fecfinrad=<?php echo $fecfinrad;?>',
                    dataType: 'json',
                    colModel : [
                        {display: 'N&deg;', name: 'rnum', width: 40,  align: 'left'},
                        {display: 'Fecha', name: 'fecha', width: 120, sortable: true, align: 'left'},
                        {display: 'Asunto', name: 'asunto', width: 200, sortable: true, align: 'left'},
                        {display: 'Texto', name: 'texto', width: 400, sortable: true, align: 'left'},
                        {display: 'Usuario', name: 'usurio', width: 190, sortable: true, align: 'left'},
                        {display: 'Inmueble', name: 'inmueble', width: 70, sortable: true, align: 'left'},
                        {display: 'Medio', name: 'Medio', width: 70, sortable: true, align: 'left'}

                    ],
                    /*searchitems : [
                         // {display: 'C&oacute;digo PQR', name: 'codigo_pqr', isdefault: true},
                          {display: 'Inmueble', name: 'cod_inm'},
                          {display: 'Oficina', name: 'entidad_pqr'},
                          {display: 'Usuario', name: 'user_pqr'},
                          //{display: 'Dias Faltantes', name: 'dias_faltan'}
                          ],*/
                    sortname: "U.LOGIN ASC, I.ID_PROCESO",
                    sortorder: "ASC",
                    usepager: true,
                    title: 'Listado Interacciones',
                    useRp: true,
                    rp: 30,
                    page: 1,
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

