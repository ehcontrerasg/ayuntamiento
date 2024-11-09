$(document).ready(function(){
  //  desContr();
    compSession();
    getAveria();
//getFotos();

        $("#marcAtendida").click(
            function() {
                var estado = $('#atendida').text();
               // alert(estado);
                if (estado !== 'S') {
                    swal
                    ({
                            title: "Advertencia!",
                            text: "Desea Marcar como atenida?.",
                            showConfirmButton: true,
                            showCancelButton: true,
                            showLoaderOnConfirm: true,
                            closeOnConfirm: false,
                            closeOnCancel: true
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                actEstado();
                            }
                        });

                }   else {
                    swal
                    ({
                        title: "Aviso",
                        text: "Este avería ya ha sido atendida.",
                        showConfirmButton: true,
                        closeOnConfirm: false
                    });

                }
            }

        )

});

function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
}

function imprSelec(muestra)
{
    var ficha=document.getElementById(muestra);
    var mapa=document.getElementById(mapa);
    var ventimp=window.open(' ','popimpr');

    ventimp.document.write(ficha.innerHTML+""+mapa.innerHTML);
    ventimp.document.close();
    ventimp.print();
    ventimp.close();
    var css = ventimp.document.createElement("link");
    css.setAttribute("href", "../css/general_averias.css.css");
    css.setAttribute("rel", "stylesheet");
    css.setAttribute("type", "text/css");
    css.setAttribute("media", "print");
    ventimp.document.head.appendChild(css);
}

var data;

function getAveria() {

    $.ajax
    ({
        url: '../datos/datos.averias_recibidas.php',
        type: 'POST',
        dataType: 'json',
        data: {tip: 'report', id: $('#idReclamo').val()},
        success: function (json) {
            console.log( JSON.parse(json))
            console.log('fdgdfg')
alert('sdfd')
            console.log(json.toString())
            for (var x = 0; x < json.length; x++) {

                iniciaForm(json['data'][x]);
            }
        },
        error: function (xhr, status) {
            console.log('fdgdfg');

        }
    });
}

function iniciaForm(data) {

    var id =data[0];
    var observacion = data[1];
    var fecha = data[2];
    var nombre = data[3];
    var telefono = data[4];
    var direccion = data[5];
    var email = data[6];
    var lat = data[7];
    var long = data[8];
    var motivo = data[9];
    var idReclamo = data[10];
    var atendida = data[11];

    $('#foto1').html("");
    $('#id').html("");
    $('#atendida').html("");
    $('#observacion').html("");
    $('#motivo').html("");
    $('#fecha').html("");
    $('#Nombre').html("");
    $('#Fecha').html("");
    $('#Direcion').html("");
    $('#Telefono').html("");
    $('#email').html("");
    $('#coordenadas').html("");

    $('#id').append(id);
    $('#observacion').append(observacion);
    $('#fecha').append(fecha);
    $('#Nombre').append(nombre);
    $('#motivo').append(motivo);
    $('#Direccion').append(direccion);
    $('#Telefono').append(telefono);
    $('#email').append(email);
    $('#atendida').append(atendida);

    getFotos(idReclamo);


    initMap(lat,long);

}

function getFotos(idReclamo) {

    $.ajax
    ({
        url: '../datos/datos.averias_recibidas.php',
        type: 'POST',
        dataType: 'json',
        data: {tip: 'fotos', id: idReclamo},
        success: function (json) {

            for (var x = 0; x < json.length; x++) {
                urlFoto1 = json[x]["URL_FOTO"];
                urlFoto1 = urlFoto1.replace("../", "");
                $('#foto1').append("<i class='thumbnail2' href=#thumb'><img src='../../../webservice/" + urlFoto1 + "' alt='foto' height='200' width='200' id='fotoVisible' ><span>" +
                    "<img src='../../../webservice/" + urlFoto1 + "' alt='foto' ></span></i>");

            }
        },
        error: function (xhr, status) {

        }
    });
}
    // Initialize and add the map

    var marker;

    function initMap(lattitud,longitud) {

        if ((lattitud === "(sin_señal_gps)") && (longitud === "(sin_señal_gps)"))
            $('#coordenadas').append("latitud: " + lat + "\n longitud: " + long);
        else{

            var lat = parseFloat(lattitud);
        var long = parseFloat(longitud);

        var latlng = new google.maps.LatLng(lat, long);
        map = new google.maps.Map(document.getElementById('map'), {
            center: latlng,
            zoom: 16
        });


        marker = new google.maps.Marker({
            map: map,
            draggable: true,
            animation: google.maps.Animation.DROP,
            position: {lat: lat, lng: long}
        });
        marker.addListener('click', toggleBounce);
    }
    }

    function toggleBounce() {
        if (marker.getAnimation() !== null) {
            marker.setAnimation(null);
        } else {
            marker.setAnimation(google.maps.Animation.BOUNCE);
        }
    }

function actEstado(){

var id=$('#idReclamo').val();

    $.ajax
    ({
        url: '../datos/datos.averias_recibidas.php',
        type: 'POST',
        dataType: 'text',
        data: {id: id, tip: 'actualiza'},
        success: function () {
            //swal.close();
            swal
            ({
                title: "Aviso",
                text: "Dato actualizado con exito!",
                showConfirmButton: true,
                closeOnConfirm: false
            });
            window.opener.location.reload();
            getAveria();
            //getReport();


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


function compSession(callback) {
    $.ajax
    ({
        url: '../../configuraciones/session.php',
        type: 'POST',
        data: {tip: 'sess'},
        dataType: 'json',
        success: function (json) {
            if (json == true) {
                if (callback) {
                    callback();
                }
            } else if (json == false) {
                swal
                ({
                        title: "Mensaje!",
                        text: "Su sesion ha finalizado.",
                        showConfirmButton: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            window.close();
                        }
                    });
                return false;
            }
        },
        error: function (xhr, status) {
            swal
            (
                {
                    title: "Mensaje!",
                    text: "Su sesion ha finalizado.",
                    showConfirmButton: true
                },
                function (isConfirm) {
                    if (isConfirm) {
                        top.location.replace("../../index.php")
                    }
                }
            );
            return false;
        }
    });
}


