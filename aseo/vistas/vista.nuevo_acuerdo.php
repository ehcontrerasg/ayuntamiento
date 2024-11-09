<?php
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta  charset=UTF-8" />
        <title>Entrada de Nuevo Acuerdo</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/nuevo_acuerdo.js?<?php echo time();?>"></script>
        <link href="../../css/general.css " rel="stylesheet" type="text/css" />
    </head>
    <body>
    <header class="cabeceraTit fondAseo">
        Registro Nuevo Acuerdo De Pago
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="regNueConForm">
                <div class="subCabecera fondAseo"> Datos del imueble</div>
                <span class="datoForm col1">
                    <input id="ingNueConEst"  type="text" style="display: none">
                    <input id="ingNueConOfi"  type="text" style="display: none">
                    <input id="ingNueConAsi"  type="text" style="display: none">
                    <input id="ingNueConCed"  type="text" style="display: none">
                    <input id="ingNueConEnt"  type="text" style="display: none">
                    <input id="ingNueConCaj"  type="text" style="display: none">
                    <input id="ingNueConGer"  type="text" style="display: none">

                    <span class="titDato numCont1">Cod. sis</span>
                    <span class="inpDatp numCont2"><input id="ingNueConCodSis"  required name="inm" placeholder="Código Sistema" type="number" min="0" max="9999999"></span>
                    <span class="titDato numCont1">Ayuntamiento</span>
                    <span class="inpDatp numCont2"><input id="ingNueConAcu" required placeholder="Ayuntamiento" readonly type="text"></span>
                   <span class="titDato numCont1">Dirección</span>
                    <span class="inpDatp numCont3"><input  id="ingNueConDir" name="direccion" required placeholder="Dirección" readonly type="text"></span>
                </span>
                <span class="datoForm col1">

                    <span class="titDato numCont1">Urbanización</span>
                    <span class="inpDatp numCont2"><input  id="ingNueConUrb" name="urba" required placeholder="Urbanización" readonly type="text"></span>
                    <span class="titDato numCont1">Uso</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConUso" name="uso" required placeholder="Uso" readonly type="text"></span>
                    <span class="titDato numCont1">Categoria</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConCat" name="categoria" required placeholder="Unidades" readonly type="text"></span>
                    <span class="inpDatp numCont1">Cuotas</span>
                    <span class="inpDatp numCont2"><input  id="ingNueConCuo"  name="cuotas"  placeholder="# Cuotas"  type="number" min="1" max="24" required></span>
                </span>

                <div class="subCabecera fondAseo"> Información del Cliente y/o Representante</div>
                <span class="datoForm col1">
                    <span class="titDato numCont1">Documento</span>
                    <span class="inpDatp numCont2"><input  id="ingNueConDoc" required placeholder="Documento"  type="text"></span>
                    <span class="titDato numCont1">Tipo Documento</span>
                    <span class="inpDatp numCont2"><select  placeholder="Tipo Documento" select id="ingNueConTipDoc" readonly ></select></span>
                    <span class="titDato numCont1">Nombre Usuario</span>
                    <span class="inpDatp numCont3"><input  id="ingNueConNom"  placeholder="Nombre Usuario"  type="text"></span>
                </span>
                <span class="datoForm col1">
                    <span class="titDato numCont1">Teléfono</span>
                    <span class="inpDatp numCont2"><input  id="ingNueConTel"  placeholder="Teléfono"  type="text"></span>
                    <span class="titDato numCont1">Email</span>
                    <span class="inpDatp numCont2"><input  id="ingNueConEma" placeholder="Email"  type="text"></span>
                    <span class="titDato numCont1">En Calidad de</span>
                    <span class="inpDatp numCont2"><select  placeholder="En Calidad" select id="ingNueConCal" required ></select></span>

                </span>

                <div class="subCabecera fondAseo"> Datos Deuda</div>
                <span class="datoForm col1">
                    <span class="titDato numCont1">Deuda Total ($RD)</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConDeu" required placeholder="Deuda" readonly type="text"></span>
                    <span class="titDato numCont1">Valor Mora ($RD)</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConMor" required placeholder="Mora" readonly type="text"></span>
                    <span class="titDato numCont1">Mora a Exonerar (%)</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConPorExoMor" required placeholder="% Mora a Exonerar" readonly type="text"></span>
                    <span class="titDato numCont1">Descuento Mora ($RD)</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConDes" required placeholder="Descuento Mora" readonly type="number"></span>
                </span>

                <span class="datoForm col1">
                    <span class="titDato numCont1">Nueva Deuda($RD)</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConPag" required placeholder="Total Nueva Deuda" readonly type="number"></span>
                    <span class="titDato numCont1">Cuota Inicial (%)</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConPorCuoIni" required placeholder="% Cuota Inicial" readonly type="text"></span>
                    <span class="titDato numCont1">Valor Inicial ($RD)</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConValIni" required placeholder="Valor Cuota Inicial" type="text" ></span>
                    <!--span class="titDato numCont1" id="ingSpaConRec">Valor Reconexión ($RD)</span>
                    <span class="inpDatp numCont1" ><input  id="ingNueConRec" required placeholder="Reconexión" readonly type="number"></span-->
                </span>
                <span class="datoForm col1">
                    <span class="titDato numCont1">Saldo Pendiente ($RD)</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConSalPend" required placeholder="SaldoPendiente" readonly type="text"></span>
                    <span class="titDato numCont1">Valor Cuota Mensual ($RD)</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConValCuoMen" required placeholder="Valor cuota mensual" readonly type="text"></span>
                    <!--/span>
                    <span class="datoForm col1"-->
                    <span class="titDato numCont1">Total a pagar ($RD)</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConValTotal" required placeholder="Total a Pagar" readonly type="text"></span>
                </span>
                <span class="datoForm col1">
                    <input type="submit"  value="Generar Acuerdo" class="botonFormulario botFormAseo"  id="ingNueConBut">
                </span>
            </form>
            <div id = "contenedorPdf">
                <object id="genNueConObj" class="conPdf" type="application/pdf"></object>
            </div>
        </article>
    </section>
    <footer>
    </footer>
    </body>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

