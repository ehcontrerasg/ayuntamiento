$(document).ready(function(){
    cargarDatatable();
    cargarDepartamentos("selectArea");
    cargarDepartamentos("dtselectArea");
});

$("#dtselectArea").on("change",function(){
    cargarUsuarios("dtselectArea","dtselectUsuario","");});

$("#selectArea").on("change",function(){
    cargarUsuarios("selectArea","selectUsuario","");
});

$("#dtDetalleExtensiones").on("click",'.btnEditar',function(){

    var row  =  $(this).closest('tr');
    var data =  $("#dtDetalleExtensiones").DataTable().row(row).data();
    llenarFormulario(data);
    $("#modalEditar").modal('show');

});

$("#dtDetalleExtensiones").on("click",'.btnEliminar',function(){

    var row  =  $(this).closest('tr');
    var data =  $("#dtDetalleExtensiones").DataTable().row(row).data();
    var id_extension = data[0]

    eliminarExtension(id_extension);

});


$("#frmInsertarExtension").submit(function(){

    var formulario = $("#frmInsertarExtension");
    var datos = formulario.serializeArray();

    datos.push({name:"tip",value:"insertarExtension"});

    $.ajax({
        type:"POST",
        url: '../datos/datos.extensionesTelefonicas.php',
        data: datos,
        success:function(res){
            var data = JSON.parse(res);
            if(data["CODERROR"]==0){
                swal("Éxito!",data["MSGERROR"], "success");
                cargarDatatable();
                limpiarFormulario(formulario);
            }else if(data["CODERROR"]==1){
                swal("Error!", data["MSGERROR"], "error");
            }
        },
        error:function(xhr, error){
            console.log(error+' '+xhr);
        }

    });

});

$("#frmActualizarExtension").submit(function(){

    var formulario = $("#frmActualizarExtension");
    var datos = formulario.serializeArray();
    datos.push({name:"tip",value:"actualizarExtension"});

    $.ajax({
        type:"POST",
        url: '../datos/datos.extensionesTelefonicas.php',
        data: datos,
        success:function(res){
            var data = JSON.parse(res);
            if(data["CODERROR"]==0){
                swal("Éxito!", data["MSGERROR"], "success");
                cargarDatatable();
                limpiarFormulario(formulario);
            }else if(data["CODERROR"]==1){
                swal("Error!", data["MSGERROR"], "error");
            }
        },
        error:function(xhr, error){
            console.log(error+' '+xhr);
        }
    });
});

function cargarDatatable(){

    var datatable = $('#dtDetalleExtensiones');
    $.ajax({
        method:'POST',
        url: '../datos/datos.extensionesTelefonicas.php',
        data:{tip:'getExtensiones'},
        datatype:'JSON',
        success:function(res){
            if ( $.fn.dataTable.isDataTable( '#dtDetalleExtensiones' ) ) {
                datatable.DataTable().destroy();
            }
            if(res != null){
                datatable.DataTable( {
                    data: JSON.parse(res),
                    dom: 'Bfrtip',
                    columns: [
                        { title: 'ID_EXTENSION' },
                        { title: 'EXTENSION' },
                        { title: 'DEPARTAMENTO' },
                        { title: 'USUARIO' },
                        { title: 'DESCRIPCION'},
                        { title: 'ACCIONES'}
                    ],
                    columnDefs:[{
                        targets:[0],
                        visible:false
                    }],
                    'info':     false,
                    'order': [[ 2, 'desc' ]],
                    'paging' : true,
                    "language":  {url: '../../js/DataTables-1.10.15/Spanish.json'}
                });
            }

        },
        error:function(error, xhr){
            console.log(error+' '+xhr);
        }
    });
}

function llenarFormulario(data){

    var indexArea    = 0;
    $("#dtselectArea>option").map(function(index,item){
        if(item.innerHTML == data[2]){
            indexArea = item.value;
        }
    });
    $("#txtIdExtension").val(data[0]);
    $("#dttxtExtension").val(data["1"]);
    $("#dtselectArea").val(indexArea);
    cargarUsuarios("dtselectArea","dtselectUsuario",data[3]);
    $("#dttxtDescripcion").val(data["4"]);

}

function cargarUsuarios(idSelectArea,idSelectUsuario,nombreUsuario){

    var areaSeleccionada = $("#"+idSelectArea).val();
    var select           = $("#"+idSelectUsuario);
    var option           = '';
    var selected         = '';

    if(areaSeleccionada == ""){
        areaSeleccionada = 0 ;
    }
    if(areaSeleccionada != null || areaSeleccionada != ""){

        $.ajax({
            type:"POST",
            url: '../datos/datos.extensionesTelefonicas.php',
            data:{tip:'getUsuarios',area: areaSeleccionada},
            success:function(res){
                var data = JSON.parse(res);
                select.empty();
                option = '<option value= ></option>';
                data.forEach(function(item){

                if(nombreUsuario!=''){
                    if (nombreUsuario == item[1]){
                        selected = 'selected';
                    }
                }

                option+= '<option value='+item[0]+' '+selected+'>'+ item[1]+'</option>';
                selected = '';

                });

                select.append(option);
            },
            error:function(xhr,error ){
                console.log(xhr+' '+error);
            }
        })
    }
}

function cargarDepartamentos(idElemento){
    var select = $("#"+idElemento);
    var option = '';

    $.ajax({
        type:"POST",
        url: '../datos/datos.extensionesTelefonicas.php',
        data:{tip:'getAreas'},
        success:function(res){
            var data = JSON.parse(res);
            option = '<option value=""></option>';
            data.forEach(function(item){
                option+= '<option value='+item[0]+' >'+ item[1]+'</option>';
            });
            select.append(option);
        },
        error:function(xhr,error ){
            console.log(error+' '+xhr);
        }
    })
}

function eliminarExtension(id_extension){
    swal({
        title: "Eliminar Extensión",
        text: "¿Está seguro que desea eliminar la extensión?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si, eliminar",
        showLoaderOnConfirm: true,
        closeOnConfirm: false
    },function(isConfirm){
        if (isConfirm) {
            $.ajax({
                type:"POST",
                url:"../datos/datos.extensionesTelefonicas.php",
                data:{tip:'eliminarExtension',id_extension:id_extension},
                datatype:"JSON",
                success:function(res){
                    var data = JSON.parse(res);
                    if(data["CODERROR"]=="0"){
                        swal("Éxito!", data["MSGERROR"], "success");
                        cargarDatatable();
                    }else{
                        swal("Error!", data["MSGERROR"], "error");
                    }
                    //
                },
                error: function(xhr, error){
                    console.log(xhr+' '+error);
                }

            });
        }
    });
}

function limpiarFormulario(formulario){
    var controlesFormulario = formulario.find(".form-group").children('select,textarea,input[type="text"]');
    controlesFormulario.val('');
}

