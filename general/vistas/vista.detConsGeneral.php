<!DOCTYPE html>
<head>
    <meta  charset="UTF-8" />
    <title>Consulta GENERAL</title>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
    <script type="text/javascript" src="../../js/sweetalert.min.js"></script>

    <!--pag-->
    <script src="../../js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="../js/consulta.js?<?php echo time()?>"></script>
    <script type="text/javascript" src="../js/tabber.js?<?php echo time()?>"></script>
    <link href="../../css/general.css?<?php echo time()?>" rel="stylesheet" type="text/css" />

    <!--flexy grid-->
    <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
    <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js "></script>

    <!--    formato moneda-->

    <script src="../../jquery.formatCurrency-1.4.0/jquery.formatCurrency-1.4.0.js" type="text/javascript"></script>
    <script src="../../jquery.formatCurrency-1.4.0/i18n/jquery.formatCurrency.es-DO.js" type="text/javascript"></script>

</head>


 </head>
 <body>


 <section class="contenido">
     <article>
         <div class="contenedor-flexy" >
             <table id="flexGenListInm">
             </table>
         </div>

         <div class="contenedor-flexy" id="detConDatInm" >
             <fieldset id="fieldInfo" class="fieldset" >
                 <legend class="legend_fact" id="LegDetConDatInmCod"><b>Inmueble N&deg;: </b></legend>


                 <input  class="input" type="text" readonly id="inpDetConDatEstCreLegend" style="width: 1000px; font-size:30px" />


                 <div id="detContent">

                     <div class="divinfo">
                         <label>Zona:</label>
                         <input class="input" type="text" readonly  id="inpDetConDatInmZon" />
                     </div>

                     <div class="divinfo">
                         <label>Direccion:</label>
                         <input class="input" type="text" readonly  id="inpDetConDatInmDir" />
                     </div>

                     <div class="divinfo">
                         <label>Urb.:</label>
                         <input class="input" type="text" readonly id="inpDetConDatInmUrb" />
                     </div>

                     <div class="divinfo">
                         <label>Fecha Alta:</label>
                         <input class="input" type="text" readonly id="inpDetConDatInmFecAlt" />
                     </div>

                     <div class="divinfo">
                         <label>Catastro:</label>
                         <input class="input" type="text" readonly id="inpDetConDatInmCat" />
                     </div>

                     <div class="divinfo">
                         <label>Proceso:</label>
                         <input class="input" type="text" readonly id="inpDetConDatInmPro" />
                     </div>

                     <div class="divinfo">
                         <label>Estado</label>
                         <input class="input" type="text" readonly id="inpDetConDatInmEst" />
                     </div>

                     <div class="divinfo" id="divClie">
                         <label>Cliente:</label>
                         <input class="input" type="text" readonly id="inpDetConDatInmCli" />
                     </div>

                     <div class="divinfo">
                         <label>Nombre:</label>
                         <input class="input" type="text" readonly id="inpDetConDatInmNom" />
                     </div>

                     <div class="divinfo" id="divContrato">
                         <label>Contrato:</label>
                         <input class="input" type="text" readonly id="inpDetConDatInmCon" />
                     </div>

                     <div class="divinfo" id="divDoc">
                         <label>Doc.:</label>
                         <input class="input" type="text" readonly id="inpDetConDatInmDoc" />
                     </div>
                     <div class="divinfo" id="divTel">
                         <label>Telefono:</label>
                         <input class="input" type="text" readonly id="inpDetConDatInmTel" />
                     </div>

                     <div class="divinfo" id="divEmail">
                         <label>Email:</label>
                         <input class="input" type="text" readonly id="inpDetConDatInmEmail" />
                     </div>

                     <div class="divinfo" >
                         <label>A Favor:</label>
                         <input class="input"   type="text" readonly id="inpDetConDatInmFav" />
                     </div>

                     <div class="divinfo" >
                         <label>Dif.:</label>
                         <input class="input"  type="text" readonly id="inpDetConDatInmDif" />
                     </div>

                     <div class="divinfo" >
                         <label>#Facturas:</label>
                         <input  class="input" type="text" readonly id="inpDetConDatInmFac" />
                     </div>

                     <div class="divinfo">
                         <label>Deuda:</label>
                         <input  class="input" type="text" readonly id="inpDetConDatInmDeu" />
                     </div>

                     <div class="divinfo" id="divReco">
                         <label>Reco.:</label>
                         <input  class="input" type="text" readonly id="inpDetConDatInmRec" />
                     </div>

                     <div class="divinfo" id="divTotali">
                         <label>Totalizador.:</label>
                         <input  class="input" type="text" readonly id="inpDetConDatTota" />
                     </div>

                     <div class="divinfo" id="divEstCre">
                         <label>Estado de credito.:</label>
                         <input  class="input" type="text" readonly id="inpDetConDatEstCre" />
                     </div>

                 </div>
                <button class="btn" id="btnActualizarDatos">Actualizar datos</button>
             </fieldset>
         </div>

         <div class="contenedor-flexy" >
             <div class="tabber" id="tab">

                 <div class="tabbertab" title="Medidor" id="tabMed">
                     <div class="contenedor-flexy" >
                         <table id="flexMed">
                         </table>
                     </div>

                 </div>

                 <div class="tabbertab" title="Medidores hijos" id="tabMedHijos">
                     <div class="contenedor-flexy" >
                         <table id="flexMedHij">
                         </table>
                     </div>
                 </div>

                 <div class="tabbertab" title="Lecturas" id="tabLec">
                     <div class="contenedor-flexy" >
                         <table id="flexyLec">
                         </table>
                     </div>
                 </div>

                 <div class="tabbertab" title="Servicios">
                     <div class="contenedor-flexy" >
                         <table id="flexyServ">
                         </table>
                     </div>

                 </div>
                 <div class="tabbertab" title="Facturas">
                     <div class="flexigrid" >
                         <div style="display: block;float: left">
                             <table id="flexyFact" style="display:none">
                             </table>
                         </div>
                         <div style="display: block;float: left" >
                             <input type="radio" id="rbfactipofac" name="rbfactipofac" onChange="recargaifr();" value="M" checked> Factura
                             <input type="radio" id="rbfactipofac" name="rbfactipofac"  onchange="recargaifr();" value="P"> Datos
                             <input type="hidden" id="inpdatfaccodfac">
                             <input type="hidden" id="inpdatfacinm">
                         </div>
                         <div style="display: block;float: left">
                             &nbsp;&nbsp;&nbsp;<a class="linkRep" id="linkImpEstCuenta" href="../../facturacion/datos/datos.RepEstCon.php" target="popupEstCuenta" ><font size="2px">Imprimir estado por cuenta</font></a>
                             &nbsp;&nbsp;&nbsp;<a class="linkRep" id="linkImpEstConcepto" target="popupEstCon" ><font size="2px">Imprimir estado por concepto</font></a>
                         </div>

                         <object id="ifpdf" name="ifpdf" style="display: block;float: left;width: 480px;height: 259px " ></object>

                         <div style="display: block;float: left">
                             <table id="flexdatdetfactura" style="display:none">
                             </table>
                         </div>

                         <div style="display: block;float: left" >
                             <table id="flexdatdetlectura" style="display:none">
                             </table>
                         </div>

                         <div style="display: block;float: left" >
                             <table id="flexDatPagAplicFct" style="display:none">
                             </table>
                         </div>

                         <div style="display: block;float: left" >
                             <table id="flexdatestconcepto" style="display:none">
                             </table>
                         </div>

                         <div style="display: block;float: left" >
                             <table id="flexDatDif" style="display:none">
                             </table>
                         </div>

                         <div style="display: block;float: left" >
                             <table id="flexDatPdc" style="display:none">
                             </table>
                         </div>

                         <div id="diddatestcuenta" >
                             <table id="flexDatFacEstCuen" style="display:none">
                             </table>
                         </div>


                     </div>

                 </div>

                 <div class="tabbertab" title="Diferidos">
                     <div class="flexigrid" >
                         <div style="display: block;float: left">
                             <table id="flexyDiferidos">
                             </table>
                         </div>

                         <div style="display: block;float: left">
                             <table id="flexyCuotaDiferidos" style="display:none">
                             </table>
                         </div>
                     </div>

                 </div>

                 <div class="tabbertab" title="Pagos">

                     <div class="flexigrid" >
                         <div style="display: block;float: left">
                             <table id="flexyPagos">
                             </table>
                         </div>
                         <div style="display: block;float: left">
                             <table id="flexyFactPag" ">
                             </table>
                         </div>
                         <div style="display: block;float: left">
                             <table id="flexyAcuerPag" ">
                             </table>
                         </div>

                         <div id="divdescPag" ><label>forma de pago</label>
                             <label id="lbfrmpagoPag" class="desc"></label>
                             <label>entidad</label> <label id="lbentidadPag" class="desc"></label>
                             <label>punto</label> <label id="lbpuntoPag" class="desc"></label>
                             <label>caja</label> <label id="lbcajaPag" class="desc"></label>
                         </div>
                     </div>

                 </div>

                 <div class="tabbertab" title="O. Recaudos">
                     <div class="flexigrid" >

                         <div style="display: block;float: left">
                             <table id="flexOtroRec" style="display:none">
                             </table>
                         </div>

                         <div style="display: block;float: left">
                             <table id="flexFacApliOtrRec" style="display:none">
                             </table>
                         </div>

                         <div id="divdescPag" ><label>forma de pago</label>
                             <label id="DatOtrReclbfrmpago" class="desc"></label>
                             <label>entidad</label> <label id="DatOtrReclbentidad" class="desc"></label>
                             <label>punto</label> <label id="DatOtrReclbpunto" class="desc"></label>
                             <label>caja</label> <label id="DatOtrReclbcaja" class="desc"></label>
                         </div>
                     </div>

                 </div>

                 <div class="tabbertab" title="Corte y Reconexion">
                     <div class="flexigrid" >

                         <div style="display: block;float: left">
                             <table id="flexCorte" style="display:none">
                             </table>
                         </div>

                         <div style="display: block;float: left">
                             <table id="flexReconexion" style="display:none">
                             </table>
                         </div>
                     </div>
                 </div>


                 <div class="tabbertab" title="Observaciones">
                     <div class="flexigrid" >

                         <div style="display: block;float: left">
                             <table id="flexObs" style="display:none">
                             </table>
                         </div>
                     </div>
                 </div>

                 <div class="tabbertab" title="Deuda Cero">
                     <div class="flexigrid" >
                         <div style="display: block;float: left">
                             <table id="flexDeudaCero" style="display:none">
                             </table>
                         </div>
                     </div>
                 </div>

                 <div class="tabbertab" title="Saldo A Favor">
                     <div class="flexigrid" >
                         <div style="display: block;float: left">
                             <table id="flexiSaldos" style="display:none">
                             </table>
                         </div>

                         <div style="display: block;float: left">
                             <table id="flexiAplSaldos" style="display:none">
                             </table>
                         </div>
                     </div>
                 </div>

                 <div class="tabbertab" title="PQRS">
                     <div class="flexigrid" >
                         <div style="display: block;float: left">
                             <table id="flexiReclamos" style="display:none">
                             </table>
                         </div>
                     </div>
                 </div>

                 <div class="tabbertab" title="Fotos">
                     <div class="flexigrid" >
                        <div id="fotosConsGen" style="display: block;float: center">
                        
                        </div>
                     </div>
                 </div>

                 <div class="tabbertab" title="Documentos">
                     <div class="flexigrid" >
                         <div style="display: block;float: left">
                             <table id="flexiDocumentos" style="display:none">
                             </table>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </article>
 </section>

 <footer>
 </footer>

 </body>
 </html>