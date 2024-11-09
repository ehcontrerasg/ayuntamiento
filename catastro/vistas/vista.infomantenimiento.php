<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>

        <meta  charset=UTF-8" />
        <title>Validacion Catastral</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../../css/bootstrap/css/bootstrap.min.css">
        <!--pag-->
        <script type="text/javascript" src="../js/infomantenimiento.js?6"></script>
        <link href="../../css/general.css?4 " rel="stylesheet" type="text/css" />

    </head>
    <body>
    <header class="cabeceraTit fondCat">
        Validacion Catastral
    </header>

    <section class="contenido">
        <div class="main">
            <form onsubmit="return false" id="formGuarMan" class="form-inline">

                <div class="subCabecera fondCat"> Datos del imueble</div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label for="inpManCatCodSis" class="control-label col-xs-6 col-sm-6 col-md-6 col-lg-6">Codigo de Sistema:</label>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <input readonly  style="width:120%;" id="inpManCatCodSis" name="inmueble"  class="form-control" >
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label for="Proceso" class="control-label col-xs-6 col-sm-6 col-md-6 col-lg-6">Proceso:</label>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <input readonly  style="width:120%;" id="inpManCatPro"   class="form-control" >
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="Proceso" class="control-label col-xs-6 col-sm-6 col-md-6 col-lg-6">Catastro:</label>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <input   style="width:150%;" readonly id="inpManCatCat"   class="form-control" >
                            </div>
                        </div>
                    </div>
                </div>


                <div class="subCabecera fondCat"> Datos a actualizar</div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="inpManCatDirAct" class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Direccion Act:</label>
                            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                                <input readonly style="width:100%;"  id="inpManCatDirAct"   class="form-control" >
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="direccion" class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Dirección Nueva:</label>
                            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                                <input style="width:100%;"  autofocus tabindex="1" name="direccion" id="inpManCatDirNuev" placeholder="Dirección"  class="form-control" >
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="inpManCatUsoAct" class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Uso Act.</label>
                            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                                <input style="width:100%;"   id="inpManCatUsoAct" readonly   class="form-control" >
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="uso" class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Uso Nuevo:</label>
                            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                                <select style="width:100%;"  tabindex="5"  name="uso"  id="selManCatUsoNuev" class="form-control" ></select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="inpManCatActAct" class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Actividad Act:</label>
                            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                                <input readonly style="width:100%;"   id="inpManCatActAct"  class="form-control" >
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="actividad" class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Actividad Nueva:</label>
                            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                                <select tabindex="10"  name="actividad" style="width:100%;"   id="selManCatActNue"  class="form-control" ></select>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="inpManCatUsoAct" class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Unidad H Act.</label>
                            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                                <input style="width:100%;"  id="inpManCatUniHAct"  readonly   class="form-control" >
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="unidadesh" class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Unidad H Nuevo:</label>
                            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                                <input style="width:100%;"  id="inpManCatUniHNue" tabindex="15" name="unidadesh" placeholder="Unidades H" min="1"   type="number" class="form-control" >
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="inpManCatUniTAct" class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Unidades T Act.:</label>
                            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                                <input readonly id="inpManCatUniTAct" style="width:100%;"   class="form-control" >
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="inpManCatUniTNue" class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Unidades T Nueva:</label>
                            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                                <input id="inpManCatUniTNue" style="width:100%;"  tabindex="20" placeholder="Unidades T" name="unidadest"  min="1" type="number"  class="form-control" >
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="inpManCatEstAct" class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Estado Act.:</label>
                            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                                <input  id="inpManCatEstAct" style="width:100%;"  readonly   class="form-control" >
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="selManCatEstNue" class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Estado Nuevo:</label>
                            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                                <select  tabindex="25" name="estado" style="width:100%;"  id="selManCatEstNue" class="form-control" ></select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="inpManCatTarAguAct" class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Tarifa Agua Act.:</label>
                            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                                <input style="width:100%;"  id="inpManCatTarAguAct" readonly   class="form-control" >
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="tarifaagua" class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Tarifa Agua Nuevo:</label>
                            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                                <select style="width:100%;"  tabindex="30"  name="tarifaagua"  id="selManCatTarAguNue" class="form-control" ></select>
                            </div>
                        </div>
                    </div>

                    <div id="divAlcAct" class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="inpManCatTarAlcAct" class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Tarifa Alc. Act.:</label>
                            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                                <input  style="width:100%;"  readonly id="inpManCatTarAlcAct"  class="form-control" >
                            </div>
                        </div>
                    </div>

                    <div id="divAlcNue" class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="selManCatTarAlcNue" class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Tarifa Alc. Nuevo:</label>
                            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                                <select style="width:100%;"   tabindex="35"  name="tarifaalcantarillado"  id="selManCatTarAlcNue"  class="form-control" ></select>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="inpManCatTelAct" class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Telefono Act.:</label>
                            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                                <input style="width:100%;"  id="inpManCatTelAct"  readonly  class="form-control" >
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="inpManCatTelNue" class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Telefono Nuevo:</label>
                            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                                <input style="width:100%;"   id="inpManCatTelNue" name="telefono" tabindex="40" placeholder="Telefono"  type="number" class="form-control" >
                            </div>
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="inpManCatTelAct" class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Cupo Basico Act.:</label>
                            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                                <input style="width:100%;"  id="inpManCupBasAct"  readonly  class="form-control" >
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="texAreManCatObs" class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Cupo Basico:</label>
                            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                                <input  style="width:100%;"   id="inpManCupBasNue" name="cupo" tabindex="50" placeholder="Cupo basico"  class="form-control" >
                            </div>
                        </div>
                    </div>

                </div>


                <div class="row">

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="inpManCatTelAct" class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Estado Servicio Act:</label>
                            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                                <input style="width:100%;"  id="inpManEstServAct"  readonly  class="form-control" >
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label for="texAreManCatObs" class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Estado Servicio:</label>
                            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                                <select style="width:100%;"   tabindex="35"  name="estServ"  id="selManCatEstSerNue"  class="form-control" ></select>
                            </div>
                        </div>
                    </div>



                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label for="texAreManCatObs" class="control-label col-xs-5 col-sm-5 col-md-5 col-lg-5">Observaciones:</label>
                            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                                <textarea    id="texAreManCatObs" name="observaciones" tabindex="50" placeholder="Observaciones de Mantenimiento"  class="form-control" ></textarea>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="subCabecera fondCat">Fotos</div>

                <div class="row" id="divManCatFot">


                </div>

                <span class="datoForm col1">
                <button tabindex="55" class="botonFormulario botFormCat"  id="botManCatEnv">Procesar</button>
            </span>

            </form>

        </div>

    </section>

    <footer>
    </footer>

    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

