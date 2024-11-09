$(document).ready(function(){
    desContr();
    compSession();
    compSession(llenarSpinerPro);
    sltUsrServicio('#sltUsrGestion');
    $("#genRepGesCobroForm").submit(
       function(){
           swal
           ({
                   title: "Advertencia!",
                   text: "El reporte puede tardar algunos minutos en generarse.",
				   type: "info",
                   showConfirmButton: true,
				   confirmButtonText: "Continuar!",
				   cancelButtonText: "Cancelar!",
                   showCancelButton: true,
                   showLoaderOnConfirm: true,
                   closeOnConfirm: false,
                   closeOnCancel: true
               },
               function(isConfirm)
               {
                   if (isConfirm)
                   {
                       compSession(generaRep);
                   }
               });
       }


    )

});


function generaRep(){
    var datos=$("#genRepGesCobroForm").serializeArray();
    $.ajax
    ({
        url : '../reportes/reportes.Gestion_Cobro.php',
        type : 'POST',
        dataType : 'text',
        data : datos ,
        success : function(urlPdf) {

            if (urlPdf.substr(0,11)=="../../temp/"){
               	
				window.location.href = urlPdf;
				
				swal
                (
                    {
                        title: "Reporte Generado!",
                        text: "Has generado correctamente el reporte",
                        type: "success",
                        html: true,
                        confirmButtonColor: "#66CC33",
                        confirmButtonText: "Ok!",
                        closeOnConfirm: true},
						function(isConfirm)
					   	{
						   if (isConfirm)
						   {
							    window.close();
								
						   }
					   });
					
            }else{
                var texto = "Contacte a sistemas";
                if (urlPdf==0) {
                    texto = 'No se encontraron gestiones.';
                }
                swal
                (
                    {
                        title: "Error",
                        text: texto,
                        type: "error",
                        html: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ok!",
                        closeOnConfirm: true
                    }
                );

            }

        },
        error : function(xhr, status) {
            swal("Error!", "error desconocido contacte a sistemas", "error");
        }
    });

}


function llenarSpinerPro()
{

    $.ajax
    ({
        url : '../datos/datos.datRepGesCobro.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selPro' },
        success : function(json) {
            $('#selProyGesCobro').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#selProyGesCobro').append(new Option(json[x]["SIGLA_PROYECTO"], json[x]["ID_PROYECTO"], false, false));
            }
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
                swal
                ({
                        title: "Mensaje!",
                        text: "Su sesion ha finalizado.",
                        showConfirmButton: true
                    },
                    function(isConfirm)
                    {
                        if (isConfirm)
                        {
                           // window.close();
						    top.location.replace("../../index.php")
                        }
                    });
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
						 window.close();
                        //top.location.replace("../../index.php")
                    }
                }
            );
            return false;
        }
    });
}

function sltUsrServicio(id)
{

    $.ajax
    ({
        url : '../datos/datos.datRepGesCobro.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selUsr' },
        success : function(jresp) {
           
            var row = jresp.ID_USUARIO.length;
            var html = '<option value="0">Todos</option>';
            for (var i = 0; i < row; i++) {
                html+='<option value="'+jresp.ID_USUARIO[i]+'">'+jresp.LOGIN[i]+'</option>';
            }
            $(id).html(html);
        },
        error : function(xhr, status) {

        }
    });
}