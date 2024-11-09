/**
* Created by Tecnologia on 25/7/2018.
*/
var cmbTiposPagos;
$(document).ready(function () {

    cmbTiposPagos = document.getElementById("selTipoPago");
    var pago=getParameterByName('idPago');
    var rec = getParameterByName('rec');

    $.post("../datos/datos.datPagos.php",{tip:'tipoPago',idPago:pago,rec:rec},
    function(resultado){ $("#formaPago").html(resultado);getFormasPagos(resultado); });

    $(cmbTiposPagos).change(function(){
        var valor = $(this).find('option:selected').val();
        $("#controles").empty();
        $.post("../datos/datos.datPagos.php",{tip:'getParametros',idMedPago:valor},
            function(resultado){
                var cantidadControles=0;
                for(var i = 0;i<resultado.length;i++)
                {
                    var rescontrol=resultado[i]['TIPO_CONTROL'];
                    var tipoCampo = resultado[i]['TIPO_CAMPO'];
                    var descripcionCampo = resultado[i]['DESCRIPCION'];
                    var codigoPar = resultado[i]['CODIGO'];
                    var idControl = descripcionCampo.split(" ").join(""); //toma la descripcion del parámetro,elimina los espacios y se lo asigna como id


                    var control = $("'"+"<"+rescontrol+"/>"+"," , {
                        'type':tipoCampo,'id':idControl,'name':"valorPar"+i,'class':"form-control",'required':true
                    });

                    var contro2 = $("'"+"<input/>"+"," , {
                        'type':'hidden','name':"codigoPar"+i,'value':codigoPar
                    });


                    var br = $('<br/>',{});
                    var label = $('<label/>',{'text':descripcionCampo+'  '});
                    $("#controles").append(label);
                    $("#controles").append(control);
                    $("#controles").append(contro2);
                    $("#controles").append(br);

                    if(idControl=='BancoEmisor')
                    {
                        getBancos(idControl);
                    }
                    if(idControl=='TipoTarjeta')
                    {
                        getTiposTarjeta(idControl);
                    }

                    cantidadControles =i+1;

                }

                var control3 = $("'"+"<input/>"+"," , {
                    'type':'hidden','name':"controlesCreados",'value':cantidadControles
                });

                $("#controles").append(control3);


            },"json");
    });

    $("#frmEdicionFormaPago").submit(function(){
        var idFormaPago = $(cmbTiposPagos).find('option:selected').val();
        var datos=$("#frmEdicionFormaPago").serializeArray();
        datos.push({name: 'tip', value: 'procesar'});
        datos.push({name: 'idPago', value: pago});
        datos.push({name: 'idFormaPago', value: idFormaPago});
        datos.push({name:'rec',value:rec});
        $.post("../datos/datos.datPagos.php",datos,
            function() {

                swal({
                        title: "Información!",
                        text: "Se han realizado los cambios.",
                        type: "info",
                        showConfirmButton: true,
                        confirmButtonText: "Continuar!",
                        showLoaderOnConfirm: true,
                        closeOnConfirm: false
                    },
                    function(isConfirm){
                        if (isConfirm){
                            window.close();
                        }
                    });
            }
        );

    });
});

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function getFormasPagos(excluirTipoPago)
{
    $.post("../datos/datos.datPagos.php",{tip:'selTipoPago',excluirTipoPago:excluirTipoPago},
        function(json){
            for(var x=0;x<json.length;x++)
            {
                cmbTiposPagos.append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], true, true));
            }},"json");

}

function getBancos(controlSelect)
{
    $.post("../datos/datos.datPagos.php",{tip:'selBancos'},
        function(json){
            for(var x=0;x<json.length;x++)
            {
                $('#'+controlSelect).append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], true, true));
            }},"json");
}

function getTiposTarjeta(controlSelect)
{
    $.post("../datos/datos.datPagos.php",{tip:'selTarjetas'},
        function(json){
            for(var x=0;x<json.length;x++)
            {
                $('#'+controlSelect).append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], true, true));
            }},"json");
}