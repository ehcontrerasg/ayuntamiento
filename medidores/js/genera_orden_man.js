/**
 * Created by PC on 7/7/2016.
 */
var genOrdManSelAcu;
var genOrdManInpCodSis;
var genOrdManInpProIni;
var genOrdManInpProFin;
var genOrdManInpManIni;
var genOrdManInpManFin;
var genOrdManButPro;
var genOrdManSelMotCamb;
var genOrdManSelCon;
var genOrdManSelOpe;
var genOrdManInpDes;
var genOrdManForm;
var genOrdManButGen;
var impOrdManObjHoj;

function complementaInpProFin()
{
    var faltante =11-genOrdManInpProIni.value.length;
    genOrdManInpProFin.value=(genOrdManInpProIni.value+faltanteFunc('9',faltante)).substr(0,11);
    genOrdManInpProIni.value= (genOrdManInpProIni.value+faltanteFunc('0',faltante)).substr(0,11);
}

function complementaInpProFin2()
{
    var faltante =11-genOrdManInpProFin.value.length;
    genOrdManInpProFin.value=(genOrdManInpProFin.value+faltanteFunc('9',faltante)).substr(0,11);

}

function completaManzFin()
{
    genOrdManInpManFin.value=genOrdManInpManIni.value;
}
function genOrdManInicio()
{
    genOrdManSelAcu     = document.getElementById("genOrdManSelPro");
    genOrdManInpCodSis  = document.getElementById("genOrdManInpCodSis");
    genOrdManInpProIni  = document.getElementById("genOrdManInpProIni");
    genOrdManInpProFin  = document.getElementById("genOrdManInpProFin");
    genOrdManInpManIni  = document.getElementById("genOrdManInpManIni");
    genOrdManInpManFin  = document.getElementById("genOrdManInpManFin");
    genOrdManButPro     = document.getElementById("genOrdManButPro");
    genOrdManSelMotCamb = document.getElementById("genOrdManSelMot");
    genOrdManSelCon     = document.getElementById("genOrdManSelCon");
    genOrdManSelOpe     = document.getElementById("genOrdManSelOpe");
    genOrdManInpDes     = document.getElementById("genOrdManInpDes");
    genOrdManForm       = document.getElementById("genOrdManFor");
    genOrdManButGen     = document.getElementById("genOrdManButPro");
    impOrdManObjHoj     = document.getElementById("genHojMedManObjHoj");
    var f = new Date();
    genOrdManInpDes.value='Proceso masivo del día '+f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear();
    genOrdManLleSelPro();
    genOrdManLleSelMotCamb();
    genOrdManLleSelCon();
    genOrdManInpProIni.addEventListener("blur",complementaInpProFin);
    genOrdManInpProFin.addEventListener("blur",complementaInpProFin2);
    genOrdManInpManIni.addEventListener("blur",completaManzFin);
    genOrdManSelCon.addEventListener("change",genOrdManLleSelOpe);

    genOrdManForm.addEventListener("submit",genOrden);
}

function faltanteFunc(constante,numero)
{
    var res="";
    for(x=1;x<=numero;x++)
    {
        res +=""+constante ;
    }
    return res;
}


function flexygenOrdMan(){

    var proyecto=genOrdManSelAcu.value, proini=genOrdManInpProIni.value,profin=genOrdManInpProFin.value,
        codsis=genOrdManInpCodSis.value, manini=genOrdManInpManIni.value, manfin=genOrdManInpManFin.value;

    var parametros =
        [
            {name:"proyecto", value:proyecto},
            {name:"proini"  , value:  proini},
            {name:"profin"  , value:  profin},
            {name:"codsis"  , value:  codsis},
            {name:"manini"  , value:  manini},
            {name:"manfin"  , value:  manfin},
            {name:"tip"     , value:  "flexy"}
        ]



    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexGeneraOrdMan").flexigrid
    (
        {
            url: './../datos/datos.genera_orden_man.php',
            dataType: 'json',
            type:  'post',

            colModel : [
                {display: 'No',  width:10,  align: 'center'},
                {display: 'Zona',  width:30,  align: 'center'},
                {display: 'Estado', width: 60,  align: 'center'},
                {display: 'Cod. Sistma',  width: 50,  align: 'center'},
                {display: 'Direccion', width: 100,  align: 'center'},
                {display: 'Medidor',  width: 70,  align: 'center'},
                {display: 'Serial',  width: 100,  align: 'center'},
                {display: 'Calibre', width: 100, align: 'center'},
                {display: 'Fecha Alta', width: 100,  align: 'center'}
            ],
            usepager: true,
            title: 'Inmuebles generacion de cambio medidor',
            useRp: false,
            page: 1,
            showTableToggleBtn: false,
            width: 750,
            height: 245,
            params: parametros
        }
    );
}


