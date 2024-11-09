/**
 * Created by PC on 7/7/2016.
 */


var ingResManInpCodSis;
var ingResManInpProy;
var ingResManInpAcue;
var ingResManInpEmpPla;
var ingResManInpFechPla;
var ingResManInpMot;
var ingResManInpOrden;
var ingResManInpCliCod;
var ingResManInpCli;
var ingResManInpDire;
var ingResManInpZon;
var ingResManInpMedRetCod;
var ingResManInpMedRet;
var ingResManInpCalRet;
var ingResManInpEmpRet;
var ingResManInpSerRet;
var ingResManInpLecRet;
var ingResManInpFecIns;
var ingResManInpSerIns;
var ingResManInpLecIns;
var ingResManInpFechGara;
var ingResManInpFechVenc;
var ingResManSelObsLec;
var ingResManSelMedIns;
var ingResManSelCalIns;
var ingResManSelEmpIns;
var ingResManTexAObsIns;
var ingResManTexAObsLec;
var ingResManButIng;
var ingResManFormPri;
var ingResManButAgrAct;
var ingResManButAgrMat;
var listaAct;

//// variables lista de actividades
var lisActForm;

function listActInicio()
{
    pruebaSes3();
    lisActForm=document.getElementById("lisActManFomr");
    muestraActividades();
    lisActForm.addEventListener("submit",agragaColaAct);
}

function agragaColaAct(){
    window.opener.listaAct=[];
    elementos=document.getElementsByName('listaMant');

    for(var x=0;x<elementos.length;x++)
    {
        var properties = new Object();
        properties.codigo = elementos[x].value;
        properties.valor = elementos[x].checked;
        if(elementos[x].checked){window.opener.listaAct.push(properties);}
    }
    window.close();



}

function muestraActividades(){
    var form= lisActForm;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var datos = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++)
            {
                var span =document.createElement('span');
                span.classList.add('col3');
                span.classList.add('datoForm');
                span.classList.add('listaItems');
                var check=document.createElement("input");
                check.type = 'checkbox';
                check.name = "listaMant";

                check.id = datos[x]["CODCOMP"];
                check.value = (datos[x]["CODCOMP"]);
                var label = document.createElement('label');
                label.htmlFor = datos[x]["CODCOMP"];
                label.appendChild(document.createTextNode( datos[x]["CODCOMP"]+" "+datos[x]["DESCRIPCION"]));
                span.appendChild(check);
                span.appendChild(label);
                lisActForm.appendChild(span);
            }

            var span =document.createElement('span');
            span.classList.add('col1');
            span.classList.add('datoForm');
            var but=document.createElement('input');
            but.type = 'submit';
            but.classList.add('botonFormulario');
            but.value='Agregar';
            span.appendChild(but);
            lisActForm.appendChild(span);

            for(var x=0;x<window.opener.listaAct.length;x++) {
                document.getElementById(window.opener.listaAct[x]['codigo']).checked = true;
            }



        }
    }
    xmlhttp.open("POST", "../datos/datos.ingresa_resultados_man.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=actMan");


}

function ingResManInicio()
{
    pruebaSes();
    ingResManInpCodSis    = document.getElementById("ingResManInpCodSis");
    ingResManInpProy      = document.getElementById("ingResManInpProy");
    ingResManInpAcue      = document.getElementById("ingResManInpAcu");
    ingResManInpEmpPla    = document.getElementById("ingResManInpEmpPla");
    ingResManInpFechPla   = document.getElementById("ingResManInpFechPla");
    ingResManInpMot       = document.getElementById("ingResManInpMot");
    ingResManInpOrden     = document.getElementById("ingResManInpOrd");
    ingResManInpCliCod    = document.getElementById("ingResManInpCodCli");
    ingResManInpCli       = document.getElementById("ingResManInpCli");
    ingResManInpDire      = document.getElementById("ingResManInpDir");
    ingResManInpZon       = document.getElementById("ingResManInpZon");
    ingResManInpMedRetCod = document.getElementById("ingResManInpCodMedRet");
    ingResManInpMedRet    = document.getElementById("ingResManInpMedRet");
    ingResManInpCalRet    = document.getElementById("ingResManInpCalRet");
    ingResManInpEmpRet    = document.getElementById("ingResManInpEmplaRet");
    ingResManInpSerRet    = document.getElementById("ingResManInpSerRet");
    ingResManInpLecRet    = document.getElementById("ingResManInpLecRet");
    ingResManInpFecIns    = document.getElementById("ingResManInpFechMan");
    ingResManInpSerIns    = document.getElementById("ingResManInpSerIns");
    ingResManInpLecIns    = document.getElementById("ingResManInpLecIns");
    ingResManInpFechGara  = document.getElementById("ingResManInpFecGar");
    ingResManInpFechVenc  = document.getElementById("ingResManInpFecVenc");
    ingResManSelObsLec    = document.getElementById("ingResManSel");
    ingResManSelMedIns    = document.getElementById("ingResManSelMarcMedIns");
    ingResManSelCalIns    = document.getElementById("ingResManSelCalIns");
    ingResManSelEmpIns    = document.getElementById("ingResManSelEmpIns");
    ingResManTexAObsIns   = document.getElementById("ingResManTexAObsIns");
    ingResManTexAObsLec   = document.getElementById("ingResManTexAObsLec");
    ingResManButIng       = document.getElementById("ingResManButIng");
    ingResManFormPri      = document.getElementById("genHojMedForm");
    ingResManButAgrAct    = document.getElementById("ingResManButAgrAct");
    ingResManButAgrMan    = document.getElementById("ingResManButAgrMant");
    ingResManInpCodSis.addEventListener("blur",complementaInfo);
    ingResManFormPri.addEventListener("submit",pruebaSes2);
    ingResManButAgrAct.addEventListener("click",agregarAct);
    ingResManLlenaSelcal();
    ingResManLlenaSelEmp();
    ingResManLlenaSelMed();
    listaAct = new Array();

}


