/**
 * Created by PC on 7/7/2016.
 */


var facturas=[];
var factura;

$(document).ready(function(){
    llenaSelect('../datos/datos.gen_fac_medcaasd.php','selPer',$("#genFacCaaSelPer"));
    desContr();
    compSession();
    compSession(genFacCaadLleSelAcu);
    $("#genFacCaaSelAcu").change(
        function(){
            genFacCaadLleNumPend();
            compSession(genFacCaadLleSelFac);
        }
    );

    $("#genFacCaaSelFac").change(
        function(){
            $("#genFacCaaInpNumInmExi").val(facturas[$("#genFacCaaSelFac").val()]);
            if($("#genFacCaaSelFac").val().trim()!=''){
                $("#genFacCaaInpCantInsNue").prop('required',false);
                $("#genFacCaaInpCantInsNue").val('');
            }else
            {
                genFacCaadLleNumPend();
                $("#genFacCaaInpCantInsNue").prop('required',true);
            }
        }
    );

    $("#genFacCaaInpCantInsNue").change(
        function(){
            if($("#genFacCaaInpCantInsNue").val().trim()!=''){
                $("#genFacCaaSelFac").prop('required',false);
                compSession(genFacCaadLleSelFac);
            }else
            {
                $("#genFacCaaSelFac").prop('required',true);
            }
        }
    );

    $("#genFacCaaForm").submit(
        function(){
            compSession(generaImp);
        }
    )

    $("#genFacCaaButGenX").click(
        function(){
            compSession(generaRep);
        }
    )

});

function generaRep(){
    if($("#genFacCaaSelFac").val().trim()!='') {
        factura = $("#genFacCaaSelFac").val();

        var datos = $("#genFacCaaForm").serializeArray();
        $.ajax
        ({
            url: '../reportes/reporte.FactMedInsCaasdXls.php',
            type: 'POST',
            dataType: 'text',
            data: datos,
            success: function (urlPdf) {

                if (urlPdf.substr(0, 11) == "../../temp/") {

                    window.location.href = urlPdf;
                    //window.opener = urlPdf;

                    swal
                    (
                        {
                            title: "Reporte Generado!",
                            text: "Has generado correctamente el reporte",
                            type: "success",
                            html: true,
                            confirmButtonColor: "#66CC33",
                            confirmButtonText: "Ok!",
                            closeOnConfirm: true
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                window.close();
                            }
                        });

                } else {
                    swal
                    (
                        {
                            title: "Error",
                            text: "Contacte a sistemas",
                            type: "error",
                            html: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Ok!",
                            closeOnConfirm: true
                        });

                }

            },
            error: function (xhr, status) {
                swal("Error!", "error desconocido contacte a sistemas", "error");
            }
        });
    }
}

function generaImp(){
    if($("#genFacCaaInpCantInsNue").val().trim()!=''){

        pruebaSes2();
    }

    if($("#genFacCaaSelFac").val().trim()!=''){
        factura=$("#genFacCaaSelFac").val();
        pruebaSes3();
    }

}

function genFacCaadLleSelAcu(){
    $.ajax
    ({
        url : '../datos/datos.gen_fac_medcaasd.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            $('#genFacCaaSelAcu').empty();
            $('#genFacCaaSelAcu').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#genFacCaaSelAcu').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function llenaSelect(urlR,tipo,select){
    $.ajax
    ({
        url : urlR,
        type : 'POST',
        dataType : 'json',
        data : { tip : tipo },
        success : function(json) {
            select.empty();
            select.append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                select.append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}


function genFacCaadLleNumPend()
{

    var proy=$('#genFacCaaSelAcu').val();
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var datos=JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++)
            {
                $("#genFacCaaInpPend").val((datos[x]["CANTIDAD"]));
                $("#genFacCaaInpCantInsNue").prop('max',datos[x]["CANTIDAD"]);


            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.gen_fac_medcaasd.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=numPend&pro="+proy);
}



function genFacCaadLleSelFac(){
    $.ajax
    ({
        url : '../datos/datos.gen_fac_medcaasd.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selFac',pro:$('#genFacCaaSelAcu').val() },
        success : function(json) {
            $('#genFacCaaSelFac').empty();
            $('#genFacCaaSelFac').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                facturas[json[x]["FACTURA"]]=json[x]["CANTIDAD"];
                $('#genFacCaaSelFac').append(new Option(json[x]["FACTURA"], json[x]["FACTURA"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
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
    xmlhttp.open("POST", "../datos/datos.gen_fac_medcaasd.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}


function genFacCaadImpFact()
{

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var datos=xmlhttp.responseText;
            if (datos.substr(0,11)=="../../temp/"){
                swal("Mensaje!", "Se ha generado correctamente el PDF", "success");
            }else if(datos["res"]=="false"){
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
            $("#genFacCaaObjFac").prop('data',datos);

        }
    }
    xmlhttp.open("POST", "../reportes/reporte.FactMedInsCaasd.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("fac="+factura);
}



function pruebaSes3()
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
                        text: "Va a generar un pdf con los parametros seleccionados <br> esto puede tardar algunos segundos",
                        type: "warning",
                        html: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "ok",
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true,
                        closeOnCancel: true
                    },
                    function(isConfirm)
                    {
                        if (isConfirm)
                        {
                            genFacCaadImpFact();
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
    xmlhttp.open("POST", "../datos/datos.gen_fac_medcaasd.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
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
                        text: "Desea Generar una nueva factura",
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
                            generaFactura();
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
    xmlhttp.open("POST", "../datos/datos.gen_fac_medcaasd.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}


function generaFactura(){

    var pro=$('#genFacCaaSelAcu').val(),can=$("#genFacCaaInpCantInsNue").val(),per=$("#genFacCaaSelPer").val();


    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

            try{
                var datos=JSON.parse(xmlhttp.responseText);
                if (datos["res"]=="true"){
                    swal
                    (
                        {
                            title: "Mensaje!",
                            text: "Has Generado una nueva factura",
                            type: "success",
                            html: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Ok!",
                            closeOnConfirm: false,
                            showLoaderOnConfirm: true,
                            closeOnCancel: true
                        },
                        function(isConfirm)
                        {
                            if (isConfirm)
                            {
                                factura=datos["error"];
                                genFacCaadImpFact();
                                $('#genFacCaaForm')[0].reset();

                            }
                        }
                    );



                }else if(datos["res"]=="false"){
                    swal("Mensaje!", datos["error"], "error");
                }
            }catch(err){
                swal("Mensaje!", xmlhttp.responseText, "error");
            }


        }

    }
    xmlhttp.open("POST", "../datos/datos.gen_fac_medcaasd.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=genFac&can="+can+"&pro="+pro+"&per="+per);
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
                compSession(RepPerf);
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

                            iniSes();
                        }
                    });
            }
        },
        error : function(xhr, status) {
            return false;
        }
    });

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