function generaOrdMant()
{
    var proyecto=genOrdManSelAcu.value, proini=genOrdManInpProIni.value,profin=genOrdManInpProFin.value,
        codsis=genOrdManInpCodSis.value, manini=genOrdManInpManIni.value, manfin=genOrdManInpManFin.value,
        motivo=genOrdManSelMotCamb.value,usr_asignado=genOrdManSelOpe.value,descripcion=genOrdManInpDes.value;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            try{
                var datos=JSON.parse(xmlhttp.responseText);
            }catch (errm){
                swal("Error!", "error: "+errm+" "+xmlhttp.responseText+", al intentar generar la orden contacte a sistemas", "error");
            }

            if (datos["res"]=="true")
            {
                swal("Mensaje!", "Has generado satisfactoriamente las ordenes", "success");
                flexygenOrdMan();
                generaImpCamb();

            }else if(datos["res"]=="false"){
                swal
                (
                    {
                        title: "Error",
                        text: datos["error"],
                        type: "error",
                        html:true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Aceptar!",
                        closeOnConfirm: true

                    }
                );
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.genera_orden_man.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=genOrdMan&proyecto="+proyecto+"&proini="+proini+"&profin="+profin+"&codsis="+codsis
    +"&manini="+manini +"&manfin="+manfin+"&motivo="+motivo+"&usr_asignado="+usr_asignado+"&desc="+descripcion);
}



function generaImpCamb()
{
    var proyecto=genOrdManSelAcu.value, proini=genOrdManInpProIni.value,profin=genOrdManInpProFin.value,
        codsis=genOrdManInpCodSis.value, manini=genOrdManInpManIni.value, manfin=genOrdManInpManFin.value,
        usr_asignado=genOrdManSelOpe.value;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var datos=xmlhttp.responseText;
            if (datos.substr(0,11)=="../../temp/"){
                swal("Mensaje!", "Has Generado correctamente las hojas de impresión", "success");
            }else if(datos["res"]=="false"){
                swal
                (
                    {
                        title: "Error",
                        text: "Contacte a sistemas",
                        type: "error",
                        html: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ok!",
                        closeOnConfirm: true});

            }
            impOrdManObjHoj.data=datos;
        }
    }
    xmlhttp.open("POST", "../reportes/reporte.HojasRutaMantMed.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("proyecto="+proyecto+"&proini="+proini+"&profin="+profin+"&codsis="+codsis
    +"&manini="+manini +"&manfin="+manfin+"&usr_asignado="+usr_asignado);
}


function genOrdManSes()
{

    genOrdManButGen.addEventListener("click",genOrden);
  //  genOrdManButGenImp.addEventListener("click",);


}

function genOrden(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var datos=xmlhttp.responseText;
            if(datos=="true")
            {
                if(validaCampos())
                {
                    swal
                    (
                        {
                            title: "Mensaje",
                            text: "Desea Generar las ordenes con los datos ingresados",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Si!",
                            cancelButtonText: "No!",
                            showLoaderOnConfirm: true,
                            closeOnConfirm: true,
                            closeOnCancel: true
                        },
                        function(isConfirm)
                        {
                            if (isConfirm)
                            {
                                generaOrdMant();
                            }
                        }
                    );
                }
            }else
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
                    }
                );
                return false;
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.genera_orden_man.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");

}








