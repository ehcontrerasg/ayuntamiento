<?php
session_start();

include '../../destruye_sesion.php';

$_SESSION['tiempo']=time();
$_POST['codigo']=$_SESSION['codigo'];
$inmueble=$_GET['cod_inmueble'];


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
<form name="FMTarifas" action="vista.tarifas.php" method="post" onsubmit="return rend();">
    <div class="flexigrid" style="width:100%">
        <div class="mDiv">
            <div><b>ADMINISTRACION</b> >> Listado de Tarifas</div>
            <!--<div style="background-color:rgb(255,255,255)">
            </div>-->
        </div>
</form>
<?php
//if($proc == 1){
?>
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
<div>
    <table id="Exportar_a_Excel" style="display:none">
        <tr>
            <th align="center">ID_TARIFAS</th>
            <th align="center">TARIFA</th>
            <th align="center">SERVICIO</th>
            <th align="center">USO</th>
            <th align="center">CODIGO TARIFA</th>
            <th align="center">CONS MINIMO</th>
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

            url: './../datos/datos.tarifas.php?proyecto=SD',

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:15,  align: 'center'},
                {display: 'Consec tarifa', name: 'TAR.CONSEC_TARIFA', width: 60, sortable: true, align: 'center'},
                {display: 'Tarifa', name: 'TAR.DESC_TARIFA', width: 170, sortable: true, align: 'center'},
                {display: 'Concepto', name: 'TAR.COD_SERVICIO', width: 42, sortable: true, align: 'center'},
                {display: 'Uso', name: 'TAR.COD_USO', width: 20, sortable: true, align: 'center'},
                {display: 'Codigo Tarifa', name: 'TAR.CODIGO_TARIFA', width: 28, sortable: true, align: 'center'},
                {display: 'Consumo Min', name: 'TAR.CONSUMO_MIN', width: 65, sortable: true, align: 'center'},
                {display: 'Rango 1', name: 'RANGO_1', width: 36, sortable: true, align: 'center'},
                {display: 'Valor 1', name: 'VALOR_1', width: 30, sortable: true, align: 'center'},
                {display: 'Rango 2', name: 'RANGO_2', width: 36, sortable: true, align: 'center'},
                {display: 'Valor 2', name: 'VALOR_2', width: 30, sortable: true, align: 'center'},
                {display: 'Rango 3', name: 'RANGO_3', width: 36, sortable: true, align: 'center'},
                {display: 'Valor 3', name: 'VALOR_3', width: 30, sortable: true, align: 'center'},
                {display: 'Rango 4', name: 'RANGO_4', width: 36, sortable: true, align: 'center'},
                {display: 'Valor 4', name: 'VALOR_4', width: 30, sortable: true, align: 'center'},
                {display: 'Rango 5', name: 'RANGO_5', width: 36, sortable: true, align: 'center'},
                {display: 'Valor 5', name: 'VALOR_5', width: 30, sortable: true, align: 'center'}
            ],
            buttons: [
                {name:'Agregar', bclass:'add', onpress: test},
                {separator: true},
                {name:'Editar', bclass:'edit', onpress: test},
                {separator: true}
            ],


            searchitems : [
                {display: 'Consec tarifa', name: 'TAR.CONSEC_TARIFA', isdefault: true},
                {display: 'Tarifa', name: 'TAR.COD_SERVICIO'},
                {display: 'Concepto', name: 'TAR.COD_SERVICIO'},
                {display: 'Uso', name: 'TAR.COD_USO'}
            ],





            sortname: "TAR.CONSEC_TARIFA",
            sortorder: "asc",
            usepager: true,
            title: 'Tarifas',
            useRp: true,
            rp: 30,
            page: 1,
            showTableToggleBtn: true,
            width: 960,
            height: 270
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
        popup("vista.agregatarifa.php",770,610,'yes');
    }

    function edita(id) { // Traer la fila seleccionada
        popup("vista.editartarifa.php?id_tarifa="+id,800,600,'yes');
    }

    function test(com,grid)
    {

        if (com=='Agregar')
        {
            //alert('Add New Item Action');
            agrega();

        }

        if (com=='Editar')
        {
            if($('.trSelected',grid).length>0){

                var items = $('.trSelected',grid);
                var itemlis ='';
                for(i=0;i<items.length;i++){
                    itemlis+= items[i].id.substr(3)+",";
                }
                itemlis=itemlis.substr(0,itemlis.length-1);
                edita(itemlis);


            } else {
                return false;
            }
        }




    }


    //-->
</script>
</body>
</html>
