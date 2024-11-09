<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?
    session_start();
    require'../clases/classPagos.php';
    include('../../destruye_sesion.php');
//pasamos variables por post
    $coduser = $_SESSION['codigo'];
    $cod_inmueble = $_GET['cod_inmueble'];
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <script language="JavaScript" type="text/javascript" src="../../js/funciones2.js"></script>
        <script language="JavaScript" type="text/javascript" src="../../js/xp_progress.js"></script>
        <script language="javascript" type="text/javascript" src="../../js/ajax2.js"></script>
        <script language="javascript" type="text/javascript" src="../../js/jquery.js"></script>
        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../flexigrid/style.css" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
        <link href="../../alertas/dialog_box.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
        <script type="text/javascript" src="../../alertas/dialog_box.js"></script>
        <script type="text/javascript" src="../../js/facturaspendientes.js"></script>

        <link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
        <style type="text/css">
            .font{
                font-style:normal;
                font:bold;
            }
            .table{
                border:1px solid #ccc;
                border-left:0px solid #ccc;
            }
            .th{
                background: #fafafa url(../../flexigrid/css/images/fhbg.gif) repeat-x bottom;
                height:24px;
                border:0px solid #ccc;
                border-left:1px solid #ccc;
                font-size:11px;
                font-weight:normal;
                font-family: Arial, Helvetica, sans-serif;
                text-align:center;
            }
            .tda{
                height:24px;
                border:1px solid #ccc;
                border-left:1px solid #ccc;
                padding:0px;
                font-size:11px;
                font-weight:normal;
                font-family: Arial, Helvetica, sans-serif;
            }
            .input{
                border:1px solid #ccc;
                font-family: Arial, Helvetica, sans-serif;
                font-size:11px;
                font-weight:normal;
            }
            .input2{
                border:1px solid #ccc;
                font-family: Arial, Helvetica, sans-serif;
                font-size:11px;
                height:16px;
                font-weight:normal;
            }
            .iframe{
                margin-top:-10px;
                margin-left:-1px;
                border-color: #666666;
                border:0px solid #ccc;
                display:block;
                width: 1121px;
                height: 265px;
                float:left;
            }
        </style>
    </head>
    <body style="margin-top:-25px">
    <div id="content" style="padding:0px; width:1120px; margin-left:0px">
        <h3 class="panel-heading" style=" background-color:#FF8000; color:#FFFFFF; font-size:18px; width:1120px" align="center">Pagos En Transito</h3>
        <form name="cargue" action="carga_archivo.php" method="post" enctype="multipart/form-data" onSubmit="return valida();" target="prueba">
            <div class="flexigrid" style="width:100%">
                <div class="mDiv">
                    <div align="left"><b>Cargue Archivo de Pagos en Transito</b></div>
                </div>
            </div>
            <div class="flexigrid" style="width:100%">
                <div class="mDiv">
                    <div style="background: linear-gradient(#F7F7F7,#F7F7F7); border-top:1px solid #ccc;" align="left">
                        <table width="1115">
                            <tr>
                                <td width="138">Seleccione el archivo...</td>
                                <td ><input type="file" size="50" name="archivo" class="input" required></td>
                                <td width="147">Forma de Pago:</td>
                                <td width="147"><select name='medio' id="medio" class='input' required>
                                        <option value="" selected>Seleccione medio...</option>
                                        <?php
                                        $c = new Pagos();
                                        $resultado = $c->seleccionaMedioPago();
                                        while (oci_fetch($resultado)) {
                                            $cod_medio = oci_result($resultado, 'CODIGO') ;
                                            $des_medio = oci_result($resultado, 'DESCRIPCION') ;
                                            if($cod_medio == $medio) echo "<option value='$cod_medio' selected>$des_medio</option>\n";
                                            else echo "<option value='$cod_medio'>$des_medio</option>\n";
                                        }oci_free_statement($resultado);
                                        ?>
                                    </select>
                                </td>
                                <td width="332" rowspan="2">
                                    <button style="background:linear-gradient(#D8D8D8,#FFFFFF,#FFFFFF,#D8D8D8);border-color:#FF8000; color:#FF8000; display:block" type="submit"
                                            name="procesar" id="procesar"class="btn btn btn-INFO"><i class="fa fa-upload"></i><b>&nbsp;Cargar Archivo</b>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td width="138">Tipo de Archivo:</td>
                                <td width="327">
                                    <input type="radio" name="tipo_archivo" value="XLS" onClick="fcargue(this.value);" required>&nbsp;CAASD D.N.&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="tipo_archivo" value="TXT" onClick="fcargue(this.value);" required>&nbsp;VIA
                                    <input type="radio" name="tipo_archivo" value="TXT3" onClick="fcargue(this.value);" required>&nbsp;VIA_BC
                                    <input type="radio" name="tipo_archivo" value="TXT2" onClick="fcargue(this.value);" required>&nbsp;TP
                                </td>
                                <?php
                                $fecha_actual = date('Y-m-d');
                                $fecha_limite = strtotime ( '-31 day' , strtotime ( $fecha_actual ) ) ;
                                $fecha_limite = date ( 'Y-m-d' , $fecha_limite );
                                ?>
                                <td width="147">Fecha Pago:</td>
                                <td width="147"><input type="date" name="fecha_emi" id="fecha_emi" class="input2" min="<?php echo $fecha_limite?>" required/></td>
                            </tr>
                            <tr>
                                <td colspan="5"><dfn id="mensaje" name="mensaje"></dfn></td>
                            </tr>
                            <tr>
                                <td colspan="5">
                                    <div id="barra" name="barra" style="display: inline">
                                        <center>
                                            <script type="text/javascript" language="javascript">

                                                var bar1 = createBar(300,10,'#FFFFFC',0,'black','#006699',85,7,3,"");

                                            </script>
                                        </center>
                                    </div>
                                    <div disabled="disabled">
                                        <iframe src="cargue_archivo_xls.php" id="prueba" name="prueba" width="100%" marginheight="0" marginwidth="0" noresize scrolling="auto" frameborder="0" height="200" style="background-color: #E0E0E0"></iframe>
                                        <iframe src="cargue_archivo_txt.php" id="prueba" name="prueba" width="100%" marginheight="0" marginwidth="0" noresize scrolling="auto" frameborder="0" height="200" style="background-color: #E0E0E0"></iframe>
                                        <iframe src="cargue_archivo_txt3.php" id="prueba" name="prueba" width="100%" marginheight="0" marginwidth="0" noresize scrolling="auto" frameborder="0" height="200" style="background-color: #E0E0E0"></iframe>
                                        <iframe src="cargue_archivo_txt2.php" id="prueba" name="prueba" width="100%" marginheight="0" marginwidth="0" noresize scrolling="auto" frameborder="0" height="200" style="background-color: #E0E0E0"></iframe>
                                    </div>
                                </td>
                            </tr>
                            <tr class="texto">
                                <td colspan="5"></td>
                            </tr>
                            <tr>
                                <td colspan="5"><dfn id="errores" name="errores"></dfn></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <input type="hidden" name="proc" value="">
        </form>
    </div>

    <script type="text/javascript" language="javascript">
        <!--
        function valida() {
            var ok = false;
            for (i = 0; i < document.cargue.elements.length; i++) {
                if (document.cargue.elements[i].type == "radio") {
                    if (document.cargue.elements[i].checked && document.cargue.elements[i].value == "XLS") {
                        document.cargue.action = "cargue_archivo_xls.php";
                        ok = true; }
                    if (document.cargue.elements[i].checked && document.cargue.elements[i].value == "TXT") {
                        document.cargue.action = "cargue_archivo_txt.php";
                        ok = true; }
                    if (document.cargue.elements[i].checked && document.cargue.elements[i].value == "TXT3") {
                        document.cargue.action = "cargue_archivo_txt3.php";
                        ok = true; }
                    if (document.cargue.elements[i].checked && document.cargue.elements[i].value == "TXT2") {
                        document.cargue.action = "cargue_archivo_txt2.php";
                        ok = true; }
                }
            }




            bar1.showBar();
            //document.cargue.proc.value = 1;
            document.all.mensaje.innerHTML = "<font color='red'>Por favor espere un momento ...</font>";
            bar1.showBar();
            return true;
        }
        function fcargue(tipo) { //alert(tipo);
            if (tipo == "XLS") document.cargue.action = "cargue_archivo_xls.php";
            else if (tipo == "TXT") document.cargue.action = "cargue_archivo_txt.php";
            else if (tipo == "TXT3") document.cargue.action = "cargue_archivo_txt3.php";
            else if (tipo == "TXT2") document.cargue.action = "cargue_archivo_txt2.php";
            else alert("Se ha producido un error !!");
        }
        //-->
    </script>
    </body>
    </html>


<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

