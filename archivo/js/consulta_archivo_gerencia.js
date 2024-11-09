const tblArchivosGerencia = $("#tblArchivosGerencia");
const tblArchivosGerenciaBody = $('#tblArchivosGerencia tbody');
var table = {};
const modalHistorico = $("#modalHistorico");
const modalActualizacion = $("#modalActualizacion");
const form = $("form");
const dtFechaDocumento = $("#dtFechaDocumento");

const arraySubcarpetas = [
    {carpeta:'ACTAS',subcarpeta:'ACTAS DEL CONSEJO DE ADMINISTRACION'},
    {carpeta:'ACTAS',subcarpeta:'ACTAS DEL CONSORCIO ACEA – ACEA DOMINICANA'},
    {carpeta:'ACTAS',subcarpeta:'ACTAS DE LA ASAMBLEA GENERAL ORDINARIA'},
    {carpeta:'ACTAS',subcarpeta:'ACTAS DE LA ASAMBLEA GENERAL EXTRAORDINARIA'},
    {carpeta:'ACTAS',subcarpeta:'ACTAS GENERAL CONSTITUTIVA'},
    {carpeta:'DOUMENTOS BASE',subcarpeta:'REGISTRO MERCANTIL'},
    {carpeta:'DOUMENTOS BASE',subcarpeta:'ESTATUTOS'},
    {carpeta:'DOUMENTOS BASE',subcarpeta:'CERTIFICADOS'},
    {carpeta:'DOUMENTOS BASE',subcarpeta:' REGISTRO DE PROVEEDORES DEL ESTADO (RPE)'},
    {carpeta:'CAASD',subcarpeta:''},
    {carpeta:'CORAABO',subcarpeta:''},
    {carpeta:'BANCO DE RESERVAS',subcarpeta:''},
    {carpeta:'DGII',subcarpeta:''},
    {carpeta:'ORDENES DE COMPRAS',subcarpeta:''},
    {carpeta:'SOLICITUD DE CLIENTE',subcarpeta:''},
    {carpeta:'ABOGADOS',subcarpeta:'JULIA FAÑA'},
    {carpeta:'ABOGADOS',subcarpeta:'LUIS SOTO'},
    {carpeta:'AUDITORES',subcarpeta:'KPMG'},
    {carpeta:'AUDITORES',subcarpeta:'PWC'},
    {carpeta:'AUDITORES',subcarpeta:'ERNEST & YOUNG'},
    {carpeta:'AUDITORES',subcarpeta:'EY'},
    {carpeta:'CONTABILIDAD',subcarpeta:''},
    {carpeta:'FAX',subcarpeta:''},

];
const groupSubcarpeta = $("#groupSubcarpeta");

/*Campos formulario*/
const hdnArchivo = $("#hdnArchivo");
const hdnIdArchivo = $("#hdnIdArchivo");
const nmbProtocolo = $('#nmbProtocolo');
const slcEmpresas = $("#slcEmpresas");
const slcTipoDocumento = $("#slcTipoDocumento");
const slcTipoCorrespondencia = $("#slcTipoCorrespondencia");
const txtAsunto = $("#txtAsunto");
const slcCarpeta  = $("#slcCarpeta");
const slcSubcarpeta  = $("#slcSubcarpeta");
const btnVerDocumento = $("#btnVerDocumento");
/*Campos formulario*/

const rutaServer = '../pdf/GERENCIA/';

//Funciones
getArchivos = ()=>{
    let options = {url:'../datos/datos.consulta_archivo_gerencia.php',method:'POST',data:{tip:'getArchivos'},dataType:'json'};
    $.ajax(options).done((res)=>{
        if(res.message_code == 200)
            this.llenarDatatable(res.data);
        else
            console.error(res.message);

    }).fail(error=>console.error(error));
};

