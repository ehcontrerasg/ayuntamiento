/* Este archivo contiene la lógica para las pantallas de edición de PQRS. */

//Constantes
const 
btnReasignar    = document.getElementById("reasignar"),
slcArea         = document.getElementById("area_res"),
txtResolucion   = document.getElementById("resolucion");

btnReasignar.onclick = function(e){

    if (!formularioValido()) { return false; }

    e.preventDefault();
    swal({
            title:"Confirmación",
            text: `Esta solicitud se enviará al departmento de <strong>${departamento()}</strong>.`,
            html:true,
            type:"warning",
            showCancelButton: true,
            cancelButtonText:`No, no enviar a ${departamento()}`,
            confirmButtonText:`Sí, enviar a ${departamento()}`,
            closeOnCancel:true,
            closeOnConfirm:true
        },function(isConfirm){
            if (isConfirm) {
                btnReasignar.onclick = ()=> {return true}; //Se revierte el efecto de e.preventDefault()
                btnReasignar.click();                
            }
        }
    );
};

//Conseguir el nombre del departamento seleccionado.
function departamento(){ 
    var nombreDepartamento = slcArea.options[slcArea.selectedIndex].text; 
    //Retorna el nombre del departamento con la primera letra en mayúscula.
    return nombreDepartamento.charAt(0).toUpperCase() + nombreDepartamento.slice(1).toLowerCase();
}

//Verificar si hay departamento seleccionado.
function hayDepartamentoSeleccionado(){
    if (slcArea.options[slcArea.selectedIndex].value.length === 0) return false;

    return true;
}

//Verificar si se especificó una resolución.
function hayResolucionPqr(){
    if (txtResolucion.value.length == 0) { return false; }
    return true
}

//Verificar si el formulario es válido.
function formularioValido(){

    if (!hayResolucionPqr()) {
        swal("Advertencia", "Debe especificar una resolución.", "warning");
        return false;
    }

    if (!hayDepartamentoSeleccionado()) {
        swal("Advertencia", "Debe seleccionar un departamento.", "warning");
        return false;
    }   

    return true;
}