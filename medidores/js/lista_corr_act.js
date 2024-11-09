$(document).ready(function(){
    desContr();
    compSession();
    compSession(listAct);

    $("#lisInsActFomr").submit(
        function(){
            agragaColaAct();
        }
    )

});




function agragaColaAct(){
    window.opener.listaAct=[];
   //elementos=document.getElementsByName('listaMant');
    elementos=$( "input[name='listaMant']" );
    for(var x=0;x<elementos.length;x++)
    {
        var properties = new Object();
        properties.codigo = elementos[x].value;
        properties.valor = elementos[x].checked;
        if(elementos[x].checked){window.opener.listaAct.push(properties);}
    }
    window.close();



}

function desContr(){
    document.onkeypress = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            return false;  }}


    document.onkeydown = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            return false;  }}
    $(document).bind("contextmenu",function(e){return false;});

}

function listAct(){
    $.ajax
    ({
        url : '../datos/datos.ingresa_resultados_corr.php',
        type : 'POST',
        data : { tip : 'obtListAct' },
        dataType : 'json',
        success : function(json) {
            if(json)
            {
                for(var x=0;x<json.length;x++)
                {
                    var span =$('<span/>', {
                        'class' : 'col3 datoForm listaItems'
                    });

                    var check =$('<input/>', {
                        'type':'checkbox',
                        'name':'listaMant',
                        'id':json[x]["CODCOMP"],
                        'value':json[x]["CODCOMP"]
                    });

                    var label =$('<labej/>', {
                        'type':'checkbox',
                        'id':json[x]["CODCOMP"],
                        'value':json[x]["CODCOMP"],
                        'text':json[x]["CODCOMP"]+" "+json[x]["DESCRIPCION"]
                    });


                    span.append(check);
                    span.append(label);
                    $("#lisInsActFomr").append(span)

                }

                var span =$('<span/>', {
                    'class' : 'col3 datoForm '
                });

                var but =$('<input/>', {
                    'class' : 'botonFormulario',
                    'type':'submit',
                    'value':'Agregar'
                });
                span.append(but);
                $("#lisInsActFomr").append(span)
                for(var x=0;x<window.opener.listaAct.length;x++) {
                    $("#"+window.opener.listaAct[x]['codigo']).prop('checked', true);
                }



            }else{

                swal
                ({
                        title: "Mensaje!",
                        text: "No tiene permisos Suficientes.",
                        showConfirmButton: true
                    },
                    function(isConfirm)
                    {
                        if (isConfirm)
                        {
                            top.location.replace("../../index.php")
                        }
                    });
                return false;
            }
        },
        error : function(xhr, status) {

            return false;
        }
    });

}


function compSession(callback)
{
    $.ajax
    ({
        url : '../../configuraciones/session.php',
        type : 'POST',
        data:{tip : 'sess'},
        dataType : 'json',
        success : function(json) {
            if(json==true){
                if(callback){
                    callback();
                }
            }else if(json==false){
                swal
                    ({
                        title: "Mensaje!",
                        text: "Su sesion ha finalizado.",
                        showConfirmButton: true
                    },
                    function(isConfirm)
                    {
                        if (isConfirm)
                        {
                            window.close();
                        }
                    });
                return false;
            }
        },
        error : function(xhr, status) {
            swal
            (
                {
                    title: "Mensaje!",
                    text: "Su sesion ha finalizado.",
                    showConfirmButton: true
                },
                function(isConfirm)
                {
                    if (isConfirm)
                    {
                        top.location.replace("../../index.php")
                    }
                }
            );
            return false;
        }
    });
}
