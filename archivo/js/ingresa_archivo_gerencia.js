/*Declaraciones*/
const slcCarpeta  = $("#slcCarpeta");
const slcSubcarpeta  = $("#slcSubcarpeta");
const groupSubcarpeta = $("#groupSubcarpeta");
const slcEmpresas = $("#slcEmpresas");
const slcTipoDocumento = $("#slcTipoDocumento");
const slcTipoCorrespondencia = $("#slcTipoCorrespondencia");
const nmbProtocolo = $("#nmbProtocolo");
const form = $("form");

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
/*Declaraciones*/

/*Eventos*/
$(document).ready(()=>{
    compSession(()=>{
        getEmpresas();
        getDocumentos();
    });
});

slcCarpeta.on('change',()=>{
    let carpetaSeleccionda = slcCarpeta.val();
    let subcarpetas = getSubcarpetas(carpetaSeleccionda);
    slcSubcarpeta.empty();
    slcSubcarpeta.append(new Option('Seleccione una subcarpeta',''));
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

form.on('submit',(e)=>{
    e.preventDefault();
    compSession(()=>{
        ingresarArchivo();
    });
});

slcTipoCorrespondencia.on('change',()=> {
    if(slcTipoCorrespondencia.val() === 'Otros'){
        nmbProtocolo.prop('readonly',true);
        nmbProtocolo.val(0);
    }else{
        nmbProtocolo.prop('readonly',false);
        if(nmbProtocolo.val() == 0){
            nmbProtocolo.val('');
        }
    }
});
/*Eventos*/

/*Funciones*/
getSubcarpetas = (carpeta)=>{
    return arraySubcarpetas.filter((subcarpeta=>subcarpeta.carpeta == carpeta));
};

getEmpresas = ()=>{
  let options = {url:'../datos/datos.ingresa_archivo_gerencia.php',method:'POST',data:{tip:'getEmpresas'},datatype:'json'};
  $.ajax(options).done((res)=>{
      let json = JSON.parse(res);
      let empresas = json.data;
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
      documentos.forEach(documento=>{
        slcTipoDocumento.append(new Option(documento.descripcion,documento.id));
      });
  }).fail((error)=>console.error(error));
};

ingresarArchivo = ()=>{
    let formSerialized = form.serializeArray();
    let file = form[0][10].files[0];

    let formData = new FormData();
    formData.append('tip','ingresarArchivo');
    formData.append('numero_protocolo',formSerialized[0].value);
    formData.append('empresa',formSerialized[1].value);
    formData.append('tipo_correspondencia',formSerialized[2].value);
    formData.append('asunto',formSerialized[3].value);
    formData.append('tipo_documento',formSerialized[4].value);
    formData.append('fecha_documento',formSerialized[5].value);
    formData.append('carpeta',formSerialized[6].value);
    formData.append('subcarpeta',formSerialized[7].value);
    formData.append('archivo',file);

    let options = {url:'../datos/datos.ingresa_archivo_gerencia.php',method:'POST',data:formData,datatype:'json',cache:false,contentType:false,processData:false};
    $.ajax(options).done((res)=>{
        let json = JSON.parse(res);
        if(json.status == 200){
            swal("Mensaje!", json.message, 'success');
            form.trigger("reset");
            nmbProtocolo.prop('readonly',false);
        }else {
            console.error(json.error_message);
            swal("Mensaje!", json.message, 'error');
        }
    }).fail((error)=>{
        swal("Mensaje!", 'Ha ocurrido un error al intentar subir el archivo.', "error");
        console.error(error);
    });
};

/*Funciones*/

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
                //compSession(llenarSpinerPro);
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

