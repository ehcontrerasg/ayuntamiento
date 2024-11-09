/**
 * Created by PC on 7/7/2016.
 */


var ingResCamInpCodSis;
var ingResCamInpProy;
var ingResCamInpAcue;
var ingResCamInpEmpPla;
var ingResCamInpFechPla;
var ingResCamInpMot;
var ingResCamInpOrden;
var ingResCamInpCliCod;
var ingResCamInpCli;
var ingResCamInpDire;
var ingResCamInpZon;
var ingResCamInpMedRetCod;
var ingResCamInpMedRet;
var ingResCamInpCalRet;
var ingResCamInpEmpRet;
var ingResCamInpSerRet;
var ingResCamInpLecRet;
var ingResCamInpFecIns;;
var ingResCamInpSerIns;;
var ingResCamInpLecIns;
var ingResCamInpFechGara;
var ingResCamInpFechVenc;
var ingResCamSelObsLecRet;
var ingResCamSelMotNoRea;
var ingResCamSelMedIns;
var ingResCamSelCalIns;
var ingResCamSelEmpIns;
var ingResCamSelEntUsr;
var ingResCamTexAObsIns;
var ingResCamTexAObsLec;
var ingResCamButIng;
var ingResCamFormPri;
var ingResCamSelFact;


function ingResCambInicio()
{
    pruebaSes();
    ingResCamInpCodSis    = document.getElementById("ingResCambInpCodSis");
    ingResCamInpProy      = document.getElementById("ingResCambInpProy");
    ingResCamInpAcue      = document.getElementById("ingResCambInpAcu");
    ingResCamInpEmpPla    = document.getElementById("ingResCambInpEmpPla");
    ingResCamInpFechPla   = document.getElementById("ingResCambInpFechPla");
    ingResCamInpMot       = document.getElementById("ingResCambInpMot");
    ingResCamInpOrden     = document.getElementById("ingResCambInpOrd");
    ingResCamInpCliCod    = document.getElementById("ingResCambInpCodCli");
    ingResCamInpCli       = document.getElementById("ingResCambInpCli");
    ingResCamInpDire      = document.getElementById("ingResCambInpDir");
    ingResCamInpZon       = document.getElementById("ingResCambInpZon");
    ingResCamInpMedRetCod = document.getElementById("ingResCambInpCodMedRet");
    ingResCamInpMedRet    = document.getElementById("ingResCambInpMedRet");
    ingResCamInpCalRet    = document.getElementById("ingResCambInpCalRet");
    ingResCamInpEmpRet    = document.getElementById("ingResCambInpEmplaRet");
    ingResCamInpSerRet    = document.getElementById("ingResCambInpSerRet");
    ingResCamInpLecRet    = document.getElementById("ingResCambInpLecRet");
    ingResCamInpFecIns    = document.getElementById("ingResCambInpFecIns");
    ingResCamInpSerIns    = document.getElementById("ingResCambInpSerIns");
    ingResCamInpLecIns    = document.getElementById("ingResCambInpLecIns");
    ingResCamInpFechGara  = document.getElementById("ingResCambInpFecGar");
    ingResCamInpFechVenc  = document.getElementById("ingResCambInpFecVenc");
    ingResCamSelObsLecRet = document.getElementById("ingResCambSelObsLec");
    ingResCamSelMotNoRea  = document.getElementById("ingResCambSelMotNoRea");
    ingResCamSelMedIns    = document.getElementById("ingResCambSelMarcMedIns");
    ingResCamSelCalIns    = document.getElementById("ingResCambSelCalIns");
    ingResCamSelEmpIns    = document.getElementById("ingResCambSelEmpIns");
    ingResCamSelEntUsr    = document.getElementById("ingResCambSelEntUsu");
    ingResCamTexAObsIns   = document.getElementById("ingResCambTexAObsIns");
    ingResCamTexAObsLec   = document.getElementById("ingResCambTexAObsLec");
    ingResCamButIng       = document.getElementById("ingResCambButIng");
    ingResCamFormPri      = document.getElementById("genHojMedForm");
    ingResCamSelFact      = document.getElementById("ingResCambSelFac");
    ingResCamInpCodSis.addEventListener("blur",complementaInfo);
    ingResCamFormPri.addEventListener("submit",pruebaSes2);
    ingResCambLlenaSelObs();
    ingResCambLlenaSelMot();
    ingResCambLlenaSelcal();
    ingResCambLlenaSelEmp();
    ingResCambLlenaSelMed();

}

