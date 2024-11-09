<?php
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambio de nombre</title>
<!-- Estilos -->
    <link rel="stylesheet" href="../../librerias/bootstrap-4.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/sweetalert.css">
    <link rel="stylesheet" href="../css/cambioNombre.css?"<? echo time();?>>
<!-- Fin de Estilos -->
</head>
<body>
    <div class="container">
        <fieldset>
            <legend>Datos básicos</legend>
            <p class="row"> 
                <label class="col-sm-6"><b>Código de sistema: </b><span id="spnCodigoSistema"></span></label>
                <label class="col-sm-6"><b>Código de contrato: </b><span id="spnIdContrato"></span></label>
            </p>
        </fieldset>
        <fieldset>
            <legend>Datos del cliente</legend>
            <form action="" method="post" class="row justify-content-center" id="frmDatosCliente">
                <input type="hidden" id="hdnDocumentoAnterior">
                <input type="hidden" name="id_contrato" value="<?= $_GET['id_contrato']?>" id="hdnIdContrato">
                <div class="form-group col-sm-6 ">
                    <label for="slcTipoDocumento">Tipo documento</label>
                    <select class="form-control" id="slcTipoDocumento" name="tipo_documento" required><option value="">Seleccione un tipo de documento</option></select>
                </div>
                <div class="form-group col-sm-6 ">
                    <label for="txtDocumento">Documento</label>
                    <input class="form-control" type="text" name="documento" id="txtDocumento" placeholder="Ingrese el documento del cliente." required>
                </div>
                <div class="form-group col-sm-6 ">
                    <label for="txtAlias">Alias</label>
                    <input class="form-control" type="text" name="alias" id="txtAlias" placeholder="Ingrese el alias para el contrato." required>
                </div>
                <div class="form-group col-sm-6 ">
                    <label for="txtNombre">Nombre</label>
                    <input class="form-control" type="text" name="nombre" id="txtNombre" placeholder="Nombre del cliente." required>
                </div>
                <div class="form-group col-sm-6 ">
                    <label for="txtDireccion">Dirección</label>
                    <input class="form-control" type="text" name="direccion" id="txtDireccion" placeholder="Introduzca una dirección.">
                </div>
                <div class="form-group col-sm-6 ">
                    <label for="txtTelefono">Teléfono</label>
                    <input class="form-control" type="text" name="telefono" id="txtTelefono" placeholder="Introduzca un número telefónico">
                </div>
                <div class="form-group col-sm-6 ">
                    <label for="txtEmail">Email</label>
                    <input class="form-control" type="text" name="email" id="txtEmail" placeholder="Introduzca un email">
                </div>                
                <div class="form-group col-sm-6 ">
                    <label for="txtDireccionCorrespondencia">Dirección correspondencia</label>
                    <input class="form-control" type="text" name="direccion_corresponcencia" id="txtDireccionCorrespondencia" placeholder="Introduzca una dirección">
                </div>
                <div class="form-group col-sm-6 ">
                    <label for="slcCorrespondencia">Correspondencia</label>
                    <select class="form-control" name="correspondencia" id="slcCorrespondencia">
                        <option value="">Sin correspondencia</option>
                        <option value="I">I</option>
                        <option value="S">S</option>
                    </select>
                </div>
                <div class="form-group col-sm-6 ">
                    <label for="slcContribuyenteDgii">Contribuyente DGII</label>
                    <select class="form-control" name="contribuyente_dgii" id="slcContribuyenteDgii">
                        <option value="S">Sí</option>
                        <option value="N" selected="selected">No</option>
                    </select>
                </div>
                <div class="form-group col-sm-12 ">
                    <label for="slcGrupo">Grupo</label>
                    <select class="form-control" name="grupo" id="slcGrupo"><option value="">Sin grupo</option></select>
                </div>
                <div class="form-group col-sm-0">
                    <input type="submit" value="Guardar cambios" class="btn btn-success">
                </div>
            </form>
        </fieldset>
    </div>
<!-- Scripts -->
    <script src="../../js/jquery-3.3.1.min.js"></script>
    <script src="../../librerias/SweetAlert/sweetalert.min.js"></script>
    <script src="../js/cambioUsuario.js?<? echo time();?>"></script>
<!-- Fin de scripts -->    
</body>
</html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>
