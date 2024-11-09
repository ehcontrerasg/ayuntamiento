<?php
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta  charset=UTF-8" />
        <title>Entrada de Nueva Amnistía</title>
        <!-- alertas -->
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
        <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
        <script type="text/javascript" src="../../js/jquery.min.js"></script>
        <!--pag-->
        <script type="text/javascript" src="../js/nueva_amnistia.js?<?php echo time();?>"></script>
        <link href="../../css/general.css " rel="stylesheet" type="text/css" />
    </head>
    <body>
    <header class="cabeceraTit fondSerCli">
<<<<<<< HEAD
        Registro Descuento Plan Ponte al Día
=======
        Registro Descuento Plan Ponte Al Día
>>>>>>> fc896d709c0b5abde0b2a0ab502c24e8e059c571
    </header>

    <section class="contenido">
        <article>
            <form onsubmit="return false" id="regNueConForm">
                <div class="subCabecera fondSerCli"> Datos del imueble</div>
                <span class="datoForm col1">
                    <input id="ingNueConEst"  type="text" style="display: none">
                    <input id="ingNueConOfi"  type="text" style="display: none">
                    <input id="ingNueConAsi"  type="text" style="display: none">
                    <input id="ingNueConCed"  type="text" style="display: none">
                    <input id="ingNueConEnt"  type="text" style="display: none">
                    <input id="ingNueConCaj"  type="text" style="display: none">
                    <input id="ingNueConGer"  type="text" style="display: none">

                    <input id="ingNueConAct"  type="text" style="display: none">
                    <input id="ingNueConTar"  type="text" style="display: none">



                    <span class="titDato numCont1">Cod. sis</span>
                    <span class="inpDatp numCont2"><input id="ingNueConCodSis"  required name="inm" placeholder="Código Sistema" type="number" min="0" max="9999999"></span>
                    <span class="titDato numCont1">Acueducto</span>
                    <span class="inpDatp numCont2"><input id="ingNueConAcu" required placeholder="Acueducto" readonly type="text"></span>
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
                    <span class="inpDatp numCont2"><input  id="ingNueConCuo"  name="cuotas"  placeholder="# Cuotas"  type="number" min="1" max="6" required></span>
                </span>

                <div class="subCabecera fondSerCli"> Información del Cliente y/o Representante</div>
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

                <div class="subCabecera fondSerCli"> Datos Deuda Hasta Noviembre 2021</div>
                <span class="datoForm col1">
                    <span class="titDato numCont1">Deuda ($RD)</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConDeu" required placeholder="Deuda" readonly type="text"></span>
                    <span class="titDato numCont1">Penalidades ($RD)</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConPen" required placeholder="Penalidad" readonly type="text"></span>
                    <span class="titDato numCont1">A Financiar ($RD)</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConPag" required placeholder="A Financiar" readonly type="text"></span>
                </span>
                <div class="subCabecera fondSerCli"> Datos Deuda A Partir Diciembre 2021</div>
                <span class="datoForm col1">
                    <span class="titDato numCont1">Deuda ($RD)</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConDeuPos" required placeholder="Deuda" readonly type="text"></span>
                    <span class="titDato numCont1">Penalidades ($RD)</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConPenPos" required placeholder="Penalidad" readonly type="text"></span>
                    <span class="titDato numCont1">A Financiar ($RD)</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConPagPos" required placeholder="A Financiar" readonly type="text"></span>
                </span>
                <div class="subCabecera fondSerCli">Descuentos Y Reconexion</div>
                <span class="datoForm col1">
                    <span class="titDato numCont1">Descuento (%)</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConDes" required placeholder="Descuento" readonly type="text"></span>
                    <span class="titDato numCont1">Descuento ($RD)</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConValDes" required placeholder="A Descontar" readonly type="text"></span>
                    <span class="titDato numCont1" id="ingSpaConRec">Valor Reconexión ($RD)</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConValRec" required placeholder="Reconexión" readonly type="number"></span>
                </span>

                <div class="subCabecera fondSerCli">Total a Pagar Sin Reconexion</div>
                <span class="datoForm col1">
                    <span class="titDato numCont1">Valor ($RD)</span>
                    <span class="inpDatp numCont1"><input  id="ingNueConValPag" required placeholder="A Pagar" readonly type="text"></span>
                </span>
                <span class="datoForm col1">
                    <input type="submit"  value="Generar Amnistía" class="botonFormulario botFormSerCli"  id="ingNueConBut">
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

