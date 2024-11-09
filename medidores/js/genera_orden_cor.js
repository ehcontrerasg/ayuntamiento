const txtProcesoInicial = $("#txtProcesoInicial"), 
txtProcesoFinal = $("#txtProcesoFinal"),
txtManzanaInicial = $("#txtManzanaInicial"),
txtManzanaFinal = $("#txtManzanaFinal"),
slcMotivo = $("#slcMotivo"),
slcContratista = $("#slcContratista"),
slcOperario = $("#slcOperario"),
txtDescripcion = $("#txtDescripcion");

$(document).ready(function(){    
    desContr();
    compSession();
    compSession(llenarSpinerPro);
    $("#genOrdCorrSelPro").change(
        function(){
            refProDes();
        }
    );
    $("#genOrdCorrInpZon").keyup(
        function(){
            RefZon();
        }
    )

    $("#genOrdCorrInpZon").blur(
        function(){
            refZonDes();
        }
    )

    $('#genOrdCorrForm').submit(
        function(){
            compSession(function(){
                swal({
                title: "Advertencia!",
                text: "¿Seguro que desea generar los mantenimientos correctivos?",
                type: "info",
                showConfirmButton: true,
                confirmButtonText: "Continuar!",
                showCancelButton: true,
                showLoaderOnConfirm: true,
                closeOnConfirm: false,
                showCancelButton:true,
                cancelButtonText:"Cancelar"},function(isConfirm){
                    if (isConfirm) { generaOrdenManCorr() }
                });
            });
        }
    )

    getMotivos();
    getContratistas();


    //Descripción del mantenimiento
    var f = new Date();
    txtDescripcion.val('Proceso masivo del día '+f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear());

    //Proceso inicial y final
    txtProcesoInicial.blur(function(){    
        txtProcesoFinal.val(faltantes(txtProcesoInicial.val(),"9"));
        txtProcesoInicial.val(faltantes(txtProcesoInicial.val(),"0"));
    });

    /* Manzana inicial y final */
    txtManzanaInicial.blur(function(){    
        txtManzanaFinal.val(txtManzanaInicial.val());
    });

    slcContratista.change(function(e){
        let contratista = e.target.value;
        getOperarios(contratista);
    });

});

function refProDes() {
    if($("#genOrdCorrSelPro").val()==""){
        $('#genOrdCorrForm')[0].reset();        
    }else if($("#genOrdCorrSelPro").val()=="BC"){
        $("#genOrdCorrInpProDesc").val("Boca Chica");
    }else if($("#genOrdCorrSelPro").val()=="SD"){
        $("#genOrdCorrInpProDesc").val("Santo Domingo");
    }
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


function RefZon(){

    $( "#genOrdCorrInpZon" ).autocomplete({
        source: function ( request,response) {
            $.ajax({
                type: "POST",
                url:"../datos/datos.genera_orden_cor.php",
                data: { tip : 'autComZon',proy:$("#genOrdCorrSelPro").val(),term:$("#genOrdCorrInpZon").val() },
                success:response,
                dataType: 'json'
            });
        }
    }, {minLength: 1 });


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
                $("#ingResInsInpCodSis").focus(false);
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

function llenarSpinerPro()
{

    $.ajax
    ({
        url : '../datos/datos.genera_orden_cor.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            //$('#genOrdCorrSelPro').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#genOrdCorrSelPro').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
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


function refZonDes() {
    if($("#genOrdCorrInpZon").val().length>2){
        $("#genOrdCorrInpZonDesc").val("ZONA "+$("#genOrdCorrInpZon").val());
        obtieneMaxPer();
    }else{
        $("#genOrdCorrInpZonDesc").val("");
        $("#genOrdCorrInpPer").val("");
        $("#genOrdCorrInpPerDesc").val("");

    }

}

function obtieneMaxPer(){
    $.ajax
    ({
        url : '../datos/datos.genera_orden_cor.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'perMax',zon:$("#genOrdCorrInpZon").val() },
        success : function(json) {
            if(json){
                $("#genOrdCorrInpPer").val(json[0]["MAXPER"]);
                $("#genOrdCorrInpPerDesc").val(json[0]["MES"]);
            }

        },
        error : function(xhr, status) {

        }
    });
}

function generaOrdenManCorr(){
    let datos = $("#genOrdCorrForm").serializeArray();
    datos.push({name:'tip', value:'genOrd'});
    $.ajax
    ({
        url : '../datos/datos.genera_orden_cor.php',
        type : 'POST',
        dataType : 'json',
        data : datos,
        success : function(json) {

            if (json["res"]=="true"){
                $('#genOrdCorrForm')[0].reset();
                var f = new Date();
                txtDescripcion.val('Proceso masivo del día '+f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear());
                swal({
                        title: "Mensaje",
                        text: "Se ha generado las ordenes correctamente. Cantidad de ordenes generadas: " +json["ordenes_generadas"],
                        type: "success"},
                    function(isConfirm){
                        if (isConfirm) {
                            $("#genOrdCorrSelPro").focus();
                        }
                    }
                );
            }else if(json["res"]=="false"){
                swal("Mensaje!", json["error"], "error");
            }
        },
        error : function(xhr, status) {
            swal("Mensaje!", "error inesperado contacte a sistemas", "error");
        }
    });  
}

function faltantes(elemento,caracter, cantidad = 11){
    let faltantes = cantidad - elemento.length;    
    for (let index = 0; index < faltantes; index++) {        
        elemento = elemento.concat(caracter);           
    }
    return elemento;
}

function getMotivos(){ 
    let options = {url:"../datos/datos.genera_orden_man.php",type:"post", data: {tip:"selMot"}};
    $.ajax(options).done((res)=>{
        let array = JSON.parse(res);
        array.forEach(motivo => {
            slcMotivo.append(new Option(motivo.DESCRIPCION, motivo.CODIGO));
        });
    }).fail(error=>{
        console.error(error);        
    });
}

function getContratistas(){ 
    let options = {url:"../datos/datos.genera_orden_man.php",type:"post", data: {tip:"selCon"}};
    $.ajax(options).done((res)=>{
        let array = JSON.parse(res);
        array.forEach(motivo => {
            slcContratista.append(new Option(motivo.DESCRIPCION, motivo.ID_CONTRATISTA));
        });
    }).fail(error=>{
        console.error(error);        
    });
}

function getOperarios(contratista){ 
    let options = {url:"../datos/datos.genera_orden_man.php",type:"post", data: {tip:"selOpe", cont:contratista}};
    $.ajax(options).done((res)=>{        
        let array = JSON.parse(res);
        slcOperario.empty();
        slcOperario.append(new Option("Seleccione a un operario", ""));
        array.forEach(motivo => {
            slcOperario.append(new Option(motivo.LOGIN, motivo.ID_USUARIO));
        });
    }).fail(error=>{
        console.error(error);        
    });
}
