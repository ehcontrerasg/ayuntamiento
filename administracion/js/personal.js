const frmCrearUsuario = $("#frmCrearUsuario");
const slcContratista = $("#slcContratista");
const slcProyecto = $("#slcProyecto");
const slcDepartamento = $("#slcDepartamento");
const slcCargo = $("#slcCargo");
const rutaArchivoDatos = "../datos/datos.personal.php";

/*Declaración de funciones*/
const getContratistas = () => {
  let options = {url: rutaArchivoDatos, type:"POST", res:"json", data:{tip:"getContratistas"}};

  $.ajax(options).done((res) => {
      let json = JSON.parse(res);
      llenarSelectContratista(json);
  }).fail((error)=>console.error(error));
};

const llenarSelectContratista = (contratistas) =>{
    slcContratista.empty();
    slcContratista.append(new Option('Seleccione un contratista.',''));
    contratistas.forEach((contratista)=>{
        slcContratista.append(new Option(contratista.descripcion,contratista.codigo));
    });
};

const getProyectos = () =>{
    let option = {url:rutaArchivoDatos,type: "POST", res:"json", data: {tip:"getProyectos"}};

    $.ajax(option).done((res) => {
        let proyectos = JSON.parse(res);
        slcProyecto.append(new Option('Seleccione un proyecto.',''));
        proyectos.forEach((proyecto) => {
            slcProyecto.append( new Option(proyecto.descripcion,proyecto.codigo));
        });
    }).fail((error) => console.error(error));
};

const getAreas = () => {
  let options = {url: rutaArchivoDatos,type:"POST",res:"json",data:{tip:"getAreas"}};
  $.ajax(options).done( (res) => {
        let areas = JSON.parse(res);
        slcDepartamento.append( new Option("Seleccione un área.", ''));
        areas.forEach((area) => {
            slcDepartamento.append( new Option(area.descripcion, area.codigo));
        });
  }).fail((error)=>console.error(error));
};

const getCargosPorArea = (idArea) =>{
    if (idArea == null){
        slcCargo.append(new Option("Seleccione un cargo", 0));
        return;
    }

    let options = {url:rutaArchivoDatos,type:"POST",res:"json",data:{tip:"getCargosPorArea", id_area: idArea}};
    $.ajax(options).done((res)=>{
        let json = JSON.parse(res);
        slcCargo.empty();
        slcCargo.append(new Option("Seleccione un cargo", 0));
        json.forEach((cargo)=>{
            slcCargo.append(new Option(cargo.descripcion, cargo.codigo));
        });
    }).fail((error)=>console.error(error));
};



/*Fin de declaración de funciones*/

/*Eventos*/
    slcDepartamento.on("change",function(e){
        getCargosPorArea(e.target.value);

    });
/*Fin de eventos*/

$(document).ready(() => {
    getContratistas();
    getProyectos();
    getAreas();
    getCargosPorArea(null);
});

frmCrearUsuario.on("submit",function(e){
    e.preventDefault();
    let formData = $(this).serializeArray();
    formData.push({name:"tip",value:"registrarEmpleado"});

    let options = {url:rutaArchivoDatos,type:"POST",res:"json",data:formData};

    $.ajax(options).done((res)=>{

        let json = JSON.parse(res);
        let messageType = "success";

        if(json.codigo != 0)
            messageType = "error";

        swal({
            title: "Mensaje",
            text: json.mensaje,
            type: messageType
        });

        if(json.codigo == 0)
            frmCrearUsuario[0].reset();

    }).fail((error) => console.error(error));
});