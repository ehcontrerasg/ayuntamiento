/*************************************
*
*	@Author : Luis Miguel Alcántara
*	@Fecha  : 22/03/2017
*
*************************************/

$(document).ready(function(){
    desContr();
    compSession();
	//buscar();	
	table = $('#dataTable').DataTable({
		"processing": "true",
		"ajax" : {
			"method" : "POST",
			"url"	 : "../datos/datos.consulta_doc_arc.php?1",
		},
		"columns":[
			{"data"	: "id"},
			{"data"	: "CODIGO_INM"},
			{"data"	: "CODIGO_ARCH"},
			//{"data"	: "DESC_AREA"},
			//{"data"	: "DESC_DOCUMENTO"},
			{"data"	: "ID_PROYECTO"},
			//{"data"	: "FECHA_DOCUMENTO"},
			//{"data"	: "OBSERVACION"},
			//{"data"	: "RUTA_ARCHIVO"},
			{"defaultContent" : "<button type='button' class='editar' data-toggle='modal' data-target='#myModal'><i class='fa fa-plus' aria-hidden='true'></i></button><button type='button' class='ver' data-toggle='modal' data-target='#listarPDFModal'><i class='fa fa-eye-slash' aria-hidden='true'></i></button><button type='button' class='info' data-toggle='modal' data-target='#infoModal'><i class='fa fa-info' aria-hidden='true'></i></button>"},
		]
	});


	obtener_data_editar("#dataTable tbody", table);
	listarPDF("#dataTable tbody", table);
    info("#dataTable tbody", table);

	$("#modDocArcForm").submit(function(e){
       $('#myModal').modal('hide');
       e.preventDefault();     
        ModDocArc();
    });

});

function ModDocArc(){
    oData = new FormData(document.forms.namedItem("modDocArcForm"));
    oData.append("tip", "ModDocArc");
     swal({
            title: "Insertar Archivo",
            text: "Esta seguro de que desea insertar este archivo?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, insertar",
            showLoaderOnConfirm: true,
            closeOnConfirm: false
        },function(isConfirm){
           if (isConfirm) {
                console.log('si');
                $.ajax({
                    url: '../datos/datos.modifica_doc_arc.php', //URL destino
                    data: oData,
                    processData: false, //Evitamos que JQuery procese los datos, daría error
                    contentType: false, //No especificamos ningún tipo de dato
                    cache: false,
                    type: 'POST',
                    success : function(json) {
                        //document.write(json);
                        //console.log(json);
                        //cargarArchivo();
                        if (json=="true"){
                           // $('#ingDocArcForm')[0].reset();
                            swal({
                                    title: "Mensaje",
                                    text: "Has ingresado el archivo correctamente.",
                                    type: "success"},
                                function(isConfirm){
                                    if (isConfirm) {
                                        $("#ingCodDoc").focus();
                                    }
                                }
                            );
                        }else{
                            swal("Mensaje!", json, "error");
                            $(this).focus();
                        }
                        table.ajax.reload();
                    }
                });
            }else{
                console.log('si');
                 swal("Cancelled", "El archivo no fue guardado :(", "error");
                $(this).focus();
            } 
        });
   
}

var obtener_data_editar = function(tbody, table) {
	$(tbody).on("click","button.editar", function(){
		var data = table.row($(this).parents("tr") ).data();
		//console.log(data.FECHA_DOCUMENTO);
		var IdRegistro = $("#IdRegistro").val(data.ID_REGISTRO);
		var	ngCodDoc = $("#ingCodDoc").val(data.CODIGO_INM);
		var	codArc = $("#codArc").val(data.CODIGO_ARCH);
		var	departamento = $("#departamento").append('<option selected>'+data.DESC_AREA+'</option>');            
		//var	documento = $("#documento").append("<option selected>"+data.DESC_DOCUMENTO.toString()+'</option>');
		//var	proyecto = $("#proyecto").val(data.ID_PROYECTO);			
		//fechaDoc = $("#fechaDoc").append(Date.parse(data.FECHA_DOCUMENTO));
		//var	observacion = $("#observacion").val(data.OBSERVACION);
		//archivo_fls = $("#archivo_fls").val(data.RUTA_ARCHIVO);
            
	});
}

function verPDF (caso, ruta, proyecto){
    // console.log('hola mundo');
    if (caso==1) {
        if(proyecto =='SD'){
            window.open('../pdf/SD/'+ruta,"Vista de registro");
        }else{
            window.open('../pdf/BC/'+ruta,"Vista de registro");
        }
    }else{
        if(proyecto =='SD'){
            window.open('../pdf/SD/'+ruta,"Vista de registro");
        }else{
            window.open('../pdf/BC/'+ruta,"Vista de registro");
        }
    }
}
   // window.open('../pdf/SD/454762.pdf',"Vista de registro");
   /**/
        

