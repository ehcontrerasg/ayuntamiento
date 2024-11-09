<?
include '../../clases/class.PermisosURL.php';
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Grandes Clientes</title>
    <!--Bootstrap-->
    <link href="../../librerias/bootstrap-4.5.3-dist/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
    <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
    <!--estilo pag    -->
    <link rel="stylesheet" type="text/css" href="../css/gclientes.css " />
    <!-- iconos botones   -->
    <link href="../../font-awesome/css/font-awesome.min.css?" rel="stylesheet" />
</head>
<body class="container-fluid">
 
        <header>
            <div class="cabecera">
                Impresión Recibos De Pagos Grandes Clientes
            </div>
        </header>
        <section class="col-sm-12">
            <article class="row">
                <form onSubmit="return false" id="genrecPForm" class="col-sm-12">
                    <div class="subCabecera col-sm-12">
                        Filtros de B&uacute;squeda Recibo De Pagos Grandes Clientes
                    </div>
                    <div class="divfiltros col-sm-12">
                        <div class="row">
                            <div class="col-sm-2 form-group">
                                <label for="recPSelPro">Proyecto</label>
                                <select id="recPSelPro" class="form-control"></select>
                            </div>
                            <div class="col-sm-2 form-group">
                                <label for="recPSelGru">Grupo</label>
                                <select id="recPSelGru" class="form-control"></select>
                            </div>
                            <div class="col-sm-2 form-group">
                                <label for="recPTxtInm">Codigo de Inmueble</label>
                                <input type="text" id="recPTxtInm" class="form-control">
                            </div>
                            <div class="col-sm-2 form-group">
                                <label for="recPTxtRnc">Documento</label>
                                <input type="text" id="recPTxtRnc" class="form-control">
                            </div>
                            <div class="col-sm-2 form-group">
                                <label for="recPTxtPeriodoInicial">Periodo inicial:</label>
                                <input type="number" id="recPTxtPeriodoInicial" class="form-control">
                            </div>
                            <div class="col-sm-2 form-group">
                                <label for="recPTxtPeriodoFinal">Periodo final</label>
                                <input type="number" id="recPTxtPeriodoFinal" class="form-control">
                            </div>
                            <div class="col-sm-2 form-group">
                                <label>&nbsp;</label>
                                <button id="recPButCon" class="btn btn-success">Generar Recibos</button>
                            </div>
                        </div>
                    </div>
                </form>
            </article>
        </section>
        <section class="col-sm-12" id="secPdf">
            <object id="divReprecP" class="conPdf" type="application/pdf" width="100%" height="400px"></object>
        </section>

</body>
    <!--Jquery -->
    <script src="../../js/jquery.min.js" type="text/javascript"></script>
    <!--Alertas-->
    <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
    <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js "></script>
    <!--logica pag    -->
    <script type="text/javascript" src="../js/recibopago.js?<?php echo time();?>"></script>
    <script type="text/javascript">
        recPInicio();
    </script>
</html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>