
var xid=0;
$(document).ready(function(){

    var d = new Date();
    var montMin=(d.getMonth()+1);
    var montMax= (d.getMonth()+1);
    if(montMin<10){
        montMin='0'+montMin;
    }

    if(montMax<10){
        montMax='0'+montMax;
    }

    var strDate = d.getFullYear() + "-" + montMin + "-" + d.getDate();
    var strDate2 = d.getFullYear() + "-" + montMax + "-" + (d.getDate()+1);
    desContr();
    compSession(llenarSpinerPro);


    $("#asiCoInpFechPla").prop('min',strDate);
    $("#asiCoInpFechPla").prop('max',strDate2);

    $("#asiCorSelPro").change(
        function() {

            compSession(llenarSelZon);
            compSession(llenarSelUso);
            //  compSession(llenarSpinerContr);
            compSession(llenarSelCal);
            compSession(llenarSelCat);


        }
        );
/*
    $("#asiCorSelZon").change(
        function () {
            compSession(llenarSelCal);
        }
    );

*/

    $('#asigCorForm').submit(
        function(){

                swal
                ({
                        title: "Advertencia!",
                        text: "El listado puede demorar unos minutos en salir.",
                        showConfirmButton: true,
                        showCancelButton: true,
                        showLoaderOnConfirm: true,
                        closeOnConfirm: false,
                        closeOnCancel: true
                    },
                    function(isConfirm)
                    {
                        if (isConfirm)
                        {
                            compSession(genRuta);
                        }
                    });



        }
    );

    $('#asigCorFormAsig').submit(
        function(){
            compSession(asignaRutas);
        }
    );


});

/*function llenarSpinerContr() {

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
}*/

