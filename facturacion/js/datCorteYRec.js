/**
 * Created by PC on 6/14/2016.
 */

var inpInm;



function inicioCorteyRec(){
    inpInm=document.getElementById("inm").value;
}


function flexyCor(){


    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexCor").flexigrid	(
        {
            url: './../datos/datos.flexyCorte.php?inmueble='+inpInm,

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:13,  align: 'center'},
                {display: 'Codigo', name: 'ID_PAGO', width: 50, sortable: true, align: 'center'},
                {display: 'Fecha Planificacion', name: 'FECHA_PAGO', width: 92, sortable: true, align: 'center'},
                {display: 'Fecha Realizacion', name: 'REFERENCIA', width: 87, sortable: true, align: 'center'},
                {display: 'Descripcion', name: 'IMPORTE', width: 250, sortable: true, align: 'center'},
                {display: 'Tipo', name: 'IMPORTE', width: 31, sortable: true, align: 'center'},
                {display: 'Operario', name: 'OPERARIO', width: 90, sortable: true, align: 'center'},
                {display: 'Observacion', name: 'impo_corte', width: 90, sortable: true, align: 'center'},
                {display: 'Impo Corte', name: 'observacion', width: 90, sortable: true, align: 'center'}
            ],

            sortname: "FECHA_EJE",
            sortorder: "desc",
            usepager: false,
            title: 'Corte',
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            height: 180
        }
    );
}


function flexyRec(){


    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexRec").flexigrid	(
        {
            url: './../datos/datos.flexyRec.php?inmueble='+inpInm,

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:13,  align: 'center'},
                {display: 'Fecha Planificacion', name: 'ID_PAGO', width: 96, sortable: true, align: 'center'},
                {display: 'Fecha Realizacion', name: 'FECHA_PAGO', width: 87, sortable: true, align: 'center'},
                {display: 'Tipo', name: 'REFERENCIA', width: 31, sortable: true, align: 'center'},
                {display: 'Observacion', name: 'IMPORTE', width: 69, sortable: true, align: 'center'},
                {display: 'Fecha Acuerdo', name: 'IMPORTE', width: 79, sortable: true, align: 'center'},
                {display: 'Usu Eje', name: 'IMPORTE', width: 50, sortable: true, align: 'center'}

            ],

            sortname: "FECHA_EJE",
            sortorder: "desc",
            usepager: false,
            title: 'Reconexion',
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            height: 180
        }
    );
}







