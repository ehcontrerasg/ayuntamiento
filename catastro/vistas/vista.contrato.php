<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <?php
    session_start();

    include '../../destruye_sesion.php';
    include '../clases/class.parametros.php';
    $_SESSION['tiempo']=time();
    $_POST['codigo']=$_SESSION['codigo'];
    $inmueble=$_GET['cod_inmueble'];
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
    <form name="contratos" action="vista.contrato.php" method="post" onsubmit="return rend();">
        <div class="flexigrid" style="width:1110px">
            <div class="mDiv">
                <div><b>INCORPORACIÓN</b> >> Listado de Contratos</div>
                <!--<div style="background-color:rgb(255,255,255)">
                </div>-->
            </div>
        </div>
    </form>
    <?php
    //if($proc == 1){
    ?>
    <form action="../../funciones/ficheroExcel.php" method="post" target="_blank"  id="FormularioExportacion">
        <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
        <div class="flexigrid" style="width:1110px">
            <div class="mDiv">
                <div>Exportar a:</div>
                <div style="background-color:rgb(255,255,255)">
                    <img src="../../images/excel/xls.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="XLS"/>
                    <img src="../../images/excel/pdf.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="PDF"/>
                </div>
            </div>
        </div>
    </form>
    <div>
        <table id="Exportar_a_Excel" style="display:none">
            <tr>
                <th align="center">CONTRATO</th>
                <th align="center">INMUEBLE</th>
                <th align="center">FECHA INICIO</th>
                <th align="center">FECHA FIN</th>
                <th align="center">CODIGO CLIENTE</th>
                <th align="center">NOMBRE CLIENTE</th>
                <th align="center">DOCUMENTO</th>
            </tr>
        </table>
        <table id="flex1" style="display:none">
        </table>
    </div>
    <script type="text/javascript">
        <!--
        $('.flexme1').flexigrid();
        $('.flexme2').flexigrid({height:'auto',striped:false});
        $("#flex1").flexigrid	(
            {

                url: './../datos/datos.contratos.php?cod_inmueble=<?php echo $inmueble;?>',

                dataType: 'json',
                colModel : [
                    {display: 'No', name: 'rnum', width: 25,  align: 'center'},
                    {display: 'Contrato', name: 'CON.ID_CONTRATO', width: 100, sortable: true, align: 'center'},
                    {display: 'Id Inmueble', name: 'CON.CODIGO_INM', width: 90, sortable: true, align: 'center'},
                    {display: 'Fecha inicio', name: 'CON.FECHA_INICIO', width: 100, sortable: true, align: 'center'},
                    {display: 'Fecha fin', name: 'CON.FECHA_FIN', width: 100, sortable: true, align: 'center'},
                    {display: 'Codigo cliente', name: 'CON.CODIGO_CLI', width: 80, sortable: true, align: 'center'},
                    {display: 'Alias', name: 'CON.ALIAS', width: 230, sortable: true, align: 'center'},
                    {display: 'Nombre', name: 'CLI.NOMBRE', width: 230, sortable: true, align: 'center'},
                    {display: 'documento', name: 'CLI.DOCUMENTO', width: 80, sortable: true, align: 'center'}
                ],
                <?php if($codcargo==1 or $codcargo==2 or $codcargo==3 or $codcargo==5  or $codcargo==11){ ?>
                buttons: [
                    {name:'Agregar', bclass:'add', onpress: test},
                    {separator: true},
                    {name:'Dar de baja', bclass:'edit', onpress: test},
                    {separator: true},
                    {name:'Cambiar Nombre', bclass:'edit', onpress: test},
                    {separator: true},
                    {name:'Agregar Usr. Temporal', bclass:'add', onpress: test},
                    {separator: true},
                    {name:'Cambio Usuario', bclass:'edit', onpress: test},
                    {separator: true}
                ],
                <?php } ?>

                searchitems : [
                    {display: 'Contrato', name: 'CON.ID_CONTRATO', isdefault: true},
                    {display: 'Id Inmueble', name: 'CON.CODIGO_INM'},
                    {display: 'Codigo cliente', name: 'CON.CODIGO_CLI'},
                    {display: 'Nombre', name: 'CLI.NOMBRE_CLI'},
                    {display: 'documento', name: 'CLI.DOCUMENTO'}
                ],





                sortname: "CON.FECHA_INICIO",
                sortorder: "DESC",
                usepager: true,
                title: 'Contratos',
                useRp: true,
                rp: 30,
                page: 1,
                showTableToggleBtn: true,
                width: 1110,
                height: 350
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


        //-->



        function agrega() { // Traer la fila seleccionada
            popup("vista.agregarcontrato.php",770,610,'yes');
        }


        function Cambiousuario(id) { // Traer la fila seleccionada
            popup("vista.cambiousuario.php?id_contrato="+id,800,600,'yes');
        }

        function  AddNombreTemp() { // Traer la fila seleccionada
            popup("vista.agregaCliTemp.php","tempNombre", 800,600,'yes');
        }

        function edita(id) { // Traer la fila seleccionada
            popup("vista.editacontrato.php?id_contrato="+id,800,600,'yes');
        }

        function test(com,grid)
        {

            if (com=='Agregar')
            {
                //alert('Add New Item Action');
                agrega();

            }


            if (com=='Cambio Usuario')
            {
                if($('.trSelected',grid).length>0){
                    if(confirm('¿Desea cambiar el nombre de este contrato?')){
                        var items = $('.trSelected',grid);
                        var itemlis ='';
                        itemlis= items[0].id.substr(3);
                        Cambiousuario(itemlis);

                    }
                } else {
                    return false;
                }
            }

            if (com=='Agregar Usr. Temporal')
            {

                if(confirm('Desea Agregar Un nombre a este inmueble')){
                    AddNombreTemp();

                }

            }

            if (com=='Cambiar Nombre')
            {
                if($('.trSelected',grid).length>0){

                    var items = $('.trSelected',grid);
                    var itemlis ='';
                    itemlis= items[0].id.substr(3);
                    edita(itemlis);


                } else {
                    return false;
                }
            }


            if (com=='Dar de baja')
            {
                {
                    if($('.trSelected',grid).length>0)
                    {
                        if(confirm('¿Desea cancelar este Contrato :'+ $('.trSelected',grid)[0].id.substr(3)+'?'))
                        {
                            var items = $('.trSelected',grid);
                            var itemlist ='';
                            itemlist+=items[0].id.substr(3);
                            $.ajax(
                                {
                                    type: "POST",
                                    dataType: "json",
                                    url: '../datos/datos.cancelacontrato.php',
                                    data: "items="+itemlist,
                                    success: function(data)
                                    {
                                        if(data.codigo==0){
                                            alert("Has cancelado correctamente el contrato");
                                            $("#flex1").flexReload();
                                        }else {
                                            alert("ERROR: "+data.query);
                                            $("#flex1").flexReload();
                                        }

                                    }
                                });
                        }
                    }
                    else
                    {
                        return false;
                    }
                }
            }

        }


        //-->
    </script>
    </body>
    </html>


<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

