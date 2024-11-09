
/*Declaraciones*/
const frm = $('form');
const slcAcueducto = $("#slcAcueducto");
const txtZonaDesde = $("#txtZonaDesde");
const txtZonaHasta = $("#txtZonaHasta");
const txtPeriodoDesde = $("#txtPeriodoDesde");
const txtPeriodoHasta = $("#txtPeriodoHasta");
const dvResumenGeneral  = $('#dvResumenGeneral');
const dvResumenConceptoUsoTarifa  = $('#dvResumenConceptoUsoTarifa');
const articleExportacion = $('#articleExportacion');
const btnLimpiar = $('#btnLimpiar');
const articleReporte = $('#articleReporte');
/*Estilos*/
let estilos = {
                tituloTabla:'background: #336699; color: white;',
                filaConcepto: 'background: #0080C0; color: white;',
                filaUso:'background-color: #83B9FC; color: white;',
                filaTotalUso: 'background: #999999; color: white;',
                filaTotalConcepto: 'background: #666666; color: white;',
                filaTotalGeneral: 'background: #333; color: white;',
                tbody: 'background-color: #eaeaea;'
              };
/*Fin de estilos*/

/*Fin de declaraciones*/



/*Funciones*/

    function getDatos(tip = ''){
        if(tip === ''){
            console.error("Debe pasar la variable 'tip'");
            return false;
        }

        let url= '../datos/datos.resumenFacturacion.php';
        let datos = {
            proyecto:    slcAcueducto.val(),
            zonaDesde:    txtZonaDesde.val(),
            zonaHasta:    txtZonaHasta.val(),
            periodoDesde: txtPeriodoDesde.val(),
            periodoHasta: txtPeriodoHasta.val(),
            tip: tip
        };

        return $.post(url,datos);
    }
    function getProyectos() {
    $.post("../datos/datos.facturadosAdeudadosRutSect.php",{tip:'getProyectos'},function(data){
        for(var i=0;i<data.length;i++)
        {
            slcAcueducto.append(new Option(data[i]["DESCRIPCION"],data[i]["CODIGO"],false,false));
        }},'json');

    }
    function formatoMoneda(valor){
        return new Intl.NumberFormat().format(valor);
    }
    function limpiar(){
        slcAcueducto.val('');
        txtZonaDesde.val('');
        txtZonaHasta.val('');
        txtPeriodoDesde.val('');
        txtPeriodoHasta.val('');
        articleExportacion.css({display:'none'});
        articleReporte.empty();
    }

    /*function table2exel(idDiv,nombreArchivo){
        $("#"+idDiv).table2excel({
            exclude: ".noExl",
            name: "Worksheet Name",
            filename: nombreArchivo+".xls", // do include extension
            preserveColors: true // set to true if you want background colors and font colors preserved
        });
    }*/
/*var tableToExcel = (function() {
    var uri = 'data:application/vnd.ms-excel;base64,'
        , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
        , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
        , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
    return function(table, name) {
        if (!table.nodeType) table = document.getElementById(table)
        var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
        window.location.href = uri + base64(format(template, ctx));
    };
})();*/

