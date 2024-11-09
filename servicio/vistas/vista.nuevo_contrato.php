<?php
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>


        <meta  charset=UTF-8" />
        <title>Entrada de Nuevo Contrato</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/nuevo_contrato.js?<?php echo time();?>"></script>
        <link href="../../css/general.css " rel="stylesheet" type="text/css" />
    </head>
    <body>
    <header class="cabeceraTit fondSerCli">
        Registro Nuevo Contrato
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="regNueConForm">
                <div class="subCabecera fondSerCli"> Datos del imueble</div>
                <span class="datoForm col1">
                    <input id="ingNueConEst"  type="text" style="display: none">
                    <input id="ingNueConDig"  type="text" style="display: none">
                    <input id="ingNueCanCon"  type="text" style="display: none">
                    <input id="ingNueConCli"  type="text" style="display: none">
                    <input id="ingNueConSec"  type="text" style="display: none">
                    <input id="ingNueConRut"  type="text" style="display: none">
                    <input id="ingNueConSeq"  type="text" style="display: none">
                    <input id="ingNueConOfi"  type="text" style="display: none">
                    <input id="ingNueConTarCon"  type="text" style="display: none">
                    <input id="ingNueCanMed"  type="text" style="display: none">

                    <span class="titDato numCont1">Cod. sis</span>
                    <span class="inpDatp numCont2"><input id="ingNueConCodSis"  required name="inm" placeholder="Código Sistema" type="number" min="0" max="9999999"></span>
                    <span class="titDato numCont1">Acueducto</span>
                    <span class="inpDatp numCont2"><input id="ingNueConAcu" required placeholder="Acueducto" readonly type="text"></span>
                    <span class="titDato numCont1">Proceso</span>
                    <span class="inpDatp numCont2"><input id="ingNueConPro" required placeholder="Proceso" readonly type="number"></span>
                    <span class="titDato numCont1">Catastro</span>
                    <span class="inpDatp numCont2"><input id="ingNueConCat" name="pqr" required placeholder="Catastro" readonly type="text"></span>
                </span>
                <span class="datoForm col1">
                    <span class="titDato numCont1">Dirección</span>
                    <span class="inpDatp numCont3"><input  id="ingNueConDir" name="pqr" required placeholder="Dirección" readonly type="text"></span>
                    <span class="titDato numCont1">Urbanización</span>
                    <span class="inpDatp numCont2"><input  id="ingNueConUrb" name="urba" required placeholder="Urbanización" readonly type="text"></span>
                    <span class="titDato numCont1">Uso</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConUso" name="uso" required placeholder="Uso" readonly type="text"></span>
                    <span class="titDato numCont1">Unidades</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConUni" name="unidad" required placeholder="Unidades" readonly type="number"></span>
                </span>

                <div class="subCabecera fondSerCli"> Información del Usuario</div>
                <span class="datoForm col1">
                    <span class="titDato numCont1">Documento</span>
                    <span class="inpDatp numCont2"><input  id="ingNueConDoc" required placeholder="Documento"  type="text"></span>
                    <span class="titDato numCont1">Tipo Documento</span>
                    <span class="inpDatp numCont2"><select required placeholder="Tipo Documento" select id="ingNueConTipDoc" readonly ></select></span>
                    <span class="titDato numCont1">Nombre Usuario</span>
                    <span class="inpDatp numCont3"><input  id="ingNueConNom" required placeholder="Nombre Usuario" readonly type="text"></span>
                </span>
                <span class="datoForm col1">
                    <span class="titDato numCont1">Teléfono</span>
                    <span class="inpDatp numCont2"><input  id="ingNueConTel" required placeholder="Teléfono" readonly  type="text"></span>
                    <span class="titDato numCont1">Email</span>
                    <span class="inpDatp numCont2"><input  id="ingNueConEma" placeholder="Email" readonly  type="text"></span>
                </span>

                <div class="subCabecera fondSerCli"> Datos Tarifas</div>
                <span class="datoForm col1">
                    <span class="titDato numCont1">Tarifa</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConTar" required placeholder="Tarifa" readonly type="text"></span>
                    <span class="titDato numCont1">Cupo Básico</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConCup" required placeholder="Cupo Básico" readonly type="text"></span>
                    <span class="titDato numCont1">Agua</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConAgu" required placeholder="Agua" readonly type="text"></span>
                    <span class="titDato numCont1">Alcantarillado</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConAlc" required placeholder="Alcantarillado" readonly type="text"></span>
                    <span class="titDato numCont1">Tipo Suministro:</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConSum" required placeholder="Tipo Suministro" readonly type="text"></span>
                    <!--span class="titDato numCont1">Diametro:</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConDia" required placeholder="Diametro" readonly type="text"></span-->
                </span>

                <div class="subCabecera fondSerCli">Valor del Contrato</div>
                <span class="datoForm col1">
                    <span class="titDato numCont1">Derecho Incorporación</span>
                    <span class="inpDatp numCont2"><input  id="ingNueConDerInc"  name="di" readonly placeholder="D.I."  type="text"></span>
                    <span class="titDato numCont1">Fianza</span>
                    <span class="inpDatp numCont2"><input  id="ingNueConFia"  name="fianza" readonly placeholder="Fianza"  type="text"></span>
                    <span class="titDato numCont1">Total </span>
                    <span class="inpDatp numCont2"><input  id="ingNueConTotCon"  name="total" readonly placeholder="Total Contrato"  type="text"></span>
                    <span class="inpDatp numCont1">Cuotas</span>
                    <span class="inpDatp numCont2"><input  id="ingNueConCuo"  name="cuotas"  placeholder="# Cuotas"  type="number" min="0" max="4" required></span>
                </span>

                <span class="datoForm col1">
                    <input type="submit"  value="Generar Contrato" class="botonFormulario botFormSerCli"  id="ingNueConBut">
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