function llenarSelCal()
{

    $.ajax
    ({
        url : '../datos/datos.asignacionCor.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selCal' },
        success : function(json) {
            $('#asiCorSeldiametro').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#asiCorSeldiametro').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}



function llenarSelCat()
{

    var datos=$("#asigCorForm").serializeArray();
    datos.push({name: 'tip', value: 'selCat'});

    $.ajax
    ({
        url : '../datos/datos.asignacionCor.php',
        type : 'POST',
        dataType : 'json',
        data : datos,
        success : function(json) {
            $('#selCat').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#selCat').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function asignaRutas(){
    for(i=0;i<xid;i++){
        if($("#usu"+i).val().trim()!=''){
            if($("#usu"+i).val().trim()!=$("#usu"+i).attr("title")){
                asignador($("#usu"+i).val(),$("#ruta"+i).text(),$("#med"+i).text(),$("#cat"+i).text(),$("#usu"+i).attr("title") );
            }

        }


    }
}


function genRuta()
{
    var datos=$("#asigCorForm").serializeArray();
    datos.push({name: 'tip', value: 'genRut'});

    $.ajax
    ({
        url : '../datos/datos.asignacionCor.php',
        type : 'POST',
        dataType : 'json',
        data : datos,
        success : function(json) {
            if (json){
                swal("Mensaje!", "Has Generado correctamente las rutas", "success");

                $('#asigCorFormAsig').empty();
                var divTitulo =$('<div/>', {
                    'class' : 'subCabecera',
                    'text':'Asignacion y Reasignacion de ordenes'
                });
                $('#asigCorFormAsig').append(divTitulo);
                for(var x=0;x<json.length;x++)
                {
                    var spanExt =$('<span/>', {
                        'class' : 'datoForm col1'
                    });


                    var spanInt =$('<span/>', {
                        'class' : 'titDato numCont2',
                        'text':json[x]["MEDIDO"],
                        'id':'med'+x
                    });

                    spanExt.append(spanInt);

                    var spanInt =$('<span/>', {
                        'class' : 'titDato numCont2',
                        'text':json[x]["RUTA"],
                        'id':'ruta'+x
                    });

                    spanExt.append(spanInt);

                    var spanInt =$('<span/>', {
                        'class' : 'titDato numCont2',
                        'text':json[x]["CANTIDAD"]
                    });

                    spanExt.append(spanInt);

                    var spanInt =$('<span/>', {
                        'class' : 'titDato numCont2',
                        'text':json[x]["CATEGORIA"],
                        'id':'cat'+x
                    });

                    spanExt.append(spanInt);

                    var spanInt =$('<span/>', {
                        'class' : 'inpDatp numCont2'
                    });

                    var sel1=$('<select/>', {
                        'id':'usu'+x

                    });
                    spanInt.append(sel1);
                    spanExt.append(spanInt);
                    llenarSpinerUsu(sel1,json[x]["USR_EJE"])



                    $('#asigCorFormAsig').append(spanExt);
                }
                xid=x;
                var spanExt =$('<span/>', {
                    'class' : 'datoForm col1'
                });0

                var inp=$('<input/>', {
                    'id':'asiCorrButAsig',
                    'value':'Asignar',
                    'class':'botonFormulario',
                    'type':'submit'

                });
                spanExt.append(inp);
                $('#asigCorFormAsig').append(spanExt);

            }else{
                swal
                (
                    {
                        title: "Menaje",
                        text: "No se encontraron rutas  con los filtros elegidos",
                        type: "warning",
                        html: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ok!",
                        closeOnConfirm: true});

            }
        },
        error : function(xhr, status) {
            swal
            (
                {
                    title: "Error",
                    text: "Contacte a sistemas. Error: "+xhr+". Status: "+status,
                    type: "error",
                    html: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ok!",
                    closeOnConfirm: true});
        }
    });
}





function llenarSelZon()
{

    $.ajax
    ({
        url : '../datos/datos.asignacionCor.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selZon',pro:$("#asiCorSelPro").val()},
        success : function(json) {
            $('#asiCorSelZon').empty();
            $('#asiCorSelZon').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#asiCorSelZon').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function llenarSelUso()
{

    $.ajax
    ({
        url : '../datos/datos.asignacionCor.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selUso',pro:$("#asiCorSelPro").val(),
            zon:$("#asiCorSelUso").val()},
        success : function(json) {
            $('#asiCorSelUso').empty();
            $('#asiCorSelUso').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#asiCorSelUso').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

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

function llenarSpinerPro()
{

    $.ajax
    ({
        url : '../datos/datos.asignacionCor.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            $('#asiCorSelPro').empty();
            $('#asiCorSelPro').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#asiCorSelPro').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
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
                            $("#hojCorSelPro").focus();
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

function llenarSpinerUsu(selUsr,idusr)
{

    $.ajax
    ({
        url : '../datos/datos.asignacionCor.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selOpe',pro:$('#asiCorSelPro').val()},
        success : function(json) {
            selUsr.empty();
            selUsr.append(new Option('', '', true, true));

            if(idusr==null){
                idusr='';
            }
            selUsr.prop('title', idusr);
            for(var x=0;x<json.length;x++)
            {
                if(idusr==json[x]["CODIGO"]){
                    selUsr.append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], true, true));

                }else{
                    selUsr.append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
                }

            }
        },
        error : function(xhr, status) {

        }
    });
}


function asignador(usu,rut,med,cat,usuViejo){
    var datos =$("#asigCorForm").serializeArray();
    datos.push({name: 'tip', value: 'asig'});
    datos.push({name: 'usu', value: usu});
    datos.push({name: 'rut', value: rut});
    datos.push({name: 'med', value: med});
    datos.push({name: 'cat', value: cat});
    datos.push({name: 'usuViejo', value: usuViejo});

    $.ajax
    ({
        url : '../datos/datos.asignacionCor.php',
        type : 'POST',
        data :datos,
        dataType : 'json',
        success : function(json) {
            if (json["res"]=="true"){
                swal("Mensaje!","Cortes asignados exitosamente.","success");

            }else if(json["res"]=="false"){
                swal("Mensaje!", json["error"], "error");
            }
        },
        error : function(xhr, status) {

            return false;
        }
    });

}


