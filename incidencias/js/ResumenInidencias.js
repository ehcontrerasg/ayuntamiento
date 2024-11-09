$(document).ready(function(){
   // desContr();
    compSession();
    compSession(llenarSpinerMotivo());
    compSession(llenarAtendida());



    $("#repPagDetCon").submit(
        function(){
            swal
            ({
                    title: "Advertencia!",
                    text: "Desea generar este reporte?.",
                    showConfirmButton: true,
                    showCancelButton: true,
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function(isConfirm)
                {
                    if (isConfirm)
                    {

                        compSession(getReport);
                    }
                });

        }


    )


});

function getReport()
{


    var motivo =$("#idMotivo").val();
    var estado =$("#Atendida").val();
    var concepto  = 20;
    var fechaIn = $("#idIniFec").val();
    var fechaFn = $("#idFinFec").val();

    $.ajax
    ({

        url : '../datos/datos.averias_recibidas.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'report',motivo: motivo,concepto : concepto,fechaIn : fechaIn, fechaFn : fechaFn,estado: estado },
        success : function(res) {
            swal.close();
            // var dat = JSON.parse(res);

            if ( $.fn.dataTable.isDataTable( '#dataTable' ) ) {
                $('#dataTable').DataTable().destroy();
            }
            var tbody="#dataTable tbody";

           table= $('#dataTable').DataTable( {

                data: res,
                dom: 'Blfrtip',
                columns: [
                    {title: "CODIGO",},
                    {title: "OBSERVACION"},
                    {title: "FECHA"},
                    {title: "NOMBRE"},
                    {title: "TELEFONO"},
                    {title: "DIRECCION"},
                    {title: "EMAIL"},
                    {title: "LATITUD"},
                    {title: "LONGITUD"},
                    {title: "MOTIVO"},
                    {title: "ID"},
                    {title: "ESTADO"},

                ],
               buttons: [
                   { extend: 'copy', text:' Copiar', className: 'btn btn-primary glyphicon glyphicon-duplicate' },
                   { extend: 'csv',  text:' CSV', className: 'btn btn-primary glyphicon glyphicon-save-file' },


                   {
                       extend: 'excel',
                       className: 'btn btn-primary glyphicon glyphicon-list-alt',
                       title: 'Reporte Incidencias',

                   },

                   {extend:'print',text:' Imprimir', className: 'btn btn-primary glyphicon glyphicon-print' },

                   { extend: 'pdfHtml5',
                       title: 'Reporte Incidencias' + '\n' + ''+fechaIn+" - "+fechaFn,
                       pageSize: 'A2',//A0 is the largest A5 smallest(A0,A1,A2,A3,legal,A4,A5,letter))
                       fontSize:'5',
                       className: 'btn btn-primary glyphicon glyphicon-file',
                       customize: function ( doc ) {

                           // Para el formato del titulo
                           doc.styles.title = {

                               fontSize: '18',
                               alignment: 'center',
                               margin: [ 0, 0, 0, 0 ]

                           };

                           // Para la imagen
                           doc.content.splice( 1, 0,  {

                                   margin: [ 0, 0, 0, 0 ],
                                   alignment: 'center',
                                   image: getLogo()


                               }

                           );
                       }
                   },
               ],


                "columnDefs": [
                    {
                        "targets": [ 3 ],
                        "visible": true,
                        "searchable": true
                    },
                    {
                        "targets": [ 4 ],
                        "visible": true,
                        "searchable": true
                    },
                    {
                        "targets": [ 5 ],
                        "visible": true,
                        "searchable": true
                    },
                    {
                        "targets": [ 6 ],
                        "visible": false,
                        "searchable": false
                    },
                    {
                        "targets": [ 7 ],
                        "visible": false,
                        "searchable": false
                    },

                    {
                        "targets": [ 8 ],
                        "visible": false,
                        "searchable": false
                    },
                    {
                        "targets": [ 10],
                        "visible": false,
                        "searchable": false
                    },

            {
                        "targets": [ 6 ],
                        "visible": true,
                        "searchable": true
                    }
                ],

                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                "info": false,
                "scrollY":     "320px",
                "scrollCollapse": true,
               "paging":         false,
                "order":false,
                "language":  {url: '../../js/DataTables-1.10.15/Spanish.json'}

            });
           /* setInterval( function () {
                table.ajax.reload( null, false ); // user paging is not reset on reload
            }, 10000000 );*/
            $('#'+'dataTable tbody').on('click','#mostrarDetalles', function () {
                var data_row = table.row($(this).closest('tr')).data();
                var idReclamo = data_row[10];
                mostrar(idReclamo)

            });

         //   obtener_data_editar("#dataTable tbody", table);

            $('#dataTable').show();

        },

        error : function(xhr, status) {

        }

    });

}







function mostrar(idReclamo){


    popup(idReclamo);
}


var popped = null;
function popup(id) {
    var params;
    var uri="vista.detalles_averia.php?id="+id;
    if (uri != "") {
        if (popped && !popped.closed) {
            popped.location.href = uri;
            popped.focus();
        }
        else {
            params = "toolbar=no,width=" + screen.width + ",height="+ screen.height +",directories=no,status=no,scrollbars="  + ",menubar=no,resizable=no,location=no,top=0,left=0,fullscreen=yes";
            popped = window.open(uri, "popup", params);
        }
    }
}

function llenarSpinerMotivo()
{

    $.ajax
    ({
        url : '../datos/datos.averias_recibidas.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selMot' },
        success : function(json) {
            $('#idMotivo').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#idMotivo').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));
            }
        },
        error : function(xhr, status) {

        }
    });

}

function llenarAtendida()
{

    $.ajax
    ({
        url : '../datos/datos.averias_recibidas.php',
        type : 'POST',
        dataType : 'json',
        data : { tip : 'selAten' },
        success : function(json) {
            $('#Atendida').append(new Option('', '', true, true));
            for(var x=0;x<json.length;x++)
            {
                $('#Atendida').append(new Option(json[x]["DESCRIPCION"], json[x]["CODIGO"], false, false));

            }
        },
        error : function(xhr, status) {

        }
    });

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


function compSession(callback) {
    $.ajax
    ({
        url: '../../configuraciones/session.php',
        type: 'POST',
        data: {tip: 'sess'},
        dataType: 'json',
        success: function (json) {
            if (json == true) {
                if (callback) {
                    callback();
                }
            } else if (json == false) {
                swal
                ({
                        title: "Mensaje!",
                        text: "Su sesion ha finalizado.",
                        showConfirmButton: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            window.close();
                        }
                    });
                return false;
            }
        },
        error: function (xhr, status) {
            swal
            (
                {
                    title: "Mensaje!",
                    text: "Su sesion ha finalizado.",
                    showConfirmButton: true
                },
                function (isConfirm) {
                    if (isConfirm) {
                        top.location.replace("../../index.php")
                    }
                }
            );
            return false;
        }
    });
}

