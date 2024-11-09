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
    include '../clases/class.cortes.php';
    include '../clases/class.parametros.php';

    $_SESSION['tiempo']=time();
    $coduser = $_SESSION['codigo'];
    $inmueble = ($_GET['cod_inmueble']);
    $p=new cortes_inmuebles();
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
                <div><b>INCORPORACIÓN</b> >> Inmuebles >>Cortes</div>
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
            <th align="center">PERIODO</th>
            <th align="center">TIPO CORTE</th>
            <th align="center">IMPO CORTE</th>
            <th align="center">LECTURA</th>
            <th align="center">IMPO LECTURA</th>
            <th align="center">FECHA</th>
            <th align="center">OBSERVACIONES</th>

        </tr>
    </table>

    <table id="flex1" style="display:none"></table>
    <script type="text/javascript">
        $('.flexme1').flexigrid();
        $('.flexme2').flexigrid({height:'auto',striped:false});
        $("#flex1").flexigrid	(
            {

                url: './../datos/datos.cortes.php?cod_inmueble=<?php echo $inmueble;?>',

                dataType: 'json',
                colModel : [
                    {display: 'No', name: 'rnum', width: 20,  align: 'center'},
                    {display: 'Periodo', name: 'ID_PERIODO', width: 50, sortable: true, align: 'center'},
                    {display: 'Tipo corte', name: 'TIPO_CORTE', width: 70, sortable: true, align: 'center'},
                    {display: 'Impo corte', name: 'IMPO_CORTE', width: 90, sortable: true, align: 'center'},
                    {display: 'Lectura', name: 'LECTURA', width: 95, sortable: true, align: 'center'},
                    {display: 'Impo lectura', name: 'IMPO_LECTURA', width: 95, sortable: true, align: 'center'},
                    {display: 'Fecha', name: 'FECHA', width: 80, sortable: true, align: 'center'},
                    {display: 'Observaciones', name: 'OBS_GENERALES', width: 90, sortable: true, align: 'center'}
                ],
                sortname: "FECHA_EJE",
                sortorder: "desc",
                usepager: true,
                title: 'Cortes',
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



                            alert("ITEMS "+itemlist+" servicios");
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