llenarDatatable = (dataSet)=>{

    let options = { data:dataSet,
        columns:[
            {data:'id'},
            {data:'numero_protocolo'},
            {data:'asunto'},
            {data:'empresa'},
            {data:'tipo_correspondencia'},
            {data:'tipo_documento'},
            {data:'fecha_documento'},
            {data:'ruta_archivo'},
            {data:'usuario_carga'},
            {data:'fecha_carga'},
            {
                "className":'tdAcciones',
                "data": "null",
                "defaultContent":"<button class='btn btn-primary' id='btnVisualizarArchivo' title='Visualizar archivo'><i class='fas fa-eye'></i></button> <button class='btn btn-primary' id='btnHistorico' title='Histórico'><i class='fas fa-history'></i></button> <button class='btn btn-primary' id='btnActualizar' title='Actualizar'><i class='fas fa-edit'></i></button> <button id='btnEliminar' class='btn btn-primary' title='Eliminar'><i class='fas fa-trash-alt'></i></button>"
            }
        ],
        columnDefs:[
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 7 ],
                "visible": false,
                "searchable": false
            }
        ],
        'info':     false,
        "language":  {url: '../../js/DataTables-1.10.15/Spanish.json'}};

    if ( $.fn.dataTable.isDataTable( '#tblArchivosGerencia' ) ) {
        table.destroy();
    }

    table = tblArchivosGerencia.DataTable(options);
};

getHistoricoArchivo = (idArchivo)=>{
    let options = {url:'../datos/datos.consulta_archivo_gerencia.php', method:'POST',data:{tip:'getHistorico',id_archivo:idArchivo},dataType:'json'};
    $.ajax(options).done((res)=> {
            if (res.status == 200)
                this.tablaHistorico(res.data);
        }
    ).fail((error)=>{
        console.error(error);
    });
};

tablaHistorico = (data)=>{
    $('#tblHistorico tbody').empty();
    data.forEach((historico)=>{
        let tr = `<tr>
                    <td>${historico.numero_fila}</td>
                    <td>${historico.numero_protocolo}</td>  
                    <td>${historico.asunto}</td>  
                    <td>${historico.tipo_correspondencia}</td>  
                    <td>${historico.empresa}</td>  
                    <td>${historico.documento}</td>  
                    <td>${historico.fecha_documento}</td>  
                    <td>${historico.usuario_carga}</td>  
                    <td>${historico.fecha_carga}</td>
                    <td>${historico.archivo_cambia}</td>
                  </tr>`;

        $('#tblHistorico tbody').append(tr);
    });

};

getEmpresas = ()=>{
    let options = {url:'../datos/datos.ingresa_archivo_gerencia.php',method:'POST',data:{tip:'getEmpresas'},datatype:'json'};
    $.ajax(options).done((res)=>{
        let json = JSON.parse(res);
        let empresas = json.data;
        slcEmpresas.empty();
        slcEmpresas.append(new Option('Seleccione una empresa',''));
        empresas.forEach(empresa=>{
            slcEmpresas.append(new Option(empresa.desc_empresa,empresa.id_empresa));
        });
    }).fail((error)=>console.error(error));
};

getDocumentos = ()=>{
    let options = {url:'../datos/datos.ingresa_archivo_gerencia.php',method:'POST',data:{tip:'getDocumentos'},datatype:'json'};
    $.ajax(options).done((res)=>{
        let json = JSON.parse(res);
        let documentos = json.data;
        slcTipoDocumento.empty();
        slcTipoDocumento.append(new Option('Seleccione un tipo de documento',''));
        documentos.forEach(documento=>{
            slcTipoDocumento.append(new Option(documento.descripcion,documento.id));
        });
    }).fail((error)=>console.error(error));
};

getSubcarpetas = (carpeta)=>{
    return arraySubcarpetas.filter((subcarpeta=>subcarpeta.carpeta == carpeta));
};

getArchivo = (idArchivo)=>{
    let options = {url:'../datos/datos.consulta_archivo_gerencia.php',method:'POST',data:{tip:'getDatosArchivo',id_archivo_gerencia:idArchivo},dataType:'json'};
    $.ajax(options).done((res)=> {

        if(res.message_code == 200)
            llenarFormularioActualizacion(res.data);
        else
            console.error(res.message);

    }).fail((error)=>{
        console.error(error);
    });
};

