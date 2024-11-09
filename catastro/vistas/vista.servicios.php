<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();

    /********************************************************************/
    /*  SOFTWARE DE GESTION COMERCIAL ACEA DOMINICANA       	        */
    /*  ACEA DOMINICANA - REPUBLICA DOMINICANA							*/
    /*  CREADO POR EDWIN HERNAN CONTRERAS								*/
    /*  FECHA CREACION 17/05/2015								*/
    /********************************************************************/


    include '../../destruye_sesion.php';
    include '../clases/class.concepto_inmueble.php';
    include '../clases/class.parametros.php';

    $_SESSION['tiempo']=time();
    $coduser = $_SESSION['codigo'];
    $inmueble = ($_GET['cod_inmueble']);
    $p=new Concepto_inmueble();
    $a=new parametros();
    $codcargo=$a->ObtenerCargo($_SESSION['codigo']);
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" href="../../flexigrid/style.css" />
        <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
        <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
        <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
        <script language="javascript">
            $(document).ready(function() {
                $(".botonExcel").click(function(event) {
                    $("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).append( $("#flex1").eq(0).clone()).html());
                    $("#FormularioExportacion").submit();
                });
            });
        </script>
    </head>

    <body>
    <form name="servicios" action="vista.servicios.php" method="post" onsubmit="return rend();">
        <div class="flexigrid" style="width:100%">
            <div class="mDiv">
                <div><b>INCORPORACIÓN</b> >> Inmuebles >>servicios</div>
            </div>
        </div>
    </form>

    <form action="../../funciones/ficheroExcel.php" method="post" target="_blank"  id="FormularioExportacion">
        <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
        <div class="flexigrid" style="width:100%">
            <div class="mDiv">
                <div>Exportar a:</div>
                <div style="background-color:rgb(255,255,255)">
                    <img src="../../images/excel/xls.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="XLS"/>
                    <img src="../../images/excel/pdf.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="PDF"/>
                </div>
            </div>
        </div>
    </form>

    <table id="Exportar_a_Excel" style="display:none">
        <tr>
            <th align="center">CODIGO SERVICIO</th>
            <th align="center">SERVICIO</th>
            <th align="center">UNIDADES TOTALES</th>
            <th align="center">UNIDADES HABITADAS</th>
            <th align="center">CUPO BASICO</th>
            <th align="center">PROMEDIO</th>
            <th align="center">CONSUMO MINIMO</th>
            <th align="center">ACTIVO</th>
        </tr>
    </table>

    <table id="flex1" style="display:none"></table>
    <script type="text/javascript">
        $('.flexme1').flexigrid();
        $('.flexme2').flexigrid({height:'auto',striped:false});
        $("#flex1").flexigrid	(
            {

                url: './../datos/datos.servicios.php?cod_inmueble=<?php echo $inmueble;?>',

                dataType: 'json',
                colModel : [
                    {display: 'No', name: 'rnum', width: 20,  align: 'center'},
                    {display: 'Codigo', name: 'CON.COD_SERVICIO', width: 50, sortable: true, align: 'center'},
                    {display: 'Servicio', name: 'CON2.DESC_SERVICIO', width: 70, sortable: true, align: 'center'},
                    {display: 'Unidades Totales', name: 'CON.UNIDADES_TOT', width: 90, sortable: true, align: 'center'},
                    {display: 'Unidades Habitadas', name: 'CON.UNIDADES_HAB', width: 95, sortable: true, align: 'center'},
                    {display: 'Cupo Basico', name: 'CON.CUPO_BASICO', width: 95, sortable: true, align: 'center'},
                    {display: 'Promedio', name: 'CON.PROMEDIO', width: 80, sortable: true, align: 'center'},
                    {display: 'Consumo Minimo', name: 'CON.CONSUMO_MINIMO', width: 90, sortable: true, align: 'center'},
                    {display: 'Tarifa', name: 'CON.TARIFA', width: 120, sortable: true, align: 'center'},
                    {display: 'Uso', name: 'AC.ID_USO', width: 120, sortable: true, align: 'center'},
                    {display: 'Activo', name: 'CON.ACTIVO', width: 90, sortable: true, align: 'center'}

                ],
                <?php if($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5 ){ ?>
                buttons: [
                    {name:'Agregar', bclass:'add', onpress: test},
                    {separator: true},
                    {name:'Editar', bclass:'edit', onpress: test},
                    {separator: true},
                    {name:'Eliminar', bclass:'delete', onpress: test},
                    {separator: true},

                    ,
                    {name:'Cambio Estado', bclass:'delete', onpress: test},
                    {separator: true}
                ],
                <?php } ?>
                sortname: "CON.COD_SERVICIO",
                sortorder: "asc",
                usepager: true,
                title: 'Servicios',
                useRp: true,
                rp: 30,
                page: 1,
                showTableToggleBtn: true,
                width: 900,
                height: 250
            }
        );


        //FUNCION PARA ABRIR UN POPUP
        var popped = null;
        function popup(uri, awid, ahei, scrollbar) {
            var params;
            if (uri != "") {
                if (popped && !popped.closed) {
                    popped.location.href = uri;
                    popped.focus();
                }
                else {
                    params = "toolbar=no,width=" + awid + ",height="+ ahei +",directories=no,status=no,scrollbars=" + scrollbar + ",menubar=no,resizable=no,location=no";
                    popped = window.open(uri, "popup", params);
                }
            }
        }




        function agrega() { // Traer la fila seleccionada
            window.open("vista.agregarconcepto.php?inmueble=<?php echo $inmueble;?>" , "ventana1" , "width=770,height=6600,scrollbars=yes")
        }

        function edita(valor) { // Traer la fila seleccionada
            window.open("vista.editarservicio.php?inmueble=<?php echo $inmueble;?>&codser="+valor , "ventana1" , "width=770,height=6600,scrollbars=yes")
        }


        function Cambiousuario(id) { // Traer la fila seleccionada
            popup("vista.cambiousuario.php?id_contrato="+id,800,600,'yes');
        }

        function test(com,grid)
        {

            if (com=='Agregar')
            {
                //alert('Add New Item Action');
                agrega();

            }



            if (com=='Cambio Estado')
            {
                var items = $('.trSelected',grid);
                var itemlist ='';
                itemlist+= items[0].id.substr(3);
                <?php  $codser= '<script> document.write(itemlist); </script>';?>

                if(confirm('Desea Cambiar el estado del servicio de  '+itemlist+' ?')){




                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "../datos/datos.CambioEstServ.php?inmueble=<?php echo $inmueble;?>",
                        data: "items="+itemlist,
                        success: function(data){
                            //alert("Has eliminado "+data.total+" registro(s)");
                            $("#flex1").flexReload();
                        }
                    });
                }

            }

            if (com=='Editar')
            {
                {
                    if($('.trSelected',grid).length==1)
                    {

                        var items = $('.trSelected',grid);
                        var itemlist ='';
                        itemlist+= items[0].id.substr(3);

                        edita(itemlist);


                    }
                    else
                    {
                        return false;
                    }
                }

            }




            if (com=='Eliminar')
            {
                {
                    if($('.trSelected',grid).length>0){
                        if(confirm('Desea cancelar este Servicio?')){
                            var items = $('.trSelected',grid);
                            var itemlist ='';

                            for(i=0;i<items.length;i++){
                                itemlist+= items[i].id.substr(3)+",";

                            }



                            $.ajax({
                                type: "POST",
                                dataType: "json",
                                url: "../datos/datos.eliminaservicios.php?inmueble=<?php echo $inmueble;?>",
                                data: "items="+itemlist,
                                success: function(data){
                                    alert("Has eliminado "+data.total+" servicios");
                                    $("#flex1").flexReload();
                                }
                            });
                        }
                    } else {
                        return false;
                    }
                }

            }

        }



    </script>
    </body>
    </html>



<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

