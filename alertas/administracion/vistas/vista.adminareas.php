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
        .input{
            border:1px solid #ccc;
            font-family: Arial, Helvetica, sans-serif;
            font-size:11px;
            font-weight:normal;
        }
        .select{
            border:0px solid #ccc;
            font-family: Arial, Helvetica, sans-serif;
            font-size:11px;
            font-weight:normal;
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
<form name="FMAreas" action="vista.adminareas.php" method="post" onsubmit="return rend();">
    <div class="flexigrid" style="width:100%">
        <div class="mDiv">
            <div><b>Administraci&oacute;n de Áreas</b></div>
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
                <th align="center" class="th" style="width:24px">Id</th>
                <th align="center" class="th" style="width:156px">Descripción</th>
            </tr>
            </thead>
            <tbody>
            <tr >
                <?php
                $l=new personal();
                $registros=$l->todosareas();
                while (oci_fetch($registros)) {
                $id_area = oci_result($registros, 'ID_AREA');
                $desc_area = oci_result($registros, 'DESC_AREA');
                ?>
                <td class="tda" style="width:24px; text-align:center"><b><?php echo $id_area?></b></td>

                    <td class="tda" style="width:100px; text-align:center;">
                        <div style="padding:0px" align="center" id="content_desc_<?php echo $id_area?>">
                        <input class="input" type="text"  id="desc_area <?php echo $id_area?>"  name="desc_area <?php echo $id_area?>" value="<?php echo $desc_area?>" title="Descripcion" />
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

        title: 'Listado de Areas',
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
                url: "../datos/datos.adminareas.php",
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
        popup("vista.agregaarea.php",770,610,'yes');
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