$(document).ready(function(){

    desContr();
    compSession();
    llenarSelArea("#departamento");
    llenarSelDoc("#documento");
    llenarSelPro("#proyecto");

    $("#ingDocArcForm").submit(function(e){
        e.preventDefault();
        IngDocArc();
        
    });
    $('#ingCodDoc').focusout(function(){
        var codigo_inm = $(this).val();
        $.post("../datos/datos.info_observacion.php", {tip: 'existReg', codigo_inm: codigo_inm}, function(result){
            //console.log(result);
            if (result.toString().trim() == 'true') {
                //console.log('si mostrar');
                $(":submit").attr("disabled", true);
                $('#msgExist').fadeIn();
            }else{
                //console.log('no mostrar');
                $(":submit").removeAttr("disabled");
                $('#msgExist').fadeOut();
            }
        });
    });
});


function llenarSelArea(id)
{
    $.ajax
    ({
        url : '../datos/datos.ingresa_doc_arc.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selAre' },
        success : function(json) {
            var row = json.CODIGO.length;
            var html = "<option></option>";
            for (var i=0; i < row; i++) {
                html += '<option value="'+json.CODIGO[i]+'">'+json.DESCRIPCION[i]+'</option>';
            }
            $(id).html(html);
        },
        error : function(xhr, status) {
                //console.log(JSON.parse(xhr+" : "+status))
        }
    });    
}

function llenarSelDoc(id)
{
    $.ajax
    ({
        url : '../datos/datos.ingresa_doc_arc.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selDoc' },
        success : function(json) {
            var row = json.length;
            var html = "<option></option>";
           // console.log(row);
            for (var i=0; i < row; i++) {
                html += '<option value="'+json[i].CODIGO+'">'+json[i].DESCRIPCION+'</option>';
            }
            //console.log(html);
            $(id).html(html);
        },
        error : function(xhr, status) {
               // console.log(JSON.parse(xhr+" : "+status))
        }
    });
}

function llenarSelPro(id)
{
    $.ajax
    ({
        url : '../datos/datos.ingresa_doc_arc.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            var row = json.CODIGO.length;
            var html = "<option></option>";
           // console.log(row);
            for (var i=0; i < row; i++) {
                html += '<option value="'+json.CODIGO[i]+'">'+json.DESCRIPCION[i]+'</option>';
            }
            //console.log(html);
            $(id).html(html);
        },
        error : function(xhr, status) {
                //console.log(JSON.parse(xhr+" : "+status))
        }
    });
}

function IngDocArc(){
    oData = new FormData(document.forms.namedItem("ingDocArcForm"));
    oData.append("tip", "IngDocArc");

    swal({
            title: "Insertar Archivo",
            text: "Esta seguro de que desea insertar este archivo?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, insertar",
            showLoaderOnConfirm: true,
            closeOnConfirm: false
        },function(isConfirm){
           if (isConfirm) {
                console.log('si');
                $.ajax({
                    url: '../datos/datos.ingresa_doc_arc.php', //URL destino
                    data: oData,
                    processData: false, //Evitamos que JQuery procese los datos, daría error
                    contentType: false, //No especificamos ningún tipo de dato
                    cache: false,
                    type: 'POST',
                    success : function(resp) {          
                       // document.write(json);
                       // cargarArchivo();
                    
                       json = resp.toString().trim();
                       console.log(resp);
                        if (json=="true"){
                            $('#ingDocArcForm')[0].reset();
                            $('#ingDocArcForm input, #ingDocArcForm select, #ingDocArcForm textarea').val('');
                            swal({
                                    title: "Mensaje",
                                    text: "Has ingresado la orden correctamente",
                                    type: "success"
                                }, function(isConfirm) {
                                    if (isConfirm) {
                                        $("#ingCodDoc").focus();
                                    }
                                }
                            );
                        }else{
                            swal("Mensaje!", json["error"], "error");
                            $(this).focus();
                        }
                    }
                });   
            }else{
                console.log('si');
                 swal("Cancelled", "El archivo no fue guardado :)", "error");
                $(this).focus();
          } 
       })
    /*$.ajax({
        url: '../datos/datos.ingresa_doc_arc.php', //URL destino
        data: oData,
        processData: false, //Evitamos que JQuery procese los datos, daría error
        contentType: false, //No especificamos ningún tipo de dato
        cache: false,
        type: 'POST',
        beforeSend: function(){

        },
        success : function(resp) {          
           // document.write(json);
           // cargarArchivo();
           json = resp.toString().trim();
           console.log(resp);
            if (json=="true"){
                $('#ingDocArcForm')[0].reset();

                swal({
                        title: "Mensaje",
                        text: "Has ingresado la orden correctamente",
                        type: "success"},
                    function(isConfirm){
                        if (isConfirm) {
                            $("#ingCodDoc").focus();
                        }
                    }
                );
            }else{
                swal("Mensaje!", json["error"], "error");
                $(this).focus();
            }
        }
    });    */
}



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
                $("#ingCodDoc").focus(false);
                swal({
                        title: "Mensaje ",
                        html: true,
                        type: "input",
                        text: "Su sesion ha finalizado ingrese los datos para continuar.<br>" +
                        "<input type='text' class='estilo-inp' required placeholder='Usuario' id='usr'><br> <input type='password'  placeholder='Password' class='estilo-inp' required id='pass'>",
                        showCancelButton: true,
                        closeOnConfirm: false,
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
                                $("#ingCodDoc").focus();
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
                        inputPlaceholder: "Write something",
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
                    });
            }
        },
        error : function(xhr, status) {
            alert("error");
            return false;
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

