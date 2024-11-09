'use strict'
//DECLARACION VARIABLES
const columnas     = [
                        {title: 'ID caja'},
                        {title: 'Posición'},
                        {title: 'Usuario'},
                        {title: 'Punto'},
                        {title: 'Acción'},
                    ];
var   idCaja       = 0;
const dtPosiciones = $('#dtPosiciones');

//Declaración de funciones.
const Datatable        = function(jsonData,columnas,idDiv){

    idDiv = "#"+idDiv;
    if ( $.fn.dataTable.isDataTable(idDiv) ) {
        $(idDiv).DataTable().destroy();
    }

    $(idDiv).DataTable( {
        data:jsonData,
        dom: 'Bfrtip',
        columnDefs:[ {
            "targets": [ 0 ],
            "visible": false
        }],
        /*buttons: [
            { extend: 'copy', text:' Copiar',  className: 'btn btn-primary glyphicon glyphicon-duplicate' },
            { extend: 'csv',  text:' CVS', className: 'btn btn-primary glyphicon glyphicon-save-file' },
            { extend: 'excel',  text:' Excel',className: 'btn btn-primary glyphicon glyphicon-list-alt' },
            { extend: 'pdf',  text:' PDF',  className: 'btn btn-primary glyphicon glyphicon-file' },
            {extend:'print',text:' Imprimir', className: 'btn btn-primary glyphicon glyphicon-print' }
        ],*/
        columns: columnas,
        language:
            {
                "url":"//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
            },

        "info":     false,
        "order": [[ 2, "desc" ]],
        "paging" : true,

    });
    $(idDiv).show();

};
const CargarPosiciones = function(){

    $.ajax({
        type: "POST",
        url: "../datos/datos.AsignacionPosicion.php",
        data: {tip:"getPosicionesAtencion"},
        datatype:"json",
        success:function(res){
            var json = JSON.parse(res);
            Datatable(json,columnas,"dtPosiciones");
        },
        error:function(jqXHR,exception){
            alert(exception);
        }
    });

};
const CargarUsuarios   = function(){

  var slcUsuarios = $(".slcUsuarios");
  $.ajax({
            type:"POST",
            url: "../datos/datos.AsignacionPosicion.php",
            data:{tip:"getUsuarioPorCargo"},
            datatype:"json",
            success:function(res){
                var json = JSON.parse(res);

                slcUsuarios.empty();
                var options = [
                                $("<option/>",{text:"--SELECCIONE UN USUARIO--"}),
                                $("<option/>",{text:"DEJAR DISPONIBLE",value:"disponible"})
                              ];

                options.forEach(function(option){
                    slcUsuarios.append(option);
                });

                json.forEach(function(usuario){
                    var option = $("<option/>",{text:usuario.NOMBRE_USUARIO,value:usuario.ID_USUARIO});
                    slcUsuarios.append(option);
                });
            },
            error:function(jqXHR,exception){
                console.log(jqXHR);
                alert(exception)
            }
  });
};
const AsginarPosicion  = function(idUsuario,idCaja){
                            $.ajax({
                                    type:"POST",
                                    url:"../datos/datos.AsignacionPosicion.php",
                                    data:{tip:"asignarPosicion",idUsuario:idUsuario,idCaja:idCaja},
                                    datatype:"json",
                                    success:function(res){
                                        var json = JSON.parse(res);
                                        if(json.Code != 0){
                                            swal("Error!", json.Status, "error");
                                        }else {
                                            swal("Éxito!", json.Status, "success");
                                            CargarPosiciones();
                                        }
                                    },
                                    error:function(jqXHR,exception){
                                        alert(exception+":"+jqXHR.status);
                                    }
                            });
                         };
const DesasignarPosicion  = function(idCaja){
                            $.ajax({
                                    type:"POST",
                                    url:"../datos/datos.AsignacionPosicion.php",
                                    data:{tip:"desasignarPosicion",idCaja:idCaja},
                                    datatype:"json",
                                    success:function(res){
                                        var json = JSON.parse(res);
                                        if(json.Code != 0){
                                            swal("Error!", json.Status, "error");
                                        }else {
                                            swal("Éxito!", json.Status, "success");
                                            CargarPosiciones();
                                        }
                                    },
                                    error:function(jqXHR,exception){
                                        alert(exception+":"+jqXHR.status);
                                    }
                            });
                         };

//EVENTOS
//Cuando el documento cargue.
$(document).ready(function (){
    compSession(CargarPosiciones());
});

//Cuando se cliquee 'asignar posición'
dtPosiciones.on("click",'.btnAsignarPosicion',function () {
        compSession(CargarUsuarios());
        idCaja     = $(this).attr("id").split("_")[1];
});
/*dtPosiciones.on("click",'.btnDejarDisponible',function () {
    idCaja     = $(this).attr("id").split("_")[1];
    swal({
        title: "Desasignar posición",
        text: "¿Está seguro que desea desasignar la posición?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si, desasignar",
        showLoaderOnConfirm: true,
        closeOnConfirm: false
    },function(isConfirm){
        if (isConfirm) {
            compSession(DesasignarPosicion(idCaja));
        }
    });

});*/
dtPosiciones.on("click",".btnAsignar",function (e) {
    var usuarioSeleccionado = $("#slcUsuarios"+idCaja).children("option:selected").val();
    idCaja     = $(this).attr("id").split("_")[1];
    if(usuarioSeleccionado !== undefined){
        if(usuarioSeleccionado === "disponible"){
            idCaja     = $(this).attr("id").split("_")[1];
            swal({
                title: "Desasignar posición",
                text: "¿Está seguro que desea desasignar la posición?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Si, desasignar",
                showLoaderOnConfirm: true,
                closeOnConfirm: false
            },function(isConfirm){
                if (isConfirm) {
                    compSession(DesasignarPosicion(idCaja));
                }
            });
        }else{
            compSession(function(){AsginarPosicion(usuarioSeleccionado,idCaja)});
        }
    }else{
        swal("Error!", "Debe seleccionar un usuario.", "error");
    }

});
dtPosiciones.on('click','.dropdown-menu select',function (event) {
    //Función que evita que el dropdown se cierre cuando se seleccione una opción del 'select'
    event.stopPropagation();
});
