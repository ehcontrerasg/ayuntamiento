const frmCambiarPass = $("#frmCambiarPass");
const txtPassActual = $("#txtPassActual");
const txtPassNuevo = $("#txtPassNuevo");
const txtPassNuevo2 = $("#txtPassNuevo2");  
const inputPassVence = $("input[name=pass_vence]");
const btnCerrarModal = $("#btnCerrarModal");
const cambiarPassModalCloseButton  = $("#cambiarPass .close");
/* Funciones */

    const varificarCaducidadPass = () => {
        /* Función para bloquear los botones para salir del modal de modificación de contraseñas, dependiendo si la contraseña del usuario es provisional. */
        /* Si la contraseña del usuario es provisional entonces se bloquearán los botones del modal de modificación de contraseña. */
        let passVence = inputPassVence.val();

        if(passVence != ''){
            $('#cambiarPass').modal('show');
            btnCerrarModal.prop('disabled', true);
            cambiarPassModalCloseButton.prop('disabled', true);
          }else{
            btnCerrarModal.prop('disabled', false);
            cambiarPassModalCloseButton.prop('disabled', false);
          }
    };

    const getUsuario = () => {

        /* Función para saber si el usuario tiene una contraseña provisional o no. Si el usuario tiene una contraseña provisional entonces se debe mostrar el modal para que la cambie.*/
        let codigoUsuario = $("input[name=codigo_usuario]").val();
        let options = {url: '../general/datos/datos.usuario.php', type:"get", data:{tip:"getUsuario", codigo_usuario:codigoUsuario}, dataType:'json'};
        $.ajax(options).done((res) => {

            if(res.codigo == 200){
                let usuario = res.data[0];

                if(usuario.id != null){
                    inputPassVence.val(usuario.pass_vence);
                    varificarCaducidadPass();
                }
            }
                
        }).fail((error) => {console.error(error)});
    };

    const verificarPassActual = () =>{
        let datos = frmCambiarPass.serializeArray();
        datos.push({name:'tip', value: 'verificarPassActual'});

        let options = {url:"../general/datos/datos.cambiarPass.php", type:"post", data:datos};
        $.ajax(options).done((res)=>{     

            if(res == 1) 
                cambiarPass();
            else{
                txtPassActual.addClass('noValido');
                swal('Información','La contraseña actual especificada no coincide.','error');
            }

        }).fail((error) => console.error(error));
    };

    const cambiarPass = () => {
        let datos = frmCambiarPass.serializeArray();
        datos.push({name:'tip', value: 'cambiarPass'});

        let options = {url:"../general/datos/datos.cambiarPass.php", type:"post", data: datos, dataType:"json"};
        $.ajax(options).done((res)=>{            
            
            switch(res.codigo){
                case 1:
                    txtPassActual.addClass('noValido');
                    swal('Información',res.mensaje,'error');
                    break;
                case 2:
                    txtPassNuevo
                    .addClass("noValido");
                    swal('Información',res.mensaje,'error');
                    break;
                case 3:
                    txtPassNuevo
                    .add(txtPassNuevo2)
                    .addClass("noValido");
                    swal('Información',res.mensaje,'error');
                    break;
                default:
                    swal('Información',res.mensaje,'success');
                    frmCambiarPass[0].reset();
                    $('#cambiarPass').modal('toggle'); 
                    /* "<?php =$_SESSION['pass_vence'] = null; ?>" */
                    break;
            }
            

        }).fail((error)=>console.error(error));
    };
/* Funciones */

/* Eventos */

    $(document).ready(()=>{
        //$("input[name=pass_vence]").val();
        getUsuario();
    });

    frmCambiarPass.on("submit",(e)=>{
        e.preventDefault();
        cambiarPass();
    });

    //Se remueve la clase CSS cuando el input es válido. 
    txtPassActual
    .add(txtPassNuevo)
    .add(txtPassNuevo2)
    .on("focus",(e)=>{ $(e.target).removeClass("noValido"); });

/* Fin de eventos */