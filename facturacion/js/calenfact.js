$(document).ready(function(){

    desContr();
    compSession();
    datosAperturaCAASD();
    datosAperturaCORAABO();
})

function desContr(){
    document.onkeypress = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            return false;  }}


    document.onkeydown = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            return false;  }}
    $(document).bind("contextmenu", function(e){return false;});

}

function datosAperturaCAASD()
{
    $.ajax({ // ajax call starts
        url: '../datos/datos.datCalenFact.php', // JQuery loads serverside.php
        type : 'POST',
        data: {tip : 'obtDatCAASD'},// Send value of text
        dataType: 'json', // Choosing a JSON datatype
        success: function(data) // Variable data contains the data we get from serverside
        {
            var html='';

            // si la consulta ajax devuelve datos
            if(data){
                $.each(data, function(i,item){

                    var f1 = item.FEC_APERTURA;
                    var f2 = item.FEC_APE_REAL;
                    var f3 = item.FEC_MAX_LEC;
                    var f4 = item.FEC_LEC_REAL;
                    var f5 = item.FEC_MORA;
                    var f6 = item.FEC_MORA_REAL;
                    var f7 = item.FEC_CIERRE;
                    var f8 = item.FEC_CIERRE_REAL;
                    var f9 = item.FEC_DISTR;
                    var f10 = item.FEC_DISTR_REAL;
                    var f11 = item.FEC_VCTO;
                    var f12 = item.FEC_VCTO_REAL;
                    var f13 = item.FEC_CORTE;
                    var f14 = item.FEC_CORTE_REAL;

                    var aFecha1 = f1.split('/');
                    if (f2 !== null) {
                        var aFecha2 = f2.split('/');
                        var fFecha2 = Date.UTC(aFecha2[2],aFecha2[1]-1,aFecha2[0]);
                    }
                    var fFecha1 = Date.UTC(aFecha1[2],aFecha1[1]-1,aFecha1[0]);
                    var dif1 = fFecha2 - fFecha1;
                    var dias1 = Math.floor(dif1 / (1000 * 60 * 60 * 24));

                    html += '<tr>'
                    html += '<td>'+item.ID_ZONA+'</td>'
                    if( dias1 > 0){
                        html += '<td style="background-color: #8A2908">'+item.FEC_APERTURA+'</td>'
                        html += '<td style="background-color: #8A2908">'+item.FEC_APE_REAL+'</td>'
                    }
                    else{
                        html += '<td style="background-color: #88A247">'+item.FEC_APERTURA+'</td>'
                        html += '<td style="background-color: #88A247">'+item.FEC_APE_REAL+'</td>'
                    }

                    var aFecha3 = f3.split('/');
                    if (f4 !== null) {
                        var aFecha4 = f4.split('/');
                        var fFecha4 = Date.UTC(aFecha4[2],aFecha4[1]-1,aFecha4[0]);
                    }
                    var fFecha3 = Date.UTC(aFecha3[2],aFecha3[1]-1,aFecha3[0]);
                    var dif2 = fFecha4 - fFecha3;
                    var dias2 = Math.floor(dif2 / (1000 * 60 * 60 * 24));
                    if( dias2 > 0) {
                        html += '<td style="background-color: #8A2908">' + item.FEC_MAX_LEC + '</td>'
                        html += '<td style="background-color: #8A2908">' + item.FEC_LEC_REAL + '</td>'
                    }
                    else{
                        html += '<td style="background-color: #88A247">' + item.FEC_MAX_LEC + '</td>'
                        html += '<td style="background-color: #88A247">' + item.FEC_LEC_REAL + '</td>'
                    }

                    var aFecha5 = f5.split('/');
                    if (f6 !== null) {
                        var aFecha6 = f6.split('/');
                        var fFecha6 = Date.UTC(aFecha6[2],aFecha6[1]-1,aFecha6[0]);
                    }
                    var fFecha5 = Date.UTC(aFecha5[2],aFecha5[1]-1,aFecha5[0]);
                    var dif3 = fFecha6 - fFecha5;
                    var dias3 = Math.floor(dif3 / (1000 * 60 * 60 * 24));
                    if( dias3 > 0) {
                        html += '<td style="background-color: #8A2908">' + item.FEC_MORA + '</td>'
                        html += '<td style="background-color: #8A2908">' + item.FEC_MORA_REAL + '</td>'
                    }
                    else{
                        html += '<td style="background-color: #88A247">' + item.FEC_MORA + '</td>'
                        html += '<td style="background-color: #88A247">' + item.FEC_MORA_REAL + '</td>'
                    }

                    var aFecha7 = f7.split('/');
                    if (f8 !== null) {
                        var aFecha8 = f8.split('/');
                        var fFecha8 = Date.UTC(aFecha8[2],aFecha8[1]-1,aFecha8[0]);
                    }
                    var fFecha7 = Date.UTC(aFecha7[2],aFecha7[1]-1,aFecha7[0]);
                    var dif4 = fFecha8 - fFecha7;
                    var dias4 = Math.floor(dif4 / (1000 * 60 * 60 * 24));
                    if( dias4 > 0) {
                        html += '<td style="background-color: #8A2908">' + item.FEC_CIERRE + '</td>'
                        html += '<td style="background-color: #8A2908">' + item.FEC_CIERRE_REAL + '</td>'
                    }
                    else{
                        html += '<td style="background-color: #88A247">' + item.FEC_CIERRE + '</td>'
                        html += '<td style="background-color: #88A247">' + item.FEC_CIERRE_REAL + '</td>'
                    }

                    var aFecha9 = f9.split('/');
                    if (f10 !== null) {
                        var aFecha10 = f10.split('/');
                        var fFecha10 = Date.UTC(aFecha10[2],aFecha10[1]-1,aFecha10[0]);
                    }
                    var fFecha9 = Date.UTC(aFecha9[2],aFecha9[1]-1,aFecha9[0]);
                    var dif5 = fFecha10 - fFecha9;
                    var dias5 = Math.floor(dif5 / (1000 * 60 * 60 * 24));
                    if( dias5 > 0) {
                        html += '<td style="background-color: #8A2908">' + item.FEC_DISTR + '</td>'
                        html += '<td style="background-color: #8A2908">' + item.FEC_DISTR_REAL + '</td>'
                    }
                    else{
                        html += '<td style="background-color: #88A247">' + item.FEC_DISTR + '</td>'
                        html += '<td style="background-color: #88A247">' + item.FEC_DISTR_REAL + '</td>'
                    }

                    var aFecha11 = f11.split('/');
                    if (f12 !== null) {
                        var aFecha12 = f12.split('/');
                        var fFecha12 = Date.UTC(aFecha12[2],aFecha12[1]-1,aFecha12[0]);
                    }
                    var fFecha11 = Date.UTC(aFecha11[2],aFecha11[1]-1,aFecha11[0]);
                    var dif6 = fFecha12 - fFecha11;
                    var dias6 = Math.floor(dif6 / (1000 * 60 * 60 * 24));
                    if( dias6 > 0) {
                        html += '<td style="background-color: #8A2908">' + item.FEC_VCTO + '</td>'
                        html += '<td style="background-color: #8A2908">' + item.FEC_VCTO_REAL + '</td>'
                    }
                    else{
                        html += '<td style="background-color: #88A247">' + item.FEC_VCTO + '</td>'
                        html += '<td style="background-color: #88A247">' + item.FEC_VCTO_REAL + '</td>'
                    }

                    var aFecha13 = f13.split('/');
                    if (f14 !== null) {
                        var aFecha14 = f14.split('/');
                        var fFecha14 = Date.UTC(aFecha14[2],aFecha14[1]-1,aFecha14[0]);
                    }
                    var fFecha13 = Date.UTC(aFecha13[2],aFecha13[1]-1,aFecha13[0]);
                    var dif7 = fFecha14 - fFecha13;
                    var dias7 = Math.floor(dif7 / (1000 * 60 * 60 * 24));
                    if( dias7 > 0) {
                        html += '<td style="background-color: #8A2908">' + item.FEC_CORTE + '</td>'
                        html += '<td style="background-color: #8A2908">' + item.FEC_CORTE_REAL + '</td>'
                    }
                    else{
                        html += '<td style="background-color: #88A247">' + item.FEC_CORTE + '</td>'
                        html += '<td style="background-color: #88A247">' + item.FEC_CORTE_REAL + '</td>'
                    }
                    html += '</tr>';
                });
            }
            // si no hay datos mostramos mensaje de no encontraron registros
            if(html == '') html = '<tr><td colspan="4">No se encontraron ciclos para aperturar el dia de hoy..</td></tr>'
            // añadimos  a nuestra tabla todos los datos encontrados mediante la funcion html
            $("#tableCAASD tbody").html(html);
        }
    });
    return false;
}


