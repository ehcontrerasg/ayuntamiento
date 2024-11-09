/**
 * Created by PC on 6/14/2016.
 */

var inpInmPag;
var tabflexPag;
var filflexPag;
var codpago;
var lbFormPag;
var lbEntPag;
var lbPuntPag;
var lbCajPag;


function inicioPagos(){
    lbFormPag=document.getElementById("lbfrmpagoPag");
    lbEntPag=document.getElementById("lbentidadPag");
    lbPuntPag=document.getElementById("lbpuntoPag");
    lbCajPag=document.getElementById("lbcajaPag");
}

function asigevenPagos() {
    tabflexPag = document.getElementById("flexpag");
    filflexPag = tabflexPag.getElementsByTagName("tr");
    for (i = 0; i < filflexPag.length; i++) {
        if(filflexPag[i]){
            var currentRow = filflexPag[i];
            currentRow.addEventListener("click",eventoPagos);
        }

    }
}

function flexyPagos(){
    inpInmPag=document.getElementById("inmPago").value;
    //alert(inpInm);
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexpag").flexigrid	(
        {
            url: './../datos/datos.pagos.php?inmueble='+inpInmPag,

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:20,  align: 'center'},
                {display: 'Cod. pago', name: 'ID_PAGO', width: 80, sortable: true, align: 'center'},
                {display: 'Fecha', name: 'FECHA_PAGO', width: 85, sortable: true, align: 'center'},
                {display: 'Referencia', name: 'REFERENCIA', width: 371, sortable: true, align: 'center'},
                {display: 'Importe', name: 'IMPORTE', width: 80, sortable: true, align: 'center'},
				{display: 'Motivo Anula', name: 'MOTIVO_REV', width: 280, sortable: true, align: 'center'},
				{display: 'Fecha Anula', name: 'FECHA_REV', width: 80, sortable: true, align: 'center'},
				{display: 'Usuario Anula', name: 'USR_REV', width: 80, sortable: true, align: 'center'}
            ],

            sortname: "FECHA_PAGO",
            sortorder: "desc",
            usepager: false,
            title: 'Pagos',
            useRp: false,
            onSuccess: function(){asigevenPagos()},
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            height: 180
        }
    );
}



function flexyfactaplPago(){


    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexfacap").flexigrid	(
        {
            url: './../datos/datos.flexyfactPend.php?pago='+codpago,

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:13,  align: 'center'},
                {display: 'Periodo', name: 'ID_PAGO', width: 52, sortable: true, align: 'center'},
                {display: 'Factura', name: 'FECHA_PAGO', width: 48, sortable: true, align: 'center'},
                {display: 'Total Factura', name: 'REFERENCIA', width: 86, sortable: true, align: 'center'},
                {display: 'Importe Aplicado', name: 'IMPORTE', width: 32, sortable: true, align: 'center'}
            ],

            sortname: "PERIODO",
            sortorder: "desc",
            usepager: false,
            title: 'Pagos aplicados',
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            height: 180
        }
    );
    $("#flexfacap").flexOptions({url: './../datos/datos.flexyfactPend.php?pago='+codpago});
    $("#flexfacap").flexReload();
}


function flexydifapPago(){


    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexdifap").flexigrid	(
        {
            url: './../datos/datos.flexydifAp.php?pago='+codpago,

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:13,  align: 'center'},
                {display: 'Cdf', name: 'ID_PAGO', width: 52, sortable: true, align: 'center'},
                {display: 'Diferido', name: 'FECHA_PAGO', width: 48, sortable: true, align: 'center'},
                {display: 'Fecha', name: 'REFERENCIA', width: 86, sortable: true, align: 'center'},
                {display: 'Importe', name: 'IMPORTE', width: 32, sortable: true, align: 'center'}
            ],

            sortname: "FECHA_PAGO",
            sortorder: "desc",
            usepager: false,
            title: 'Acuerdos aplicados',
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            //width: 686,
            height: 180
        }
    );
    $("#flexdifap").flexOptions({url: './../datos/datos.flexydifAp.php?pago='+codpago});
    $("#flexdifap").flexReload();
}

function eventoPagos(){
    codpago=this.getAttribute("id").replace("row","");
    formpagoPagos();
    entpagoPagos();
    flexydifapPago()
    flexyfactaplPago();

}


function formpagoPagos(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var datos=obj = JSON.parse(xmlhttp.responseText);
            lbFormPag.innerHTML="";
            for(var x=0;x<datos.length;x++)
            {
                if(x==0){
                    lbFormPag.innerHTML=datos[x]["DESCRIPCION"];
                }else{
                    lbFormPag.innerHTML=lbFormPag.textContent+','+datos[x]["DESCRIPCION"];
                }
            }
        }
        else if(xmlhttp.readyState == 1){
           // spinner = new Spinner(opts).spin(formRealizaRes);
           // formRealizaRes.appendChild(spinner.el);
        }
    }
    xmlhttp.open("POST", "../datos/datos.detpag.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=tipPago&pag="+codpago);


}


function entpagoPagos(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var datos=obj = JSON.parse(xmlhttp.responseText);

            lbEntPag.innerHTML="";
            lbPuntPag.innerHTML="";
            lbCajPag.innerHTML="";

            for(var x=0;x<datos.length;x++)
            {
                if(x==0){
                    lbEntPag.innerHTML=datos[x]["DESC_ENTIDAD"];
                    lbPuntPag.innerHTML=datos[x]["DESC_PUNTO"];
                    lbCajPag.innerHTML=datos[x]["DESC_CAJA"];

                }else{

                    lbEntPag.innerHTML=lbEntPag.textContent+','+datos[x]["DESC_ENTIDAD"];
                    lbPuntPag.innerHTML=lbPuntPag.textContent+','+datos[x]["DESC_PUNTO"];
                    lbCajPag.innerHTML=lbCajPag.textContent+','+datos[x]["DESC_CAJA"];

                }
            }
        }
        else if(xmlhttp.readyState == 1){
            // spinner = new Spinner(opts).spin(formRealizaRes);
            // formRealizaRes.appendChild(spinner.el);
        }
    }
    xmlhttp.open("POST", "../datos/datos.detpag.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=ubPago&pag="+codpago);
}



