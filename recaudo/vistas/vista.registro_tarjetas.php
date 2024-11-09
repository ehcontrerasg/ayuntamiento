<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
<!doctype html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../../css/style.css?<?echo time();?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
    <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
    <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
    <!--<link rel="stylesheet" type="text/css" href="../../css/font-awesome.min.css">-->
    <!--<link rel="stylesheet" type="text/css" href="../../css/style.css?<?/*echo time();*/?>">-->
    <!--<link rel="stylesheet" type="text/css" href="../../css/reportes.css" />-->
    <link rel="stylesheet" href="../../js/DataTables-1.10.15/media/css/jquery.dataTables.min.css">
    <script src="../../js/jquery-3.2.1.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/DataTables-1.10.15/media/js/jquery.dataTables.js"></script>
    <script src="../../js/cleave/cleave.min.js"></script>
    <script src="../../js/imask/imask.js?<? echo time();?>"></script>
</head>
<header>
    <div style=" background-color: #ff8000;padding: 0.5px;margin: 4px;color:#FFFFFF;"><center><h4>Registro Tarjetas</h4></center></div>
</header>
<body>
    <div style="    background-color: #bdbdbd99;
    min-height: 139px;
    margin: 4px;
    padding-bottom: 5px;">
        <form onsubmit="return false" id="frmRegistroTarjeta" >
            <div class="form-group col-sm-2">
                <label  for="codigo_inmueble">Inmueble</label>
                <input type="text" name="codigo_inmueble" id="txtCodigoInmueble" class="form-control" maxlength="10" pattern= "[0-9]{4,8}" maxlength="10" title="Campo numérico de ocho o menos digitos." tabindex="-1" required>
            </div>
            <div class="form-group col-sm-2">
                <label  for="txtNumeroTarjeta">Número de Tarjeta</label>
                <input type="text" name="numero_tarjeta" id="txtNumeroTarjeta" class="form-control"  tabindex="1" required>
            </div>
            <div class="form-group col-sm-2">
                <label  for="fecha_expiracion">Fecha Expiración</label>
                <input type="text" name="fecha_expiracion" id="txtFechaExpiracion" class="form-control" tabindex="2" required>
            </div>
            <div class="form-group col-sm-2">
                <label  for="CVV">CVV</label>
                <input type="text" name="cvv" id="txtCVV" class="form-control" pattern= "[0-9]{3}" title="Campo numérico de 3 a 4 dígitos." tabindex="3" required>
            </div>
            <div class="form-group col-sm-2">
                <label  for="banco_emisor">Banco emisor</label>
                <select id="txtBancoEmisor"  name= "banco_emisor" class="form-control" tabindex="4" required></select>
            </div>
            <div class="form-group col-sm-2">
                <label  for="txtCorreoElectronico">Correo Electrónico</label>
                <input type="email" name="correo_electronico" id="txtCorreoElectronico" class="form-control" tabindex="5" required>
            </div>
            <div class="form-group col-sm-2">
                <label  for="txtCedula">Número de cédula</label>
                <input type="text" name="cedula" id="txtCedula" class="form-control" tabindex="5" required>
            </div>
            <div class="form-group col-sm-4">
                <label>Nombre del cliente</label>
                <input type="text" class="form-control" id="txtNombreCliente" name="nombre_cliente" required>
            </div>
            <div class="form-group col-sm-2">
                <label  for="txtTelefono">Teléfono</label>
                <input type="text" name="telefono" id="txtTelefono" class="form-control" tabindex="6" title="Campo numérico de diez dígitos" required>
            </div>
            <div class="form-group col-sm-2">
                <label  for="txtTelefono">Celular</label>
                <input type="text" name="celular" id="txtCelular" class="form-control" tabindex="7" title="Campo numérico de diez dígitos" required>
            </div>
            <div class="form-group col-sm-2">
                <input id="btnGuardar" type="submit" class="btn btn-success" style="margin-top: 10%;" value="Guardar" tabindex="8">
                <input id="btnLimpiar" type="button" class="btn btn-info" style="margin-top: 10%;" value="Limpiar" tabindex="9">
            </div>
        </form>
    </div>

    <div style="width: 363px;box-shadow: 0px 0px 4px 0px;margin-left: 69%;margin-top: 10px;">
        <div class="form-group">
            <div class="row" style=" padding: 12px;">
                <label class="col-sm-4">Buscar:</label>
                <input type="text" placeholder="Código de inmueble" id="txtCodigoInmuebleBuscar" class="form-control col-sm-3" style="width: 136px;">
                <button id="btnBuscarDatosTarjeta" class="btn btn-primary col-sm-3" style="margin-top: 0px;margin-left: 8px;">Buscar</button>
            </div>
        </div>
    </div>

    <div id="detalleTarjeta" class="container-fluid">
        <table id="dataTable" class="stripe"></table>
    </div>

    <div id="formularioPDF"></div>


    <div class="modal" tabindex="-1" role="dialog" id="modalAnular">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Anulación de Servicio de Pagos Recurrentes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="frmAnularPago" onsubmit="return false">
                        <input type="hidden" id="idRegistro">
                        <input type="hidden" id="codigoInmueble">
                        <div class="form-group">
                            <label for="txtMotivoAnulacion" >Motivo de anulación</label>
                            <textarea id="txtMotivoAnulacion" name="motivo_anulacion" class="form-control" style="width: 100%;" required> </textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" id="btnAnularServicio" value="Guardar cambios">
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
<script src="../js/registroTarjeta.js?<?echo time();?>"></script>
</html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>