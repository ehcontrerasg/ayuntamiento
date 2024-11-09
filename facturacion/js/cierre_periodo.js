/**
 * Created by Econtrerasg on 7/11/2016.
 */
var cieLotSelPro   ;
var cieLotInpProDes;
var cieLotInpPer   ;
var cieLotInpPerDes;
var cieLotInpZon   ;
var cieLotInpZonDes;
var cieLotButAbrPer;


function refZonDes() {
    if(cieLotInpZon.value.length>2){
        cieLotInpZonDes.value="ZONA "+cieLotInpZon.value;
        obtieneMaxPer();
    }else{
        cieLotInpPer.value="";
        cieLotInpPerDes.value="";
        cieLotInpZonDes.value="";
    }

}
function refProDes() {
    if(cieLotSelPro.value==""){
        cieLotInpProDes.value="";
    }else if(cieLotSelPro.value=="BC"){
        cieLotInpProDes.value="Boca Chica";
    }else if(cieLotSelPro.value=="SD"){
        cieLotInpProDes.value="Santo Domingo";
    }
}
function cieLotInicio(){

    cieLotSelPro    = document.getElementById("cieLotSelPro");
    cieLotInpProDes = document.getElementById("cieLotInpProDsc");
    cieLotInpPer    = document.getElementById("cieLotInpPer");
    cieLotInpPerDes = document.getElementById("cieLotInpPerDesc");
    cieLotInpZon    = document.getElementById("cieLotInpZon");
    cieLotInpZonDes = document.getElementById("cieLotInpZonDesc");
    cieLotButAbrPer = document.getElementById("cieLotButPerDesc");
    cieLotButAbrPer.addEventListener("click",verificaSession);
    cieLotInpZon.addEventListener("keyup",RefZon);
    cieLotInpZon.addEventListener("blur",refZonDes);
    cieLotSelPro.addEventListener("change",refProDes)
    cieLotLleSelPro();

}

function RefZon(){
    $(function() {
        $("#cieLotInpZon").autocomplete({
            source: "../datos/datos.cierre_periodo.php?proyecto="+cieLotSelPro.value+"&tip=autComZon",
            minLength: 1,
            html: true,
            open: function(event, ui)
            {
                $(".ui-autocomplete").css("z-index", 1000);
            }
        });

    });

}


function obtieneMaxPer(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var datos= JSON.parse(xmlhttp.responseText);
            cieLotInpPer.value=datos[0]["MAXPER"];
            cieLotInpPerDes.value=(datos[0]["MES"]+" ").trim()+" "+((datos[0]["MAXPER"]+"    ").substr(0,4)+" ").trim() ;
        }
    }
    xmlhttp.open("POST", "../datos/datos.cierre_periodo.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=maxPer&zona="+cieLotInpZon.value);
}





function verificaSession(){

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var datos=xmlhttp.responseText;
            if(datos=="true"){
                if(validaCampos()){
                    swal({
                            title: "Mensaje",
                            text: "Desea cerrar el periodo "+cieLotInpPerDes.value+" de la zona "+cieLotInpZon.value,
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
                                cieLotPer();
                            }
                        });



                }
            }else{
                swal({
                        title: "Mensaje!",
                        text: "Su sesion ha finalizado.",
                        showConfirmButton: true },
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
    xmlhttp.open("POST", "../datos/datos.cierre_periodo.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");

}

function cieLotLleSelPro(){
    var select= cieLotSelPro;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
            select.add(option,select[0]);
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["ID_PROYECTO"]);
                option.text=datos[x]["SIGLA_PROYECTO"];
                select.add(option,select[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.cierre_periodo.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selPro");

}



function cieLotPer(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var datos=JSON.parse(xmlhttp.responseText);
            if (datos["res"]=="true"){
                swal("Mensaje!", "Has procesado satisfactoriamente  los inmuebles de la zona", "success");
            }else if(datos["res"]=="false"){
                swal
                (
                    {
                        title: "Error",
                        text: datos["error"]+"<br>"+"Contacte a sistemas",
                        type: "error",
                        html: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ok!",
                        closeOnConfirm: true
                    }
                );

            }
        }

    }
    xmlhttp.open("POST", "../datos/datos.cierre_periodo.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=cieLotPer&zona="+cieLotInpZon.value+"&periodo="+cieLotInpPer.value);
}

function validaCampos(){
    if(cieLotSelPro.selectedIndex==0){
        swal("Error!", "debe seleccionar el proyecto", "error");
        return false;
    }
    if(cieLotInpZon.value.trim()==""){
        swal("Error!", "La zona no puede ser vacia", "error");
        return false;
    }
    if(cieLotInpPer.value.trim()==""){
        swal("Error!", "Error en el periodo", "error");
        return false;
    }

    return true;
}




