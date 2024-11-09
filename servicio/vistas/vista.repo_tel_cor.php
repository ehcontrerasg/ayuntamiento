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
    $fecini = $_POST['fecini'];
    $fecfin = $_POST['fecfin'];
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
        <script type="text/javascript" src="../js/repo_tel_cor.js?"></script>
        <!--script src="../../js/jquery.min.js"></script>
        <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js"></script-->
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
            .inputm{
                border:1px solid #ccc;
                font-family: Arial, Helvetica, sans-serif;
                font-size:11px;
                height:16px;
                width:80px;
                font-weight:normal;
            }

        </style>

    </head>
    <body>
    <form name="formRepInt" id="formRepInt" action="vista.repo_tel_cor.php" method="post" >
        <h3 class="panel-heading" style=" background-color:#000040; color:#FFFFFF; font-size:18px; width:1120px; margin-top:-5px" align="center">Reporte Tel&eacute;fonos y Correos</h3>
        <div class="flexigrid" style="width:1120px">
            <div class="mDiv">
                <div>Filtros de B&uacute;squeda Reporte Telefonos y Correos</div>
                <div style="background-color:rgb(255,255,255)">
                    <table width="100%">
                        <tr>
                            <td width="15%" style=" border:1px solid #EEEEEE; text-align:center">Acueducto<br />
                                <select name="proyecto" class="input"><option></option>
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
                            <td style=" border:1px solid #EEEEEE; text-align:center" colspan="2">Fecha<br />
                                Desde:&nbsp;&nbsp;<input type="date" name="fecini" id="fecini" value="<?php echo $fecini;?>" required class="input"/>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                Hasta:&nbsp;&nbsp;<input type="date" name="fecfin" id="fecfin" value="<?php echo $fecfin;?>" required class="input"/>
                            </td>
                            <td width="19%" style=" border:1px solid #EEEEEE" rowspan="2" align="center">
                                <button style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#000040; color:#000040; display:block" type="submit"
                                        name="Buscar" id="Buscar"class="btn btn btn-INFO"><i class="fa fa-search"></i><b>&nbsp;Consultar</b>
                                </button>
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
        <div class="contenedor">
            <form onsubmit="return false" id="genExc">
                <button  type="submit"  title="Descargar en Excel" class="butExcel"><i class="fa fa-file-excel-o fa-2x"></i></button>
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
                    url: './../datos/datos.repo_tel_cor.php?proyecto=<?php echo $proyecto;?>&fecini=<?php echo $fecini;?>&fecfin=<?php echo $fecfin;?>',
                    dataType: 'json',
                    colModel : [
                        {display: 'N&deg;', name: 'rnum', width: 45,  align: 'center'},
                        {display: 'Usuario', name: 'codigo_pqr', width: 100, sortable: true, align: 'center'},
                        {display: 'Nombre', name: 'fecrad', width: 280, sortable: true, align: 'center'},
                        {display: 'Cantidad<br>Tel&eacute;fonos', name: 'cod_inmueble', width: 60, sortable: true, align: 'center'},
                        {display: 'Cantidad<br>Email', name: 'nom_cliente', width: 60, sortable: true, align: 'center'}
                    ],
                    sortname: "U.LOGIN",
                    sortorder: "ASC",
                    usepager: true,
                    title: 'Reporte Tel&eacute;fonos y Correos del <?php echo $fecini;?> al <?php echo $fecfin;?>',
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