llenarFormularioActualizacion = (archivo)=>{

    /*Relleno de formulario*/
    hdnArchivo.val(archivo.ruta_archivo);
    hdnIdArchivo.val(archivo.id);
    nmbProtocolo.val(archivo.numero_protocolo);
    slcEmpresas.val(archivo.id_empresa);
    dtFechaDocumento.val(archivo.fecha_documento);

    /**Un usuario no debe cambiar el tipo de correspondencia desde "Enviado" o "Recibido" a "Otros" y viceversa.
     * Se estableció que desde un principio que si al momento de insertar un nuevo documento el tipo de correspondencia es "Otros" entonces no se debe especificar un número de protocolo. */
    if(archivo.tipo_correspondencia == "Otros"){
        $('#slcTipoCorrespondencia option[value=Otros]').css('display','block');
        slcTipoCorrespondencia.prop('disabled',true);
    } else{
        slcTipoCorrespondencia.prop('disabled',false);
        $('#slcTipoCorrespondencia option[value=Otros]').css('display','none');
    }

    slcTipoCorrespondencia.val(archivo.tipo_correspondencia);
    txtAsunto.val(archivo.asunto);
    slcTipoDocumento.val(archivo.id_tipo_documento);

    let rutaArchivoSplit = archivo.ruta_archivo.split('/');
    let carpeta = rutaArchivoSplit[2].toUpperCase();
    let subcarpeta = rutaArchivoSplit[3].toUpperCase();

    slcCarpeta.val(carpeta).change();
    slcSubcarpeta.val(subcarpeta);

    //Se elimina el evento click del botón porque si la ruta fue modificada anteriormente por el usuario, el botón se mantiene con la ruta anterior.
    btnVerDocumento.off('click');

    //Se crea el evento click del botón de visualización de documentos.
    btnVerDocumento.on('click',(e)=>{
        e.preventDefault();
        let ruta = rutaServer + archivo.ruta_archivo;
        window.open(ruta,'_blank')
    });
};

inputFileRequired = (rutaPrevia, carpetaSeleccionada, subcarpetaSeleccionada)=>{
    /*@Params:
    *   rutaPrevia: Es la ruta del archivo que fue subido antes de realiar la modificacíón de los datos del mismo.
    * */

    let rutaPreviaSplit = rutaPrevia.split('/');
    let carpetaPrevia = rutaPreviaSplit[2];
    let subcarpetaPrevia = rutaPreviaSplit[3];

    return carpetaPrevia != carpetaSeleccionada || subcarpetaPrevia != subcarpetaSeleccionada;
};

eliminarArchivo = (idArchivo)=>{
  let options = {url:'../datos/datos.consulta_archivo_gerencia.php',method:'POST', data:{tip:'eliminarArchivo',id_archivo:idArchivo},dataType:'json'};

  $.ajax(options).done((res)=>{
      if(res.message_code == 0){
          swal("Mensaje!", res.message, 'success');
          getArchivos();
          return true;
      }else{
          swal("Mensaje!", res.message, 'error');
          return false;
      }}
  ).fail((error)=>{
      swal("Mensaje!", 'Ha ocurrido un error al intentar eliminar información del archivo.', "error");
      console.error(error)
  });
};

//Eventos
tblArchivosGerenciaBody.on('click','#btnVisualizarArchivo', function (e) {
    compSession(()=>{
        e.preventDefault();
        var data_row = table.row($(this).closest('tr')).data();
        let ruta = rutaServer + data_row.ruta_archivo;
        window.open(ruta);
    });
});

tblArchivosGerenciaBody.on('click','#btnHistorico', function (e) {
    compSession(()=>{
        e.preventDefault();
        var data_row = table.row($(this).closest('tr')).data();
        let idArchivo = data_row.id;
        getHistoricoArchivo(idArchivo);
        modalHistorico.modal('show');
    });
});

tblArchivosGerenciaBody.on('click','#btnActualizar', function (e) {
    compSession(()=>{
        e.preventDefault();
        getEmpresas();
        getDocumentos();
        var data_row = table.row($(this).closest('tr')).data();
        getArchivo(data_row.id);
        modalActualizacion.modal('show');
    });
});

tblArchivosGerenciaBody.on('click','#btnEliminar',function(e){
    compSession(()=>{
       e.preventDefault();
        var data_row = table.row($(this).closest('tr')).data();
        let idArchivo = data_row.id;

        let options = {title: "¿Está segur@ que desea eliminar este archivo?",
                    text: "Si elimina este archivo no podrá recuperarlo.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Sí, eliminarlo!",
                    cancelButtonText: "No, no eliminarlo!",
                    closeOnConfirm: false};

        swal(options,()=>{
            eliminarArchivo(idArchivo);
        });
    });
});

