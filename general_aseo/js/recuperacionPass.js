const frmRecuperacionPass = $("#frmRecuperacionPass");

const enviarCorreoRecuperacion = (datos) => {

    let options = {url:"../datos/datos.cambiarPass.php", type:"post", data: datos, dataType:"json"};
    
    $.ajax(options)
    .done((res) => {

        if(res.codigo == 200)
            swal("Información", res.mensaje, "success");
        else
            swal("Información", res.mensaje, "error");
        
    })
    .fail((error) => console.error(error));
};




frmRecuperacionPass.on("submit", (e) => {
    e.preventDefault();

    let datos = frmRecuperacionPass.serializeArray();
    datos.push({name:"tip", value:"correoRecuperacion"});

    enviarCorreoRecuperacion(datos);
});


