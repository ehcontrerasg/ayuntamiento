/**
 * Created by PC on 6/20/2016.
 */

var inmueble;
function inicioDatTot(){
    inmueble = getUrlVars()["inmueble"];
}

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}

function flexyTotalizadores(){
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flexTot").flexigrid	(
        {
            url: './../datos/datos.flexyTotalizadores.php?inmueble='+inmueble,

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:13,  align: 'center'},
                {display: 'Inmueble Padre', name: 'ID_PAGO', width: 117, sortable: true, align: 'center'},
                {display: 'Inmueble Hijo', name: 'FECHA_PAGO', width: 156, sortable: true, align: 'center'}
            ],

            sortname: "OI.FECHA",
            sortorder: "desc",
            usepager: false,
            title: 'Totalizadores',
            useRp: false,
            rp: 1000,
            page: 1,
            showTableToggleBtn: false,
            //onSuccess: function(){asigevenOtrosRec()},
            width: 356,
            height: 180
        }
    );
}