$(document).ready(function(){
    desContr();
    compSession(printDiv);
    compSession(getAveria);
    gerPermiso();

    compSession(
    $("#marcAtendida").click(
            function() {
                var estado = $('#atendida').text();
               // alert(estado);
                if (estado !== 'Atendida') {
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

        ));

});

function gerPermiso() {

    $.ajax
    ({
        url: '../datos/datos.averias_recibidas.php',
        type: 'POST',
        dataType: 'text',
        data: {tip: 'permiso'},
        success: function (text) {


                if ( text==='S') {
                    $('#marcAtendida').show();
                    $('#imprimir').show();
                }


        },
        error: function (xhr, status) {

        }
    });
}

function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
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
            for (var x = 0; x < json.data.length; x++) {

                iniciaForm(json.data[0]);
            }
        },
        error: function (xhr, status) {

        }
    });
}

function iniciaForm(data) {
console.log(data)
console.log(data.CODIGO)
    var id =data.CODIGO;
    var observacion = data.OBSERVACION;
    var fecha = data.FECHA;
    var nombre = data.NOMBRE;
    var telefono = data.TELEFONO;
    var direccion = data.DIRECCION;
    var email = data.EMAIL;
    var lat = data.LATITUD;
    var long = data.LONGITUD;
    var motivo = data.DESCRIPCION;
    var idReclamo = data.ID;
    var atendida = data.ESTADO;

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
                var idFoto="fotoVisible"+x;
                $('#foto1').append("<img src='../../../webservice/" + urlFoto1 + "' alt='foto' onclick='set(this.id)' id='"+idFoto+"' width='230px' height='200px'><span>" +
                    "</span>");

            }
        },
        error: function (xhr, status) {

        }
    });
}
    // Initialize and add the map
function set(id){

    $('#imagepreview').attr('src', $('#'+id).attr('src')); // here asign the image to the modal when the user click the enlarge link
    $('#miModal').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function


}


    var marker;

    function initMap(latitud,longitud) {

        if ((latitud=== "(sin_señal_gps)") || (longitud==="(sin_señal_gps)"))
            $('#coordenadas').append("Latitud: " + latitud + ", Longitud: " + longitud);

        var lat = parseFloat(latitud);
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


