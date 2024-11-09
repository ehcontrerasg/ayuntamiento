/**
 * Created by PC on 6/14/2016.
 */

var inpInmObs;

function inicioObs(){
    inpInmObs=document.getElementById("inmObs").value;
}


function flexyObs(){
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexObs").flexigrid	(
        {
            url: './../datos/datos.flexyObs.php?inmueble='+inpInmOtrosRec,

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:13,  align: 'center'},
                {display: 'Codigo', name: 'ID_PAGO', width: 35, sortable: true, align: 'center'},
                {display: 'Asunto', name: 'FECHA_PAGO', width: 272, sortable: true, align: 'center'},
                {display: 'Descripción', name: 'REFERENCIA', width: 678, sortable: true, align: 'center'},
                {display: 'Fecha', name: 'IMPORTE', width: 61, sortable: true, align: 'center'},
                {display: 'Usuario', name: 'IMPORTE', width: 80, sortable: true, align: 'center'}
            ],

            sortname: "OI.FECHA",
            sortorder: "desc",
            usepager: false,
            title: 'Observaciones',
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            //onSuccess: function(){asigevenOtrosRec()},
            //width: 686,
            height: 180
        }
    );
}





