/**
 * Created by PC on 8/5/2016.
 */

    //// pantalla principal
var relPagInpBusInm;
var factura;
var relPagForm;


/// rel facturas
var sfCabecera;
var sfValor;
var sfPeriodo;
var sfFechar;
var sfValApl;
var sfValRes;
var sfObs;
var sfNunFac;
var sfForm;
var sfObsCom;


/// sf inmueble
var sfinmDeu, sfInmPer, sfInmNumFac, sfInmValAp,sfInmObsSal,sfInmForm,sfInmObsSalCom,sfInmCab ;

function aplSfInmInicio()
{
    pruebaSes2();
    sfinmDeu = document.getElementById("aplSfInmInpDeu");
    sfInmPer = document.getElementById("aplSfInmInpPer");
    sfInmNumFac = document.getElementById("aplSfInmInpNumfac");
    sfInmValAp = document.getElementById("aplSfInmInpAValAp");
    sfInmObsSal = document.getElementById("aplSfInmTexAObs");
    sfInmObsSalCom = document.getElementById("aplSfInmTextAObsCom");
    sfInmForm = document.getElementById("aplSfInmForm");
    sfInmCab = document.getElementById("aplSfInmCab");


    sfInmForm.addEventListener("submit",generaSfInm);
    sfInmObsSalCom.addEventListener("keyup",function(){sfInmObsSal.value="Saldo a favor aplicado A inmueble con deuda "+sfinmDeu.value+" del periodo "+sfInmPer.value+" "+sfInmObsSalCom.value; });

    sfInmCab.innerHTML+=' al inmueble ' +window.opener.relPagInpBusInm.value;
    llenaInfoInmDeuSf();
}

