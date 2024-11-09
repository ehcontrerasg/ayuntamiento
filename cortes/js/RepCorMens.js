/**
 * Created by Edwin on 3/06/2016.
 */
var butGeneraRep;
var selPeriodo;
var ifReporte;
var divform;
var selproyect;
var selContratista;

function compSession(callback)
{
    $.ajax
    ({
        url : '../../configuraciones/session.php',
        type : 'POST',
        data:{tip : 'sess'},
        dataType : 'json',
        success : function(json) {
            if(json==true){
                if(callback){
                    callback();
                }
            }else if(json==false){
                $("#hojCorSelPro").focus(false);
                swal({
                        title: "Mensaje ",
                        html: true,
                        type: "input",
                        text: "Su sesion ha finalizado ingrese los datos para continuar.<br>" +
                        "<input type='text' class='estilo-inp' required placeholder='Usuario' id='usr'><br> <input type='password' tabindex='4' placeholder='Password' class='estilo-inp' required id='pass'>",
                        showCancelButton: true,
                        closeOnConfirm: false,
                        closeOnCancel: true,
                        inputPlaceholder: "Usuario",
                        animation: "slide-from-top"

                    },

                    function(inputValue){
                        if (inputValue === false)
                            return false;
                        if ($("#usr").val() == "") {
                            swal.showInputError("El usuario no puede ser vacio !");
                            return false
                        }else  if ($("#pass").val() == "") {
                            swal.showInputError("La contraseña no puede ser vacia !");
                            return false
                        }

                        else{
                            $("#hojCorSelPro").focus();
                            iniSes();
                        }
                    }
                );


                return false;
            }
        },
        error : function(xhr, status) {
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
    });
}



function inicio(){

    butGeneraRep=document.getElementById("butRepResMen");
    selproyect=document.getElementById("SelProyecto");
    selPeriodo=document.getElementById("selPeriodo");
    ifReporte= document.getElementById("ifRepResMen");
    selContratista = document.getElementById("selCon");
    butGeneraRep.addEventListener("click",generaReporte);
    divform=document.getElementById("divForm");
    compSession();
    desContr();
    compSession(llenarSpinerContr);
    compSession(llenarSelPer);
    compSession(llenarSelPro);
}


function generaReporte(){
    if(valida()){
        var periodo= selPeriodo.value;
        var proyeto=selproyect.value;
        var contratista=selContratista.value;
        ifReporte.src="../datos/datos.repCorMens.php?periodo="+periodo+"&tipo=rep"+"&pro="+proyeto+"&selCon="+contratista;
    }
}

function valida(){

    if(selPeriodo.value==""){
        swal("Oops!", "el periodo no puede ser vacio", "error");
        return false;
    }

    return true;
}

function llenarSelPer(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
            selPeriodo.add(option,selPeriodo[0]);
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["PERIODO"]);
                option.text=datos[x]["PERIODO"];
                selPeriodo.add(option,selPeriodo[x+1]);
            }
            divform.removeChild(spinner.el);
        }
        else if(xmlhttp.readyState == 1){
            spinner = new Spinner(opts).spin(divform);
            divform.appendChild(spinner.el);
            //desabilitarBotones();

        }
    }
    xmlhttp.open("POST", "../datos/datos.repCorMens.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tipo=per");

}




function llenarSpinerContr() {

    $.ajax
    ({
        url: '../../general/datos/datos.contratista.php',
        type: 'POST',
        dataType: 'json',
        data: {tip: 'selCon'},
        success: function (json) {
            $('#selCon').empty();
            $('#selCon').append(new Option('', '', true, true));

            for (var x = 0; x < json.length; x++) {

                $('#selCon').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));

            }

        },
        error: function (xhr, status) {

        }

    });
}

function desContr(){
    document.onkeypress = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            return false;  }}


    document.onkeydown = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            return false;  }}
    $(document).bind("contextmenu",function(e){return false;});

}



function llenarSelPro(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
            selproyect.add(option,selproyect[0]);
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["CODIGO"]);
                option.text=datos[x]["DESCRIPCION"];
                selproyect.add(option,selproyect[x+1]);
            }

        }
        else if(xmlhttp.readyState == 1){

        }
    }
    xmlhttp.open("POST", "../datos/datos.repCorMens.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tipo=pro");

}