/*Resumen general*/

    function filaFacturacionPorPromedio(facturacionPorPromedio){

        facturacionPorPromedio= JSON.parse(facturacionPorPromedio);

        var fila = ``;
        facturacionPorPromedio.forEach((facturacion)=>{
            let total = Math.round(facturacion.recaudado)+Math.round(facturacion.pendiente);
            fila = `<td>${formatoMoneda(facturacion.cantidad)}</td><td>${formatoMoneda(facturacion.consumo)} m³</td><td>RD$ ${formatoMoneda(facturacion.facturado)}</td><td> RD$ ${formatoMoneda(total)}</td>`;
        });

        return fila;
    }
    function filaFacturacionPorDiferencia(facturacionPorDiferencia){

        facturacionPorDiferencia= JSON.parse(facturacionPorDiferencia);

        var fila = ``;
        facturacionPorDiferencia.forEach((facturacion)=>{
            let total = Math.round(facturacion.recaudado)+Math.round(facturacion.pendiente);
            fila = `<td>${formatoMoneda(facturacion.cantidad)}</td><td>${formatoMoneda(facturacion.consumo)} m³ </td><td> RD$ ${formatoMoneda(facturacion.facturado)}</td><td>RD$ ${formatoMoneda(total)}</td>`;
        });

        return fila;
    }
    function filaTotalResumenFacturacion(facturacionPromedioDiferencia){

        let valores = {cantidad:0,consumo:0,facturado:0, total:0};
        Object.values(facturacionPromedioDiferencia).forEach((facturacion)=>{

            let tipoFacturacion = JSON.parse(facturacion)[0];

            valores.cantidad += Math.round(tipoFacturacion.cantidad);
            valores.consumo += Math.round(tipoFacturacion.consumo);
            valores.facturado += Math.round(tipoFacturacion.facturado);

            let recaudado = Math.round(tipoFacturacion.recaudado);
            let pendiente = Math.round(tipoFacturacion.pendiente);

            valores.total += (recaudado+pendiente);
        });

        let celdas = `<td>${formatoMoneda(valores.cantidad)}</td><td>${formatoMoneda(valores.consumo)} m³</td><td> RD$ ${formatoMoneda(valores.facturado)}</td><td> RD$ ${formatoMoneda(valores.total)}</td>`;

        return celdas;
    }

    function tablaResumenGeneral(facturacionPromedioDiferencia){

        let facturacionPorPromedio    = facturacionPromedioDiferencia.facturacionPromedio;
        let facturacionPorDiferencia  = facturacionPromedioDiferencia.facturacionDiferencia;

        let celdasFacturacionPorPromedio = filaFacturacionPorPromedio(facturacionPorPromedio);
        let celdasFacturacionPorDiferencia = filaFacturacionPorDiferencia(facturacionPorDiferencia);
        let celdasTotal = filaTotalResumenFacturacion(facturacionPromedioDiferencia);

        let table = `<table>
                        <thead>
                            <tr class='tituloTabla' style="${estilos.tituloTabla}"><th colspan="5">Resumen general</th></tr>
                            <tr><th>Método</th><th>Inmuebles</th><th>Consumo</th><th>Facturado</th><th>Total</th></tr>
                        </thead>
                        <tbody style="${estilos.tbody}">
                            <tr>
                                <td>Promedio</td>
                                ${celdasFacturacionPorPromedio}
                            </tr>
                            <tr>
                                <td>Diferencia</td>
                                ${celdasFacturacionPorDiferencia}
                            </tr>
                            <tr>
                                <td>Total</td>
                                ${celdasTotal}
                            </tr>
                        </tbody>
                     </table>`;

        dvResumenGeneral.empty();
        dvResumenGeneral.append(table);
    }

    function getResumenGeneral(){

        getDatos('facturacionPorPromedio')
            .then(facturacionPromedio => {
                getDatos('facturacionPorDiferencia').then(facturacionDiferencia =>{
                    let facturacionPromedioDiferencia = {facturacionPromedio:facturacionPromedio,facturacionDiferencia:facturacionDiferencia};
                    tablaResumenGeneral(facturacionPromedioDiferencia);
                });
            });
    }
    /*Fin del resumen general*/

    /*Resumen por concepto, uso y tarifa*/
    function getDetalle(){

        getDatos('conceptos').then((conceptos)=>{
            getDatos('usos').then((usos)=>{
                getDatos('tarifas').then((tarifas)=>{
                    let datos = {conceptos:conceptos, usos:usos, tarifas:tarifas};
                    let cuerpoTabla = cuerpoTablaResumenConceptoUsoTarifa(datos);
                    tablaResumenConceptoUsoTarifa(cuerpoTabla);
                    swal("Mensaje!", "Has Generado correctamente el reporte", "success");
                });
            });
        });

    }
    function cuerpoTablaResumenConceptoUsoTarifa(datos){

        let objetos = Object.values(datos);

        let conceptos = JSON.parse(objetos[0]);
        let usos      = JSON.parse(objetos[1]);
        let tarifas  = JSON.parse(objetos[2]);

        /*Totales por tarifa*/
        let tarifaTotalCantidad  = 0;
        let tarifaTotalConsumo   = 0;
        let tarifaTotalUnidades  = 0;
        let tarifaTotalFacturado = 0;
        let tarifaTotal          = 0;
        /*Fin de totales por tarifa*/

        /*Totales por concepto*/
        let conceptoTotalCantidad  = 0;
        let conceptoTotalConsumo   = 0;
        let conceptoTotalUnidades  = 0;
        let conceptoTotalFacturado = 0;
        let conceptoTotal          = 0;
        /*Fin de totales por concepto*/

        /*Totales generales*/
        let totalGeneralCantidad  = 0;
        let totalGeneralConsumo   = 0;
        let totalGeneralUnidades  = 0;
        let totalGeneralFacturado = 0;
        let totalGeneral          = 0;
        /*Fin de totales generales*/

        let cuerpo = ``;

        conceptos.forEach((concepto)=>{
            cuerpo += `<tr class='filaConcepto' style="${estilos.filaConcepto}"><td colspan=6> Concepto: ${concepto.desc_servicio}</td></tr>`;
            usos.filter((uso)=>uso.concepto === concepto.concepto).forEach((uso)=>{
                cuerpo += `<tr class="filaUso" style="${estilos.filaUso}"><td colspan=6>Uso: ${uso.desc_uso}</td></tr>`+
                          `<tr><th>Tarifas</th><th>Inmuebles</th><th>Consumo</th><th>Unidades</th><th>Facturado</th><th>Total</th></tr>`;
                let tarifasPorUso = tarifas.filter((tarifa)=>tarifa.uso === uso.desc_uso && tarifa.concepto === concepto.concepto );
                tarifasPorUso.forEach((tarifaPorUso)=>{

                    tarifaTotalCantidad  += Math.round(tarifaPorUso.cantidad);
                    tarifaTotalConsumo   += Math.round(tarifaPorUso.consumo);
                    tarifaTotalUnidades  += Math.round(tarifaPorUso.unidades);
                    tarifaTotalFacturado += Math.round(tarifaPorUso.facturado);
                    let total             = (Math.round(tarifaPorUso.recaudado) + Math.round(tarifaPorUso.pendiente));
                    tarifaTotal          += total;

                    cuerpo += `<tr>
                                    <td>${tarifaPorUso.codigo_tarifa}-${tarifaPorUso.desc_tarifa}</td>
                                    <td>${formatoMoneda(tarifaPorUso.cantidad)}</td>
                                    <td>${formatoMoneda(tarifaPorUso.consumo)} m³</td>
                                    <td>${formatoMoneda(tarifaPorUso.unidades)}</td>
                                    <td>RD$ ${formatoMoneda(tarifaPorUso.facturado)}</td>
                                    <td>RD$ ${formatoMoneda(total)}</td>
                                </tr>`;



                });

                cuerpo +=`<tr class='filaTotalUso' style="${estilos.filaTotalUso}">
                                <td><strong>Total uso ${uso.desc_uso.toLowerCase()}</strong></td>
                                <td>${formatoMoneda(tarifaTotalCantidad)}</td>
                                <td>${formatoMoneda(tarifaTotalConsumo)} m³</td>
                                <td>${formatoMoneda(tarifaTotalUnidades)}</td>
                                <td>RD$ ${formatoMoneda(tarifaTotalFacturado)}</td>
                                <td>RD$ ${formatoMoneda(tarifaTotal)}</td>
                          </tr>`;

                conceptoTotalCantidad   += Math.round(tarifaTotalCantidad);
                conceptoTotalConsumo    += Math.round(tarifaTotalConsumo);
                conceptoTotalUnidades   += Math.round(tarifaTotalUnidades);
                conceptoTotalFacturado  += Math.round(tarifaTotalFacturado);
                conceptoTotal           += Math.round(tarifaTotal);


                /*Reseteo de variables para continuar calculando los totales por tarifa*/
                tarifaTotalCantidad  = 0;
                tarifaTotalConsumo   = 0;
                tarifaTotalUnidades  = 0;
                tarifaTotalFacturado = 0;
                tarifaTotal          = 0;
                /*Fin del reseteo*/

            });

            cuerpo += `<tr class='filaTotalConcepto' style="${estilos.filaTotalConcepto}">
                           <td><strong>Total concepto ${concepto.desc_servicio.toLowerCase()}</strong></td>
                           <td>${formatoMoneda(conceptoTotalCantidad)}</td>
                           <td>${formatoMoneda(conceptoTotalConsumo)} m³</td>
                           <td>${formatoMoneda(conceptoTotalUnidades)}</td>
                           <td>RD$ ${formatoMoneda(conceptoTotalFacturado)}</td>
                           <td>RD$ ${formatoMoneda(conceptoTotal)}</td>
                       </tr>`;


            totalGeneralCantidad  += Math.round(conceptoTotalCantidad);
            totalGeneralConsumo   += Math.round(conceptoTotalConsumo);
            totalGeneralUnidades  += Math.round(conceptoTotalUnidades);
            totalGeneralFacturado += Math.round(conceptoTotalFacturado);
            totalGeneral          += Math.round(conceptoTotal);


            /*Reseteo de variables de total por concepto*/
            conceptoTotalCantidad    =0;
            conceptoTotalConsumo     =0;
            conceptoTotalUnidades    =0;
            conceptoTotalFacturado   =0;
            conceptoTotal            =0;
            /*Fin reseteo de variables de total por concepto*/
        });

        cuerpo += `<tr class='filaTotalGeneral' style="${estilos.filaTotalGeneral}">
                        <td><strong>Total general</strong></td>
                        <td>${formatoMoneda(totalGeneralCantidad)}</td>
                        <td>${formatoMoneda(totalGeneralConsumo)} m³</td>
                        <td>${formatoMoneda(totalGeneralUnidades)}</td>
                        <td>RD$ ${formatoMoneda(totalGeneralFacturado)}</td>
                        <td>RD$ ${formatoMoneda(totalGeneral)}</td>
                   </tr>`;

        return cuerpo;
    }
    function tablaResumenConceptoUsoTarifa(cuerpoTabla){
        let tabla = `
                        <table>
                            <thead>
                                <tr class='tituloTabla' style="${estilos.tituloTabla}"><th  colspan=6 >Resumen concepto-uso-tarifa</th></tr>
                            </thead>
                            <tbody style="${estilos.tbody}">
                                ${cuerpoTabla}
                            </tbody>
                        </table>
                    `;
        dvResumenConceptoUsoTarifa.empty();
        dvResumenConceptoUsoTarifa.append(tabla);
    }

    /*Fin resumen por concepto, uso y tarifa*/

/*Fin de funciones*/

/*Eventos*/

$(document).ready(()=>{
    getProyectos();
});

frm.submit((e)=> {
    e.preventDefault();

    swal
    ({
            title: "Advertencia!",
            text: "El reporte demorara unos segundos en salir.",
            showConfirmButton: true,
            showCancelButton: true,
            showLoaderOnConfirm: true,
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function(isConfirm)
        {
            if (isConfirm)
            {
                compSession(getResumenGeneral);
                compSession(getDetalle);
                articleExportacion.css({display:'flex'});
            }
        });

});

$("#dvPdf").on('click',()=>{
    let proyecto     = slcAcueducto.val();
    let zonaDesde    = txtZonaDesde.val();
    let zonaHasta    = txtZonaHasta.val();
    let periodoDesde = txtPeriodoDesde.val();
    let periodoHasta = txtPeriodoHasta.val();

    let nombreArchivo = 'Resumen de facturación'+proyecto+' '+zonaDesde+' '+zonaHasta+' '+periodoDesde+' '+periodoHasta;
    tableToExcel('articleReporte', nombreArchivo);
    //table2exel('articleReporte',nombreArchivo);
});

btnLimpiar.on('click',limpiar);
/*Fin de eventos*/

