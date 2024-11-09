const btnGenerarReporte = $("#btnGenerarReporte");
const frmEncuestaSatisfaccionUsuarios = $("#frmEncuestaSatisfaccionUsuarios");
const slcProyecto = $('#proyecto');
const tblSatisfaccionUsuario = $("#tblSatisfaccionUsuario");
const btnExportarExcelResumen = $("#btnExportarExcelResumen");
const dvResumenSatisfaccionUsuarios = $("#dvResumenSatisfaccionUsuarios");
const tblResumenSatisfaccionUsuarios = $("#dvResumenSatisfaccionUsuarios table");
//var btnImprimirEncuesta = $("#btnImprimirEncuesta");
var proyecto = null;
var fechaDesde = null;
var fechaHasta = null;
var arrayProyectos = [];

/*Eventos*/
$(document).ready(()=>{
    compSession(()=>{
        obtenerProyectos();
    })
});

frmEncuestaSatisfaccionUsuarios.on('submit',(e)=>{
    e.preventDefault();
    compSession(()=>{
        proyecto = frmEncuestaSatisfaccionUsuarios[0].proyecto.value;
        fechaDesde = frmEncuestaSatisfaccionUsuarios[0].fecha_desde.value;
        fechaHasta = frmEncuestaSatisfaccionUsuarios[0].fecha_hasta.value;


        let options = {url:'../datos/datos.EncuestaSatisfaccionUsuarios.php',method:'POST',dataType:'json',data:{tip:'getEncuestas',proyecto:proyecto, fecha_desde:fechaDesde, fecha_hasta:fechaHasta}};

        $.ajax(options).done((encuestas)=>{
            if(encuestas.data.length > 0){
                rellenarDatatable(encuestas.data);
            }
        }).fail((error)=>{
            console.error(error);
        });
    });

});

tblSatisfaccionUsuario.on('click','.btnVisualizarEncuesta',function(){
    compSession(()=>{
        var idEncuesta = $(this).closest('tr').find('td:eq(0)').text(); // amend the index as needed
        detalleReporte(idEncuesta);
    });
});

btnExportarExcelResumen.on('click',()=>{
    compSession(()=>{
        getReporteParaImprimir(proyecto,fechaDesde,fechaHasta);
    });
});
/*Eventos*/

/*Funciones*/
obtenerProyectos = ()=>{
//Obtener los proyectos (CAASD, CORAABO).

  let options = {method:'POST',url:'../datos/datos.EncuestaSatisfaccionUsuarios.php',data:{tip:'getProyectos'},dataType:'json'};
  $.ajax(options).done((proyectos)=>{
      proyectos.forEach((proyecto)=>{
          arrayProyectos.push({codigo:proyecto.CODIGO,descripcion:proyecto.DESCRIPCION});
          slcProyecto.append(new Option(proyecto.DESCRIPCION,proyecto.CODIGO));
      });
  }).fail((error)=>{
      console.error(error);
  });
};

rellenarDatatable = (data)=>{

    $("#pNoExistenDatos").css('display','none');
    tblSatisfaccionUsuario.css('display','table');
    btnExportarExcelResumen.css('display','inline-block');

    let options = {
        data:data,
        columns: [
            { data: 'ID'},
            { data: 'ENCUESTADOR'},
            { data: 'CLIENTE'},
            { data: 'OFICINA'},
            { data: 'FECHA'},
            { data: 'VISUALIZAR'},
        ],"language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
        }};
    tblSatisfaccionUsuario.DataTable(options);
};

detalleReporte = (idEncuesta)=>{
    let options = {method:'POST',url:'../reportes/reporte.EncuestaSatisfaccionUsuarios.php',data:{id_encuesta:idEncuesta}};
    $.ajax(options).done((detalleEncuesta)=>{
        $(".modal-body").empty();
        $(".modal-body").append(detalleEncuesta);
        //se crea el botón para imrpimir el contenido del modal.
        let btnImprimirEncuesta = $("<button>",{id:'btnImprimirEncuesta',class:'btn btn-primary',text:'Imprimir'}).click(()=> {
                compSession(()=>{
                    printElement(document.getElementById('dvDetalleEncuesta'));
                });
            }
        );
        $(".modal-body").prepend(btnImprimirEncuesta);
        $("#modal").modal('show');
    }).fail((error)=>{
        console.error(error);
    });
};

getReporteParaImprimir = (proyecto, fechaDesde, fechaHasta)=>{
    //Obtiene los datos del reporte que se utilizará para ser impreso.
    // Se coloca en pantalla y se dispara la función que genera el archivo Excel.
    let options = {method:'POST',url:'../datos/datos.EncuestaSatisfaccionUsuarios.php',data:{tip:'getReporteParaImprimir',proyecto:proyecto,fecha_desde:fechaDesde,fecha_hasta:fechaHasta},dataType:'json'};
    $.ajax(options).done((json)=>{
        rellenarTablaResumen(json);
        exportF("dvResumenSatisfaccionUsuarios","Resumen Encuesta Satisfaccion Usuarios "+proyecto+" "+fechaDesde+"-"+fechaHasta);
    }).fail((error)=>{
        console.error(error);
    });
};

rellenarTablaResumen =(json)=>{

    let proyectoEncontrado = arrayProyectos.filter((dataProyecto)=>dataProyecto.codigo == proyecto);
    dvResumenSatisfaccionUsuarios.prepend(`<h5 style="text-align: center;font-size:14px;font-weight: bold;">Resumen de Encuesta Satisfacción Usuarios ${proyectoEncontrado[0].descripcion} desde ${fechaDesde} hasta ${fechaHasta}</h5>`);
    let tbody = tblResumenSatisfaccionUsuarios.children('tbody');
    tbody.empty();
    json.data.forEach((encuesta,index)=>{

        let numeroFila = index+1;
        let backgroundGray = 'white';
        if(numeroFila%2 > 0){
            backgroundGray = '#eee';
        }

        let tr = $(`<tr style="border: 1px solid black; background: ${backgroundGray}">`);
        tr.append(`<td>${numeroFila}</td>`);
        tr.append(`<td>${encuesta.fecha}</td>`);
        tr.append(`<td>${encuesta.oficina}</td>`);
        tr.append(`<td>${encuesta.cliente}</td>`);
        tr.append(`<td>${encuesta.encuestador}</td>`);

        encuesta.preguntas.forEach((pregunta)=>{
            let respuesta = pregunta.respuesta;
            tr.append(`<td>${respuesta}</td>`);
        });

        tbody.append(tr);
    });
};

exportF = (elementId,nombreArchivo = 'export') => {
    var table = document.getElementById(elementId);
    var html = table.outerHTML;
    var url = 'data:application/vnd.ms-excel,' + escape(html); // Set your html table into url
    var elem = document.createElement('a');
    elem.setAttribute("href", url);
    elem.setAttribute("download", nombreArchivo+'.xls'); // Choose the file name
    elem.click();
    return false;
};

printElement = (elem)=> {
    //Función que imprime un elemento deteminado.
    //Se deben especificar las propiedades de la impresión en el archivo CSS dentro del media Print{}
    var domClone = elem.cloneNode(true);

    var $printSection = document.getElementById("printSection");

    if (!$printSection) {
        var $printSection = document.createElement("div");
        $printSection.id = "printSection";
        document.body.appendChild($printSection);
    }
    $printSection.innerHTML = "";
    $printSection.appendChild(domClone);
    window.print();
};

/*
compSession = (callback) =>{
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
                $("#genHojMedCorSelPro").focus(false);
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
            console.error(JSON.stringify(xhr) + ' ' + status);
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
};
*/

/*Funciones*/