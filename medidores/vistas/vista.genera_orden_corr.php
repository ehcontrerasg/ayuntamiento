<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta  charset=UTF-8/>
        <title>Genaracion de ordenes de mantenimiento</title>
        <!-- Bootstrap -->
        <link rel="stylesheet" href="../../librerias/bootstrap-4.6/css/bootstrap.min.css">
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <!--autocompltar -->
        <script src="../../js/jquery-1.11.2.min.js"></script>
        <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js "></script>
        <link href="../../css/css.css " rel="stylesheet" type="text/css" />
        <!--pag-->
        
        <link href="../../css/general.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="../css/genera_orden_corr.css">
    </head>

    </head>
    <body>
    <header class="cabeceraTit fondMed">
        Generación mantenimiento correctivo de medidor
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="genOrdCorrForm" class="row">
                <div class="form-group col-sm-6 col-md-4">
                    <label for="genOrdCorrSelPro"><strong>Acueducto</strong></label>
                    <select name="acueducto" id="genOrdCorrSelPro" class="form-control" required>
                        <option value="">Seleccione un acueducto</option>
                    </select>
                </div>
                <div class="form-group col-sm-6 col-md-4">
                    <label for="txtZona"><strong>Zona</strong></label>
                    <input type="text" name="zona" id="genOrdCorrInpZon" class="form-control">
                </div>
                <div class="form-group col-sm-6 col-md-4">
                    <label><strong>Proceso inicial</strong></label>
                    <input type="text" name="proceso_inicial" id="txtProcesoInicial" placeholder="Inicial" class="form-control">
                </div>
                <div class="form-group col-sm-6 col-md-4">
                    <label for=""><strong for="txtProcesoFinal">Proceso final</strong></label>
                    <input type="text" name="proceso_final" id="txtProcesoFinal" class="form-control" placeholder="Final">
                </div>
                <div class="form-group col-sm-6 col-md-4">
                    <label for="txxCodigoSistema"><strong>Código de sistema</strong></label>
                    <input type="text" name="codigo_sistema" id="txxCodigoSistema" class="form-control">
                </div>
                <div class="form-group col-sm-6 col-md-4">
                    <label for="txtManzanaInicial"><strong>Manzana inicial</strong></label>
                    <input type="text" name="manzana_inicial" id="txtManzanaInicial" placeholder="Inicial" class="form-control">
                </div>
                <div class="form-group col-sm-6 col-md-4">
                    <label for="txtManzanaFinal"><strong>Manzana final</strong></label>
                    <input type="text" name="manzana_final" id="txtManzanaFinal" placeholder="Final" class="form-control">
                </div>
                <div class="form-group col-sm-6 col-md-4">
                    <label for="slcContratista"><strong>Contratista</strong></label>
                    <select name="contratista" id="slcContratista" class="form-control" required>
                        <option value="">Seleccione un contratista</option>
                    </select>
                </div>
                <div class="form-group col-sm-6 col-md-4">
                    <label for="slcOperario"><strong>Operario</strong></label>
                    <select name="operario" id="slcOperario" class="form-control" required>
                        <option value="">Seleccione a un operario</option>
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <label for="txtDescripcion"><strong>Descripción</strong></label>
                    <textarea name="descripcion" id="txtDescripcion" cols="30" rows="2" class="form-control"></textarea>
                </div>
                <div class="form-group col-sm-12"><input type="submit" value="Generar" class="btn" id="btnGenerar"></div>
            </form>
        </article>
    </section>

    <footer>
    </footer>
    </body>
        <script type="text/javascript" src="../js/genera_orden_cor.js?<?php echo time();?>"></script>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

