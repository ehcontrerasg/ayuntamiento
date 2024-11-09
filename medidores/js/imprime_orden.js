/**
 * Created by PC on 7/7/2016.
 */
var impOrdSelAcu;
var impOrdInpCodSis;
var impOrdInpProIni;
var impOrdInpProFin;
var impOrdInpManIni;
var impOrdInpManFin;
var impOrdInpFecIni;
var impOrdInpFecFin;
var impOrdButPro;
var impOrdSelMed;
var impOrdSelEstInm;
var impOrdform;
var impOrdSelOpe;
var impOrdObjHoj;



function complementaInpProFin()
{
    var faltante =11-impOrdInpProIni.value.length;
    impOrdInpProFin.value=(impOrdInpProIni.value+faltanteFunc('9',faltante)).substr(0,11);
    impOrdInpProIni.value= (impOrdInpProIni.value+faltanteFunc('0',faltante)).substr(0,11);
}

function complementaInpProFin2()
{
    var faltante =11-impOrdInpProFin.value.length;
    impOrdInpProFin.value=(impOrdInpProFin.value+faltanteFunc('9',faltante)).substr(0,11);

}

function completaManzFin()
{
    impOrdInpManFin.value=impOrdInpManIni.value;
}
function genHojMedInicio()
{
    impOrdSelAcu     = document.getElementById("genHojMedSelPro");
    impOrdInpCodSis  = document.getElementById("genHojMedInpCodSis");
    impOrdInpProIni  = document.getElementById("genHojMedInpProIni");
    impOrdInpProFin  = document.getElementById("genHojMedInpProFin");
    impOrdInpManIni  = document.getElementById("genHojMedInpManIni");
    impOrdInpManFin  = document.getElementById("genHojMedInpManFin");
    impOrdButPro     = document.getElementById("genHojMedButPro");
    impOrdSelMed     = document.getElementById("genHojMedSelMed");
    impOrdSelEstInm  = document.getElementById("genHojMedSelEstInm");
    impOrdform       = document.getElementById("genHojMedForm");
    impOrdSelOpe     = document.getElementById("genHojMedSelOpe");
    impOrdObjHoj     = document.getElementById("genHojMedObjHoj");
    impOrdInpFecIni  = document.getElementById("genHojMedInpFecIni")
    impOrdInpFecFin  = document.getElementById("genHojMedInpFecFin")


    impOrdLleSelPro();
    impOrdLleSelOpe();
    impOrdInpProIni.addEventListener("blur",complementaInpProFin);
    impOrdInpProFin.addEventListener("blur",complementaInpProFin2);
    impOrdInpManIni.addEventListener("blur",completaManzFin);
    impOrdform.addEventListener("submit",impOrdSes );

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


function generaImpCamb()
{
    var proyecto=impOrdSelAcu.value, proini=impOrdInpProIni.value,profin=impOrdInpProFin.value,
        codsis=impOrdInpCodSis.value, manini=impOrdInpManIni.value, manfin=impOrdInpManFin.value,
        medido=impOrdSelMed.value,estado=impOrdSelEstInm.value,usr_asignado=impOrdSelOpe.value,
        fecIni=impOrdInpFecIni.value,fecFin=impOrdInpFecFin.value;
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
            impOrdObjHoj.data=datos;
        }
    }
    xmlhttp.open("POST", "../reportes/reporte.HojasRutaCambMed.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("proyecto="+proyecto+"&proini="+proini+"&profin="+profin+"&codsis="+codsis
    +"&manini="+manini +"&manfin="+manfin+"&medido="+medido+"&estado="+estado+"&usr_asignado="+usr_asignado
    +"&fecIni="+fecIni+"&fecFin="+fecFin);
}


function impOrdSes()
{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var datos=xmlhttp.responseText;
            if(datos=="true")
            {
                if(validaCampos()){
                    swal({
                            title: "Mensaje",
                            text: "Desea generar las hojas de impresión con los parametros seleccionados?",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Si!",
                            cancelButtonText: "No!",
                            showLoaderOnConfirm: true,
                            closeOnConfirm: false,
                            closeOnCancel: true },
                        function(isConfirm){
                            if (isConfirm) {
                                generaImpCamb();
                            }
                        });

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
    xmlhttp.open("POST", "../datos/datos.imprime_orden.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}

function impOrdLleSelPro()
{
    var select= impOrdSelAcu;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
            select.add(option,select[0]);
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++)
            {
                var option=document.createElement("OPTION");
                option.value=(datos[x]["CODIGO"]);
                option.text=datos[x]["DESCRIPCION"];
                select.add(option,select[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.imprime_orden.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selPro");
}




function validaCampos()
{
    if(impOrdSelAcu.selectedIndex==0)
    {
        swal("Error!", "Debe seleccionar el proyecto", "error");
        return false;
    }
    if(impOrdInpCodSis.value.trim()=="" && (impOrdInpProIni.value.trim()=="" || impOrdInpProFin.value.trim()==""))
    {
        swal("Error!", "Debe seleccionar un codigo de sistema o un rango de procesos", "error");
        return false;
    }

    if(impOrdInpManIni.value!="" && impOrdInpManIni.value.length<3 )
    {
        swal("Error!", "Manzana inicial incorrecta", "error");
        return false;
    }

    if(impOrdInpManFin.value!="" && impOrdInpManFin.value.length<3 )
    {

        swal("Error!", "Manzana final incorrecta", "error");
        return false;
    }

    return true;
}


function impOrdLleSelOpe()
{
    var select= impOrdSelOpe;
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
            var datos=JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++)
            {
                var option=document.createElement("OPTION");
                option.value=(datos[x]["ID_USUARIO"]);
                option.text=datos[x]["LOGIN"];
                select.add(option,select[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.imprime_orden.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selOpe");
}



