<!DOCTYPE html>
<head>
    <htmllang="es">
    <meta  charset="UTF-8" />
    <title>Entrada de documentos Contables</title>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
    <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
    <script type="text/javascript" src="../../js/jquery.min.js"></script>
    <!--pag-->
    <script type="text/javascript" src="../js/ingresa_doc_arc_cont.js?29"></script>
    <link href="../../css/general.css " rel="stylesheet" type="text/css" />
    <link href="../../css/bootstrap/css/bootstrap.css " rel="stylesheet" type="text/css" />
    <style type="text/css">
        #msgExist {
            padding: 1%;
            font-weight: bold;
            display: none;
        }
        #msgExist div{
            padding: 1%;
            border-radius: 5px 5px 5px 5px;
            -moz-border-radius: 5px 5px 5px 5px;
            -webkit-border-radius: 5px 5px 5px 5px;
            border: 0px solid #000000;
        }
    </style>
</head>
<body id="c">
<header class="cabeceraTit fondArc">
    Entrada De Documentos Contables
</header>

<section class="contenido" id="contenidohtml">
    <article>
        <form id="ingDocArcForm" name="ingDocArcForm" enctype="multipart/form-data" class="form-horizontal">

            <div class="subCabecera fondArc"> Datos del Archivo</div>
            <br>

            <div class="form-group">
                <label for="cladocumento" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Clase Documento</label>
                <div class="col-xs-9 col-sm-6 col-md-6 col-lg-6">
                    <select class="form-control form-control-lg" id="cladocumento" name="cladocumento" required>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="tipdocumento" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Tipo Documento</label>
                <div class="col-xs-9 col-sm-6 col-md-6 col-lg-6">
                    <select class="form-control form-control-lg" id="tipdocumento" name="tipdocumento" required>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="ingCodDoc" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Código del Documento</label>
                <div class="col-xs-9 col-sm-6 col-md-2 col-lg-2">
                    <input class="form-control" type="number" id="ingCodDoc" name="ingCodDoc" required>
                </div>
            </div>

            <div class="form-group">
                <label for="codSeg" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Segmento Estanteria</label>
                <div class="col-xs-9 col-sm-6 col-md-6 col-lg-6">
                    <input class="form-control" type="text" id="codSeg" name="codSeg" required>
                </div>
            </div>

            <div class="form-group row" style="display: none" id="divFecEmi">
                <label for="fechaDoc" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Fecha Emisión</label>
                <div class="col-xs-9 col-sm-6 col-md-6 col-lg-6">
                    <!--input class="form-control" type="date" value="<?php echo date("Y-m-d");?>" id="fechaDoc" name="fechaDoc" required-->
                    <input class="form-control" type="date" id="fechaDoc" name="fechaDoc">
                </div>
            </div>

            <div class="form-group" style="display: none" id="divBeneficiario">
                <label for="beneficiario" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Beneficiario</label>
                <div class="col-xs-9 col-sm-6 col-md-6 col-lg-6">
                    <input class="form-control" type="text" id="beneficiario" name="beneficiario">
                </div>
            </div>

            <div class="form-group" style="display: none" id="divBanco">
                <label for="banco" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Banco</label>
                <div class="col-xs-9 col-sm-6 col-md-6 col-lg-6">
                    <select class="form-control form-control-lg" id="banco" name="banco">
                    </select>
                </div>
            </div>

            <div class="form-group" style="display: none" id="divEmpresa">
                <label for="empresa" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Empresa</label>
                <div class="col-xs-9 col-sm-6 col-md-6 col-lg-6">
                    <select class="form-control form-control-lg" id="empresa" name="empresa">
                    </select>
                </div>
            </div>

            <div class="form-group row" style="display: none" id="divFecRec">
                <label for="fechRep" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Fecha Recepción</label>
                <div class="col-xs-9 col-sm-6 col-md-6 col-lg-6"">
                <!--input class="form-control" type="date" value="<?php echo date("Y-m-d");?>" id="fechaDoc" name="fechaDoc" required-->
                <input class="form-control" type="date" id="fechaRep" name="fechaRep">
                </div>
            </div>

            <div class="form-group" style="display: none" id="divAsunto">
                <label for="asunto" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Asunto</label>
                <div class="col-xs-9 col-sm-6 col-md-6 col-lg-6">
                    <input class="form-control" type="text" id="asunto" name="asunto">
                </div>
            </div>

            <div class="form-group" style="display: none" id="divPeriodo">
                <label for="periodo" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Periodo</label>
                <div class="col-xs-9 col-sm-6 col-md-6 col-lg-6">
                    <input class="form-control" type="number" id="periodo" name="periodo">
                </div>
            </div>

            <div class="form-group" style="display: none" id="divCodFac">
                <label for="codFac" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Código Factura</label>
                <div class="col-xs-9 col-sm-6 col-md-6 col-lg-6">
                    <input class="form-control" type="number" id="codFac" name="codFac">
                </div>
            </div>

            <div class="form-group" style="display: none" id="divCuenta">
                <label for="cuenta" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Cuenta</label>
                <div class="col-xs-9 col-sm-6 col-md-6 col-lg-6">
                    <input class="form-control" type="text" id="cuenta" name="cuenta">
                </div>
            </div>

            <div class="form-group" style="display: none" id="divAproba">
                <label for="aprobacion" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Aprobación</label>
                <div class="col-xs-9 col-sm-6 col-md-6 col-lg-6">
                    <input class="form-control" type="number" id="aprobacion" name="aprobacion">
                </div>
            </div>

            <div class="form-group row">
                <label for="archivo_fls" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Archivo</label>
                <div class="col-xs-9 col-sm-6 col-md-6 col-lg-6">
                    <input  type="file" name="archivo_fls" id="archivo_fls" class="form-control" required>
                </div>
            </div>

            <!--div class="form-group">
                <label for="observacion" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Obervación</label>
                <div class="col-xs-9 col-sm-6 col-md-6 col-lg-6">
                    <textarea class="form-control" id="obs" rows="3" id="observacion" name="observacion"></textarea>
                </div>
            </div-->

            <span class="datoForm col1">
                <button type="submit" tabindex="5" id="btnGuardar" class="botonFormulario botFormArc" >Guardar</button>
            </span>
        </form>
    </article>
</section>

<footer>
</footer>
<script type="text/javascript" src="../../js/sweetalert.min.js?1"></script>
</body>
</html>