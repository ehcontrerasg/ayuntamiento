/**
 * Created by Algenis Mosquea on 12/01/2018.
 */

var selProyEstPqr;
var inpFecIniEstPqr;
var inpFecFinEstPqr;
var butGenEstPqr;
var genRepForm;
var inpHidValRes;
var motivo_pqr;

$(document).ready(function(){
    repEstPqrSelPro();
    selProyEstPqr=document.getElementById("proyecto");
    inpFecIniEstPqr=document.getElementById("idFechaIn");
    inpFecFinEstPqr=document.getElementById("idFechaFn");
    inpHidValRes=document.getElementById("inpHidValRes");
    motivo_pqr=document.getElementById("inpMotivoPqr");
    butGenEstPqr=document.getElementById("butGenEstPqr");
    genRepForm=document.getElementById("btnGenerar");



});



function repEstPqrSelPro(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="";
            selProyEstPqr.add(option,selProyEstPqr[0])
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["ID_PROYECTO"]);
                option.text=datos[x]["SIGLA_PROYECTO"];
                selProyEstPqr.add(option,selProyEstPqr[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.datRepEstPqr.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=selPro");
}

function repQuejasSugerencias(){



            if (validaCampos()) {

                swal
                ({
                        title: "Advertencia!",
                        text: "El reporte demorara unos minutos en salir.",
                        showConfirmButton: true,
                        showCancelButton: true,
                        showLoaderOnConfirm: true,
                        closeOnConfirm: false,
                        closeOnCancel: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            compSession(genRepPqrsReSol);
                        }
                    });
           }
      //  });
}



function validaCampos(){
    console.log("valida");


    if(selProyEstPqr.selectedIndex==0){
        selProyEstPqr.focus();
        swal("Error!", "Debe seleccionar el proyecto", "error");
        return false;
    }

    if(inpFecIniEstPqr.value=="" ){
        inpFecFinEstPqr.focus();
        swal("Error!", "Debe indicar una fecha inicial", "error");
        return false;
    }

    if( inpFecFinEstPqr.value==""){
		inpFecIniEstPqr.focus();
        swal("Error!", "Debe indicar una fecha final", "error");
        return false;
    }



    return true;
}


