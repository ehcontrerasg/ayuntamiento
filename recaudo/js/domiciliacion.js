
$(document).ready(function () {

    getDatosDomiciliacion();
    $("#btnGeneraDomiciliacion").on("click",function(){
        /*var datatable = $("#dataTable").DataTable();
        var data = datatable.rows().data();
        var cantidad_filas = data.length;
        for(var indice = 0;indice <cantidad_filas;indice++){
            var codigo_inmueble = data[indice][0];*/
           var codigo_inmueble = 103676;
           getIkey(codigo_inmueble);
        /*}*/
    });
});


/*
function procesarPago() {
        getParametrosPago(80801);
}
*/


function getDatosDomiciliacion(){
        $.ajax({
            type:"POST",
            url: "../datos/datos.domiciliacion.php",
            data: {tip:"getDatosDomiciliacion"},
            success:function(res){

                if ( $.fn.dataTable.isDataTable( '#dataTable' ) ) {
                    $('#dataTable').DataTable().destroy();
                }
                $('#dataTable').DataTable( {
                    data: JSON.parse(res),
                    columns: [
                        { title: "INMUEBLE" },
                        { title: "NOMBRE DEL CLIENTE"},
                        { title: "MONTO PENDIENTE"}
                    ],
                    "info":     false,
                    "order": [[ 2, "desc" ]],
                    "paging" : true,
                     "language":  {url: '../../js/DataTables-1.10.15/Spanish.json'}

                });
                $('#dataTable').show();
            },
            error:function(xhrjs,error){
                console.log(xhrjs,error );
            }
        });
    }


function getIkey(codigo_inmueble) {


    var promesaIKey = $.post('https://lab.cardnet.com.do/api/payment/idenpotency-keys',{},function(res){
        return res;
    },'text').fail(function(error){
        alert(error);
    });

    promesaIKey.then(function (res_ikey) {

            var ikey_split = res_ikey.split(':');
            var i_key      =   ikey_split[1];

            getParametrosPago (codigo_inmueble,i_key);
        });


//console.log(codigo_inmueble);
   /* $.ajax({type:"POST",
            url:"http://ecommerce.cardnet.com.do/api/payment/idenpotency-keys",
            datatype:'json',
           /!* headers:{
                        'Accept':'application/json',
                        'Access-Control-Allow-Origin': '*'/!*,
                        'content-type':'content-type'*!/
                    },*!/
        success:function(res){
             console.log('RESPUESTA');
             console.log(res);
        }});*/

/* $.post('https://lab.cardnet.com.do/api/payment/idenpotency-keys',{},function(res){
     console.log(res);
 },'text');*/



    //console.log("IKEYYY: "+ikey);


    /*var formData = new FormData();
    formData.append('tip','getParametrosPago');
    formData.append('codigo_inmueble',codigo_inmueble);*/

        /*fetch("../datos/datos.domiciliacion.php", {
        method: "POST",
        body: formData
    }).then(function (res) {
        return res.json();
    }).catch(function (res) {
        console.log('Error: ' + res);
    });*/

    /*promesaIKey.done(function (res_ikey) {

        var ikey_split = res_ikey.split(':');
        var ikey = ikey_split[1];

        promesaGetParametrosPagos.done(function (parametros_pago) {

            console.log(parametros_pago);
            parametros_pago.idempotency_key = ikey;
            parametros_pago.token           = ikey;
            parametros_pago.cvv             = '333';
            parametros_pago.card_number     = '5360212121212121';

            //var parametros_pago = JSON.stringify(parametros_pago);
            //console.log(parametros_pago);
            procesarPago(parametros_pago);

        });


    });*/
   /* var promesaGetIKey = fetch('https://lab.cardnet.com.do/api/payment/idenpotency-keys',
        {
            method: "POST"/!*,
            datatype:"text"*!/
            /!*headers: {
               "Content-Type": "text/plain",
                "Access-Control-Allow-Origin":"*",
                "accept": "application/json",
                "content-type": "application/x-www-form-urlencoded",
                "Access-Control-Allow-Origin": "origin-list",
                "Access-Control-Allow-Methods": "GET,PUT,POST,DELETE",
                "Access-Control-Allow-Headers": "Content-Type, Authorization"
                },
            mode:"CORS"*!/
        }).then(function (res) {
            console.log(res);

            return res.text()/!*.json()*!/;
           // return JSON.parse(res).json();
        }).catch(function (res) {
            console.log('Error: ' + res);
        });*/

    /*promesaGetIKey.then(function (iKey) {
       // console.log(JSON.parse(iKey));
        console.log(iKey);
        //console.log(jQuery.parseJSON(iKey));
         /!*promesaGetParametrosPagos.then(function(parametros_pagos){
             parametros_pagos.idempotency_key =iKey.ikey ;
             console.log(parametros_pagos);
             procesarPago(parametros_pagos);

         });*!/
    });*/
}


