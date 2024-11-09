/**
 * Created by PC on 7/7/2016.
 */
var apeLotSelPro   ;
var apeLotInpProDes;
var apeLotInpPer   ;
var apeLotInpPerDes;
var apeLotInpZon   ;
var apeLotInpZonDes;
var apeLotButAbrPer;


function refZonDes() {
    if(apeLotInpZon.value.length>2){
        apeLotInpZonDes.value="ZONA "+apeLotInpZon.value;
        obtieneMaxPer();
    }else{
        apeLotInpPer.value="";
        apeLotInpPerDes.value="";
        apeLotInpZonDes.value="";
    }

}
function refProDes() {
    if(apeLotSelPro.value==""){
        apeLotInpProDes.value="";
    }else if(apeLotSelPro.value=="BC"){
        apeLotInpProDes.value="Boca Chica";
    }else if(apeLotSelPro.value=="SD"){
        apeLotInpProDes.value="Santo Domingo";
    }
}
function apeLotInicio(){

    apeLotSelPro    = document.getElementById("apeLotSelPro");
    apeLotInpProDes = document.getElementById("apeLotInpProDsc");
    apeLotInpPer    = document.getElementById("apeLotInpPer");
    apeLotInpPerDes = document.getElementById("apeLotInpPerDesc");
    apeLotInpZon    = document.getElementById("apeLotInpZon");
    apeLotInpZonDes = document.getElementById("apeLotInpZonDesc");
    apeLotButAbrPer = document.getElementById("apeLotButPerDesc");
    apeLotButAbrPer.addEventListener("click",verificaSession);
    apeLotInpZon.addEventListener("keyup",RefZon);
    apeLotInpZon.addEventListener("blur",refZonDes);
    apeLotSelPro.addEventListener("change",refProDes)
    apeLotLleSelPro();

}

function RefZon(){
    $(function() {
        $("#apeLotInpZon").autocomplete({
            source: "../datos/datos.apertura.php?proyecto="+apeLotSelPro.value+"&tip=autComZon",
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
            apeLotInpPer.value=datos[0]["MAXPER"];
            apeLotInpPerDes.value=(datos[0]["MES"]+" ").trim()+" "+((datos[0]["MAXPER"]+"    ").substr(0,4)+" ").trim() ;
        }
    }
    xmlhttp.open("POST", "../datos/datos.apertura.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=maxPer&zona="+apeLotInpZon.value);
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
                                text: "Desea aperturar la zona "+apeLotInpZon.value+" para el periodo de "+apeLotInpPerDes.value,
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
                                    apeLotAbrPer();
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
        xmlhttp.open("POST", "../datos/datos.apertura.php", true);   // async
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("tip=sess");

}

function apeLotLleSelPro(){
    var select= apeLotSelPro;
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
    xmlhttp.open("POST", "../datos/datos.apertura.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selPro");

}



function apeLotAbrPer(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var datos=JSON.parse(xmlhttp.responseText);
            if (datos["res"]=="true"){
                swal("Mensaje!", "Has procesado satisfactoriamente  los "+datos["error"]+" inmuebles de la zona", "success");
            }else if(datos["res"]=="false"){
                swal
                (
                    {
                        title: "Error",
                        text: datos["error"]+"<br>Desea eliminar todos los datos de esta zona, para abrir nuevamente el periodo?",
                        type: "error",
                        showCancelButton: true,
                        html: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Si, eliminar!",
                        cancelButtonText: "No!",
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true,
                        closeOnCancel: true
                    },
                    function(isConfirm)
                    {
                        if (isConfirm)
                        {
                            eliminafac();
                        }
                    }
                );

            }
        }

    }
    xmlhttp.open("POST", "../datos/datos.apertura.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=procPer&zona="+apeLotInpZon.value+"&periodo="+apeLotInpPer.value);
}


function validaCampos(){
    if(apeLotSelPro.selectedIndex==0){
        swal("Error!", "debe seleccionar el proyecto", "error");
        return false;
    }
    if(apeLotInpZon.value.trim()==""){
        swal("Error!", "La zona no puede ser vacia", "error");
        return false;
    }
    if(apeLotInpPer.value.trim()==""){
        swal("Error!", "Error en el periodo", "error");
        return false;
    }

    return true;
}



function eliminafac(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var datos=JSON.parse(xmlhttp.responseText);
            if (datos["res"]=="true"){
                swal("Mensaje!", "Has eliminado los datos", "success");
            }else if(datos["res"]=="false"){
                swal("Mensaje!", "Error: "+datos["error"], "error");

            }
        }

    }
    xmlhttp.open("POST", "../datos/datos.apertura.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=eliPer&zona="+apeLotInpZon.value+"&periodo="+apeLotInpPer.value+"&proyecto="+apeLotSelPro.value);
}

