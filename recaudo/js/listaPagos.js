/**
 * Created by Tecnologia on 3/7/2018.
 */
$(document).ready(function(){
    var form = $('form[name="report_form"]');
    var dataTable = $("#dataTable");

    dataTable.hide();
    $(form).submit(getReport);
    function getReport(e) {

        e.preventDefault();
        var data = e.target;

        if (validateDate(data)) {
            var datos = $(form).serialize();
            $.get('../datos/datos.listaPagos.php?tipo=report&'+datos, function(res) {

                if ( $.fn.dataTable.isDataTable( '#dataTable' ) ) {
                    $('#dataTable').DataTable().destroy();
                }
                $('#dataTable').DataTable( {
                    data: JSON.parse(res),
                    columns: [
                        { title: "FACTURA" },
                        { title: "MONTO" },
                        { title: "FECHA PAGO" },
                        { title: "INMUEBLE" },
                        { title: "PROYECTO"},
                        { title: "ORIGEN"},
                        { title: "ACCIÓN"}
                    ],
                    columnDefs:[
                        {
                            "targets": [ 5 ],
                            "visible": false,
                            "searchable": false
                        }
                    ],
                    "info":     false,
                    "order": [[ 2, "desc" ]],
                    "paging" : true
                });
                $('#dataTable').show();
            });
        }
    }

    $('#dataTable').hide();
    $(form).submit(getReport);

    dataTable.on('click','.btnAnular',function () {
        var row  =  $(this).closest('tr');
        var data = dataTable.DataTable().row(row).data();
        anularPago(data);
    })

});



function validateDate(data) {
   if (data.fechaDesde.value.length == 0) {
       swal('Error!', 'Seleccione una fecha Inicial' ,'error' );
       return false;
   } else if (data.fechaHasta.value.length == 0){
       swal('Error!', 'Seleccione una fecha Final' ,'error' );
       return false;
   }

   return true;
}

function anularPago(data){

   var datos_pago = {codigo_inmueble:data[3],id_pago:data[0]};
   var formData   = new FormData();

   formData.append('tip',"getDatosAnulacion");
   formData.append('datos_pago',JSON.stringify(datos_pago));

   var getDatosAnulacion = fetch("../datos/datos.domiciliacion.php",
       {method: "POST",body:formData})
                               .then(function(res){
                                   return res.json();
                                })
       .catch(function(error){
           alert(error);
       });

   getDatosAnulacion.then(function(res){
       console.log(res);
   });
}


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