function getParametrosPago(codigo_inmueble,ikey){



    var promesaGetParametrosPagos = $.post('../datos/datos.domiciliacion.php',
        {tip:'getParametrosPago',codigo_inmueble:codigo_inmueble, ikey:ikey },
        function(res){
            return res;
        },
        'json')
        .fail(function(error){
            alert(error);
        });

    promesaGetParametrosPagos.done(function(res) {
        procesarPago(res);
    });

  /*  $.ajax({
        type: "POST",
        url: "../datos/datos.domiciliacion.php",
        data: {tip: "setPago",
               ikey: ikey,
               codigo_inmueble: codigo_inmueble,
               monto:_monto},
        async: false,
        success: function (res) {
            console.log(res);
        },
        error: function (xhrjs,error) {
            console.log(xhrjs+" "+error);
        }}
    );*/

  /*var promesaVoidPayment = fetch("https://ecommerce.cardnet.com.do/api/payment/transactions/voids",{
                                      method:"POST",
                                      body:formData
                                  }).then(function(res){
                                      return res.json();
                                  }).catch(function(res){
                                      console.log('Error: '+res);
                                  });*/



}


function procesarPago(parametros_pago ){

    console.log(JSON.stringify(parametros_pago));
   /* var datos = {
                 "contrato"               :  parametros_pago["invoice-number"],
                 "monto"                  :  parametros_pago["amount"],
                 "idemptency_key"         :  parametros_pago["idempotency-key"],
                 "internal_response_code" :  '0000',
                 "response_code_desc"     :  'Transaction Approved',
                 "response_code_source"   :  'gw',
                 "approval_code"          :  '008039',
                 "pnref"                  :  'txn-ee4a26dfdb094548bb3e5414268b659d'
                };

    datos = JSON.stringify(datos);*/
    //aplicarPago(datos);

  //////////////Estos son los que va a producción
  /*$.ajax({
      type:"POST",
      url:"https://lab.cardnet.com.do/api/payment/transactions/sales",
      data:datos,
      //ContentType:'application/json',
      headers:{
               // "Content-Type":"application/json",
                "Content-Type":"application/json",
                "Accept"      :"application/json"
      },

      datatype:"text",
      success:function(res){
          console.log("RESPUESTA PAGOS:"+res);

          //errorCobroTarjeta(parametros_pago);
      },
      error:function(xhrjs,error){
          /!*alert(JSON.parse(xhrjs)+" "+JSON.parse(error));*!/
          //errorCobroTarjeta(parametros_pago);
      }
  });*/
}

function aplicarPago(parametros_pago){
    //console.log(parametros_pago);
    $.ajax({
            type:"POST",
            url: '../datos/datos.domiciliacion.php',
            data:{tip:'setPago',parametros_pago:parametros_pago},
            async: false,
            success: function(res){
                console.log(res);
                //Enviar correo
               /* if(res["coderror"]==0){
                    enviarCorreo(parametros_pago);
                }else{
                 correoErrorPagoRecurrente(parametros_pago);
                }*/
            },
            error: function(xhrjs, error){
               // console.log(xhrjs +' '+error);
                //correoErrorPagoRecurrente(parametros_pago);
            }


    });
}

function enviarCorreo(parametros_pago){
    $.ajax(
        {type:"POST",
         url:"../datos/datos.domiciliacion.php",
         data:{
               tip:"enviarCorreo",
               parametros_pago: parametros_pago
              },
         success: function(res){
            console.log(res);
         },
         error: function(xhrjs, error){
            alert(xhrjs+" "+error);
         }
        }

    );
}

function correoErrorPagoRecurrente(parametros_pago){
    $.ajax(
        {type:"POST",
         url:"../datos/datos.domiciliacion.php",
         data:{
               tip:"errorPagoRecurrente",
               parametros_pago: parametros_pago
              },
         success: function(res){
            console.log(res);
         },
         error: function(xhrjs, error){
             console.log(xhrjs,error);
         }
        }

    );
}

function errorCobroTarjeta(parametros_pago){
    $.ajax(
        {type:"POST",
            url:"../datos/datos.domiciliacion.php",
            data:{
                tip:"errorCobroTarjeta",
                parametros_pago: parametros_pago
            },
            success: function(res){
                console.log(res);
            },
            error: function(xhrjs, error){
                console.log(xhrjs,error);
            }
        }

    );
}


//Función para enviar los datos a la pasarela de pagos
/*
function getParametrosPago_old(codigo_inmueble){

    var salesForm = $("#frmSales");
    fetch("https://ecommerce.cardnet.com.do/api/payment/idenpotency-keys")
        .then(function(ikey){

            $.ajax({
                type:   "POST",
                url:    "../datos/datos.domiciliacion.php",
                data:   {tip:"getParametrosPago", ikey:ikey, codigo_inmueble:codigo_inmueble},
                async:  false,
                success:function(sales){
                    console.log(sales);

                    if(sales.lenght > 0){
                        /!*
                          salesForm.token.value             = sales.token;
                          salesForm.idempotency.value       = sales.idempotency_key;
                          salesForm.merchant_id.value       = sales.merchant_id;
                          salesForm.terminal_id.value       = sales.terminal_id;
                          salesForm.card_number.value       = sales.card_number;
                          salesForm.environment.value       = sales.environment;
                          salesForm.expiration_date.value   = sales.expiration_date;
                          salesForm.cvv.value               = sales.cvv;
                          salesForm.amount.value            = sales.amount;
                          salesForm.currency.value          = sales.currency;
                          salesForm.invoice_number.value    = sales.invoice_number;
                          salesForm.client_ip.value         = sales.client_ip;
                          salesForm.reference_number.value  = sales.reference_number;

                          setParametros(invoice_number,reference_number,sales.amount );
                          sales.submit();*!/
                    }
                },
                error: function(xhrjs, error){
                    console.log(xhrjs+' '+error);
                }
            });


        });
}
*/