var info = function(tbody, table){
    $(tbody).on("click","button.info", function(){
        var datos = table.row($(this).parents("tr") ).data();
        var IdRegistro = (datos.CODIGO_INM);
        //console.log(IdRegistro);
        buscar(IdRegistro);     
    });
}


function buscar(id){
    var datos = 'IdRegistro='+id+'&tip=IdRegistro';
    $.ajax({
        url: '../datos/datos.info_observacion.php',
        data: datos,
        type: 'POST',
        success: function(resp) {
            //document.write(resp);
            //console.log('hola mundo');
            jresp = $.trim(resp);
            jresp = JSON.parse([resp]);
            //console.log(jresp);
            //document.write(resp);
            //llenar_table('#tblRecpPqr', jresp);
           dibujar_tbl_info ('#dataTableInfo tbody', jresp);
            
        },
        error: function(jqXHR, estado, error) {
            console.log(estado+' : '+ error);
        }
    });
}


var listarPDF = function(tbody, table){
    $(tbody).on("click","button.ver", function(){
        var data = table.row($(this).parents("tr") ).data();
        var codigo_inm = (data.CODIGO_INM);
        buscarPDF(codigo_inm);       
    });
}

function buscarPDF(id)
{
    var datos = 'codigo_inm='+id.trim()+'&tip=codigo_inm';
    $.ajax({
        url: '../datos/datos.info_observacion.php',
        data: datos,
        type: 'POST',
        success: function(resp) {
            //document.write(resp);
            //console.log('hola mundo');
            jresp = $.trim(resp);
            jresp = JSON.parse([resp]);
            //console.log(jresp);
            //document.write(resp);
            //llenar_table('#tblRecpPqr', jresp);
            dibujar_tbl_listaPDF('#dataTablePDF tbody', jresp);            
        },
        error: function(jqXHR, estado, error) {
            console.log(estado+' : '+ error);
        }
    });
}

function dibujar_tbl_listaPDF(id, json) {
    var row = json['CODIGO_INM'].length;
    var html = '';
    
    for (var i = 0; i < row; i++) {
        var ruta_fls = json['RUTA_ARCHIVO'][i]==null||json['RUTA_ARCHIVO'][i]==undefined?(json['CODIGO_INM'][i]):json['RUTA_ARCHIVO'][i].toString();
        if (ruta_fls.indexOf('/') == -1 && ruta_fls.indexOf('(') == -1 ) {
            var ruta = json['CODIGO_INM'][i]+'/'+json['CODIGO_INM'][i]+'.pdf';
        }else{
            var ruta = json['CODIGO_INM'][i]+'/'+ruta_fls.substring(ruta_fls.lastIndexOf('/')+1);
        }
        
        html += '<tr>'+
                    '<td>'+i+'</td>'+
                    '<td>'+json['CODIGO_INM'][i]+'</td>'+
                    '<td>'+ruta_fls+'</td>'+
                    '<td><button class="btn btn-default" onclick="verPDF(1, '+" '"+ruta+"' , '"+json['ID_PROYECTO'][i]+"'"+')"  ><span class="glyphicon glyphicon-eye-close"></span></button>'+
                    '<button type="button" onclick="verPDF(2, '+" '"+json['CODIGO_INM'][i]+"' ,'"+json['ID_PROYECTO'][i]+"'"+')" class="btn btn-default">'+
                        '<span class="glyphicon glyphicon-folder-open"></span>'+
                    '</button></td>'+
                '</tr>';
    }
    $(id).html(html);
}

function dibujar_tbl_info(id, json) {
    var row = json['FECHA_CREACION'].length;
    var html = '';
    for (var i = 0; i < row; i++) {
        html += '<tr>'+
                    '<td>'+i+'</td>'+
                    '<td>'+json['USR_CREACION'][i]+'</td>'+
                    '<td>'+json['FECHA_CREACION'][i]+'</td>'+
                    '<td>'+json['OBSERVACION'][i]+'</td>'+
                '</tr>';

    }
    $(id).html(html);
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
                $("#ingCodDoc").focus(false);
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
                                $("#ingCodDoc").focus();
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

//FUNCION PARA ABRIR UN POPUP
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
                        }

                        else{
                            $("#ingResInsInpCodSis").focus();
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