function getLogo() {
    var logo;
        logo = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAANUAAABtCAYAAAA22SSbAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAEUzSURBVHhe7X0HfFVF9r+QAhEV7IV1XXtbde11XdtaIQQQAUE6NlCxIIiACkhPJXSlCAISQkeQpvQqvYQaQCCEDqkvL4/v//uduS95SV5oixt+/30nn8PcO3fqmfOdc2bu3McFCFCAAnROKQCqAAXoHFMAVAEK0DmmAKgCFKBzTAFQBShA55gCoApQgM4x/WegOnECHvIJTw7cuvZkIzfXzdADt8eNXHcudh48grEL1iBxwQb8tHAtxs5fh9TjmUxv05k87hzmy2FxJ5yCAxSg/7v0H1oqD3IFKgIplyBKTctCv0lLEDtxGXkxohk+3nIISlWNQ6nweJSOiERI5XiEtx+FKD6PmbiU4VKmXYAdhw8RVB6n3AAF6P8unTWoZFNom5CrfwmGE7Q4P83fgODXuhNAsbigShSCw3uSo3FBRC/ex5MJLvIF1foxTQxKVY5GUHgvBFXpjpgxv7K0gKUK0P99+g8tlWDlBZULP8zdiAsIolIRsSgt8EQQSOIqfQmiPrwm2HhfioC7oEpvcgyZaSPiEPPTvACkAvT/BZ09qIgALYHksnlO5NIN9GD4nPUIqiyQ0AqFC1R9EVopDqV1XZUgihDH0h2k5TKAU8jnvO/+09wAqAL0/wX5B5W0u4CGCz1c7xgUOYGsk9ZTZFmqHIYLk3bh74174OYmcbitURxubdQbF79BC0XXz7h9TlihZi/c3jCeafrgDqa7u1EPjFuaZDc9bBU+bOP0j6nLiQ9QgM5XOgWovGotJ4+rJwMsgclhxpl7Dy1VrhvZ5COZmTiU6SJnYV9GFqp2+IlAkgsoK9XXuHvv9p6EA3yWmpmNg5k5OJiRCZcrm+W4TVkQO3XksglmM+QEnxlWG9S2AAXo/KSTuH/SXNkFKbcApS2JXOPqeTyEmdgAKh9U2gH08FrsJufw/vVvx3N95bh7BFapqvH4tN8k4sbF9Dk4kavtd4KH+VVOHmBN2bxXmby2wBIHbFWAzm/yCyqv8gpIBkS89hjAuOnmuQgCAYIhLYebwBA4ck8otEDxeDIJqBy4CJjqncfTUnEdxfWTWWdV7YcW/X+Bi0DKMQB1w6UyyQKllwXMXAFT77P4TO/CTJy59wIswAEunkuKTmKprLWQhdB6KSc3F8dycrEnLQubDhzH7zsOYNl2y8sNHzTXS524xdv3YwnDV75MIKhkqfqR+5jrOj0n8lkqlian4nemM2Vsyy/Py8uTD2LdvuPYdTwNh7OyDUiNdTwPBBegABVHJ7VUshTpbg+WbtmDjwb8gic+/A5/qR2HS6r0wIWVuiOscjeHexguW7knwiqRTVxXlGFottYFKrl+ThgSEc38fB7ejXl6mDC/LMsXmrArLorojCtqdcd97w5A08ixmPL7Zhx30WLm0HWkC2raav4CFKDzg/yCykNXy03LlJqehXdiJ+GSyp0QFKEXt9rJs9vg9mWu3jUp9L0uFGe2zr2hz7V55vM8L6/vtZ6xLj4vHR6P4PAYA9SX2wzG1pQjBvTeNVcAVAE6X8gvqNy5OUhzuVCn2yhaGr2olZLLyujlrZRc7Hv9J7Gxcn15zbqrKo6grtwfQZX74ukPB2BfRiaBJYsV2LwI0PlD/kHldmPC8m3GNStNK1EqXBaFbBRbAJPFEqgUr5MSfxITzKWq2BfJpRyrpw2PCyJiEFwpEr0nLUMOAaVNjgCoAnS+kF9Quej61emaaJTYgojKHC5LRTah4v5EQBnQigVkrcW89dv2aDdRFuyZj4eYtmoH0XfzIsABFpcU+QXVoawc3PxWL3O6/IIq/ajYUmYqePgAlImIRvnqXXFptZ64tPqfwxWq9UAFJ7y0WjeUqx5NIFlrlfcime26pGpP7DqcSVC5SlSIAQqQL/kF1caUY7ioUg+6Wjq3J3dLHI/SlXuhcdwv2H40m8qcjZ3iI38uJx/JwhfDfkWQLFd1nciQhYyl5eqD0pW6YvaGneZdWQBUATpfyC+ofl23C2Ve7WaskzmzZ9xAWYpe+Fgvbp13RX86sQ5PrgeRP/1mD+qGR6M0wRVUWS5gHEIqdccPM9cY9y9AATpfyC+oRs1Zh5DwKAJJO28FQWVOQ8BjTgP+6SRQeTzoNnoOAUXrVCWalooWS5+RVLWnNLqPWQT3CVqqwEZFgM4T8guqQTNWUYHtZoAFFd0/8/WuQDXdWKr/BqgEqBxPLrr/NMd8MSwrFdpgKILqaFudlor3HYbNstvqAfcvQOcJ+QXV9wTVBeG0TmYrXbtuWlMRZFxTtehnz+0VPi7057CHdeVg4rwVqNXlJ7zedTyu770Ed0TPRa3OE1C3UwISZq1Arttf3gD/r3NJUTGWaiWBpF02uX3W9TPunyxVv+nIMYDSC9ezI3XXdNlc6FMOsfee/0go+mMoa+XiminL7cbqjBxcn7AVT0zdihR3DjL5Z07Hmx+NKVhEgbIU5P05j86S7UXBsk5JTJTXBpNX995IydI+MxdnTcrrbZPzp7pMhHPt/J0WMZny5uXKuz8Ndv6xf75xzjXZFmbHPS+O/xbIc1LWn/wl3/TeP3tfUlQ8qASmwqAyayq6f+aw7dk3WzkNJE3vnc9KWJ61fg6bX7+wp+RzTmQj2+3Cl2tTEZq4ExclJGPK7iPm8xKdXDcn2M1pepXBInUm0JwLVFlurrkUrzpU7mn8Mb26Z0Mv23bxH6csfapi052SmEbt8n6+om/EFNq+23dsHtjwbIklM7/aaK7451wXYNv/0yKmN/JUOWYcnPx5ZZ2E82TvyNyJ5z+2XfozcXruvRY7HtBp/bFNTut889tnkkfJ0emBypykOHegsr12hGBlba49+qzDAYSEq3t9U5XjycIegur2yZsROmaf4Xrzk5FpvsVym89M7LdXzKO8xj09YcAkpTVlCaR5dZ4pq2wBN8u0ydvGMwcVr9VOlmcnEFpZtcmU4yiuzXHGpElK+WFehOvrAlZr2qjPcryfyzCR+efUpJbYfouVzbnPk8mpWHUrlBehfKZEFqSdWraH195JxshUnw4xnX6WwX95fljlm/46Y8yyeKPGWy4hKkFQqf8SioQvpnCoCW4JVgOZa9mjkIoxZMcBlBu1A0Fjd6P0uFRUTEjCujQqea6L7mGuyWc+jKSA7XdayidQ6jssfbslwCnetv2UbCyl0jr59NkJ26e22W/L7E8InJYU1Ffmk9IY4DtttN+gqY2SA5+xrtMrsCgZkao9lGXeB6RyjRnavuubNCng6VUgBTV9ZVvVbjsp+cjuJH+y5Jrs3GyH6haoLcD0jClUjilbspBMrFxsGp86imXlc1jlMJ/6mTdBkdmMEiP/GxXTVxBQvZwjSc4mhXYDw+PwUb9fkC0hO0I6K3Y6bgQsBeWaKIeclZONvelZ2LI/HZv2HceGfcewKfUojjDNK7OTCai9uGDcTpQauwchCSnovvEQDmemYfPeQ9jItFuYZ+eRdBx1ZcNt1lpWMXK4HkvafxRJKUdNuqTUY9hI9oa+1/lxTE/epDaw3C0HMrA/K4eKQpCao1G27X7754el1Nm5Wdh5OBObVS7r2JByBEfS041iyap6Z+6zYymlLUMKncVJYJv6w/ZvZPs37TuEtBxOh6c9bh6kHsvAOubfwPZuSD1uypH8DDuyKo4lt63MsyfNhQzJi2OQy7pzNO5mwuTEQj7uysHuo+lGJkksX1z8mPDa1J8/jlv2H0PK8UzqDseZfVYdpo+sp6SohCyVHTQVIdOtjYiVfxxAtQ6jcN2b+paqCy58rRvCwrviyWaRWJOeQ8u0nYAiqAioC8b9geAxuxAxexuWJO/DJZXbo2wl5nm1By6J6IGH3u+FgdOWw+XWJywZVDA37qjbEWFMU7ayvuGyrO+2Ct7rG7Hu9r5KV8Nlwrubz03Cwnvg5vqx6Dh8JpVTsyyl4PShKLF/mirNM6uknlxOGGkZuKVuT5Sp0g2h5Asrf4vPBkxCpmZrbbYQsE6mMydnpjYzNsG7aOteXBPRnm23fb64UkcMnbWaSpdNpXOsjsmn9pkSCpAmvHaDppvv3spV+pbl6Ju5bxGq7+T0HVwVyslwvqwKyEzfyzHtlW/E4I0OP3ICOWxej1gvxAJt5Lw1ePLDeFxWvQvl0RMhHO8wp712PAqGBa6ZVnIsy/G65vWuCP/ye8zbvMd4Lcb6U6YlRSUCKqNyZjBVjst8t3XHWz0QpLN9EfrsPt583hFSJQ61OgzHjNQ0XJSYjFKJ+3BBYiqBlcJwH+4cvw3bMly4ulYcSlfWSQudCZR17Y2wKjEYNHOVsSwuAvem+upDHzKfe38izYTMo59P82Vz+l7PdExL15ID28X2BFeOQ5shszlwmvXVk4Kke0KIzpJcGimnQmuJI8fOp+JEm985tKdVBuDKGj2RdOCoeXWgGbZIgadJkqesndxgF9v2VrcxlF80Zcp2s64g9uO+DwbgUKY9K6kJgS1jAzVB+OvHCbQdOgfBHAv7m42WS1XpR+7rw4xTXwwzrZGbTrxIbsrDuiOi8ffG8Uihu25+Dpygmvz7VlxE8JWqyjI0DlX1ZbjVMyv/4tjqpOoxP31nPCmOEz2pv9bpTst11IzN/x6o8gClWZXrpenLOQNG2vo4GMFU3iAqd2nWXT9yAiYmH0S5MXT7xhBMslRjBay9+MuYbdiS6cLNdWLMwJQ2Jy00yH2ovH3wTMsBSOeMmEXFuak+nzmKoJMiOvYUFB7DPMonjvcJ1Ved3tAZQ15HeH/8U7LojRtqdsN+TgQGBIXIgIr9k9WQ4krJZRn2Hs/AjW/1ZDvZL5ZTim0oxbYGEfwf95lsNlusa2bLOWNSnbRCmkQW00pVqEZ5Vu9v+qID0Rq7YFqbQTNlrTQhqC4B/mSgmotQgYry1FgIoKWpzEGStZRazL6UpkwtK53AxZBgkZwtuGJZTix+nLXSWJEstrFq+x8QVElp1DaOhcBvZK76OJ6+rM9/qBMFWO1gXUEMzdhw0ihDqxU1ei5lmW36VlJUgu6fBhNUglx82GcKhWRnNSn2v9v8iEZRCWgamYi+437FnIMZqJCwBUGJexA8bjdKjacbOGEn7hm3BXvok7fsPR6NYsahQdRkXFqTQDED0RfXvhmJ3VTmbALr5gaaCTmI7FNQlR54r99MKs0MtCO3HTaLPBttf2Do8Jfkdj/8asIqtJbBkoWOSFFB5IIs3b6Hikl3rZAYZKW8u4PsoXHHdAKla+ICKrW1wiFVo1C+Oq2eQEz5Xl0jGkl7DhmLWlS9T48MkNmedHcu3uqaYCyqrEC5qpEIez2G7VbdfXDv2wNwNNsLYGdN6KdOxbehpQpVGw2Y4vDYx8PwXq+Jefx+r0kOTyD7xk9Egx6jcXVt56gbQSiL1/q7GaaNR+iO39SA1qWSrF4cLq4ai7rdE9EkegyaRk1Ak6hxBbhx1FhyosO6Hsu05MixCP/mJ1pk/SKyQB2P2t8mwqVf6qLMS4pKDFR290aL6lzU6pxoZxsCK7hSFBIWJIFzLgdAu0gu7ON6pOqMJIQl0EqNT6UbmIryP23C17//YWY9leFiWUeoKHc2tRbuAipUhdejsI0LWS2Ib2yg8jWrxXFG64S1KceMEmsHzi1w+7B3bSJwyNLsoTW8rSEHTTMwlaMMZ9T5W3aZ3cnC+mhAxfx255Eh6xawb6P7qbz6Sez7P+iHbuOXI0SKL5DTYn5ERczWukp5yWdKqlM/Cbd02z6Ur6bfsLdAqNN9DJrEctIioHR+MohtH/TL7/T61HfVRWvlpz4Lqt/YRoHKulff/LiQrqV5o1aIFecTz7yZ5BbfzWT/Yh1QxeH9+GlmTPenu3EVJz+rW/G4juuuXcczkW12QznmBcr2sjbifZjjo53FOUl76OrLjZSX0hfPtxlJOQpUZy7Dc0V+QfWn7/6RzSKfZZifMesw2iiXLEHpyj0xdv5Go+zGNTHA8mBHtgtd1/6B2r9tQMNfN6N3UgoOaiNC/jOVSenSqJS3N+nPcuxglecsvYng0S9BWVDJB483i+2kvUcNGP32Q1aG8VJUDU6ay41HmmtWJSionKFcF83fLFD5k4HixNrV1K9QZaNbwiIu3HUgOJKzag/KdzX2CWhN5PLQXaWyX10jCqt3HjTtOSvZyq1i/+t1HU3XSy5YP1wY0R2LtuzGyu37zPdvQfqd+2rxuLdJPA5yojA7ccZaFa1PbfhiyK/WuhqXqxc6D5/DeLlWAr+XLSgt275rMspm/q9H6CA0wcNJOYi606zXVMr1BPYdc+GK13twPDgmbOu1tWIZl8HxYHkEu287Cpedd+3IaQFBFUr33K7leuPpz4eZyUnPSopKaKPCzuh6UemikKt2lKWSa0UfPDySoEoyM5F5b2HeXZEJAK2N0mlK0qjMWVIIDkBeOg6u1k+3N5Wbp1mQbkX1SGxMOW62Wm9sIP/d9iMkvAs27j3IvBQ+8xUg0ziBScqiNYcH6Tm5uP/D75hXMultNgAWbPrDyVtIDhp0ts3swrG9e44ew820cupfMAf+3qZ9cSAt04Atetw8WmZaAiquZtm34ycZS2DcR+angEx5Rchpo/ePFTJPLpYTPOUiuphx0pqt+rcJyGA9We4s1Os5htamn1Hy0Mox6Dd5sWmfBUbROhTXZuivXHtqzaPyCKphc+xk55WNZO+wZGFf4mqCo8VhvV+NXGB0qBTdu2BOHBZUHq4vMwmqbiyXY0W5XFszFvsNqCRvlZ9frmX1sSAzmnXpp8b3ElR0+Q3w47mO/oEytBNFSVEJgkrC0ZqDoOqkT/c109BSVYlCIkFlBSn3yqQ09+K8WdwnjldGkdPJdzRVm6UE8biEoDKWigN1U32BzVrekMpdsIGg0iaJgGlBmT+A3llQLCVId7nwULOBpn0CfigVc8EmWioqkOouQGo22yG3UVvIPccupELpf0JhvXRt4yYvMbtfepmckp6FG+s6XzVH9EeFGt2xctd+PtN7KymGFLdQ+SLTZbaPF+bFrKwU66pLV8/8fgdBWo6z92/rCXz2XYo+f2sKLqoWybroHvP5PU3jWL92Ak8OquDKduewdLVodBi+0FgTvcy1px+8bNeObIlhTXQZ9CLejp/O8dRYaFOoL1oP+sXIJOVYFq5gXyUTgf/a2r2QSsttdkqNc3dqsiI4gfmb9qKMF1Qcn2daDiGoipHbf4lKFlQsR7NN1U5jWb4FVVABUOXPNrr3clHyARVdG7VZW7wC1WYHVDfXp1to6ojlTN0Ja/YeRiaFr1nNKKZhtUdgsu86tP0rN+aQKwv3vzOY8uhLaxOLMLpyc7WmYn2mG77ktFFl7OHse0tDrpm4ppDsbub1ASqy+RVfAiebYceRc1lmDC2CNljiuAAfb9aA5vMaleMjAy/JyudPALLibixJTkE5Kqrd7OmLqu1+RKb+h0q2Q33LcGfTco0wSqy1ldzQuIlL4Sa4VU9hUlTbIbMJBlkq6UIsHmzxA96OTkTTKG0oFM/aXHi902iuaaXsGo9eZh06cUmS6fu+Y5m4/I1IxgsIAlU8UtPOHFTMYEBV1hdUnzugKjIw/z0670BVOtyCyusCeYFUmH1J90qfTgW50wGV+OLqPYuCisLXy8Oxv+/GsuSD5ANYuuNQAV6SfAiLdxzk9X4s3n4QA2avwyWcqc2mAl2Z0CoxWLh1NxVa7pnTCIfUZgNMN63UuEVUSq0p7E8RdBs7H263FtF2Deimi7TrcDoqvhlp10EsX5sMq/84YEBtLWbRrWFKwPRZqiMlzZR7120swclx4hiFcS01dcU2WkTl97qiWZi9bifKVdG2vsayN/7epB9SqeD6v8UKd0R37Qgq4/qxXeaHd5zXFoYZVywzrQFiNb3eiDYfvNbvOgJp2XbzSaC67A2tgyyorpGlCoDqTwJVVQ1YJEG10fx/wcY9M4pVkL3A8rLipEAC1V1NtSGhdvu3VOqDduHKhfcwXDYikkrY0+EehssaZry2ozWrv6b3JczLa4UXV4tC8oGjrNu/wtsfInXhlnq0QBxsWce/1onD3mNpxvJJsexGCK0hrVKbITONBdQ2uBSyfs/xcOWo79bKFCZ22fTb7oBlYxkBXqGaFEtt7IuXWg2ly6p3VmLWp7QMM7keDf9yEAGs3Tz9OGk04gh8A2AzHvmkO4HKAETWiixQya228tUk6DDjDefdW3Br/an3h1fX6Y8FyfuQnSPrT7eXa6rLa9AVPaegUtvOY1D92bt/XrdGs7qUK8JsVEj5OLNV7okx8zdYhSIYbGjT5VJZxd57bxsUJ0VOIxtL5UwGF3MNsWnvUfPe4qZ63jf2tByqy/xClJjrJLp1lnVNUBrmMymKcX8smLSlHkyu8sUI56fR/ACcrI2RyAmLCBLt+PWiteqOr3+cZQZb295aT+UBi6DavO8oKtbsjqBKak8vVKCrtGJbCkGjCYMK4lO+tw5TN9uQwfz1e4xhHVQq9unCytGYsDjJWEQ3rZPLjJXko9P8LvyycjvKhtNaaW3FPt7RKBZ707JNmYXr+HIw3T/t/mlNxfIvrh6Dq+m2XVMzmhzjcKwJr2aobfIrakWT4wjyaLuWNC+7e+PuhlH4w/zylYeTSybXVD0pY4GvD65h+n3H041MdFrdtx3FsWkvy5qXtMe4luY/v2A9/2o5GPovnfy5zf8tKmFLZYVTTaAyQulLUEVhJC3V5iPpXCPpeI8LWVSepGPHuC5wm889slwebExLwzEKVUrqolXbdDQDB/jsTro0Wk9JOS+hRdEhWh0BulknKsxMavuiDRHrMkjx9aMytCpmErH9tAPOBTYtVhAX/Vrz6AzgQ+/1xoY/DpoZN18R7SASMiZMpZLe1MC+kNQGRHm6OuPX7MTynQewekcqVu04gBW8XrlzP1byfild0De6T2R6TlyUt04K1Osxmn23rxNsHbRYnJmlKsYtZJw+0Fy4NYUuo375yrpk97w9GHO372cdYtZBXk3W9aqd++jWHsD9zb4n4DVZxCGEwO85dgGyWKY53mPcRQKZYdshswgMpROoYvH54HnYeTjNuKziP/I4w7DidpKTeb2Wco/oMI5ub38jT52Y6DVhsdnASTmexTWVttStdbm2Vi/zisFYKsqSXSvKeXqTz3rgtVTqiybI/1n3z5LyW+Wo1mGMBRV98NKV4vDV9PV4buxqJO49hn0E0gIOwovjFyNhzxGk0i1ayPtXpizCqN1HkEIXcQndiX9PXoXhfxzDrW/bN/jW/YsyP7eWTeDd3MABFfug/+D7s6Fz0W30QvJ8dP5pHrr+tBCPNu9Ha6SjPXIROeBM+3LHSXw+F5Hjl2AyZ/mDmVoPaS3DtmtVI0V3rK5mYW3TRyXSSsmd40wdpM0NzqRBXFcEV+qOMq91JXdHSKUeVOzu5GiEvtaTgKU7ZCYWtT2ei/xuWLZ5j5m9fU+wq1bVK7BlUzb1IhNZhtxTO0bBXO+VCdcB2EhjKUMrRRkOeU1hT/OOLsgo4UDridAK/a1uFFLorsrltoCVYnvwpUBlwNfbuLEdhs9jOzj6sn7+2FhVyoKTmJvrvIHT19D6aqLgBMY+NeyWyEnQQwDR/atJUFG/LKispTL5OVZ5qHFYqlYoyshCsregsp5EAFRGUlQPch6oWL7OeV0euRghibtQYcxG3D9lK65N2IQyCQdwceIm3Dt5M/7y0waUGcvnCVtx38RkXJeQhDKJu3HZmG0oW8fu0sn3v7h6tAFVFgfqJvOeSoKPQdnKHc1MatccWtdkmxeGWw8ex33vcKFNP9/OzgNxRdVuSFiwwW4wMJ3dQJDSadjyQWW/t8qlK5WG2xpykM3Gg33xqTNymklLy43mwt3+R+Na1Kudfcz5Nd3LqpljPbK0zF+n6xhaYe/upKYggcoqsMC2amcq+9iNYKTbrPLZZhvK5eS1JgazHmJ5ujd1OhNGhN5Z6VoWOhrdE+ZTDhxZKr21FoVARUvV6cf5fK7Dqnb8zRBqKPPY/gmULspz+G/raOGlO2pfX7z+dQLXgCfMmurKN7pznNgmL6iOeUFVjPtX+E9tMKDa4wOqvnmg0piUFJUgqMSc2SmA6sb9o8JJEenDh0WuNd9MlU5MQelxe3idilDdj93N6/3mqNIF43W/l2n2o9S4/bzeg3KjU1G63jAj3AuqxRYEVUNZKtufUFqMjVxrebjmMArLNnhf1m7i+ub+Jtra1zsdKidn+0uq9TQ/hpORm2UAaGdkdsAMuFSdcQSVXuh2pyulI0JB4QRIVR3sjaN1oKWgNdGpAh1wNcrMfupamxOhnM31crQUFdy6pLK28biIAFwoa0U3Ty/BZdUNuFi/6qrXY6w5IWG20VmefnFKSqwT+6pDlkr3QbT+YrMRIibQjOWkBbUL/H648c04/HFU6xrn5SsrMu6fA6rSbH+nH+exHS72W66oTVNA8Rkv1u6mXmILVMqn8dB6tOpXYwyo9PL3Sp2oMJOAXv72QoovqDRZOVOIlzWl+LKpKw9UdiNJ4H221RDjmfzPg6paxzFmVtVsfQGV5KKolQgau8MA64JxKeaTjyBzQj0VQYl7EZpAHk1w8Xlw4k6UGq9DtvsRNpoAbPg92zqA3JegsmsqvQ+62XtMqarWRnr5e5gKlGEGUTO/jkRp7ZVLi7Tuj/24vaEUTtZKGxt0JcO7o+e4Bc67HyoQ05ttfzOzyx3LNWf8bqWbaU50c/a/olYP9EhYiKixCxE9djF5CaLGLUW0mNdxiYsR68RHJ5J5/WqbH+g6SvayZn3wRqeRyMhh+zjzG8VlnXL9Vu46iIu53tNGSjDH5d63+yBmHMsbswgxiUsRw7LiWGYMy/Zl1Rc7dhG6jV+Ee961L7T1eYvWjB1HLIT+V0vVob4VABVB35Hun/lIk+NmAe7LinPiqdQuTgS9f/6d+Xpa3SFX+2a0cWX30n2/uob9KkF91JpKmxd6Z2Y3KyTTfM4v27ImQO18ahx09i+Uk4/ZTOPk8nzroRZURsFKhvyC6r9x9s/MchSglLFh9ESjGJrhVVe5tjNQttdqlOm1ChfGriSvwIVxK3n/O8IYlo1bYa7LxP3OZ4pbjnIxK1AhajlK1fZuSPQ2H8jt4sJaHyneUl8ThOKjUbZSZ6znes0cZ6HL5m2PFEIKm+M+Yc7L3apPNXSUhgDVjKr/iK7dsNlIN1v9eonLQSZrcLXDGEPQhbwmi6P0cfiw389GUcznH0YhbN+9Smiso2GtmfTcjeXJnBwi5NLJHY7FRVU6Y16SczKCslJ7s+i2NuwyHiGUmSajslyTDZy1mmss7Y5KqW1ZAoaXvXLXtayzvrkaPGsdQsP1HZt2PWNxc90Y7D6UhhNu9cnr/sl15STBuhpFT8X01TswM493+uUZfDZyQRIe+XAA8yq/3lfFo+a3o025h3M8qFhbnolc7d64vEZvDPttAyYuSsKExZswfsnmIjyhAG8x4cRFm9Bt3HJOQjoNL4vYB1U6/uQclP5fs1TMahVZbkwuvh35K2d2uSFSJCo+ha3tYbu2UP2nYAMWXXu3zdXW3rinaTyOZ2WbIzy3NMhPq2NKG/ceoSuSQ0+CroyxmnlNMwDTruO8zXtxfS356xowta8vLqQF+mTgVBx2UQpmHSXl5eI7LZvWTZ8fcFbn+qF89R7GGloFVx2+FdhLL9lbycPDtZ0HNb4dQXlocqBbQ4Dq84ZsKrq28U/Qki5KTkX5CB2QVZ/644G3+5oDst6Jzm8lXmK0XZO5sT/DhfvfluJTuXUcidbqm+FzzQSh9WFrnVKvJFBwHNinIAIvhCAsQyW2HGlDupE6KqSwrK7pOgfrJbMmYo0LZVea68sOw2dx0tIJdjce/dCuffVc9YeER5L19S/Lo7t8SlY6ehE6/W5OqJsJhhNZ/ykcb71gL/p+779FJQQqu5tF+XJmzeHsvBdl6V4JRGYAVRfrtMf5BSwvuHzDQtfMaz8u1HU/8x1Q894TKGD7g5w3affPCD7e7MAl7T1ExdI5tqKOglwON2f9HCrwzHXJuEbAooKYzQVaUp2oeC96Ao5k6WWmrIOHbt0is8Mmd0zrh0YxicYayN0RWIrTcV8yllLb5Bt3ohwVrDTXRCpLn6zP25iMDNaVSWVv3G0sSptzfHFUrF7oPXk5cuQiqi6HT0ZMSQDSSyB44ictZb9kXVkX+a91emPLIW3iZKHt4Blmy11rMKsLArrkSBnLuhW5dkJNht6JThsiHMvr63TFphSdwpe8XPiWANMa0OQ3+kVwaWLlGMp6nYwFQjPOzmaMqYdtLEcdmrJiK2VODf2fBRUHV66R3Jn2w3/DZdXtFrMEJjYHOSVks6NVPEu59NWo/vtSpS9TpSv+9XF/bNvPdROVVJ+y31hPawe5sXF0lzpjHS2V+VUk1l8UVFbpNFvn0EJMWUFg1ehh+i9rqgV/2UpRqNN5tDlxfig9G7fV5RqMM30wLcyldN9+355KV01WSrJyrOEpSOnUJr2Pq9T2R9M3u6EQi2rthpj13LJtu3FF1c5UVLUlHnfVi+V65LgBMOFk2n6ysdETu2UuVzcHKenHcVt9WRJ9NCmLEYOvhs40P5bzxaCZjLfrFQFP4Cosd3ut/GyPT6jdwpDXaLkqR+KR9+Mxc/0u4wobt5Rjoi30Sp8PouXvTAuoLX7pm/IKMLaM/OuicdJNAyyG2lQqX7UjPuo/Dcdy1Dc7riVFJeb+2fWF1hsEGEGVRYXRi9ABU5fhi6Gz8NmgGfj8+1/I08m6zg9bMbRsr82zwdPQavBMdB6zEJN+34zDmdksWzt1OdDn1d+OmOWU8Qu+/G4KUo6l5SlXYRLcOY+b9smlUv6fV2xBq++mo7XK+G4Wvhg4A58N+Bm/Ll2Dtdv/QCu6hLYt09Br4hzzi7rmnYtZz+jozenIi7VSGbIJkMVcR7X+7mfWOZXhTHzN6/1H0zBzyWreT8DnRj4zMXb+WsqP7o4DqlORJjKzNjP1cNLg2nDs/PUsbypl/gs+GzwL3UdNR5YrG1OXrMcX3/3CvlLO7Hsr9rv194VYcYW4peIHzUIsrff0VcnYz7Gwh4hZtxlvyTSXlj4Tv6xJRtTYpWjD9HZMZ7LcwjyjEDPuOxu2Gfwb4iYvxaKtu5FmXj/IpXcmshKiU4DKu1axoNKu1rna/ZMC2AW7/Q08KaCO0cidMu+OxBwEDYB2hMxLUF6bHSKHZUnE5viSSWdnX/v+SQqjZ/aLWBuvewperPReUJm+5LPirIXJB5XHLdfMlmnKMhaQ5dHtsm1S2doZZF0cXHMciWnNhkyh8otjU6/6KAWU+8l6VYfe+cgdy2Ff7HEjPqPMTFtys1mHNjG0MPdfri9b10/9k8yUTy6qZMd2K46Tm85K5rpZn66NrCQ/9U/joXxKb2WTPz4+1948LM+++1I9UngrU4210toxyTEbRt4xtmWonuJZ7ZcumE9QJBuymSBVjxlT9pRhSZFfUH1vQCVAWTMrQNmFeixBNcsMKEXqpD47EqjMnxSXUrCKbEMjeIclaP1gpgbGDpSsGxWdrHNiOsFg360on4TpgMGwFFsDIaWg8pjBVlnOzGl8b7Gu/bNOEMhHt+k56IYVZ+tVefa5FsdipjNx9pk9gVCwzOJZbouUhW2X0pm61H4pm+qwoco2ddIS6/S5+qP8Bb/I9Ve++mPZ3jO/Kce20cY75ZJVh+TmTWP666Q7OSut8gg03rw2NOXrmmxAxXt5FLY//soqjr1AF0tPrK6Yaz477zYqvp/pWKoCoKIPWzUWH/efTQH856A6HTKgogAPZ6ZjO12fXVw77KbblnI0HXt0f+Q4DmbqPynQC0nNxPmkW4HAzsJZSCewtMjXN1fpfOjLGX7ZYzjdJ/TH/vKeuuziOZ1tziB7Q8u2Db6s34AQK4/32h8XLt8fG5mQM8S8z6Ts/OX17dep2Deftyx/bfIXd7Ky8tN4kEbZiCUrhRmyeDmakOzkW1JUjKVaRTBpEejs4nh3Z/7roJKpz0VcwixcXa0brqrRDVe+EYWrdFKa19dW74zOw3+hJcsyaQuQAypZ1VSXCw1/24Q35yej1jwvb/cJfa/t89rzdzDMZ93Xzsufn/5Nw8l5Ye1Cz73lnV4cw/lbC/K8rSx3m089OxzW9UnKOZ049cfpU23GecusM9dyfp32umDffK+LxhXMW5hZ1lx/z33ryC9bcbY8bx7vNeUydxvbup28De/O3YhdXL+ZVx0c95Iiv6AaOnMNXT2CKdx7Xo6g0m5LRAxa9JtlQcWZwLpcfw6LFEo43UbPRWm92Tdb5mqP3n/EmB2wDiPm0hI5ayMfsi6g1j+52J6Vg8tHbUbImD0IHZNiOMQnDBpbiBMZn7iXnIJgJ/Te2zgbni4rfZE6/HFenn15YbDamLjH3KscU/cYPme891758vPm82nXK1ZaJ08o6w1lWLi8s2HT3tOIO1MO0ikbyUeySpA89uEvCduxJY3upHEB9eFlyZBfUI1fvAlmC9X7HkFWSqCq4gWV9f19QfCnsOwhhdM9YY59H+HdOHFcUn2+ET9+kdmsUHpfMqAyPvYJJBNUt/+4FJeP3oDLE5JwRcKmPL7c5zqfkxi/0fAVYxxO2MBwveHLvZwgVlpbzsm4aB3+2KmnEF+ex7ZeG++tt2B/zoYLt/NKJ/SNP1v2rcfL/tKdKav/ksPVHNNrKbdrRm/E339aiqR0/QquJuPzbKNi0RbOJq/pGyJZK/3ijRSa1oqW4qO+M6FTwGq4PV5TkM2W6Rmyvzx2Z9Au1ruP/g32RLUDKodLV4rGmIUbjQCLAFKsNnIBq2NK29KzsTUjB1szXdiWx/Z+O8PCrGeGmeek7FPWydhfHf7Ybx0ZbL9h771vH/y3/z9hf+0/ey7Y1nz2l/bMWOO5nZzsyGR7RjYy3ZpM7fiXFPkF1e7D6ShftSetg6yCXECtqQSqPnjgo2Fo3mcKPujzM3nqn8YfGp6Gj3r/jKc/G0YrqR8sEbhlObXe08vFLliavI/AydESqgD5gsts/dKaKTyXbHeeOMG4tcvoP423/gD/Z+w7lv6eKfSOsTdtSZFfUGW43Liv+QDY31cQWxfQfg2rtVVvuoZc34Tb4y1/FtvPJPQWX2DSOkr1y1rpLF6c+Vnhg/rvbWjNigOV73XhOG94sjgvFU4n1gBmZWWZwSycXuQb583jy77x/shfmsJpvc984/3Fecn3mfe5b7qTxfmSb7rC1758MvKX3pdPRv7Si32flRT5BZVO+bYcON2soez6RYDS2S4ptayEPbVt4/yx0viLPxn7y+PUbU6Jq26ddrZHjQSqWl3G0BXlzER3tAiqfEjKv2LFCmzduhVHjx7FjBkzcPz4cfz666+YP38+du3ahZ9//hmpqanYsmULli5div3792Pq1Kkmj9KuWbPGgEfPkpOTsXHjRhPfr18/uFwuU77yq/w//vgjr7w5c+Zg1qxZyM7ONrx8+XJTzubNm7Fy5Ups377dlKN7pZ05cyaSkpIwffp0HDhwwDxXXenp6Zg9ezb27NmDX375BXPnzjXPf/vtNwPsffv2mWfr16837Tl8+LC5PnbsmGmP6lS56oeeqx9qj+7Vf4XTpk0zaX///XdT5rJly0x7du/ebeSg8tPS0kz/VZ7S6nrv3r0mr9oguXnbpLTqh+oVSyYq48iRIyb9hg0bTB/Ur8zMTNMOb7tUd4758RsPduzYgSVLlpjnkq+3PJW1cOFC4ykovfKdt5ZKC3/9lsKltbrQUui/qJHl8ILLnsGyyi4L5mWCoMD92bDK8JbjDVWXQgGpn2lHEN3AC6v0xMw128x/Jqa118lApcH78ssv0bt3b3z11VcYNWoUvvnmG9SuXRuNGjVCly5d8O233+KTTz7B+++/b64jIyPNvZ6PHj3apBUAu3btivj4eLRq1Qrt27dHbGwsvv/+ezz99NPo1asXVq9ejUGDBuG7777DRx99hDfeeMNcS8mkAC+99JIBSosWLRAdHY3OnTtj7dq1aNeunUnbv39/08Zhw4bh888/N2k+++wzU+fgwYMxYsQI0y4pqMKhQ4ea8g8dOoT33nvPtElK9uOPP+Ktt97CqlWrMHDgQKSkpJjyoqKizATwzDPPYNOmTXjuuefQrVs3zJs3D/Xq1TPgfvHFF7FgwQI0adLE9LNt27ZGJpKb2h4XF2dAJbnpWUJCgum7yv/www9NmyQTAU5tV7uUTum9k1azZs2MbJVH6dU/xfXt29e0Re1SXQJK8+bNTbzarrS6b926tSlbY6pJ5/nnnzfjo3adn6DizG9+EWjMPFxcuQsVWZsVUnKCi0ruPchodwQt0PJZFuVsWWUVLM98vEgghehwrVnf9UFY5Ui0GTAN6c5aRjt9JyMpiBRLitiyZUszy0kha9asaRRJCqABktIrlOJKOevXr29AJKWIiIgwAy+ATZkyxSiDytAM37FjRwPaV199FevWrTPlyep88MEHaNy4MSZMmGBA9fXXX+P111/H8OHDDahlCaXQmqmldGrLuHHjTDpZNIFSZUuJFAoMsgIxMTFGOaWEsiqaAKRMAqisk67ffvtt0x61VQqveD3Xfffu3VGjRg0DftX15JNPGmsgRVVbqlevbkAuUNWtW9eAWaBSek1Qyi9LWK1aNVSuXNlMAOqLZKu2CshKI1Ao/PTTT418lF/WTaB65513jHzVR1nsTp064eOPPzZyUx6V/cMPPyAjI8PIWZZZYO7Tp4+pQ3VpDBQ/cuRI02ZNRtIHr7UqKfIPKiE9V6elc/DjvHV45P3+CKsSSYulk9r20+UiR/FpuSzI9HsEXranzf2z97lPWpWRx3rG9VRlgYlheBQurNwVdzXubX4H/BjXfeYIkwMq7+zkj+VGCFRSbilXz549jVJOnjzZDKIUTUqlGV5pZC0EFgFEs/PYsWPNDL5o0SIMGDDAKJDySnmk0FIUuXiaseUKyVURGBVGcXYVCOSGJSYmGouhtCpTSqZ6e/ToYcAhq6Ty1AbFqY2amQVC1aF2q/2ayeU2qXyVrTIFJPVFFkuz+/jx440lkqvboUMH015NCAKA6pZrqjhNOEOGDDH5Jk2aZEArd0/gllwEIuVTmwUs5RU41S6vG6o6FKf2qDz1Q+UfPHjQxCuPF8zq586dO01ZAo13UlCdKk+yV9vlSmoSVL8USu6qSy7gxIkTTdvVVlkqtVvpJTelPy9BZd5Daa/fnJnLxmH6u7PX7Ua3UQvQsNtEvPbFMPzrk/54/ON+5L54jPxoC3GfvPAxn+v8sHAc8/qJe/Rj5meZT5CfbzXQ/Fc77X5YgMm/J2NXWiYyKTSz4+e89D2V+Lyzl5e9gveywKS4wvHeOG+8d5fPN51vmsLsTVNcOm+9pyrHl71pfcv2ZW+6wn0q7rpwPm98cW3zl8f33ve5vzjvvW+8b1sL8+mk8WVv+vMOVFr4y6Oy74ycTvHasg6znkA22+zvTNZZM0Gc6cuMU5jNUPXpfZPcUssCk37Vx+s/n9z9UxpfYXuvT8bFpbP15XPhZ773/thfvpPF+7L3+emmKy7uVPnFhdOc7P5Myj7V8+JY+bx5i6uvcJqSIv+WimxPjKtxVGKFApoazLbaF7O2AzaNt0O+4enEFX7O0Pzx2gn1p3gGMjlEiNMOJ4V5hpI75xWgABWmYiwVVZbo8X4LJCa0nGvN/FJ1u+tWlAW6s2EvhAr+8UleG/LrtzBSO2RSTbxpeYACVPLkF1TSUK9SG3WVFpto758T9V9iW6cAZG6cUCxAWegpOkABOh/IP6gCFKAAnTUFQBWgAJ1jCoAqQAE6x3QaoMrCzgWj0S+yI77uFIVBk9fioNuFpMXLcFCLmTxyY/+aKRgc3Qlfte+IqEFTsPag765cFrbMGoKor79A61at0Kp1a3zxZXt06NEHo+YmI8NJVRy51s7Cb7tOc5fPtRazftvlZ0/QtiHyq9bm+E2rVq3R+os2aNv+W0R/PwHLU4r5sM29H2umDEZ0p6/QviNlMIUyOIMNx4wdC5DQtzu+adcWX/f8HpNXp8KVtgYrN5bcNz9+KXcb+rxaEbe8OwXpTtRZUdZOLBjdD5Edv0anqEGYvPYg3K4kLF520Kx/z2s6Axmkb5qMqGav4p4HPsKsbCeSdFJQZW0YjveeuhfPvhuHScu3YW/KdqyYFIPmr92Lig9+iWVenTiyDPF1HsFjdXpi8qqd2JeyBYtGtkelfzyCJkPW+wDGg5QBr6Bc0LWo0X8ZNm5YgZ9j6+KuchfitvojsL1YRT2En2pXxH1tluF0vuc89FNtVLyvDZb5TezB3r4v4cKgiqiXkGJ+pit991KM/PwZXFPhbjQelVwAjEeWxaPOI4+hTs/J5v93StmyCCPbV8I/HmmCIetPMRXk7sKk1s/hjgdro3PCAiTt3oe9zJ/QuT6evfMGvD7kqJPwfKFsbJ3SD4PmpJyl8mdhw/D38NS9z+LduElYvm0vUravwKSY5njt3op48Mtlp/n/JJYknb4M3OkHMb/1fQj963uYeTqg8uwehTo3XIT7Ws1HmhPnJc/+iWj6bHNbkGs94l68Cpe9EIdNhUCRsegL3Ffur6j14868BmZNboRrQ/6G9/OgnYU5H92K4KAb8J5vy3wod3ssXrgkCEHXN8GUwo0pTLnbEfvCJQgKuh5NiklctA0kzz4MrlIBwdc2xERninKtj8OLV12GF+I2FbJ6GVj0xX0o99da+HFnMaL3HML0D/+Oi29tjAn7CqfJxfZBNVCt51Y/1vT/Knmwe1Qd3HDRfWg1v4jGYP/Epni2+Uyq7P9PRCPR+wWEnR6o0jDtnb8huHw4Bqf6UxoqxbDBmJ7FQodWxWXB16D+eD8K7NmFXs+HIfiGtzHNUdSsKY0LKbQHO6OfQWipS1BjlL+Z34WlbV9Fo7aNcVPIpagy+OQziGtpW7zaqC0a3xSCS6sMRoqfxEXbIMrGrx/chKDQZxG7i5k8KRha9TIEX1Mf/rvWC8+HBeOGt6f5dROy5n2KO0Muxsv9dvtvr3stpk7bVghUHmQfP4K0IhbWjYwMzfFuZJnQS8WldznpmSIrDQWy+JAr7TCOcgwLkgvp6YULdCP98GGknczMpE3DO38LRvnwwfCvMtsxbPB0TqE+lJuBo8ez/crHnZHheCXqu297cpFxPN2vxfNkH8eRosIwZSm9O8uG+eRC2uGjKCICfzJwp+Pw4bRC+T1I7fviaYIqazIaXVsaIU92x9aTTqVHMbzaJSgV+jzi9/oTjQuLW91FK/TXPCtURKFp6SKfLY/g62pipD8EHKVVfLktlmaswlcPhCLsyW7YWGybjmJi05fRdmkGVn31AELDnkQ3P4n9gSp37zg0JBAv/md3rJPkjg5HtUtKIfT5ePjv2mK0uisYQYUEaikLPzepiKCQp9Bj2+nZoqNL+6Nl84/x+Ydv4enbbsGzLSdgV04qFg9uher3X4e73uuLnpWuR5myd9MSuIpJvx+Lh7RG9X9ci7s+GImEz17ArZeVQejVz6Lr0nx1Tl8zGJ82/QDtOrZHwydvx6PvjMAW1yGsGNEONR+8Dje9PdWxKB7sm9YOVV6uhzYdW6Pmw7fhiY8n+p+oZP1Lh+DJ7qdhfbOSMKp1YzT5tB3afVgFjzxYBV9P38vaPDi8ciTa1XoQFW99B6OmdkatJ27BpWXK4553E7GN7W7yz9twzUVlcNW/OmKhdzY7uhT9WzbHx59/iLeevg23PNsSE3blIHXxYLSqfj+uu+s99O1ZCdeXKYu76XlRAlgz+FM0/aAdOrZviCdvfxTvjNgCN72LIjKgBzOtXRW8XK8NOrauiYdvewIfT/RO7GcAKs+OKDwdWgphEcNOvljLTUKXx0JQ6qLqGOF3eUGL1vOfCClVFpWHHDcxRqGDr8Czn8QgskMLvPH43bi/UgsMWWmfFyS6FANrICJWM7oHyb2ex0Uht+PTBUW02JBn90DUiIiF9NiTTEtyUQhu/3RBEZfDtCEoDDc/+yYaN22MOhHP4K4ry+Cyhz/GxD1WJXKTuuCxkFK4qPoI/5sonHl7/pN9L1sZTtfyiYvdHk/yWVhVDCskwNzdCzCsy0eoV7sWajf4DNFTNgKHJ6Dpiy0xz6koY3ZzWuWr8GbCEc6Qa/HNg6Go8GALTNyyDuP7/YCFm8edJP0qtP9HKC579H0MWX4I7mxORg+VwTWNJlkrcWgimtzzEno5C9iMSY1wXejdaL2EM3PuRnR+tAwqNnUUKnc9Oj58IR7vusUAJWPcW7iyXDiKLgU92BH1NL2NMEQU7nARSsecz+4j6Gc7cs1Fcr9XcFmFZxC5QbXkIqnLYwi97Bm0mbSJqT3YMzgCl4fehqo9fsZ2dsK9JRL/Knc53kxUCYcxoemLaJkvDDTn5HjVmwk4Qruy9psHEVrhQbSYuAXrxvfDDwv3UwRNcM9LvZw1fAYmNboOoXe3hkRQWAa56zvi4QsfR9ctRgIY99aVKBc+hNO36ExAtacXniOoylYe7GQuhujexT4bSsWqhEF+E+ZiS7cnCKoKqJ1gO22txF/QYNRGzP32GVQoczuaTTvE5vkh9xp0fKUuRux3nh4cgTeuCMa19cZSYIXJjTUdX0HdEfudsg5ixBtXcI1UD2MLJS5sqTzpO7FwaAs8eUUILn+0FaazPs+uWDwrGVQa5F8GuVvQ7QkCp0JtOF3LJ8olhnK5IPQF9PY3rR8fiRoVSqNCzZ/MejXtp5q48u6a+LpzZ/OpROeObfFJ8w/RZTJdR89e9HouDLd8NMdxh06Vfiei/8X0LeY66bMwscHVCHP6cXBwOC598BuszfNj3Dh+6KiT9iD6vxyWDyo+2zF3IhbuycbB1eMQ2/RRXFr2eT99ouL3eo6g4uQ5+KQaQ6syCm9cdjGq/egDvqOSRwju/mKpuT008BWEXf8uZjiK6l7zNR4M+wfar3QanT0D7/41DC/2TZUwUPPKu1Hza0cWnTui7SfN8WGXydjt8WAv2xV2y0eY4xUe+zg4/FI8+M3afFfOfRyHjnoTFJKBewfmTlyIPdkHsXpcLJo+einKPt/bsdZn4v655qLFrcEIvqsVFuc1xh+5MP+T2xAcfBdaGZgXpmzMep9rs7LPINqZGQsoNBWz76tXIuRv9ZHoR/nSZzbH40/VwXvNmpkP8po1exe1HrkSQZe8jH5a9/hS+kw0f/wp1HnPm7YZ3q31CK4MuoTrml0FQFsYVJY428Y9j3KlQqzAXfPxyW1WBv67Ngvvcw1R9ploP7uWLiz87A4El74G9fwtyLJ/RpOKIc7AcU0Z8wzKPdalyEaPIYIqnuvSfJCcKr0A7Zs+27iiYa9+x4koFxs6PYyyd36ORX7H9TAGvuILKs7NSaPRpkF9tOo7C0lTmuHGMP+uvmtuC9waHIy7Wi126vVPues74eGQcqg6zEcu7qX44u4QXN1wkrk98t2rBUAla/FQ2H1olwcqyT4ML/TZB/fOGDxT7jF08S8M7I1/nqBqgbneRuVuQKeHy+LOzxcV087CMshA0ug2aFC/FfrOSsKUZjciLG9JcCag0qz/7WMoF3wrms3075YdXjITiw954F7XFU/SzbqbwvRVUUNZ8/HpHVSeOgl5i9fCCp278wdUvy4E11X/ATt85eJJxbC6EeiZVFBY7lVf4YHQUDz0zZr8mUadG1YXET2TjJuSR3SFtA4LfegbrMlPXAyoOFZTm6BiUDBuNQrpxrquT9Ld5BpmcZGesWuf4o6QiqiTkMrai1Lulni8eGkQKrzctyjoCoBKhqsGKpR9wKwF8yhrBWbO2WcsVUFQnSr9yUDFeXhQZdOnzxfmr7Fyd4zG0Oka50IKlT4N797EPo6xpj6bbmZxoJJX8e1j5RB8azP4V5nDWDJzMQ4d5FqVVun2zxbkK7VrAT67/SI8HbnN3J4JqDzG6pfFA22X+rjpWVgxcw72yVIVBhUt0aDKFyHk7s+RL4Jc7Bg9FEYEhWSQPu1d3FSxDqwIsjG7+VmDipS1Br1euw5l/xqOHnP2+qDahZ3TYvDN0JVOJ1zYMPB13HTF42g//3C+guXuw/RPH8b1j7XCDK/7RsoaXw9XBFdEk6neVniQklgPN4Rcime6rshbw7lXd8QLNYcU3Uny7KDLqR3FJpji9TTcq9HxhZoYUjQxdsQ+i7DgG9AkLzHbwLXBFYVB5dqGoa9XRPCF96OtF0SuDRj4+k244vH2mH84v+zcfdPx6cPX47FWM+DTtUKkgWqCuy++HE+1m419vsAqBCocnoQmfwtB8NX/RLOYUZg4cSi+rlMfMdoxcdy/G5vNtmlFJ02fjMinuV780OsuZmNK4+sQ9spAHOKdZ/8Y1K0YjDI3R6DD8EmYPLI7GldtiZ+N0tD1eSkM1zX+2dRlXOAy5fF8TBKys5Lx8xdP4dIyT6DzwmVYWWAGtJS1phdeu64s/hreA3P25msMXDsxLeYbDF0pjTmOWR/dgbCKNTFijxWee30X/PM2eivO+B0aQPfvL29jmtPh3HUdCKp70XaFF1Qz8f4NYXg+XpsbhzGpyd8QEnw1/tksBqMmTsTQr+ugfsw6TouO+3djM8zOE54H+8fURcXgMrg5ogOGT5qMkd0bo2rLn50lha8MPNhF/SlT/nnEJGUjK/lnfPHUpSjzRGcsXLaSRkBb6gTt9e9gel75JwOViMKY3qMx/n3frbjzoX/hpYiaqNf4A3QYk1Rwa5QKlDIvHs2rh+ONRu+i2ftvo8Hr1dDgqzHYkGflaUZnDkL7165HUKlg/I0dGjRrqx14zyFMfe82hARdgYfeaovI3l3Q6MHyuOSBxlzI+9ZFRf01Hg3vDUMpumk3VmqLH2dMRZ/GD6L8JQ+gcfQUJPk0LHfHr4hveC/CSpVCyI2V0HbkQrbhe7R/9S9sQxhueDwcNRs0xbuN66DSY3/HPc+8hc7TdvlYQFJuCubFN0f18DfQ6N1meP/tBni9WgN8NWZDkfd3/ujo2tH4puHLePblWni7RUu0/Ph9NKhWGTU+iMSE9flAT1v1HZo+dQPK0wpXuOUFtByXbHejRrXGc1cHsf1V0WnC+rxJx296rprWJLbGs1cEIey+xoifnYSk6dGoc2cogiq+iq8nbjD5jyvvP2/CZRdfihuffgeDV6snx7mQb4cXr2Fdd9ZFr193CFVIfP9BXHXJVbjr5RYYOqUHXrzyctzb8Ads8sGML7l2TkePxv/GfbfeiYf+9RIiatZD4w86YIzvwGQnYdTHL+GBR15Fw2bN0bjxZxi6xkozfcMEtH/5OgSV+Tvq95qJTckLMLgFwRxUHo82G4C52zZhRkw9/L1sEK7595cYt5750lbhu6ZP4Yby9Eoq3IIXWo5DstuDQytGofVzVyMo5EZU7TQB6/OWccex6rum+OdNl+HiS2/E0+8MhhGBHxl4diXi/QevwiVX3YWXWwzFlB4v4srL70XDHzbhyMYJaPM8lyOhd6JuzAxsdWRyclAFKEABOmMKgCpAATrHFABVgAJ0jikAqgAF6BxTAFQBCtA5pgCoAhSgc0wBUAUoQOeYAqAKUIDOMQVAFaAAnWMKgCpAATrHFABVgAJ0jikAqgAF6BxTAFQBCtA5pgCoAhSgc0wBUAUoQOeYAqAKUIDOMQVAFaAAnWMKgCpAATrHFABVgAJ0jikAqgAF6BxTAFQBCtA5pgCoAhSgc0wBUAUoQOeUgP8HvB5HGXLl8kgAAAAASUVORK5CYII='
        return logo;

}



