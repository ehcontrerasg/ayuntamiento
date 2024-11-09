<!DOCTYPE html>
<head>
    <meta  charset="UTF-8" />
    <title>Consulta</title>

    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
    <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
    <!-- ICONOS -->
    <link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
    <!--autocompltar -->
    <script src="../../js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js"></script>
    <link href="../../css/css.css " rel="stylesheet" type="text/css" />

    <!--pag-->
    <script type="text/javascript" src="../js/main.js?4"></script>
    <script type="text/javascript" src="../js/consulta.js?135"></script>
    <link href="../../css/general.css?4" rel="stylesheet" type="text/css"/>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <script type="text/javascript" src="../../js/bootstrap.min.js"></script>

    <script type="text/javascript" src="../js/modales.js?3"></script>


</head>


 </head>
 <body id="body-consulta">
 <header id="headGen" class="cabeceraTit">
     Consulta General de Inmuebles
 </header>

 <section class="contenido">
     <article>
         <form id="ConsGenForm" target="modal-consulta-1">
            <input type="hidden" name="mod" value="" id="mod">
        <!-- <span class="datoForm col1">-->
                 <ul id="accordion" >
                     <li>
                         <div class="link">
                             <i class="fa fa-home"></i>Búsqueda Inmueble<i class="fa fa-chevron-down"></i>
                         </div>

                         <ul class="submenu">
                            <span class="datoForm col1">
                                <!-- <span class="titDato numCont1">Acueducto:</span> -->
                                <span class="inpDatp numCont1"><select required name="proyecto" select id="busGenSelPro" ></select></span>
                                <!-- <span class="titDato numCont1">Cod. Sistema:</span> -->
                                <span class="inpDatp numCont1"><input  id="busGenInpCodSis" placeholder="Cod. Sistema" type="number"  name="codSis"></span>
                                <!-- <span class="titDato numCont1">Zona Inicial:</span> -->
                                <span class="inpDatp numCont1"><input  id="busGenInpZonIni" placeholder="Zona Inicial:" pattern="[0-9]{2}[A-Za-z]{1}" name="zonIni" type="text" pattern=""  ></span>
                                <!-- <span class="titDato numCont1">Zona final:</span> -->
                                <span class="inpDatp numCont1"><input  id="busGenInpZonFin" placeholder="Zona final:" pattern="[0-9]{2}[A-Za-z]{1}" name="zonFin" type="text"  ></span>
                                <!-- <span class="titDato numCont1">Proceso inicial:</span> -->
                                <span class="inpDatp numCont1"><input  id="busGenInpProIni" placeholder="Proceso inicial:" type="text" name="procesoIni"  ></span>
                                <!-- <span class="titDato numCont1">Proceso final:</span> -->
                                <span class="inpDatp numCont1"><input  id="busGenInpProFin" placeholder="Proceso final:" type="text" name="procesoFin"   ></span>
                            </span>


                             <span class="datoForm col1">
                                <!-- <span class="titDato numCont1">Catastro Inicial:</span> -->
                                <span class="inpDatp numCont2"><input  id="busGenInpCatastroIni" placeholder="Catastro Inicial:" type="text" name="catastroIni"  ></span>

                                 <!-- <span class="titDato numCont1">Catastro Final:</span> -->
                                <span class="inpDatp numCont2"><input  id="busGenInpCatastroFin" placeholder="Catastro Final:" type="text" name="catastroFin"  ></span>
                                <span class="titDato numCont3"></span>
                                <span class="titDato numCont3"></span>
                            </span>



                             <span class="datoForm col1">
                                 <!-- <span class="titDato numCont1">Urb.:</span> -->
                                 <span class="inpDatp numCont1"><input  id="busGenInpUrb" placeholder="Urb.:" name="urb"   ></span>
                                 <span class="inpDatp numCont1"><input  id="busGenInpUrbDesc" readonly  ></span>
                                 <!-- <span class="titDato numCont1">Tipo Via:</span> -->
                                 <span class="inpDatp numCont1"><select  name="tipVia" select id="busGenSelTipVia" >
                                     <option>Tipo Vía:</option>
                                 </select></span>
                                 <!-- <span class="titDato numCont1">Nombre Via:</span> -->
                                 <span class="inpDatp numCont1"><input  id="busGenInpNomVia" placeholder="Nombre Vía:" name="NomVia"   ></span>
                                 <!-- <span class="titDato numCont1">Numero:</span> -->
                                 <span class="inpDatp numCont1"><input  id="busGenInpNumCasa" placeholder="Numero:"  name="numCasa" type="text" ></span>
                                 <!-- <span class="titDato numCont1">Estado Inm:</span> -->
                                 <span class="inpDatp numCont1"><select  name="estadoInm" select id="busGenSelEstInm" >
                                         <option value="">Estado Inm:</option>
                                         <option value="A">Activo</option>
                                         <option value="I">Inactivo</option>
                                     </select></span>
                                 <span class="inpDatp numCont1"><select  name="codEstInn" select id="busGenSelEstInmCod" ></select></span>
                            </span>

                         </ul>
                     </li>
                     <li>
                         <div class="link"><i class="fa fa-user"></i>Búsqueda Cliente<i class="fa fa-chevron-down"></i></div>
                         <ul class="submenu">
                             <span class="datoForm col1">
                                 <!-- <span class="titDato numCont1">Codigo:</span> -->
                                 <span class="inpDatp numCont1"><input  id="busGenInpCodCli" placeholder="Codigo:" type="text" name="codCli"  ></span>
                                 <!-- <span class="titDato numCont1">Nombre:</span> -->
                                 <span class="inpDatp numCont2"><input  id="busGenInpNomCli" placeholder="Nombre:" name="nomCli" pattern="[A-Za-z]{3-100}" type="text"  ></span>
                                 <!-- <span class="titDato numCont1">Cedula:</span> -->
                                 <span class="inpDatp numCont1"><input  id="busGenInpCedula" placeholder="Cedula:" name="cedula" type="text" ></span>
                                 <!-- <span class="titDato numCont1">Grupo:</span> -->
                                 <span class="inpDatp numCont2"><select  name="grupoCli" select id="busGenSelGrupoCli" ></select></span>
                                 <!-- <span class="titDato numCont1">Tipo Cliente:</span> -->
                                 <span class="inpDatp numCont1"><select  name="tipoCli" select id="busGenSelTipocli" ></select></span>
                             </span>


                             <span class="datoForm col1">
                                 <!-- <span class="titDato numCont1">Contrato.:</span> -->
                                 <span class="inpDatp numCont1"><input  id="busGenInpContrato" placeholder="Contrato:" name="contrato" type="text" pattern="[SD BC]{2}[-]{1}[0-9]{4}[-]{1}{0-9}[7]"   ></span>
                                 <span class="titDato numCont2">Inicio:</span>
                                 <span class="inpDatp numCont1"><input  id="busGenInpFecIniCon" type="date" name="fecIniCon"   ></span>
                                 <span class="titDato numCont2">Final:</span>
                                 <span class="inpDatp numCont1"><input  id="busGenInpFecFinCon" type="date" name="fecFinCon"   ></span>
                                 <span class="titDato numCont2"></span>
                                 <span class="inpDatp numCont2"></span>
                            </span>

                         </ul>
                     </li>
                     <li>
                         <div class="link"><i class="fa fa-dashboard"></i>Búsqueda Medidor<i class="fa fa-chevron-down"></i></div>
                         <ul class="submenu">
                             <span class="datoForm col1">
                                 <!-- <span class="titDato numCont1">Marca.:</span> -->
                                 <span class="inpDatp numCont2"><select  name="marcaMed" select id="busGenSelMarMed" ></select></span>
                                 <!-- <span class="titDato numCont1">Serial:</span> -->
                                 <span class="inpDatp numCont2"><input  id="busGenInpSerial" placeholder="Serial:" name="serial"   ></span>
                                 <!-- <span class="titDato numCont1">Emplazamiento:</span> -->
                                 <span class="inpDatp numCont2"><select  name="emplazamiento" select id="busGenSelEmpla" ></select></span>
                                 <!-- <span class="titDato numCont1">Suministro:</span> -->
                                 <span class="inpDatp numCont2"><select  name="suministro" select id="busGenSelSuministro" ></select></span>
                            </span>

                             <span class="datoForm col1">
                                 <span class="titDato numCont2">Fecha Instalación Inicial:</span>
                                 <span class="inpDatp numCont1"><input  id="busGenInpFecInsMedIni" type="date" name="fecInsMedIni"   ></span>
                                 <span class="titDato numCont2">Fecha Instalación Final:</span>
                                 <span class="inpDatp numCont1"><input  id="busGenInpFecInsMedFin" type="date" name="fecInsMedFin"   ></span>
                                 <span class="titDato numCont3"></span>
                                 <span class="inpDatp numCont3"></span>
                            </span>

                         </ul>
                     </li>
                     <li>
                         <div class="link"><i class="fa fa-qrcode"></i>Búsqueda Códigos<i class="fa fa-chevron-down"></i></div>
                         <ul class="submenu">
                             <span class="datoForm col1">
                                 <!-- <span class="titDato numCont1">Mora:</span> -->
                                 <span class="inpDatp numCont1"><select  name="mora" select id="busGenSelMora" >
                                         <option>Mora:</option>
                                         <option value="S">Con mora</option>
                                         <option value="N">Sin mora</option>
                                     </select></span>
                                 <!-- <span class="titDato numCont1">Totalizadores:</span> -->
                                 <span class="inpDatp numCont1"><select  name="totalizadores" select id="busGenSelTotaliza" >
                                         <option value="">Totalizadores:</option>
                                         <option value="tot">Totalizadores</option>
                                         <option value="dep">Dependientes</option>
                                         <option value="totDep">Totalizadores y Dependientes</option>
                                     </select></span>
                                 <span class="titDato numCont4"></span>
                                 <span class="inpDatp numCont4"></span>
                            </span>
                         </ul>
                     </li>
                     <li>
                         <div class="link"><i class="fa fa-file"></i>Búsqueda Factura<i class="fa fa-chevron-down"></i></div>
                         <ul class="submenu">
                             <span class="datoForm col1">
                                 <!-- <span class="titDato numCont1">Uso:</span> -->
                                 <span class="inpDatp numCont3"><select  name="uso" select id="busGenSelUso" ></select></span>
                                 <!-- <span class="titDato numCont1">Actividad:</span> -->
                                 <span class="inpDatp numCont3"><select  name="actividad"  id="busGenSelActividad" >
                                     <option value="">Actividad:</option>
                                 </select></span>
                                 <!-- <span class="titDato numCont1">Tarifa:</span> -->
                                 <span class="inpDatp numCont3"><select  name="tarifa"  id="rendCorSelTarifa" >
                                     <option value="">Tarifa:</option>
                                 </select></span>
                            </span>

                             <span class="datoForm col1">
                                 <!-- <span class="titDato numCont1"> Factura Nº:</span> -->
                                 <span class="inpDatp numCont1"><input  id="rendCorInpFactura" placeholder="Factura Nº:" name="factura" type="text"   ></span>
                                 <!-- <span class="titDato numCont1">Tipo facturas:</span> -->
                                 <span class="inpDatp numCont1"><select  name="tipoFac"  id="rendCorInpTipFac" >
                                         <option value="">Tipo facturas:</option>
                                         <option value="vencidas">Vencidas</option>
                                     </select></span>
                                 <!-- <span class="titDato numCont2">Fecha Ultimo pago inicial:</span> -->
                                 <span class="inpDatp numCont2"><input  id="rendCorInpFecUltPagIni" placeholder="Fecha Ultimo pago inicial:" name="ultPagIni"   ></span>
                                 <!-- <span class="titDato numCont2">Fecha Ultimo pago Final:</span> -->
                                 <span class="inpDatp numCont2"><input  id="rendCorInpFecUltPagFin" placeholder="Fecha Ultimo pago Final:" name="ultPagFin"   ></span>
                            </span>
                         </ul>
                     </li>
                 </ul>
<!--            </span>-->
             <input type="submit" class="botonFormulario" id="botConsGen" value="Generar" data-toggle="modal" data-target="#consultar-1">

         </form>


     </article>


 </section>

 <section id="modales-line">
    <!-- <div class="modal-window">
         <i class="fa fa-binoculars"></i><b>Consulta General</b> 
     </div>
    <div class="modal-window">
         <b>100025</b> <i class="fa fa-minus" aria-hidden="true"></i>
     </div> -->
 </section>

<div class="modal fade" id="consultar-1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-close="1"><span aria-hidden="true">&times;</span></button>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="minimize-buttom">&minus;</button>
        <h4 class="modal-title" id="myModalLabel">Consulta General</h4>
      </div>
      <div class="modal-body">
        <iframe frameborder="0" width="100%" class="modal-consulta-body" name="modal-consulta-1"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" data-close="1">Cerrar</button>
      </div>
    </div>
  </div>
</div>

 <footer>
 </footer>



 </body>
 </html>