function genRepPqrsReSol(muestraMesnaje='S')
{

    var proyecto =$("#proyecto option:selected").text();
    var datos=$("#formQuejasSugerencias").serializeArray();
    datos.push({name:"tip",value:"quejasSugerencias"});
    $.ajax
    ({

        url : '../datos/datos.form_quejas_sugerencias.php',
        type : 'POST',
        dataType : 'json',
        data : datos ,
        success : function(res) {

            if (muestraMesnaje==='S')
                swal("Mensaje!", "Has Generado correctamente el reporte", "success");

            if ( $.fn.dataTable.isDataTable( '#dataTable' ) ) {
                $('#dataTable').DataTable().destroy();
            }

            table = $('#dataTable').DataTable( {
                dom: 'lBfrtip',
                columns: [
                    {title: "CODIGO FORMULARIO", name:'codFormulario'}, // 0
                    {title: "CODIGO INMUENLE", name:'inmueble'}, // 1
                    {title: "DIRECCION", name:'DIRECCION'}, // 2
                    {title: "NOMBRE",name:'nombre'}, // 3
                    {title: "CEDULA",name:'CEDULA'}, // 4
                    {title: "CELULAR",name:'CELULAR'}, // 5
                    {title: "TELEFONO",name:'TELEFONO'}, // 6
                    {title: "EMAIL",name:'EMAIL'}, // 7
                    {title: "OFICINA",name:'oficina'}, // 8
                    {title: "FECHA",name:'fecha'}, // 9
                    {title: "QUEJA",name:'queja', width: 10}, // 10
                    {title: "SUGERENCIA",name:'sugerencia', width: 10 }, // 11
                    {title: "GERENCIA",name:'gerencia', width: 10 }, // 12
                    {title: "PREGUNTA",name:'pregunta'}, // 13
                    {title: "RESPUESTA",name:'respuesta'}, // 14
                    {title: "",name:'accion'}, // 15
                    {title: "",name:'accionSelect'}, //  16

                ],
               "columnDefs": [
                    {
                        "targets": [ 0 ],
                        "visible": false,
                        "searchable": false
                    },
                   {
                        "targets": [ 1 ],
                        "visible": true,
                        "searchable": true
                    },
                   {
                        "targets": [ 2 ],
                        "visible": false,
                        "searchable": false
                    },
                    {
                        "targets": [ 3 ],
                        "visible": true
                    },
                    {
                        "targets": [ 4 ],
                        "visible": false
                    } ,
                    {
                        "targets": [ 5 ],
                        "visible": false
                    },
                    {
                        "targets": [ 6 ],
                        "visible": false
                    },
                   {
                        "targets": [ 7 ],
                        "visible": false
                    },
                    {
                        "targets": [ 9],
                        "visible": true

                    },
                    {
                        "targets": [ 10 ],
                        "visible": true
                    },  {
                        "targets": [ 11 ],
                        "visible": true
                    },
                   {
                        "targets": [ 12 ],
                        "visible": false
                    },
                   {
                        "targets": [ 13 ],
                        "visible": false
                    },
                   {
                        "targets": [ 14 ],
                        "visible": false
                    },

                   {
                        "targets": [ 15 ],
                        "visible": true
                    },
                   {
                       render: function (data, type, full, meta) {
                           if (data===null)data="";
                           return "<div class='text-wrap' id='qs'>"+data+"</div>";
                       },
                       targets: 10
                   },
                   {
                       render: function (data, type, full, meta) {
                           if (data===null)data="";
                           return "<div class='text-wrap' id='qs'>"+data+"</div>";
                       },
                       targets: 11
                   },

                   { width: '10px', targets: 9 },
                   { width: '10px', targets: 10 }

               ],
                data: res,
                rowsGroup : [// Always the array (!) of the column-selectors in specified order to which rows groupping is applied
                // (column-selector could be any of specified in https://datatables.net/reference/type/column-selector)
                'inmueble:name', 'nombre:name', 'oficina:name', 'fecha:name', 'queja:name', 'sugerencia:name', 'accion:name', 'accionSelect:name', 'CEDULA:name', 'DIRECCION:name', 'CELULAR:name', 'TELEFONO:name',
            ],
                buttons: [
                    { extend: 'copy', text:' Copiar', className: 'btn btn-primary glyphicon glyphicon-duplicate', exportOptions: {
                            columns: [ 1,2,3,4,5,6,7,8,9,12,13,14 ],orthogonal: 'export'
                        },
                        title: 'Reporte Formularios de Quejas y Sugerencias '+proyecto  },
                    { extend: 'csv',  text:' CSV', className: 'btn btn-primary glyphicon glyphicon-save-file',   exportOptions: {
                            columns: [ 1,2,3,4,5,6,7,8,9,12,13,14 ],orthogonal: 'export'
                        },
                        title: 'Reporte Formularios de Quejas y Sugerencias '+proyecto  },

                    {title: "CODIGO FORMULARIO", name:'codFormulario'}, // 0
                    {title: "CODIGO INMUEBLE", name:'inmueble'}, // 1
                    {title: "DIRECCION", name:'DIRECCION'}, // 2
                    {title: "NOMBRE",name:'nombre'}, // 3
                    {title: "CEDULA",name:'CEDULA'}, // 4
                    {title: "CELULAR",name:'CELULAR'}, // 5
                    {title: "TELEFONO",name:'TELEFONO'}, // 6
                    {title: "EMAIL",name:'EMAIL'}, // 7
                    {title: "OFICINA",name:'oficina'}, // 8
                    {title: "FECHA",name:'fecha'}, // 9
                    {title: "QUEJA",name:'queja'}, // 10
                    {title: "SUGERENCIA",name:'sugerencia'}, // 11
                    {title: "GERENCIA",name:'gerencia' }, // 12
                    {title: "PREGUNTA",name:'pregunta'}, // 13
                    {title: "RESPUESTA",name:'respuesta'}, // 14
                    {title: "",name:'accion'}, // 15
                    {title: "",name:'accionSelect'}, //  16

                    {
                        extend: 'excel',text:' Excel',
                        className: 'btn btn-primary glyphicon glyphicon-list-alt',
                        title: 'Reporte Formularios de Quejas y Sugerencias '+proyecto, exportOptions: {
                            columns: [ 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15 ]
                        }


                    },

                    {extend:'print',text:' Imprimir', className: 'btn btn-primary glyphicon glyphicon-print',
                        exportOptions: {
                            columns: [ 1,2,3,4,5,6,7,8,9,10,11,12,13,14 ],orthogonal: 'export'
                        },
                        title: 'Reporte Formularios de Quejas y Sugerencias '+proyecto },

                    { extend: 'pdfHtml5',   exportOptions: {
                            columns: [ 1,2,3,4,5,6,7,8,9,10,11,12,13,14 ],orthogonal: 'export'
                        },
                        title: 'Reporte Formularios de Quejas y Sugerencias '+proyecto,
                        pageSize: 'A3',//A0 is the largest A5 smallest(A0,A1,A2,A3,legal,A4,A5,letter))
                        fontSize:'5',
                        className: 'btn btn-primary glyphicon glyphicon-file',
                        customize: function ( doc ) {

                            // Para el formato del titulo
                            doc.styles.title = {

                                fontSize: '14',
                                alignment: 'center',
                                margin: [ 0, 0, 0, 0 ]

                            };

                        }
                    },
                ],


                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                "info": false,
                "paging": false,
                "ordering": false,
                "searching": true,
                "scrollY":        "400px",
                "scrollCollapse": true,
                "language":  {url: '../../js/DataTables-1.10.15/Spanish.json'}

            });
            obtener_data_editar("#dataTable tbody", table);

            $('#dataTable').show();

        },

        error : function(xhr, status) {
            swal
            (
                {
                    title: "Error",
                    text: "Se ha producido un error inesperado. Comuniquese con sistema. status: "+status,
                    type: "error",
                    html: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ok!",
                    closeOnConfirm: true});
        }

    });

}

