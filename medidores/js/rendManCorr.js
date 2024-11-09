var codCol;
$(document).ready(function(){

    desContr();
    compSession();
    compSession(llenarSpinerPro);
    $("#rendManCorrSelPro").change(
        function(){
            cargSectores();
        }
    );


    $('#rendManCorrForm').submit(
        function(){
            compSession(flexyRendManCorr);
        }
    )


});






function initMap() {



    variables = codCol.split(' ');
    usuario=variables[0];
    proyecto=variables[1];
    ruta=variables[2];
    fec=variables[3];

    var parametros=[];


    parametros.push({name: 'fec', value: fec});
    parametros.push({name: 'ruta', value: ruta});
    parametros.push({name: 'usr', value: usuario});
    parametros.push({name: 'tip', value: 'corrManCorr'});

    $.ajax
    ({
        url : '../datos/datos.rendManCorr.php',
        type : 'POST',
        dataType : 'json',
        data : parametros,
        success : function(json) {

            ////creamos el mapa
            center1={lat:0,lng:0};
            var map = new google.maps.Map(document.getElementById('map1'), {
                zoom: 16,
                center: center1,
                mapTypeId: google.maps.MapTypeId.TERRAIN
            });
            //// fin creacion mapa


            /////////////// pintamos  puntos y banderas///////////////
            var latPro=0;
            var logPro=0;
            var tot=0;



            var flightPlanCoordinates = [];
            for(var x=0;x<json.length;x++)
            {

                var infowindow = new google.maps.InfoWindow({
                    content: ''
                });

                var marcadores = [];


                var  contenido='<div id="content"><center>'+(json[x]["ID_INMUEBLE"])+'</center><p><center>fecha:'+(json[x]["FECHA"])+'</center></div>';



                if(x==0){

                    var marker = new google.maps.Marker({
                        position: {lat:parseFloat(json[x]["LAT"]),lng:parseFloat(json[x]["LGN"])},
                        icon: '../../images/flag-red.png' ,
                        map: map   });

                }else if(x==json.length-1){

                    var marker = new google.maps.Marker({
                        position: {lat:parseFloat(json[x]["LAT"]),lng:parseFloat(json[x]["LGN"])},
                        icon: '../../images/flag-green.png' ,
                        map: map   });

                }else{
                    var marker = new google.maps.Marker({
                        position: {lat:parseFloat(json[x]["LAT"]),lng:parseFloat(json[x]["LGN"])},
                        icon: '../../images/circle_red.png' ,
                        map: map   });
                }


                (function(marker, contenido) {
                    google.maps.event.addListener(marker, 'click', function() {
                        infowindow.setContent(contenido);
                        infowindow.open(map, marker);
                    });
                })(marker, contenido);

                
                flightPlanCoordinates.push({lat: parseFloat(json[x]["LAT"]), lng:parseFloat(json[x]["LGN"]) });

                if(parseFloat(json[x]["LAT"])!=0 && parseFloat(json[x]["LGN"]) !=0){
                    latPro=latPro+parseFloat(json[x]["LAT"]);
                    logPro=logPro+parseFloat(json[x]["LGN"]);
                    tot++;

                }
            }

            /////////////// fin pintamos  puntos y banderas///////////////

            ////// centro del mapa//////////////
            latPro=latPro/tot;
            logPro=logPro/tot;
            center1={lat:latPro,lng:logPro};
            map.setCenter(center1);
            ////// fin centro del mapa//////////////

            //// adicioonamos las coordenadas (lineas)
            var flightPath = new google.maps.Polyline({
                path: flightPlanCoordinates,
                geodesic: true,
                strokeColor: '#FF0000',
                strokeOpacity: 1.0,
                strokeWeight: 2

            });

            flightPath.setMap(map);
            //// fin adicioonamos las coordenadas (lineas)


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
                $("#rendManCorrSelPro").focus(false);
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
                            $("#rendManCorrSelPro").focus();
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
        url : '../datos/datos.rendManCorr.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            $('#rendManCorrSelPro').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#rendManCorrSelPro').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });
}

function cargSectores()
{

    $.ajax
    ({
        url : '../datos/datos.rendManCorr.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selSec' , pro:$('#rendManCorrSelPro').val() },
        success : function(json) {
            $('#rendManCorrSelSec').empty();
            $('#rendManCorrSelSec').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#rendManCorrSelSec').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
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
                            $("#rendManCorrSelPro").focus();
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


function flexyRendManCorr(){

    var parametros =
        $("#rendManCorrForm").serializeArray();
        parametros.push({name: 'tip', value: 'flexy'});
        parametros.push({name: 'fecIni', value: $("#rendManCorrFecIni").val()});
        parametros.push({name: 'fecFin', value: $("#rendManCorrFecFin").val()});
        parametros.push({name: 'tip', value: 'flexy'});


    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexRendManCorr").flexigrid
    (
        {
            url: '../datos/datos.rendManCorr.php',
            dataType: 'json',
            type:  'post',

            colModel :
                [
                    {display: 'N&deg;', name: 'numero', width: 15, sortable: true, align: 'center'},
                    {display: 'No.<br>Cédula', name: 'cedula', width: 218, sortable: true, align: 'center'},
                    {display: 'Nombre Lector', name: 'nom_completo', width: 218, sortable: true, align: 'center'},
                    {display: 'Ruta', name: 'ruta', width: 31, sortable: true, align: 'center'},
                    {display: 'Fecha<br>Asignación', name: 'fecha_asig', width: 51, sortable: true, align: 'center'},
                    {display: 'Fecha<br>Inicio', name: 'fecha_asig', width: 94, sortable: true, align: 'center'},
                    {display: 'Fecha<br>Final', name: 'fecha_asig', width: 96, sortable: true, align: 'center'},
                    {display: 'Predios<br>Asignados', name: 'leidos', width: 34, sortable: true, align: 'center'},
                    {display: 'Tiempo Total<br>Recorrido', name: 'hora', width: 64, sortable: true, align: 'center'},
                    {display: 'Tiempo Promedio<br>Por Predio', name: 'min_prom', width: 92, sortable: true, align: 'center'},
                    {display: 'Predios Promedio<br>Por Hora', name: 'predio_promedio_hora', width: 100, sortable: true, align: 'center'}
                ],
            usepager: true,
            title: 'Rendimiento por dia',
            useRp: false,
            page: 1,
            showTableToggleBtn: false,
            width: 1000,
            onSuccess: function(){asigevenRend()},
            height: 245,
            rp: 1000,
            sortname: "TO_CHAR(MIN(RC.FECHA_ASIGNACION),'YYYY/MM/DD HH24:MI:SS')",
            sortorder: "DESC",
            params: parametros
        }
    );
    $("#flexRendManCorr").flexOptions({url: '../datos/datos.rendManCorr.php'});
    $("#flexRendManCorr").flexOptions({params: parametros});
    $("#flexRendManCorr").flexReload();
}



function flexRendManCorrDet(){



    variables = codCol.split(' ');
    usuario=variables[0];
    proyecto=variables[1];
    ruta=variables[2];
    fecha=variables[3];
    var parametros=[];

    parametros.push({name: 'fec', value: fecha});
    parametros.push({name: 'ruta', value: ruta});
    parametros.push({name: 'usr', value: usuario});
    parametros.push({name: 'tip', value: 'flexyDetRend'});


    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexRendManCorrDet").flexigrid
    (
        {
            url: '../datos/datos.rendManCorr.php',
            dataType: 'json',
            type:  'post',

            colModel :
                [
                    {display: 'N&deg;',  width: 15, sortable: false, align: 'center'},
                    {display: 'Código', width: 31, sortable: false, align: 'center'},
                    {display: 'Proceso', width: 58, sortable: false, align: 'center'},
                    {display: 'Catastro',  width: 84, sortable: false, align: 'center'},
                    {display: 'Nombre',  width: 175, sortable: false, align: 'center'},
                    {display: 'Dirección',  width: 126, sortable: false, align: 'center'},
                    {display: 'Serial',  width: 58, sortable: false, align: 'center'},
                    {display: 'Observacion',  width: 100, sortable: false, align: 'center'},
                    {display: 'Fecha Mnatenimiento',  width: 89, sortable: false, align: 'center'},
                    {display: 'Fotos',  width: 54, sortable: false, align: 'center'}
                ],
            usepager: true,
            title: 'Detalle Mantenimientos Operario ruta' ,
            useRp: false,
            page: 1,
            showTableToggleBtn: false,
            width: 1000,
            height: 245,
            sortname: "TO_CHAR(MIN(RC.FECHA_REEALIZACION),'DD/MM/YYYY HH24:MI:SS')",
            sortorder: "DESC",
            params: parametros
        }
    );
    $("#flexRendManCorrDet").flexOptions({url: '../datos/datos.rendManCorr.php'});
    $("#flexRendManCorrDet").flexOptions({title: 'Detalle Mantenimientos Operario ruta:'+ruta});
    $("#flexRendManCorrDet").flexOptions({params: parametros});
    $("#flexRendManCorrDet").flexReload();
}



function asigevenRend() {
    tabflexPag = document.getElementById("flexRendManCorr");
    filflexPag = tabflexPag.getElementsByTagName("tr");
    for (i = 0; i < filflexPag.length; i++) {
        if(filflexPag[i]){
            var currentRow = filflexPag[i];
            currentRow.addEventListener("click",eventoPagos);
        }

    }
}


function eventoPagos(){
    codCol=this.getAttribute("id").replace("row","");
    compSession(flexRendManCorrDet);
    initMap();



}

function fotos(id) { // Traer la fila seleccionada

    popup("vista.fotos_ManCorr.php?orden="+id,1100,800,'yes');
}

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