slcCarpeta.on('change',()=>{
    let carpetaSeleccionda = slcCarpeta.val();
    let subcarpetas = getSubcarpetas(carpetaSeleccionda);
    slcSubcarpeta.empty();
    slcSubcarpeta.append(new Option('Seleccione una subcarpeta','',true));
    subcarpetas.forEach((subcarpeta)=>{

        if(subcarpetas.length == 1 && subcarpeta.subcarpeta == ''){
            slcSubcarpeta.prop('required',false);
            groupSubcarpeta.css('display','none');
            return true;
        }
        slcSubcarpeta.prop('required',true);
        groupSubcarpeta.css('display','block');
        slcSubcarpeta.append(new Option(subcarpeta.subcarpeta,subcarpeta.subcarpeta));
    });


});

slcCarpeta.on('click',()=>{
    fileRequired = inputFileRequired(hdnArchivo.val(), slcCarpeta.val(), slcSubcarpeta.val());
    //inputArchivo.prop('required',false);
});

slcSubcarpeta.on('click',()=>{
    fileRequired = inputFileRequired(hdnArchivo.val(), slcCarpeta.val(), slcSubcarpeta.val());
    //inputArchivo.prop('required',false);
});

form.on('submit',(e)=> {

    //compSession(() => {
        let file = {};
        if (form[0][13].files.length > 0) {
            file = form[0][13].files[0];
        }

        e.preventDefault();
        let formData = new FormData();
        //Anexar datos a form data.
        formData.append('tip', 'actualizarArchivo');
        formData.append('ruta_archivo', hdnArchivo.val());
        formData.append('numero_protocolo', nmbProtocolo.val());
        formData.append('tipo_correspondencia', slcTipoCorrespondencia.val());
        formData.append('tipo_documento', slcTipoDocumento.val());
        formData.append('fecha_documento', dtFechaDocumento.val());
        formData.append('carpeta', slcCarpeta.val());
        formData.append('subcarpeta', slcSubcarpeta.val());
        formData.append('archivo', file);
        formData.append('asunto', txtAsunto.val());
        formData.append('id_empresa', slcEmpresas.val());
        formData.append('id_archivo', hdnIdArchivo.val());

        let options = {
            url: '../datos/datos.consulta_archivo_gerencia.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false
        };
        $.ajax(options).done((res) => {
            if (res.status == 200) {
                modalActualizacion.modal('hide');
                swal("Mensaje!", res.message, 'success');
                form.trigger("reset");
                getArchivos();
            } else
                swal("Mensaje!", res.message, 'error');
        }).fail((error) =>{
            swal("Mensaje!", 'Ha ocurrido un error al intentar actualizar información del archivo.', "error");
            console.error(error)
        } );

    //});
});

    $(document).ready(function () {
        compSession(() => {
            getArchivos();
        });
    });

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
                    //$("#rutPdcSelPro").focus(false);
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

                        function (inputValue) {
                            if (inputValue === false)
                                return false;
                            if ($("#usr").val() == "") {
                                swal.showInputError("El usuario no puede ser vacio !");
                                return false
                            } else if ($("#pass").val() == "") {
                                swal.showInputError("La contraseña no puede ser vacia !");
                                return false
                            }

                            else
                                iniSes();
                        }
                    );
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

    function iniSes() {
        $.ajax
        ({
            url: '../../configuraciones/session.php',
            type: 'POST',
            data: {tip: 'iniSes', pas: $("#pass").val(), usu: $("#usr").val()},
            dataType: 'text',
            success: function (json) {
                if (json == "true") {
                    swal("Loggin Exitoso!")
                } else if (json == "false") {
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
                        function (i) {
                            if (i === false)
                                return false;
                            if ($("#usr").val() == "") {
                                swal.showInputError("El usuario no puede ser vacio !");
                                return false
                            } else if ($("#pass").val() == "") {
                                swal.showInputError("La contraseña no puede ser vacia !");
                                return false
                            }

                            else {
                                iniSes();
                            }
                        });
                }
            },
            error: function (xhr, status) {
                alert("error");
                return false;
            }
        });

    }




