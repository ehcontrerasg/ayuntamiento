/**
 * Created by Ehcontrerasg on 7/5/2016.
 */

var butRepCat;
var butRepFac;
var butRepInc;
var butRepResInm;

function repMensInmInicio(){
 /*   butRepCat=document.getElementById("butRepMensCat");
    butRepFac=document.getElementById("butRepMensFact");
    butRepInc=document.getElementById("butRepMensInc");
    butRepResInm=document.getElementById("butRepMensResInm");
    butRepCat.addEventListener("click",abreRepCat);
    butRepFac.addEventListener("click",abreRepFac);
    butRepInc.addEventListener("click",abreRepInc);
    butRepResInm.addEventListener("click",abreRepResInm);*/

    $('article').find('div').click(function(e){
        e.preventDefault();
        var div = e.target,
        id = $(div).attr('id'),
        text = $(div).text(),
        url;

        $('#myModalLabel').text($.trim(text));

        switch (id) {
            case 'butRepMensCat':
                url = 'vista.reporteMensCatastrados.php';
                break;
            case 'butRepMensFact':
                url = 'vista.reporteMensFacturados.php';
                break;
            case 'butRepMensInc':
                url = 'vista.reporteMensIncorporados.php';
                break;
            case 'butRepMensResInm':
                url = 'vista.reporteMensResumenInm.php';
                break;
        }
        
        $('.modal-consulta-body').attr('src', url);
    })


}
/*function abreRepCat(){
   window.open("vista.reporteMensCatastrados.php", "reporteMensCatastrados", "toolbar=no,scrollbars=yes,resizable=yes,top=500,left=500,width=720,height=640");
}
function abreRepFac(){
    window.open("vista.reporteMensFacturados.php", "reporteMensFacturados", "toolbar=no,scrollbars=yes,resizable=yes,top=500,left=500,width=720,height=640");
}
function abreRepInc(){
    window.open("vista.reporteMensIncorporados.php", "reporteMensIncorporados", "toolbar=no,scrollbars=yes,resizable=yes,top=500,left=500,width=720,height=640");
}
function abreRepResInm(){
    window.open("vista.reporteMensResumenInm.php", "reporteMensResumenInm", "toolbar=no,scrollbars=yes,resizable=yes,top=500,left=500,width=720,height=640");
}*/