function agregarAct(){
    popup("vista.lista_actMant.php",638,400,'yes');
}




function complementaInfo(){
    var inmueble=ingResManInpCodSis.value;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var datos=JSON.parse(xmlhttp.responseText);
            if(datos){
                ingResManInpProy.value=datos[0]["ID_PROYECTO"];
                ingResManInpAcue.value=datos[0]["SIGLA_PROYECTO"];
                ingResManInpEmpPla.value=datos[0]["LOGIN"];
                ingResManInpFechPla.value=datos[0]["FECHA_GENORDEN"];

                ingResManInpMot.value=datos[0]["MOTIVO"];
                ingResManInpOrden.value=datos[0]["ID_ORDEN"];
                ingResManInpCliCod.value=datos[0]["CODIGO_CLI"];
                ingResManInpCli.value=datos[0]["NOMBRE"];
                ingResManInpDire.value=datos[0]["DIRECCION"];
                ingResManInpZon.value=datos[0]["ID_ZONA"];
                ingResManInpMedRetCod.value=datos[0]["COD_MEDIDOR"];
                ingResManInpMedRet.value=datos[0]["DESC_MED"];
                ingResManInpCalRet.value=datos[0]["DESC_CALIBRE"];
                ingResManInpEmpRet.value=datos[0]["DESC_EMPLAZAMIENTO"];
                ingResManInpSerRet.value=datos[0]["SERIAL"];

            }else{

                if(ingResManInpCodSis.value!=''){

                    swal({
                            title: "Mensaje",
                            text: "No existe una orden abierta para el inmueble",
                            type: "error"},
                        function(isConfirm){
                            if (isConfirm) {

                            }
                        });
                    ingResManInpCodSis.focus();
                }
                ingResManFormPri.reset();
                ingResManInpCodSis.focus();

            }


        }
    }
    xmlhttp.open("POST", "../datos/datos.ingresa_resultados_man.php", true);   // async
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
    xmlhttp.open("POST", "../datos/datos.ingresa_resultados_man.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}


function pruebaSes3()
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
                            opener.location.reload();
                            window.close();
                        }
                    });
                return false;
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.ingresa_resultados_man.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}



function ingResManLlenaSelcal()
{
    var select= ingResManSelCalIns;
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
    xmlhttp.open("POST", "../datos/datos.ingresa_resultados_man.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selCal");
}


function ingResManLlenaSelEmp()
{
    var select= ingResManSelEmpIns;
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
    xmlhttp.open("POST", "../datos/datos.ingresa_resultados_man.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selEmp");
}

function ingResManLlenaSelMed()
{
    var select= ingResManSelMedIns;
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
    xmlhttp.open("POST", "../datos/datos.ingresa_resultados_man.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selMed");
}


function pruebaSes2()
{
   //alert(JSON.stringify(listaAct))
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
                            listaAct=[];
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
    xmlhttp.open("POST", "../datos/datos.ingresa_resultados_man.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}



function guardaOrden(){
    var ord=ingResManInpOrden.value,fecMan=ingResManInpFecIns.value,
        med=ingResManSelMedIns.value,cal=ingResManSelCalIns.value,ser=ingResManInpSerIns.value,lect=ingResManInpLecIns.value,
        emp=ingResManSelEmpIns.value,obIns=ingResManTexAObsIns.value,
        obLecIns=ingResManTexAObsLec.value,inm=ingResManInpCodSis.value,
        listaAct2=JSON.stringify(listaAct);

    
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

            try{
                var datos=JSON.parse(xmlhttp.responseText);
                if (datos["res"]=="true"){
                    ingResManFormPri.reset();
                    swal("Mensaje!", "Has ingresado la orden correctamente", "success");

                }else if(datos["res"]=="false"){
                    swal("Mensaje!", datos["error"], "error");
                }
            }catch(err){
                swal("Mensaje!", xmlhttp.responseText, "error");
            }


        }

    }
    xmlhttp.open("POST", "../datos/datos.ingresa_resultados_man.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=ingOrd&ord="+ord+"&med="+med+"&cal="+cal+"&ser="+ser+"&lect="+lect
                    +"&emp="+emp+"&obIns="+obIns+"&obLecIns="+obLecIns +"&inm="+inm+"&fecMan="+fecMan
                    +"&listaAct="+listaAct2);
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
            popped = window.open(uri, "popup", params);
        }
    }
}













