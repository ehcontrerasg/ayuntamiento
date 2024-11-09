$(document).ready(function(){
    desContr();
    compSession();
    compSession(datInsPend);

    $("[name='butsel']").click(
        function(){
            alert('hola')
        }
    )





});


//
//function llenarSelArea()
//{
//
//    $.ajax
//    ({
//        url : '../datos/datos.ing_res_ins.php',
//        type : 'POST',
//        dataType : 'json',
//        data : { tip : 'selAre' },
//        success : function(json) {
//            $('#ingResInsSelInp').append(new Option('', '', true, true));
//            for(var x=0;x<json.length;x++)
//            {
//                $('#ingResInsSelInp').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], true, true));
//            }
//            $("#ingResInsSelInp option[value='"+"']").attr("selected",true);
//        },
//        error : function(xhr, status) {
//
//        }
//    });
//}
//
//
//
//
//
//function IngIns(){
//
//    var datos=$("#ingResInsMedForm").serializeArray();
//    datos.push({name: 'tip', value: 'ingIns'});
//
//    $.ajax
//    ({
//        url : '../datos/datos.ing_res_ins.php',
//        type : 'POST',
//        data : datos,
//        dataType : 'json',
//        success : function(json) {
//            if (json["res"]=="true"){
//                $('#ingResInsMedForm')[0].reset();
//                swal({
//                        title: "Mensaje",
//                        text: "Has ingresado la orden correctamente",
//                        type: "success"},
//                    function(isConfirm){
//                        if (isConfirm) {
//                            $("#ingResInsInpCodSis").focus();
//                        }
//                    }
//                );
//            }else if(json["res"]=="false"){
//                swal("Mensaje!", json["error"], "error");
//                $(this).focus();
//            }
//        },
//        error : function(xhr, status) {
//
//            return false;
//        }
//    });
//
//}
//
//
//
//
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
//
function datInsPend(){
    $.ajax
    ({
        url : '../datos/datos.ing_res_ins.php',
        type : 'POST',
        data : { tip : 'obtInsT' },
        dataType : 'json',
        success : function(json) {
            if(json)
            {

                var spanExt =$('<span/>', {
                    'class' : 'datoForm col1'
                });

                var spanInt =$('<span/>', {
                    'class' : 'inpDatp numCont2'
                });

                var inp1=$('<input/>', {
                    'type':'text',
                    'value':'Cod Sis',
                    'readonly':'true'
                });

                spanInt.append(inp1);
                spanExt.append(spanInt);

                var spanInt =$('<span/>', {
                    'class' : 'inpDatp numCont5'
                });

                var inp1=$('<input/>', {
                    'type':'text',
                    'value':'Desc.',
                    'readonly':'true'
                });

                spanInt.append(inp1);
                spanExt.append(spanInt);

                var spanInt =$('<span/>', {
                    'class' : 'inpDatp numCont2'
                });

                var inp1=$('<input/>', {
                    'type':'text',
                    'value':'Fecha',
                    'readonly':'true'
                });

                spanInt.append(inp1);
                spanExt.append(spanInt);

                var spanInt =$('<span/>', {
                    'class' : 'inpDatp numCont2'
                });

                var inp1=$('<input/>', {
                    'type':'text',
                    'value':'Motivo',
                    'readonly':'true'
                });

                spanInt.append(inp1);
                spanExt.append(spanInt);

                var spanInt =$('<span/>', {
                    'class' : 'inpDatp numCont1'
                });

                var inp1=$('<button/>', {
                    'text':'Seleccionar'


                });

                spanInt.append(inp1);
                spanExt.append(spanInt);


                $("#lisInsActFomr").append(spanExt);

                for(var x=0;x<json.length;x++) {

                    var spanExt =$('<span/>', {
                        'class' : 'datoForm col1'
                    });

                    var spanInt =$('<span/>', {
                        'class' : 'inpDatp numCont2'
                    });

                    var inp1=$('<textarea/>', {
                        'type':'text',
                        'value':json[x]['CODIGO_INM'],
                        'readonly':'true'
                    });

                    spanInt.append(inp1);
                    spanExt.append(spanInt);

                    var spanInt =$('<span/>', {
                        'class' : 'inpDatp numCont5'
                    });

                    var inp1=$('<textarea/>', {
                        'type':'text',
                        'value':json[x]['DESCRIPCION'],
                        'readonly':'true'
                    });

                    spanInt.append(inp1);
                    spanExt.append(spanInt);

                    var spanInt =$('<span/>', {
                        'class' : 'inpDatp numCont2'
                    });

                    var inp1=$('<textarea/>', {
                        'type':'text',
                        'value':json[x]['FECH_GEN'],
                        'readonly':'true'
                    });

                    spanInt.append(inp1);
                    spanExt.append(spanInt);

                    var spanInt =$('<span/>', {
                        'class' : 'inpDatp numCont2'
                    });

                    var inp1=$('<textarea/>', {
                        'type':'text',
                        'value':json[x]['DESC_MOTIVO_REC'],
                        'readonly':'true'
                    });

                    spanInt.append(inp1);
                    spanExt.append(spanInt);

                    var spanInt =$('<span/>', {
                        'class' : 'inpDatp numCont2'
                    });

                    var inp1=$('<button/>', {
                        'id' : json[x]['CODIGO_INM'],
                        'text':'Seleccionar',
                        'value':json[x]['CODIGO_INM']
                    });

                    spanInt.append(inp1);
                    spanExt.append(spanInt);


                    $("#lisInsActFomr").append(spanExt);

                    $("#"+json[x]['CODIGO_INM']+"").click(
                        function () {
                            window.opener.$("#ingResInsInpCodSis").val($(this).val());
                            window.close();
                        }
                    )


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
//
////FUNCION PARA ABRIR UN POPUP
//var popped = null;
//function popup(uri, awid, ahei, scrollbar) {
//    var params;
//    if (uri != "") {
//        if (popped && !popped.closed) {
//            popped.location.href = uri;
//            popped.focus();
//        }
//        else {
//            params = "toolbar=no,width=" + awid + ",height="+ ahei +",directories=no,status=no,scrollbars=" + scrollbar + ",menubar=no,resizable=no,location=no";
//            popped = window.open(uri, "popup", params);
//        }
//    }
//}
//
//
