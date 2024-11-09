<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Reporte Pagos Por Inmueble</title>

    <!--Estilos-->
    <link href="../css/recaudadovsfacturado.css?"<?echo time();?> rel="stylesheet" type="text/css"/>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css?2" />
    <script type="text/javascript" src="../../js/sweetalert.min.js?2"></script>
    <!--autocompltar -->
    <script src="../../js/jquery-1.11.2.min.js?2"></script>
    <!--Librerpia bootstrap-->
    <link href="../../css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <script type="text/JavaScript" src="../../js/bootstrap.min.js"></script>
    <!-- iconos botones   -->
    <link href="../../font-awesome/css/font-awesome.min.css?2" rel="stylesheet" />

    <!--estilo pag    -->
    <!--<link rel="stylesheet" type="text/css" href="../css/gerencia.css?3" />-->

</head>
<body>
    <div class="cabecera" id="cabecera">
        Reporte Facturado VS Recaudado
    </div>
<section>
    <article>

             <form onSubmit="return false" id="frmRecaudadovsFacturado">
                 <div id="parametrosReporte" class=" form-group col-lg-12" >
                <div class=" form-group col-lg-3">
                    <label> Proyecto: </label>
                    <select class="form-control campo"  id="selProyecto" name="proyecto" required ></select>
                    <label >Periodo uno: </label>
                    <input  type="number" id="txtPerIni" name="perIni" class="form-control campo" required >
                </div>
                <div class="col-lg-3 form-group">
                    <label>Periodo dos: </label>
                    <input type="number" id="txtPerFin" name="perFin" class="form-control campo" required >
                    <label>Sector Inicial</label>
                    <input type="number" id="txtSectorIni" name="sectorIni" class="form-control campo"  >
                </div>
                <div class="col-lg-3 form-group">
                    <label>Sector Final</label>
                    <input type="number" id="txtSectorFin" name="sectorFin" class="form-control campo"  >
                    <label>Ruta Inicial</label>
                    <input type="number" id="txtRutaIni" name="rutaIni" class="form-control campo" >
                </div>
                <div class="col-lg-3 form-group">
                    <label>Ruta Final</label>
                    <input type="number" id="txtRutaFin" name="rutaFin" class="form-control campo" >

                </div>
                     <div class="col-lg-3 form-group">
                         <label>&nbsp;</label>
                         <input type="submit" id="btnGenerarReporte" class="btn btn-success" value="Generar Reporte">
                         <button class="btn btn-primary" id="btnLimpiar">Limpiar</button>
                     </div>
                 </div>
            </form>


        <div id="divData">

        </div>
    </article>
</section>

</body>
<!--logica pag    -->
<script type="text/javascript" src="../js/facturadovsrecaudado.js?"<?echo time();?>></script>
    </html>

<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

