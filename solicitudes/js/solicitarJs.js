 const urlDatosSolicitudes = "../Datos/datos.solicitudes.php";
 var accionSolicitud = JSON.parse(localStorage.getItem("EditarSolicitud"));
 localStorage.clear();
$(document).ready(function() {


    localStorage.removeItem("EditarSolicitud");
	//getMenus();
	checkStatus();
    getUserData();

	$('#sltMenu').change(function() {
		getPantalla($(this).val());
	});


	if(accionSolicitud != null){
        $("input[value="+accionSolicitud.data.ID_TIPO_SCMS+"]").prop("checked",true);
        $("#obsRequerimiento").text(accionSolicitud.data.DESCRIPCION);
        showFiles(accionSolicitud.data);
    }

	$('#genSolicitudTI').submit(function(event) {
		event.preventDefault();
		checkStatus();
        
        if(accionSolicitud != null && accionSolicitud.edita == "true") {
            sendRequest('editar',accionSolicitud.idSCMS)
            .done(solicitudActualizada)
            .fail((error)=>{
                console.error(error);
            });
        }else {
            sendRequest()
            .done(solicitudCreada)
            .fail((error)=>{
                console.error(error);
            });
        }

	});
});

function checkStatus(){

    $.get('../webServices/ws.getSession.php', function(respuesta) {

        var resp = JSON.parse(respuesta);
        if (typeof(resp.usuario) === "undefined") {
            $('#myModal').modal('show', function() {
                $('#main').html(' ');
            });
        }

    });
}

function getMenus(datosUsuario) {
	var sltMenu = $('#sltMenu');

	$.post('../Datos/datos.solicitar.php', {type: 'menu', datosUsuario:datosUsuario}, function(data) {

		var json = JSON.parse(data);
		$.each(json, function(index, val) {
			 var option = `<option value="${val.id}">${val.desc}</option>`;
			$(sltMenu).append(option);
		});
	}).done(function(){
        if(accionSolicitud != null){
            $("#sltMenu").val(accionSolicitud.data.ID_MODULO);
            $("#sltMenu").change();
        }
    });
}

function getPantalla(id_menu) {
	var sltPantalla = $('#sltPantalla');
	$(sltPantalla).html('');

	$.post('../Datos/datos.solicitar.php', {type: 'pantalla', 'menu': id_menu}, function(data) {

		var json = JSON.parse(data);
		$(sltPantalla).append('<option value>Seleccionar Pantalla</option>');
		$.each(json, function(index, val) {
			 var option = `<option value="${val.id}">${val.desc}</option>`;
			$(sltPantalla).append(option);
		});
	}).done(function(){

	    if(accionSolicitud != null){
            $("#sltPantalla").val(accionSolicitud.data.ID_PANTALLA);
	    }

    });
}

function sendRequest(type = 'submit', idScms = null){

    let form = $('#genSolicitudTI')[0];
    let formData = new FormData(form);
    formData.append('type',type);
    if (idScms != null) { formData.append('idSCMS',idScms);  } 
    
    let options = {
        url:'../Datos/datos.solicitar.php',
        type:'POST', 
        data:formData, 
        res:'json', 
        processData:false,
        contentType:false
    };

    return $.ajax(options);
}

function solicitudCreada(res){
    let dataParsed = JSON.parse(res);

    if (dataParsed.Code == 00) {
        swal("Registrado!", "Solicitud generada correctamente", "success");
        $('#genSolicitudTI').trigger('reset');
        localStorage.removeItem("EditarSolicitud");
        enviarCorreo(dataParsed.Data.Id_scms,'A');
    } else {
        swal("Error!", dataParsed.Data, "error");
    }
}

function solicitudActualizada(res){
    let dataParsed = JSON.parse(res);
    if (dataParsed == true) {
        //localStorage.removeItem("EditarSolicitud");
        localStorage.clear();
        swal("Registrado!", "Solicitud generada correctamente", "success");
        $('#genSolicitudTI').trigger('reset');
        accionSolicitud = {};
        $("#obsRequerimiento").text("");
        $("#dvArchivosSubidos").css('display','none');
    } else {
        swal("Error!", dataParsed.data, "error");
    }
}

 function getUserData(){

     $.ajax({
         type:"POST",
         url: "../Datos/datos.solicitudes.php",
         data: {type:"getUserData"},
         success: function(res){

             usuario = {};
             usuario = JSON.parse(res);
             getMenus(res);
         },error:function(settings, jqXHR){
            console.error(jqXHR);
         }
     });

 }

function enviarCorreo(idSCMS, tipoSolicitud){

    $.ajax({
        type:"POST",
        url:  "../Datos/datos.solicitudes.php",
        data:{type:"enviarCorreo", idSCMS:idSCMS,tipoSolicitud:tipoSolicitud },
        success:function(res){
            console.log(res);
        },
        error:function(settings, jqXHR){
            console.error(jqXHR);
        }
    });
}

function showFiles(solicitud){
	
	if (solicitud.ARCHIVOS.length == 0) { return; }

	let 
	div = $("<div/>",{
		class: 'col-xs-10 row',
        id:'dvArchivosSubidos'
	});
    
    $("<label/>",{
		text: "Archivo(s)",
        class:"col-sm-12"
	})
	.appendTo(div);
	
	solicitud.ARCHIVOS.forEach(file => {
		let 
		a = $("<a/>",{
			href: file.ruta,
			text: file.nombre,
			class: "file col-sm-11"

		}),
		img = $("<img/>",{
			src:file.icono,
			class: "col-sm-6"
		}),
        aDeleteFile = $("<a/>",{
            text: "x",
            class: "aDeleteFile"
        });

		a.prepend(img);
		let dvFile = $("<div class='dvFile col-sm-11 row'/>",{
			class: "form-group row"
		})
		.append(a,aDeleteFile);

        div.append(dvFile);
	});
	
	div.appendTo($("#dvArchivos"));
}

function deleteFile(idScms,filename){
    
    let options = {
        url:urlDatosSolicitudes,
        type:"POST",
        dataType:"text",
        data:{type:"deleteFile", id_scms:idScms, filename:filename}       
    };

    return $.ajax(options);
}

function getIdScms(aDeleteFile){
    let 
    href = aDeleteFile.previousSibling.href,
    arrayCadenas = href.split("/")
    penultimoElemento = arrayCadenas.length - 2;

    return arrayCadenas[penultimoElemento];
};

function getFileName(aDeleteFile){
    let 
    href = decodeURI(aDeleteFile.previousSibling.href),
    arrayCadenas = href.split("/")
    ultimoElemento = arrayCadenas.length - 1;

    return arrayCadenas[ultimoElemento];
};

$("#btnCanReclamo").on("click",function(){
    localStorage.removeItem("EditarSolicitud");
});

$(document).on('click','.aDeleteFile',(e)=>{

    let confirm = window.confirm("¿Seguro/a que desea eliminar este archivo?");
    if (!confirm) { return; }

    let 
    idScms = getIdScms(e.target),
    filename = getFileName(e.target);

    deleteFile(idScms,filename).done((res)=>{  
        let elementoPadre = e.target.parentNode; //Elemento padre del botón de eliminar pulsado.
        if (res == "true") {  elementoPadre.remove(); }
    })
    .fail((error)=>{
        console.error(error);
    });    
});