function datosAperturaCORAABO()
{
    $.ajax({ // ajax call starts
        url: '../datos/datos.datCalenFact.php', // JQuery loads serverside.php
        type : 'POST',
        data: {tip : 'obtDatCORAABO'},// Send value of text
        dataType: 'json', // Choosing a JSON datatype
        success: function(data) // Variable data contains the data we get from serverside
        {
            var html='';

            // si la consulta ajax devuelve datos
            if(data){
                $.each(data, function(i,item){

                    var f1 = item.FEC_APERTURA;
                    var f2 = item.FEC_APE_REAL;
                    var f3 = item.FEC_MAX_LEC;
                    var f4 = item.FEC_LEC_REAL;
                    var f5 = item.FEC_MORA;
                    var f6 = item.FEC_MORA_REAL;
                    var f7 = item.FEC_CIERRE;
                    var f8 = item.FEC_CIERRE_REAL;
                    var f9 = item.FEC_DISTR;
                    var f10 = item.FEC_DISTR_REAL;
                    var f11 = item.FEC_VCTO;
                    var f12 = item.FEC_VCTO_REAL;
                    var f13 = item.FEC_CORTE;
                    var f14 = item.FEC_CORTE_REAL;

                    var aFecha1 = f1.split('/');
                    if (f2 !== null) {
                        var aFecha2 = f2.split('/');
                        var fFecha2 = Date.UTC(aFecha2[2],aFecha2[1]-1,aFecha2[0]);
                    }
                    var fFecha1 = Date.UTC(aFecha1[2],aFecha1[1]-1,aFecha1[0]);
                    var dif1 = fFecha2 - fFecha1;
                    var dias1 = Math.floor(dif1 / (1000 * 60 * 60 * 24));

                    html += '<tr>'
                    html += '<td>'+item.ID_ZONA+'</td>'
                    if( dias1 > 0){
                        html += '<td style="background-color: #8A2908">'+item.FEC_APERTURA+'</td>'
                        html += '<td style="background-color: #8A2908">'+item.FEC_APE_REAL+'</td>'
                    }
                    else{
                        html += '<td style="background-color: #88A247">'+item.FEC_APERTURA+'</td>'
                        html += '<td style="background-color: #88A247">'+item.FEC_APE_REAL+'</td>'
                    }

                    var aFecha3 = f3.split('/');
                    if (f4 !== null) {
                        var aFecha4 = f4.split('/');
                        var fFecha4 = Date.UTC(aFecha4[2],aFecha4[1]-1,aFecha4[0]);
                    }
                    var fFecha3 = Date.UTC(aFecha3[2],aFecha3[1]-1,aFecha3[0]);
                    var dif2 = fFecha4 - fFecha3;
                    var dias2 = Math.floor(dif2 / (1000 * 60 * 60 * 24));
                    if( dias2 > 0) {
                        html += '<td style="background-color: #8A2908">' + item.FEC_MAX_LEC + '</td>'
                        html += '<td style="background-color: #8A2908">' + item.FEC_LEC_REAL + '</td>'
                    }
                    else{
                        html += '<td style="background-color: #88A247">' + item.FEC_MAX_LEC + '</td>'
                        html += '<td style="background-color: #88A247">' + item.FEC_LEC_REAL + '</td>'
                    }

                    var aFecha5 = f5.split('/');
                    if (f6 !== null) {
                        var aFecha6 = f6.split('/');
                        var fFecha6 = Date.UTC(aFecha6[2],aFecha6[1]-1,aFecha6[0]);
                    }
                    var fFecha5 = Date.UTC(aFecha5[2],aFecha5[1]-1,aFecha5[0]);
                    var dif3 = fFecha6 - fFecha5;
                    var dias3 = Math.floor(dif3 / (1000 * 60 * 60 * 24));
                    if( dias3 > 0) {
                        html += '<td style="background-color: #8A2908">' + item.FEC_MORA + '</td>'
                        html += '<td style="background-color: #8A2908">' + item.FEC_MORA_REAL + '</td>'
                    }
                    else{
                        html += '<td style="background-color: #88A247">' + item.FEC_MORA + '</td>'
                        html += '<td style="background-color: #88A247">' + item.FEC_MORA_REAL + '</td>'
                    }

                    var aFecha7 = f7.split('/');
                    if (f8 !== null) {
                        var aFecha8 = f8.split('/');
                        var fFecha8 = Date.UTC(aFecha8[2],aFecha8[1]-1,aFecha8[0]);
                    }
                    var fFecha7 = Date.UTC(aFecha7[2],aFecha7[1]-1,aFecha7[0]);
                    var dif4 = fFecha8 - fFecha7;
                    var dias4 = Math.floor(dif4 / (1000 * 60 * 60 * 24));
                    if( dias4 > 0) {
                        html += '<td style="background-color: #8A2908">' + item.FEC_CIERRE + '</td>'
                        html += '<td style="background-color: #8A2908">' + item.FEC_CIERRE_REAL + '</td>'
                    }
                    else{
                        html += '<td style="background-color: #88A247">' + item.FEC_CIERRE + '</td>'
                        html += '<td style="background-color: #88A247">' + item.FEC_CIERRE_REAL + '</td>'
                    }

                    var aFecha9 = f9.split('/');
                    if (f10 !== null) {
                        var aFecha10 = f10.split('/');
                        var fFecha10 = Date.UTC(aFecha10[2],aFecha10[1]-1,aFecha10[0]);
                    }
                    var fFecha9 = Date.UTC(aFecha9[2],aFecha9[1]-1,aFecha9[0]);
                    var dif5 = fFecha10 - fFecha9;
                    var dias5 = Math.floor(dif5 / (1000 * 60 * 60 * 24));
                    if( dias5 > 0) {
                        html += '<td style="background-color: #8A2908">' + item.FEC_DISTR + '</td>'
                        html += '<td style="background-color: #8A2908">' + item.FEC_DISTR_REAL + '</td>'
                    }
                    else{
                        html += '<td style="background-color: #88A247">' + item.FEC_DISTR + '</td>'
                        html += '<td style="background-color: #88A247">' + item.FEC_DISTR_REAL + '</td>'
                    }

                    var aFecha11 = f11.split('/');
                    if (f12 !== null) {
                        var aFecha12 = f12.split('/');
                        var fFecha12 = Date.UTC(aFecha12[2],aFecha12[1]-1,aFecha12[0]);
                    }
                    var fFecha11 = Date.UTC(aFecha11[2],aFecha11[1]-1,aFecha11[0]);
                    var dif6 = fFecha12 - fFecha11;
                    var dias6 = Math.floor(dif6 / (1000 * 60 * 60 * 24));
                    if( dias6 > 0) {
                        html += '<td style="background-color: #8A2908">' + item.FEC_VCTO + '</td>'
                        html += '<td style="background-color: #8A2908">' + item.FEC_VCTO_REAL + '</td>'
                    }
                    else{
                        html += '<td style="background-color: #88A247">' + item.FEC_VCTO + '</td>'
                        html += '<td style="background-color: #88A247">' + item.FEC_VCTO_REAL + '</td>'
                    }

                    var aFecha13 = f13.split('/');
                    if (f14 !== null) {
                        var aFecha14 = f14.split('/');
                        var fFecha14 = Date.UTC(aFecha14[2],aFecha14[1]-1,aFecha14[0]);
                    }
                    var fFecha13 = Date.UTC(aFecha13[2],aFecha13[1]-1,aFecha13[0]);
                    var dif7 = fFecha14 - fFecha13;
                    var dias7 = Math.floor(dif7 / (1000 * 60 * 60 * 24));
                    if( dias7 > 0) {
                        html += '<td style="background-color: #8A2908">' + item.FEC_CORTE + '</td>'
                        html += '<td style="background-color: #8A2908">' + item.FEC_CORTE_REAL + '</td>'
                    }
                    else{
                        html += '<td style="background-color: #88A247">' + item.FEC_CORTE + '</td>'
                        html += '<td style="background-color: #88A247">' + item.FEC_CORTE_REAL + '</td>'
                    }
                    html += '</tr>';
                });

            }
            // si no hay datos mostramos mensaje de no encontraron registros
            if(html == '') html = '<tr><td colspan="4">No se encontraron ciclos para aperturar el dia de hoy..</td></tr>'
            // añadimos  a nuestra tabla todos los datos encontrados mediante la funcion html
            $("#tableCORAABO tbody").html(html);
        }
    });
    return false;
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
                //$("#ingResInsInpCodSis").focus(false);
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
                           /* $("#ingResInsInpCodSis").focus();*/
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
                        }else{
                            //$("#ingResInsInpCodSis").focus();
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