function obtener_data_editar (tbody, table) {

    $(tbody).on("change", "select[name = 'selectMotivo']", function () {

        var data = table.row($(this).closest('tr')).data();
        var datosPQR=[];
        var codigoFormulario = data[0];
        var motivoID=$('#motivo'+codigoFormulario).val();
        var motivoDescription=$("#motivo"+codigoFormulario+" option:selected").text();
        var queja=data[10];
        var sugerencia=data[11];
        var descripcion;
        var telefono=data[6];
        var celular=data[5];


        if (queja==null)
            descripcion=sugerencia;
        else if (sugerencia==null)
            descripcion=queja;
        else
            descripcion='Queja:'+queja+' | Sugerencia:'+sugerencia;

        if (telefono==null)
            telefono=celular;

        datosPQR.push({name:'tip',value: 'ingresaPqr'});
        datosPQR.push({name:'medio',value: 'VL'});
        datosPQR.push({name:'tipo',value: 3});
        datosPQR.push({name:'cod_inmueble',value: data[1]});
        datosPQR.push({name:'direccion',value: data[2]});
        datosPQR.push({name:'nom_cli',value: data[3]});
        datosPQR.push({name:'ced_cli',value: data[4]});
        datosPQR.push({name:'celular',value: celular});
        datosPQR.push({name:'tel_cli',value: telefono});
        datosPQR.push({name:'mail_cli',value: data[7]});
        datosPQR.push({name:'ger_inm',value: data[12]});
        datosPQR.push({name:'descripcion',value:descripcion});
        datosPQR.push({name:'codigoFormulario',value: codigoFormulario});
        datosPQR.push({name:'motivo',value: motivoID});
        datosPQR.push({name:'motivoDescription',value: motivoDescription});


        if (motivoID!=='')
        {

            swal
            ({
                    title: "Advertencia!",
                    text: "¿Desea guardar como PQR esta queja y sugerencia con el Motivo PQR: " + motivoDescription  + "?.",
                    showConfirmButton: true,
                    showCancelButton: true,
                    cancelButtonText:'Cancelar',
                   confirmButtonText:'Aceptar',
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function (isConfirm) {
                    if (isConfirm) {
                        ingresaPQR( datosPQR);
                    }

                });
        }


    });
}

function ingresaPQR(datosPQR)
{

    $.ajax
    ({
        url: '../datos/datos.form_quejas_sugerencias.php',
        type: 'POST',
        dataType: 'json',
        data: datosPQR,
        success: function (res) {

            if (res["error"]) {
                genRepPqrsReSol('N');
                mensajeCustom(res["mensaje"],'Error','error');
            } else if (res["error"]===false) {
                genRepPqrsReSol('N');
                mensajeCustom(res["mensaje"],'Registro satisfactorio','success');
            } else {
                swal
                ({
                    title: "Error",
                    text: "Se ha producido un error inesperado. Comuniquese con sistema",
                    icon: 'error',
                    showConfirmButton: true,
                    showCancelButton: false,
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Aceptar',
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false,
                    closeOnCancel: true
                });
            }


        },
        error: function (xhr, status) {
            swal
            (
                {
                    title: "Error",
                    text: "Se ha producido un error inesperado. Comuniquese con sistema. status: "+status,
                    type: "error",
                    html: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ok!",
                    closeOnConfirm: true});
        }

    });
}

function mensajeCustom(msj='',titulo='',icono='') {

   swal(titulo, msj, icono)

}

function PrintElem(elem)
{

   var container = document.getElementById(elem); // full page
    html2canvas(container,{allowTaint : true
   }).then(function(canvas) {

       var img = document.createElement("IMG");

        var canvas2 = document.querySelector("canvas");

        img.src = canvas.toDataURL("image/png");

        $('#vistaFormularioPrint').empty();
        $('#vistaFormularioPrint').append(img);


   var mywindow = window.open('', 'PRINT', 'height='+screen.height+',width='+screen.width);
    mywindow.document.write(document.getElementById('vistaFormularioPrint').outerHTML);
    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*!/

        setTimeout(function() {
            mywindow.print();
            mywindow.close();
        }, 250);
    });

}


function mostrarModal(CodFormulario){
    var datos=[];
    var i=0;
    datos.push({name:"tip",value:"generaVista"});
    datos.push({name:"codFormulario",value:CodFormulario});

    $.ajax
    ({

        url : '../datos/datos.form_quejas_sugerencias.php',
        type : 'POST',
        dataType : 'text',
        data : datos ,
        success : function(res) {
            $('#vistaFormulario').empty();
            $('#vistaFormulario').append(res);
            var inputCodigoPR=$('#codigoPR').val().split('-');
            // console.log(inputCodigoPR);

            for ( i=0;i<inputCodigoPR.length;i++) {
                var id=inputCodigoPR[i];
                console.log(id);
                $('#id'+id).attr('checked','checked');

            }

            $('#miModal').modal('show');
        },

        error : function(xhr, status) {
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

    });
}

function verificaSession(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var datos=xmlhttp.responseText;
            if(datos=="true"){
                if(validaCampos()){
                    swal({
                            title: "Mensaje",
                            text: "El reporte puede tomar algunos minutos.\nDesea generarlo ahora?",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Si!",
                            cancelButtonText: "No!",
                            showLoaderOnConfirm: true,
                            closeOnConfirm: false,
                            closeOnCancel: true
                        },
                        function(isConfirm){
                            if (isConfirm) {
                                genRepPqrsReSol();
                            }
                        });
                }
            }else{
                swal({
                        title: "Mensaje!",
                        text: "Su sesion ha finalizado.",
                        showConfirmButton: true },
                    function(isConfirm)
                    {
                        if (isConfirm)
                        {
                            top.location.replace("../../index.php");
                        }
                    }
                );
                return false;
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.datPqrsReSolicitadas.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
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
                $("#ingResInsInpCodSis").focus(false);
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
                            $("#repRecPendSelPro").focus();
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