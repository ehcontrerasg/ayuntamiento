var xid=0;
var btnExcel = $("#btnExcel");
var dvExcel  = $("#dvExcel");
var tableId  = "dvExcel";

$(document).ready(function(){

    desContr();
    compSession();
    compSession(llenarSpinerPro);
    $('#genHojMedCorSelPro').change(
        function(){
            compSession(llenarSpinerUsu());
        }
    );

    $('#genHojMedCorForm').submit(
        function(){
            swal
            ({
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
                        compSession(generaImp);
                    }
                });
        }
    )

});

btnExcel.on("click",function(){
    generaExcel();
});

function generaImp(){
    var datos=$("#genHojMedCorForm").serializeArray();
    $.ajax
    ({
        url : '../reportes/reporte.HojasRutaMantCorr.php',
        type : 'POST',
        dataType : 'text',
        data : datos ,
        success : function(urlPdf) {

            if (urlPdf.substr(0,11)=="../../temp/"){
                swal("Mensaje!", "Has Generado correctamente las hojas de impresión", "success");
                document.getElementById("genHojMedCorObjHoj").data=urlPdf;
                SetBtnExcelCss();
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
function compSession(callback){
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
                $("#genHojMedCorSelPro").focus(false);
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
                            $("#ingResInsInpCodSis").focus();
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
function llenarSpinerPro() {

    $.ajax
    ({
        url : '../datos/datos.imprime_orden_cor.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            $('#genHojMedCorSelPro').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#genHojMedCorSelPro').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}
function llenarSpinerUsu() {

    $.ajax
    ({
        url : '../datos/datos.imprime_orden_cor.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'SelUsu',pro:$('#genHojMedCorSelPro').val()},
        success : function(json) {
            $('#genHojMedCorSelOpe').empty();
            $('#genHojMedCorSelOpe').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#genHojMedCorSelOpe').append(new Option(json[x]["NOMBRE"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}
function iniSes(){
    $.ajax
    ({
        url : '../../configuraciones/session.php',
        type : 'POST',
        data : { tip : 'iniSes',pas:$("#pass").val(),usu:$("#usr").val()},
        dataType : 'text',
        success : function(json) {
            if (json=="true"){
                swal("Loggin Exitoso!")
                compSession(llenarSpinerPro);
            }else if(json=="false"){
                swal({
                        title: "Mensaje ",
                        html: true,
                        type: "input",
                        inputPlaceholder: "Write something",
                        text: "Usuario o Contraseña  incorrecta.<br>" +
                        " <input type='text' class='estilo-inp' placeholder='Usuario' id='usr'><br> <input  placeholder='Password' tabindex='4' class='estilo-inp' type='password' id='pass'>",
                        showCancelButton: true,
                        closeOnConfirm: false,
                        inputPlaceholder: "Usuario",
                        animation: "slide-from-top"

                    },
                    function(i){
                        if (i === false)
                            return false;
                        if ($("#usr").val() == "") {
                            swal.showInputError("El usuario no puede ser vacio !");
                            return false
                        }else  if ($("#pass").val() == "") {
                            swal.showInputError("La contraseña no puede ser vacia !");
                            return false
                        }

                        else{
                            $("#ingResInsInpCodSis").focus();
                            iniSes();
                        }
                    });
            }
        },
        error : function(xhr, status) {
            alert("error");
            return false;
        }
    });

}

/**
 * @Creado por Jean Carlos Holguín B.
 * */

function generaExcel(){
    /**
        * Función que genera el reporte de mantenimiento correctivo.
    */
    var datos=$("#genHojMedCorForm").serializeArray();
    $.ajax({
            type: "POST",
            url:   "../reportes/reporte.HojasRutaMantCorrExcel.php",
            data: datos,
            success:function(res){
                //Limpiar el div que contiene los datos del excel.
                dvExcel.empty();
                //Llenar el div con los datos del excel.
                dvExcel.append(res);
                //Formar el nombre del archivo.
                var fileName = NombreDeArchivo(datos);
                //Exportar tabla a excel.
                HtmlToExcel(res,fileName);
            },
            error:function(settings, jqXHR){
                console.log(jqXHR);
            }
    });
}

function HtmlToExcel(table,fileName){
//export data in Chrome or FireFox
    //this works in Chrome as well as in FireFox
    sa = true;
    var myBlob =  new Blob( [table] , {type:'text/html'});
    var url = window.URL.createObjectURL(myBlob);
    var a = document.createElement("a");
    document.body.appendChild(a);
    a.href = url;
    a.download = fileName+".xls";
    a.click();
//adding some delay in removing the dynamically created link solved the problem in FireFox
    setTimeout(function() {window.URL.revokeObjectURL(url);},0);
}

function NombreDeArchivo(datosFormulario){
    var proyecto       = datosFormulario[0].value;
    var procesoInicial = datosFormulario[1].value;
    var procesoFinal   = datosFormulario[2].value;
    var periodo        = datosFormulario[3].value;
    var codigoSistema  = datosFormulario[4].value;
    var manzanaInicial = datosFormulario[5].value;
    var manzanaFinal   = datosFormulario[6].value;
    var operario       = datosFormulario[7].value;

    return nombreArchivo  =
            "Mant.Corr._"+proyecto+"_"+procesoInicial+"_"+procesoFinal+"_"+periodo+"_"+codigoSistema+"_"+manzanaInicial+"_"+manzanaFinal+"_"+operario;
}

function SetBtnExcelCss(){
    /**
     * Función que hace visible el botón que exporta a excel y establece otros estilos al botón.
     * */

    var css = {
        'display'         : 'inline-block',
        'float'           : 'left',
        'margin-top'      : '10px',
        'background-color': '#217346',
        'border-color'    : '#217346',
        'border'          : '0px',
        'border-radius'   : '3px',
        'color'           : 'white',
        'padding'         : '12px',
        'cursor'          : 'pointer'
    };
    btnExcel.css(css);
}