function complementaInfo(){
    var inmueble=ingResCamInpCodSis.value;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var datos=JSON.parse(xmlhttp.responseText);
            if(datos){
                ingResCamInpProy.value=datos[0]["ID_PROYECTO"];
                ingResCamInpAcue.value=datos[0]["SIGLA_PROYECTO"];
                ingResCamInpEmpPla.value=datos[0]["LOGIN"];
                ingResCamInpFechPla.value=datos[0]["FECHA_GENORDEN"];

                ingResCamInpMot.value=datos[0]["MOTIVO"];
                ingResCamInpOrden.value=datos[0]["ID_ORDEN"];
                ingResCamInpCliCod.value=datos[0]["CODIGO_CLI"];
                ingResCamInpCli.value=datos[0]["NOMBRE"];
                ingResCamInpDire.value=datos[0]["DIRECCION"];
                ingResCamInpZon.value=datos[0]["ID_ZONA"];
                ingResCamInpMedRetCod.value=datos[0]["COD_MEDIDOR"];
                ingResCamInpMedRet.value=datos[0]["DESC_MED"];
                ingResCamInpCalRet.value=datos[0]["DESC_CALIBRE"];
                ingResCamInpEmpRet.value=datos[0]["DESC_EMPLAZAMIENTO"];
                ingResCamInpSerRet.value=datos[0]["SERIAL"];
                if(datos[0]["DESC_MED"]=='N/A'){
                    ingResCamInpLecRet.required=false;
                    ingResCamInpLecRet.readOnly=true;

                    ingResCamSelObsLecRet.required=false;
                    ingResCamSelObsLecRet.readOnly=true;
                }else{
                    ingResCamInpLecRet.required=true;
                    ingResCamInpLecRet.readOnly=false;

                    ingResCamSelObsLecRet.required=true;
                    ingResCamSelObsLecRet.readOnly=false;
                }
            }else{

                if(ingResCamInpCodSis.value!=''){

                    swal({
                            title: "Mensaje",
                            text: "No existe una orden abierta para el inmueble",
                            type: "error"},
                        function(isConfirm){
                            if (isConfirm) {

                            }
                        });
                    ingResCamInpCodSis.focus();
                }
                ingResCamFormPri.reset();
                ingResCamInpCodSis.focus();

            }


        }
    }
    xmlhttp.open("POST", "../datos/datos.ingresa_resultados_cambio.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=lleDat&inm="+inmueble);

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
    xmlhttp.open("POST", "../datos/datos.ingresa_resultados_cambio.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}

function ingResCambLlenaSelObs()
{
    var select= ingResCamSelObsLecRet;
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
    xmlhttp.open("POST", "../datos/datos.ingresa_resultados_cambio.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selObs");
}

function ingResCambLlenaSelMot()
{
    var select= ingResCamSelMotNoRea;
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
    xmlhttp.open("POST", "../datos/datos.ingresa_resultados_cambio.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selMot");
}

function ingResCambLlenaSelcal()
{
    var select= ingResCamSelCalIns;
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
    xmlhttp.open("POST", "../datos/datos.ingresa_resultados_cambio.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selCal");
}


function ingResCambLlenaSelEmp()
{
    var select= ingResCamSelEmpIns;
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
    xmlhttp.open("POST", "../datos/datos.ingresa_resultados_cambio.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selEmp");
}

function ingResCambLlenaSelMed()
{
    var select= ingResCamSelMedIns;
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
    xmlhttp.open("POST", "../datos/datos.ingresa_resultados_cambio.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selMed");
}


function pruebaSes2()
{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var datos=xmlhttp.responseText;
            if(datos=="true")
            {

                swal
                (
                    {
                        title: "Menaje",
                        text: "Desea guardar la orden con los datos Ingresados",
                        type: "warning",
                        showCancelButton: true,
                        html: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Si!",
                        cancelButtonText: "No!",
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true,
                        closeOnCancel: true
                    },
                    function(isConfirm)
                    {
                        if (isConfirm)
                        {
                            guardaOrden();
                        }
                    }
                );


            }else{
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
    xmlhttp.open("POST", "../datos/datos.ingresa_resultados_cambio.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}



function guardaOrden(){
    var ord=ingResCamInpOrden.value,lecRet=ingResCamInpLecRet.value,obsLec=ingResCamSelObsLecRet.value,motImp=ingResCamSelMotNoRea.value,
    fecIns=ingResCamInpFecIns.value,med=ingResCamSelMedIns.value,cal=ingResCamSelCalIns.value,ser=ingResCamInpSerIns.value,
    lect=ingResCamInpLecIns.value,emp=ingResCamSelEmpIns.value,entUsr=ingResCamSelEntUsr.value,obIns=ingResCamTexAObsIns.value,
    obLecIns=ingResCamTexAObsLec.value,inm=ingResCamInpCodSis.value,fact=ingResCamSelFact.value;



    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

            try{
                var datos=JSON.parse(xmlhttp.responseText);
                if (datos["res"]=="true"){
                    ingResCamFormPri.reset();
                    swal("Mensaje!", "Has ingresado la orden correctamente", "success");

                }else if(datos["res"]=="false"){
                    swal("Mensaje!", datos["error"], "error");
                }
            }catch(err){
                swal("Mensaje!", xmlhttp.responseText, "error");
            }


        }

    }
    xmlhttp.open("POST", "../datos/datos.ingresa_resultados_cambio.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=ingOrd&ord="+ord+"&lecRet="+lecRet+"&obsLec="+obsLec+"&motImp="+motImp+"&fecIns="+fecIns+"&med="+med
        +"&cal="+cal+"&ser="+ser+"&lect="+lect+"&emp="+emp+"&entUsr="+entUsr+"&obIns="+obIns+"&obLecIns="+obLecIns
        +"&inm="+inm+"&fact="+fact);
}











