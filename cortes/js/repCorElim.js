/**
 * Created by Edwin on 26/05/2016.
 */
var selProyecto, 
    butProcesar, 
    formHojaIns,
//var spinner;
    iframePdf,
    fechaIni,
    fechaFin,
    tipo,
    outPut,
    tip;


function inicio(){

    iframePdf=document.getElementById("ifHojaCortElim");
    selProyecto=document.getElementById("repCorElim");

    butProcesar=document.getElementById("butCortElim");
    butProcesar.addEventListener("click",procHojaIns);

    formHojaIns=document.getElementById("genRepCorElim");

    fechaIni = document.getElementById('inpFechaIni');
    fechaFin = document.getElementById('inpFechaFin');

    outPut = document.getElementsByName('output');

    tipo = document.getElementById('tipo');

    llenarSelProy();
}

function procHojaIns(){
    var pro=selProyecto.value;
    var fechaI = fechaIni.value;
    var fechaF = fechaFin.value;
    for(var i = 0; i < outPut.length; i++){
        if (outPut[i].checked) {
            tip = outPut[i].value;
        }
    }
    
    if (tip == 'excel') {
            
            swal({
                   title: "Advertencia!",
                   text: "El reporte puede tardar algunos minutos en generarse.",
                   type: "info",
                   showConfirmButton: true,
                   confirmButtonText: "Continuar!",
                   showCancelButton: true,
                   showLoaderOnConfirm: true,
                   closeOnConfirm: false,
                   closeOnCancel: true
               },
               function(isConfirm)
               {
                   if (isConfirm)
                   {
                       generaRep(pro, fechaI, fechaF, tip, tipo);
                   }
               });

       
    } else {
        iframePdf.src="../datos/datos.repCortElim.php?tip="+tip+"&tipo="+tipo.value+"&proy="+pro+"&fechaIni="+fechaI+"&fechaFin="+fechaF;
    }
    
}

function generaRep(pro, fechaI, fechaF, tip, tipo){
    $.ajax
    ({
        url : "../datos/datos.repCortElim.php?tip="+tip+"&tipo="+tipo.value+"&proy="+pro+"&fechaIni="+fechaI+"&fechaFin="+fechaF,
        type : 'GET',
        dataType : 'text',
        success : function(urlPdf) {

            if (urlPdf.substr(0,11)=="../../temp/"){

                window.location.href = urlPdf;
               
                swal
                (
                    {
                        title: "Reporte Generado!",
                        text: "Has generado correctamente el reporte",
                        type: "success",
                        html: true,
                        confirmButtonColor: "#66CC33",
                        confirmButtonText: "Ok!",
                        closeOnConfirm: true},
                        function(isConfirm)
                        {
                           if (isConfirm)
                           {
                                window.close();
                           }
                       });
                    
            }else{
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

        },
        error : function(xhr, status) {
            swal("Error!", "error desconocido contacte a sistemas", "error");
        }
    });
}

function llenarSelProy(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="Proyecto";
            selProyecto.add(option,selProyecto[0]);
            var datos=obj = JSON.parse(xmlhttp.responseText);

            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["CODIGO"]);
                option.text=datos[x]["DESCRIPCION"];
                selProyecto.add(option,selProyecto[x+1]);
            }
            //formHojaIns.removeChild(spinner.el);
            habilitarBotones();
        }
        else if(xmlhttp.readyState == 1){
            //spinner = new Spinner(opts).spin(formHojaIns);
            //formHojaIns.appendChild(spinner.el);
            desabilitarBotones();
        }
    }
    xmlhttp.open("POST", "../datos/datos.repCortElim.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=proy");
}


function desabilitarBotones()
{

    for (a=0; a < document.getElementsByTagName('input').length; a++)
    {
        // Desabilitamos todos para bloquear el formulario
        document.getElementsByTagName('input')[a].disabled = true;
    }

    for (a=0; a < document.getElementsByTagName('select').length; a++)
    {
        // Desabilitamos todos para bloquear el formulario
        document.getElementsByTagName('select')[a].disabled = true;
    }


}

function habilitarBotones()
{

    for (a=0; a < document.getElementsByTagName('input').length; a++)
    {
        document.getElementsByTagName('input')[a].disabled = false;
    }

    for (a=0; a < document.getElementsByTagName('select').length; a++)
    {
        document.getElementsByTagName('select')[a].disabled = false;
    }


}
