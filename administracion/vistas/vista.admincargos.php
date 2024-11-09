<?php
session_start();
include '../clases/class.personal.php';
include '../../destruye_sesion.php';
$_SESSION['tiempo']=time();
$_POST['codigo']=$_SESSION['codigo'];
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
    <style type="text/css">
        .input{
            border:1px solid #ccc;
            font-family: Arial, Helvetica, sans-serif;
            font-size:11px;
            font-weight:normal;
            width: 180px;
        }
        .table{
            border:1px solid #ccc;
            border-left:0px solid #ccc;
        }
        .th{
            background: #fafafa url(../../flexigrid/css/images/fhbg.gif) repeat-x bottom;
            height:24px;
            border:1px solid #ccc;
            border-left:0px solid #ccc;
            border-bottom:0px solid #ccc;
            border-top:0px solid #ccc;
            font-size:11px;
            font-weight:normal;
            font-family: Arial, Helvetica, sans-serif;
        }
        .tda{

            height:24px;
            border:0px solid #ccc;
            border-left:1px solid #ccc;
            padding:0px;
            font-size:11px;
            font-weight:normal;
            font-family: Arial, Helvetica, sans-serif;
        }

        select{
            border:1px solid #ccc;
            font-family: Arial, Helvetica, sans-serif;
            font-size:11px;
            font-weight:normal;
            width: 146px;
        }
        .btn-info{
            color:#fff;
            background-color:#5bc0de;
            border-color:#46b8da;
        }
        .btn{
            display:inline-block;
            padding:6px 12px;
            margin-bottom:0;
            font-size:14px;
            font-weight:400;
            line-height:1.42857143;
            text-align:center;
            white-space:nowrap;
            vertical-align:middle;
            cursor:pointer;
            -webkit-user-select:none;
            -moz-user-select:none;
            -ms-user-select:none;
            user-select:none;
            background-image:none;
            border:1px solid transparent;
            border-radius:4px
        }
    </style>
</head>
<body>
<form name="FMCargos" action="vista.admincargos.php" method="post" onsubmit="return rend();">
    <div class="flexigrid" style="width:100%">
        <div class="mDiv">
            <div><b>Administraci&oacute;n de Cargos</b></div>
        </div>
    </div>
</form>




<form action="../../funciones/ficheroExcel.php" method="post" target="_blank"  id="FormularioExportacion">
    <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
    <div class="flexigrid" style="width:100%">
        <div class="mDiv">
            <div style="background: linear-gradient(#F7F7F7,#F7F7F7); border-top:1px solid #ccc;">Exportar a:&nbsp;&nbsp;
                <img src="../../images/excel/xls.ico" onMouseOver="this.src" width="20" height="20" class="botonExcel" title="Excel" style="vertical-align:middle"/>
                <!--img src="../../images/excel/pdf.ico" onMouseOver="this.src" width="20" height="20" class="botonPdf" title="PDF" style="vertical-align:middle"/-->
            </div>
        </div>
    </div>
</form>
<form id="ficha" method="post">
    <div>


        <table id="flex1" style="display:none;">
            <thead>
            <tr>
                <th align="center" class="th" style="width:28px">Id</th>
                <th align="center" class="th" style="width:192px">Descripción</th>
                <th align="center" class="th" style="width:156px">Area</th>
            </tr>
            </thead>
            <tbody>
            <tr >
                <?php
                $l=new personal();
                $registros=$l->todoscargos();
                while (oci_fetch($registros)) {
                $id_cargo = oci_result($registros, 'ID_CARGO');
                $desc_cargo = oci_result($registros, 'DESC_CARGO');
                $desc_area = oci_result($registros, 'DESC_AREA');
                $id_area = oci_result($registros, 'ID_AREA');
                ?>
                <td class="tda" style="width:24px; text-align:center"><b><?php echo $id_cargo?></b></td>

                <td class="tda" style="width:100px; text-align:center;">
                    <div style="padding:0px" align="center" id="content_desccargo_<?php echo $desc_cargo?>">
                        <input class="input" type="text"  id="desc_cargo <?php echo $id_cargo?>"  name="desc_cargo <?php echo $id_cargo?>" value="<?php echo $desc_cargo?>" title="Descripcion" />
                    </div>
                </td>

                <td class="tda" style="width:100px; text-align:center;">
                    <div style="padding:0px" align="center" id="content_descarea_<?php echo $id_cargo?>">
                        <select id="id_area <?php echo $id_cargo?>" name="id_area <?php echo $id_cargo?>"  required  type='button' data-toggle='dropdown' aria-expanded='true'>
                            <option></option>
                            <?php
                            $p=new personal();
                            $stid = $p->obtenerareas();
                            while (oci_fetch($stid)) {
                                $cod_area1= oci_result($stid, 'ID_AREA') ;
                                $desc_area1= oci_result($stid, 'DESC_AREA') ;
                                if($cod_area1 == $id_area) echo "<option value='$cod_area1' selected>$desc_area1</option>\n";
                                else
                            echo "<option value='$cod_area1'>$desc_area1</option>\n";
                            }oci_free_statement($stid);
                            ?>
                        </select>
                    </div>
                </td>
            </tr>
            <?php
            }
            ?>
            </tbody>
        </table>

    </div>
</form>
<script type="text/javascript">
    <!--




    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flex1").flexigrid	({


        buttons: [
            {name:'Agregar', bclass:'add', onpress: test},
            {separator: true}
        ],

        title: 'Listado de Cargos',
        height: 340
    });


    $(document).ready(function(){


        $('input').blur(function(){
            var field = $(this);

            var parent = field.parent().attr('id');
            $('#im1'+parent).remove();
            $('#imagen_'+parent).remove();
            if($('#'+parent).find(".ok").length){
                $('#'+parent).append('<img id="imagen_'+parent+'" src="loader.gif"/>').fadeIn('slow');
            }else{
                $('#'+parent).append('<img id="imagen_'+parent+'" src="loader.gif"/>').fadeIn('slow');
            }

            var dataString = 'value='+$(this).val()+'&field='+$(this).attr('name');

            $.ajax({
                type: "POST",
                url: "../datos/datos.admincargos.php",
                data: dataString,

                success: function(data) {
                    field.val(data);
                    $('#imagen_'+parent).remove();

                    $('#'+parent).append('<img id="im1'+parent+'" src="ok.png"/>').fadeIn('slow');
                }
            });
        });


        $('select').blur(function(){
            var field = $(this);

            var parent = field.parent().attr('id');
            $('#im1'+parent).remove();
            $('#imagen_'+parent).remove();
            if($('#'+parent).find(".ok").length){
                $('#'+parent).append('<img id="imagen_'+parent+'" src="loader.gif"/>').fadeIn('slow');
            }else{
                $('#'+parent).append('<img id="imagen_'+parent+'" src="loader.gif"/>').fadeIn('slow');
            }

            var dataString = 'value='+$(this).val()+'&field='+$(this).attr('name');
            $.ajax({
                type: "POST",
                url: "../datos/datos.admincargos.php",
                data: dataString,

                success: function(data) {
                    field.val(data);
                    $('#imagen_'+parent).remove();

                    $('#'+parent).append('<img id="im1'+parent+'" src="ok.png"/>').fadeIn('slow');
                }
            });
        });
    });
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
        popup("vista.agregacargo.php",770,610,'yes');
    }

    function test(com,grid)
    {

        if (com=='Agregar')
        {
            //alert('Add New Item Action');
            agrega();

        }
    }
    //-->
</script>
</body>
</html>