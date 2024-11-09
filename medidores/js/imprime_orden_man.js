/**
 * Created by PC on 7/7/2016.
 */
var impOrdManSelAcu;
var impOrdManInpCodSis;
var impOrdManInpProIni;
var impOrdManInpProFin;
var impOrdManInpManIni;
var impOrdManInpManFin;
var impOrdManInpFecIni;
var impOrdManInpFecFin;
var impOrdManButPro;
var impOrdManform;
var impOrdManSelOpe;
var impOrdManObjHoj;


function complementaInpProFin()
{
    var faltante =11-impOrdManInpProIni.value.length;
    impOrdManInpProFin.value=(impOrdManInpProIni.value+faltanteFunc('9',faltante)).substr(0,11);
    impOrdManInpProIni.value= (impOrdManInpProIni.value+faltanteFunc('0',faltante)).substr(0,11);
}

function complementaInpProFin2()
{
    var faltante =11-impOrdManInpProFin.value.length;
    impOrdManInpProFin.value=(impOrdManInpProFin.value+faltanteFunc('9',faltante)).substr(0,11);

}

function completaManzFin()
{
    impOrdManInpManFin.value=impOrdManInpManIni.value;
}
function genHojMedManInicio()
{
    impOrdManSelAcu     = document.getElementById("genHojMedManSelPro");
    impOrdManInpCodSis  = document.getElementById("genHojMedManInpCodSis");
    impOrdManInpProIni  = document.getElementById("genHojMedManInpProIni");
    impOrdManInpProFin  = document.getElementById("genHojMedManInpProFin");
    impOrdManInpManIni  = document.getElementById("genHojMedManInpManIni");
    impOrdManInpManFin  = document.getElementById("genHojMedManInpManFin");
    impOrdManInpFecIni  = document.getElementById("genHojMedManInpFecIni");
    impOrdManInpFecFin  = document.getElementById("genHojMedManInpFecFin");
    impOrdManButPro     = document.getElementById("genHojMedManButPro");
    impOrdManform       = document.getElementById("genHojMedManForm");
    impOrdManSelOpe     = document.getElementById("genHojMedManSelOpe");
    impOrdManObjHoj     = document.getElementById("genHojMedManObjHoj");

    impOrdManLleSelPro();
    impOrdManLleSelOpe();
    impOrdManInpProIni.addEventListener("blur",complementaInpProFin);
    impOrdManInpProFin.addEventListener("blur",complementaInpProFin2);
    impOrdManInpManIni.addEventListener("blur",completaManzFin);
    impOrdManform.addEventListener("submit",impOrdManSes );

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
    var proyecto=impOrdManSelAcu.value, proini=impOrdManInpProIni.value,profin=impOrdManInpProFin.value,
        codsis=impOrdManInpCodSis.value, manini=impOrdManInpManIni.value, manfin=impOrdManInpManFin.value,
        usr_asignado=impOrdManSelOpe.value,fecIni=impOrdManInpFecIni.value,fecFin=impOrdManInpFecFin.value;
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
    +"&manini="+manini +"&manfin="+manfin+"&usr_asignado="+usr_asignado+"&fecIni="+fecIni+"&fecFin="+fecFin);
}


function impOrdManSes()
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
    xmlhttp.open("POST", "../datos/datos.imprime_orden_man.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}

function impOrdManLleSelPro()
{
    var select= impOrdManSelAcu;
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
    xmlhttp.open("POST", "../datos/datos.imprime_orden_man.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selPro");
}




function validaCampos()
{
    if(impOrdManSelAcu.selectedIndex==0)
    {
        swal("Error!", "Debe seleccionar el proyecto", "error");
        return false;
    }
    if(impOrdManInpCodSis.value.trim()=="" && (impOrdManInpProIni.value.trim()=="" || impOrdManInpProFin.value.trim()==""))
    {
        swal("Error!", "Debe seleccionar un codigo de sistema o un rango de procesos", "error");
        return false;
    }

    if(impOrdManInpManIni.value!="" && impOrdManInpManIni.value.length<3 )
    {
        swal("Error!", "Manzana inicial incorrecta", "error");
        return false;
    }

    if(impOrdManInpManFin.value!="" && impOrdManInpManFin.value.length<3 )
    {

        swal("Error!", "Manzana final incorrecta", "error");
        return false;
    }

    return true;
}


function impOrdManLleSelOpe()
{
    var select= impOrdManSelOpe;
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
    xmlhttp.open("POST", "../datos/datos.imprime_orden_man.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selOpe");
}



