/**
 * Created by Econtrerasg on 7/11/2016.
 */
var corMorSelPro   ;
var corMorInpProDes;
var corMorInpPer   ;
var corMorInpPerDes;
var corMorInpZon   ;
var corMorInpZonDes;
var corMorButAbrPer;


function refZonDes() {
    if(corMorInpZon.value.length>2){
        corMorInpZonDes.value="ZONA "+corMorInpZon.value;
        obtieneMaxPer();
    }else{
        corMorInpPer.value="";
        corMorInpPerDes.value="";
        corMorInpZonDes.value="";
    }

}
function refProDes() {
    if(corMorSelPro.value==""){
        corMorInpProDes.value="";
    }else if(corMorSelPro.value=="BC"){
        corMorInpProDes.value="Boca Chica";
    }else if(corMorSelPro.value=="SD"){
        corMorInpProDes.value="Santo Domingo";
    }
}
function corMorInicio(){

    corMorSelPro    = document.getElementById("corMorSelPro");
    corMorInpProDes = document.getElementById("corMorInpProDsc");
    corMorInpPer    = document.getElementById("corMorInpPer");
    corMorInpPerDes = document.getElementById("corMorInpPerDesc");
    corMorInpZon    = document.getElementById("corMorInpZon");
    corMorInpZonDes = document.getElementById("corMorInpZonDesc");
    corMorButAbrPer = document.getElementById("corMorButPerDesc");
    corMorButAbrPer.addEventListener("click",verificaSession);
    corMorInpZon.addEventListener("keyup",RefZon);
    corMorInpZon.addEventListener("blur",refZonDes);
    corMorSelPro.addEventListener("change",refProDes)
    corMorLleSelPro();

}

function RefZon(){
    $(function() {
        $("#corMorInpZon").autocomplete({
            source: "../datos/datos.corre_mora.php?proyecto="+corMorSelPro.value+"&tip=autComZon",
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
            corMorInpPer.value=datos[0]["MAXPER"];
            corMorInpPerDes.value=(datos[0]["MES"]+" ").trim()+" "+((datos[0]["MAXPER"]+"    ").substr(0,4)+" ").trim() ;
        }
    }
    xmlhttp.open("POST", "../datos/datos.corre_mora.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=maxPer&zona="+corMorInpZon.value);
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
                            text: "Desea correr mora en la zona "+corMorInpZon.value+" para el periodo de "+corMorInpPerDes.value,
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
                                corMorAbrPer();
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
    xmlhttp.open("POST", "../datos/datos.corre_mora.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");

}

function corMorLleSelPro(){
    var select= corMorSelPro;
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
    xmlhttp.open("POST", "../datos/datos.corre_mora.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selPro");

}



function corMorAbrPer(){
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
                        text: datos["error"]+"<br>"+"Contacte a sistemas",
                        type: "error",
                        html: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ok!",
                        closeOnConfirm: true});

            }
        }

    }
    xmlhttp.open("POST", "../datos/datos.corre_mora.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=corMorPer&zona="+corMorInpZon.value+"&periodo="+corMorInpPer.value);
}

function validaCampos(){
    if(corMorSelPro.selectedIndex==0){
        swal("Error!", "debe seleccionar el proyecto", "error");
        return false;
    }
    if(corMorInpZon.value.trim()==""){
        swal("Error!", "La zona no puede ser vacia", "error");
        return false;
    }
    if(corMorInpPer.value.trim()==""){
        swal("Error!", "Error en el periodo", "error");
        return false;
    }

    return true;
}