function generaSfInm(){

    var val=sfInmValAp.value,obs=sfInmObsSal.value,
        inm=window.opener.relPagInpBusInm.value;

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

            try{
                var datos=JSON.parse(xmlhttp.responseText);
                if (datos["res"]=="true"){
                    swal
                    (
                        {
                            title: "Mensaje!",
                            text: "Has Procesado correctamente los SF.",
                            showConfirmButton: true,
                            type: "success"
                        },
                        function(isConfirm)
                        {
                            if (isConfirm)
                            {
                                window.opener.flexyFactPag();
                                window.close();
                            }
                        });
                }else if(datos["res"]=="false"){
                    swal("Mensaje!", datos["error"], "error");
                }
            }catch(err){
                swal("Mensaje!", xmlhttp.responseText, "error");
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.reliquidacion_pag.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=ingSfInm&val="+val+"&obs="+obs+"&inm="+inm);


}

function llenaInfoInmDeuSf(){
    var inm=window.opener.relPagInpBusInm.value;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var datos=JSON.parse(xmlhttp.responseText);
            if(datos){
                sfinmDeu.value=datos[0]["DEUDA"];
                sfInmPer.value=datos[0]["PERIODO"];
                sfInmNumFac.value=datos[0]["CANTIDAD"];

                sfInmObsSal.value=sfInmObsSal.value="Saldo a favor aplicado A inmueble con deuda "+sfinmDeu.value+" del periodo "+sfInmPer.value;

            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.reliquidacion_pag.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=lleDatInm&inm="+inm);
}




function relFacPagInicio()
{
    pruebaSes();
    relPagInpBusInm=document.getElementById("relPagBusInm");
    relPagInpBusInm.addEventListener("blur",flexyFactPag);
    relPagForm=document.getElementById("relPagForm");
    relPagForm.addEventListener("submit",selccionaRango);

}

function selccionaRango(){
    if($('.trSelected').length>0){
        var items = $('.trSelected');
        var itemlist ='';
        for(i=0;i<items.length-1;i++){
            itemlist+= items[i].id.substr(3)+",";
        }
        itemlist+= items[i].id.substr(3);
        relFacPag(itemlist);
    }else{
        aplSaldFav();
    }

}

function aplSaldFav(){
    popup("vista.aplica_sf_inm.php",638,400,'yes');
}

function aplSfInicio()
{
    pruebaSes2();
    sfValor   = document.getElementById("aplSfInpVal");
    sfPeriodo = document.getElementById("aplSfInpPer");
    sfFechar  = document.getElementById("aplSfInpFec");
    sfCabecera=document.getElementById("aplSfCab");
    sfValApl=document.getElementById("aplSfInpValAp");
    sfValRes=document.getElementById("aplSfInpValRes");
    sfObs=document.getElementById("aplSfTexIObs");
    sfNunFac=document.getElementById("aplSfInpNumFac");
    sfForm=document.getElementById("aplSfForm");
    sfObsCom=document.getElementById("aplSfTexIComp");


    sfForm.addEventListener("submit",generaSf);
    sfValApl.addEventListener("keyup",function(){sfValRes.value=sfValor.value-sfValApl.value; });
    sfValRes.addEventListener("keyup",function(){sfValApl.value=sfValor.value-sfValRes.value; });
    sfObsCom.addEventListener("keyup",function(){sfObs.value="Saldo a favor aplicado por correción de balance del periodo "+sfPeriodo.value+" en "+sfNunFac.value+" facturas("+window.opener.factura+") "+sfObsCom.value; });

    sfCabecera.innerHTML+=' por factura ' +window.opener.factura;
    llenaInfoFacSf();
}


function generaSf(){
    var fac=window.opener.factura,sald=sfValApl.value,obs=sfObs.value,
        numfac=sfNunFac.value,inm=window.opener.relPagInpBusInm.value;

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

            try{
                var datos=JSON.parse(xmlhttp.responseText);
                if (datos["res"]=="true"){
                    swal
                    (
                        {
                            title: "Mensaje!",
                            text: "Has Procesado correctamente los SF.",
                            showConfirmButton: true,
                            type: "success"
                        },
                        function(isConfirm)
                        {
                            if (isConfirm)
                            {
                                window.opener.flexyFactPag();
                                window.close();
                            }
                        });
                }else if(datos["res"]=="false"){
                    swal("Mensaje!", datos["error"], "error");
                }
            }catch(err){
                swal("Mensaje!", xmlhttp.responseText, "error");
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.reliquidacion_pag.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=ingSf&fac="+fac+"&sal="+sald+"&obs="+obs+"&numFac="+numfac+"&inm="+inm);
}

function llenaInfoFacSf(){
    var facturas=window.opener.factura;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var datos=JSON.parse(xmlhttp.responseText);
            if(datos){
                sfValor.value=datos[0]["VALOR"];
                sfPeriodo.value=datos[0]["PERIODO"];
                sfFechar.value=datos[0]["FECHA"];
                sfValApl.max=datos[0]["VALOR"];
                sfValRes.value=datos[0]["VALOR"];
                sfNunFac.value=datos[0]["CANTIDAD"];
                sfValApl.value=0;
                sfObs.value="Saldo a favor aplicado por correción de balance del periodo "+sfPeriodo.value+" en "+sfNunFac.value+" facturas("+window.opener.factura+")";

            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.reliquidacion_pag.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=lleDat&fac="+facturas);

}

function flexyFactPag()
{
    pruebaSes();
    var inmueble=relPagInpBusInm.value;

    var parametros =
        [
            {name:"inm", value:inmueble},
            {name:"tip"     , value:  "flexy"}
        ]

    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexFactPag").flexigrid
    (
        {
            url: './../datos/datos.reliquidacion_pag.php',
            dataType: 'json',
            type:  'post',
            colModel : [
                {display: 'No',  width:10,  align: 'center'},
                {display: 'Consecutivo Factura',  width:99,  align: 'center'},
                {display: 'Periodo', width: 43,  align: 'center'},
                {display: 'Fecha Expedicion',  width: 83,  align: 'center'},
                {display: 'NCF', width: 112,  align: 'center'},
                {display: 'Valor',  width: 48,  align: 'center'},
                {display: 'SF Aplicado',  width: 48,  align: 'center'},
            ],
            usepager: true,
            title: 'Facturas Pagadas',
            useRp: false,
            page: 1,
            showTableToggleBtn: false,
            width: 580,
            height: 245,
            params: parametros
        }
    );

    $("#flexFactPag").flexOptions({url: './../datos/datos.reliquidacion_pag.php'});
    $("#flexFactPag").flexOptions({params: parametros});
    $("#flexFactPag").flexReload();

}

function relFacPag(fac){
    factura=fac;
    popup("vista.aplica_sf.php",638,400,'yes');
}


//FUNCION PARA ABRIR UN POPUP
var popped = null;
function popup(uri, awid, ahei, scrollbar) {
    var params;
    if (uri != "") {
        if (popped && !popped.closed) {
            popped.location.href = uri;
            popped.focus();
        }
        else {
            params = "toolbar=no,width=" + awid + ",height="+ ahei +",directories=no,status=no,scrollbars=" + scrollbar + ",menubar=no,resizable=no,location=no";
            popped = window.open(uri, uri, params);
        }
    }
}


function pruebaSes()
{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var datos=xmlhttp.responseText;
            if(datos=="false")
            {
                swal
                (
                    {
                        title: "Mensaje!",
                        text: "Su sesion ha finalizado.",
                        showConfirmButton: true
                    },
                    function(isConfirm)
                    {
                        if (isConfirm)
                        {
                            top.location.replace("../../index.php")
                        }
                    });
                return false;
            }
        }
    }
    xmlhttp.open("POST", "./../datos/datos.reliquidacion_pag.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}


function pruebaSes2()
{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var datos=xmlhttp.responseText;
            if(datos=="false")
            {
                swal
                (
                    {
                        title: "Mensaje!",
                        text: "Su sesion ha finalizado.",
                        showConfirmButton: true
                    },
                    function(isConfirm)
                    {
                        if (isConfirm)
                        {
                            window.opener.flexyFactPag();
                            window.close();
                        }
                    });
                return false;
            }
        }
    }
    xmlhttp.open("POST", "./../datos/datos.reliquidacion_pag.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}