function genOrdManLleSelPro()
{
    var select= genOrdManSelAcu;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
            select.add(option,select[0]);
            try{
                var datos=JSON.parse(xmlhttp.responseText);
            }catch (errm){
                swal("Error!", "error: "+errm+", al llenar la lista de proyecto", "error");
            }
            for(var x=0;x<datos.length;x++)
            {
                var option=document.createElement("OPTION");
                option.value=(datos[x]["CODIGO"]);
                option.text=datos[x]["DESCRIPCION"];
                select.add(option,select[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.genera_orden_man.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selPro");
}




function validaCampos()
{
    if(genOrdManSelAcu.selectedIndex==0)
    {
        swal("Error!", "Debe seleccionar el proyecto", "error");
        return false;
    }


    if(genOrdManInpCodSis.value.trim()=="" && (genOrdManInpProIni.value.trim()=="" || genOrdManInpProFin.value.trim()==""))
    {
        swal("Error!", "Debe seleccionar un codigo de sistema o un rango de procesos", "error");
        return false;
    }

    if(genOrdManInpManIni.value!="" && genOrdManInpManIni.value.length<3 )
    {
        swal("Error!", "Manzana inicial incorrecta", "error");
        return false;
    }

    if(genOrdManInpManFin.value!="" && genOrdManInpManFin.value.length<3 )
    {

        swal("Error!", "Manzana final incorrecta", "error");
        return false;
    }

    if(genOrdManSelMotCamb.value.trim()=="")
    {

        swal("Error!", "Debe especificar el motivo de cambio de medidor", "error");
        return false;
    }

    if(genOrdManSelCon.selectedIndex==0)
    {
        swal("Error!", "Debe seleccionar el Contratista", "error");
        return false;
    }

    if(genOrdManSelOpe.selectedIndex==0)
    {
        swal("Error!", "Debe seleccionar el Operario", "error");
        return false;
    }


    return true;
}


function genOrdManLleSelMotCamb()
{
    var select= genOrdManSelMotCamb;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
            select.add(option,select[0]);
            try{
                var datos=JSON.parse(xmlhttp.responseText);
            }catch (errm){
                swal("Error!", "error: "+errm+", al llenar la lista de motivos", "error");
            }
            for(var x=0;x<datos.length;x++)
            {
                var option=document.createElement("OPTION");
                option.value=(datos[x]["CODIGO"]);
                option.text=datos[x]["DESCRIPCION"];
                select.add(option,select[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.genera_orden_man.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selMot");
}

function genOrdManLleSelCon()
{
    var select= genOrdManSelCon;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
            select.add(option,select[0]);
            try{
                var datos=JSON.parse(xmlhttp.responseText);
            }catch (errm){
                swal("Error!", "error: "+errm+", al llenar la lista de contratistas", "error");
            }
            for(var x=0;x<datos.length;x++)
            {
                var option=document.createElement("OPTION");
                option.value=(datos[x]["ID_CONTRATISTA"]);
                option.text=datos[x]["DESCRIPCION"];
                select.add(option,select[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.genera_orden_man.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selCon");
}


function genOrdManLleSelOpe()
{

    var contratista=genOrdManSelCon.value;
    var select= genOrdManSelOpe;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var length = select.options.length;
            for (i = length; i >=0; i--) {
                select.options[i] = null;
            }

            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
            select.add(option,select[0]);
            try{
                var datos=JSON.parse(xmlhttp.responseText);
            }catch (errm){
                swal("Error!", "error: "+errm+", al llenar la lista de operarios", "error");
            }
            for(var x=0;x<datos.length;x++)
            {
                var option=document.createElement("OPTION");
                option.value=(datos[x]["ID_USUARIO"]);
                option.text=datos[x]["LOGIN"];
                select.add(option,select[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.genera_orden_man.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selOpe&cont="+contratista);
}




