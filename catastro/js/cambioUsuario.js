const url = '../datos/datos.cambioUsuario.php';
var idContrato = $("#hdnIdContrato")[0].value;

//Campos del formulario
const 
slcTipoDocumento = $("#slcTipoDocumento"),
txtDocumento = $("#txtDocumento"),
txtAlias = $("#txtAlias"),
txtNombre = $("#txtNombre"),
txtDireccion = $("#txtDireccion"),
txtTelefono = $("#txtTelefono"),
txtEmail = $("#txtEmail"),
txtDireccionCorrespondencia = $("#txtDireccionCorrespondencia"),
slcCorrespondencia = $("#slcCorrespondencia"),
slcContribuyenteDgii = $("#slcContribuyenteDgii"),
slcGrupo = $("#slcGrupo"),
frmDatosCliente = $("#frmDatosCliente"),
hdnDocumentoAnterior = $("#hdnDocumentoAnterior"),
spnCodigoSistema = $("#spnCodigoSistema"),
spnIdContrato = $("#spnIdContrato");


const getDatosCliente = ()=>{
    let options = {url: url, type:'POST', data: {tip:'getDatosCliente', id_contrato: idContrato}, res:'json'};
    $.ajax(options)
    .done((res)=>{
        cliente = JSON.parse(res)[0];
        
        if (cliente == undefined) {
            //Si no existe el contrato especficado se muestra la alerta y se cierra la ventana.
            alert("No existe el contrato especificado. Debe crear un nuevo contrato.");
            window.close();
            return;
        }
        cargarDatosFormulario(cliente, true);
    })
    .fail((error)=>console.error(error));
};


const getTiposDocumentos = () =>{
    let options = {url:url, type:"POST", data:{tip:'getTiposDocumentos'}, res :'json'};
    $.ajax(options)
    .done((res)=>cargarTiposDocumentos(res))
    .fail((error)=>console.error(error));
};

const getGrupos = ()=>{
    let options = {url:url, type:"POST", data:{tip:"getGrupos"}, res:'json'};
    $.ajax(options)
    .done((res)=>cargarGrupos(res))
    .fail((error)=>console.error(error));
};

const getClientePorDocumento = (documentoParam)=>{
    let 
    codigoInmueble = spnCodigoSistema.text(),
    options = {url:url,type:"POST", data: {tip:"getClientePorDocumento",documento:documentoParam, codigo_inmueble:codigoInmueble}};
    
    $.ajax(options)
    .done((res)=>{
        var cliente = JSON.parse(res)[0];
        if (cliente == undefined) { cliente = {documento: documentoParam, contribuyente_dgii:"N"} }else{ cliente.documento = documentoParam; }
        cargarDatosFormulario(cliente,false);
    })
    .fail((error)=>{console.error(error)});
};

const cambiarNombreContrato = ()=>{
    let formData = frmDatosCliente.serializeArray();
    formData.push({name:"tip",value:"cambioNombreContrato"});

    let options = {url:url,type:'POST', data:formData,res:'json'};
    $.ajax(options)
    .done((res)=>{
        let respuesta = JSON.parse(res);
        if (respuesta.codigo != 0) {            
            swal("Advertencia","La operación no fue realizada satisfactoriamente. Contacte al departamento de desarrollo.","warning");
            console.error(respuesta);
            return;
        }

        swal("Información","La operación fue realizada satisfactoriamente.","success");
    })
    .fail((error)=>{
        console.error(error);
        swal("Advertencia","La operación no fue realizada satisfactoriamente. Contacte al departamento de desarrollo.","warning");
    });
};

//Cargar los datos del cliente cuando cargue la vista.
getTiposDocumentos();
getGrupos();

//LLenar campos datos cliente
const cargarDatosFormulario = (cliente, actualizarDocumentoAnterior = false)=>{

    if(cliente.documento == 999999){  frmDatosCliente[0].reset(); return; }

    //Si es un cliente nuevo no se limpian los demás campos del formulario.
    if(!cliente.nombre_cliente == undefined || cliente.nombre_cliente == null) return;

    spnIdContrato.text(idContrato);
    txtDocumento.val(cliente.documento);
    txtAlias.val(cliente.alias);
    txtNombre.val(cliente.nombre_cliente);
    txtDireccion.val(cliente.direccion);
    txtTelefono.val(cliente.telefono);
    txtEmail.val(cliente.email);
    txtDireccionCorrespondencia.val(cliente.dir_correspondencia);
    slcTipoDocumento.val(cliente.tipo_documento);
    slcCorrespondencia.val(cliente.correspondencia);
    slcContribuyenteDgii.val(cliente.contribuyente_dgii);
    slcGrupo.val(cliente.grupo);

    if (actualizarDocumentoAnterior) { hdnDocumentoAnterior.val(cliente.documento); spnCodigoSistema.text(cliente.inmueble);}
};

const cargarTiposDocumentos = (jsonDocumentos)=>{
    let tiposDocumentos = JSON.parse(jsonDocumentos);
    tiposDocumentos.forEach(tipoDocumento=> {
        //Cargar options.      
        slcTipoDocumento.append(new Option(tipoDocumento.descripcion_tipo_doc,tipoDocumento.id_tipo_doc));
    });

    getDatosCliente();
};

const cargarGrupos = (jsonGrupos)=>{
    let grupos = JSON.parse(jsonGrupos);
    grupos.forEach(grupo => {
        slcGrupo.append(new Option(grupo.desc_grupo, grupo.cod_grupo));
    });
};

const cargarClienteIndefinido = () =>{
    frmDatosCliente[0].reset();
    slcTipoDocumento.val(99);
    txtDocumento.val("9999999");
    txtNombre.val("Inmueble sin documento asociado");
    txtAlias.val("Inmueble sin documento asociado");
    slcContribuyenteDgii.val("N");
};

//Eventos
txtDocumento.blur(()=>{
    let documento = txtDocumento[0].value;
    getClientePorDocumento(documento);
});

frmDatosCliente.submit((e)=>{

    e.preventDefault();

    let documentoActual = txtDocumento.val(),
    documentoAnterior = hdnDocumentoAnterior.val();

    let mensajeConfirmacion = (documentoActual != documentoAnterior) ? "¿Desea realizar un cambio de nombre para el contrato actual?" : "¿Desea actualizar los datos del cliente?";

    swal({
        title:"¿Está seguro/a?",
        text: mensajeConfirmacion,
        icon:"warning",
        buttons:{
            cancel: {
              text: "No",
              value: null,
              visible: true,
              className: "",
              closeModal: true,
            },
            confirm: {
              text: "Sí",
              value: true,
              visible: true,
              className: "",
              closeModal: false,
            }
        }
    }).then((confirm)=>{
        if (confirm) { cambiarNombreContrato(); }
    });
    
});

slcTipoDocumento.change((e)=>{
    let tipoDocumento = e.target.value;
    if (tipoDocumento == 99) 
        cargarClienteIndefinido();
});
