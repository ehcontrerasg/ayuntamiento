<?
include_once ('../../clases/class.PermisosURL.php');
 $permisos = new PermisosURL();
 $verificarPermisos = $permisos->VerificaPermisos();
 if ($verificarPermisos==true): ?>

     <?php
     session_start();
     include '../clases/class.adminconceptos.php';
     include '../../destruye_sesion.php';
//$_SESSION['tiempo']=time();
//$_POST['codigo']=$_SESSION['codigo'];
     $inmueble=$_GET['cod_inmueble'];

     $sortname = $_POST['sortname'];
     $sortorder = $_POST['sortorder'];

     if (!$sortname) $sortname = "COD_SERVICIO";
     if (!$sortorder) $sortorder = "ASC";

     $sort = "ORDER BY $sortname $sortorder";

     $fname="COD_SERVICIO";
     $tname="SGC_TP_SERVICIOS";
     $where= "CALCULO IS NOT NULL";
     ?>


  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
  <head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
   <link href="../../css/bootstrap.min.css" rel="stylesheet">
   <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
   <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
   <link rel="stylesheet" href="../../flexigrid/style.css" />
   <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
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
  <body style="margin-top:-25px" >
  <div id="content" style="padding:0px; width:1120px; margin-left:0px">
   <h3 class="panel-heading" style=" background-color:#336699; color:#FFFFFF; font-size:18px; width:1120px" align="center">Administrar Conceptos</h3>
   <div style="text-align:center; width:1120px;">
    <form name="FMTarifas" action="vista.adminconceptos.php" method="post" onsubmit="return rend();">
     <div class="flexigrid" style="width:100%">
      <div class="mDiv">
       <div align="left"><b>Administraci&oacute;n de Conceptos</b></div>
      </div>
     </div>
    </form>
    <div class="flexigrid" style="width:100%">
     <div class="mDiv">
      <div style="background: linear-gradient(#F7F7F7,#F7F7F7); border-top:1px solid #ccc;" align="left">Exportar&nbsp;&nbsp;
       <a href="vista.reporte_excel_concep.php">
        <img src="../../images/excel/Excel.ico" width="25" height="25" title="Excel" style="vertical-align:middle"/></a>
       <a href="vista.reporte_word_concep.php">
        <img src="../../images/excel/Word.ico" width="25" height="25" title="Word" style="vertical-align:middle"/></a>
       <a href="vista.reporte_pdf_concep.php">
        <img src="../../images/excel/pdf.ico" width="25" height="25" title="PDF" style="vertical-align:middle"/></a>
      </div>
     </div>
    </div>

    <form id="ficha" method="post">
     <div>
      <table id="flex1" style="display:block;">
       <thead>
       <tr>
        <th align="center" class="th" style="width:24px">N&deg;</th>
        <th align="center" class="th" style="width:71px">C&oacute;digo</th>
        <th align="center" class="th" style="width:192px">Descripción</th>
        <th align="center" class="th" style="width:70px">Orden</th>
        <th align="center" class="th" style="width:70px">Tipo</th>
        <th align="center" class="th" style="width:70px">Mora</th>
        <th align="center" class="th" style="width:70px">Cuotas</th>
       </tr>
       </thead>
       <tbody>
       <tr>
           <?php
           $cont = 0;
           $l=new conceptos();
           $registros=$l->obtenerconceptos($where,$sort,$start,$end, $tname);
           while (oci_fetch($registros)) {
           $cont++;
           $id_concepto = oci_result($registros, 'COD_SERVICIO');
           $desc_concepto = oci_result($registros, 'DESC_SERVICIO');
           $orden = oci_result($registros, 'ORDEN');
           $tipo_concepto = oci_result($registros, 'DIFERIDO');
           $mora = oci_result($registros, 'MORA');
           $num_cuotas = oci_result($registros, 'LIMITE_MAX_CUOTAS');
           $min_cuotas = 1;
           if($num_cuotas == '') $max_cuotas = 12;
           else $max_cuotas = $num_cuotas;
           ?>
        <td class="tda" style="width:24px; text-align:center"><b><?php echo $cont?></b></td>
        <td class="tda" style="width:71px; text-align:center"><?php echo $id_concepto?></td>
        <td class="tda" style="width:192px; text-align:center"><?php echo $desc_concepto?></td>
        <td class="tda" style="width:70px; text-align:center">
         <div style="padding:0px" align="center" id="content_orden_<?php echo $id_concepto?>">
          <input class="input" type="text"  id="orden <?php echo $id_concepto?>" name="orden <?php echo $id_concepto?>" value="<?php echo $orden?>" size="2"/>
         </div>
        </td>
        <td class="tda" style="width:70px; text-align:center">
         <div style="padding:0px" align="center" id="content_diferido_<?php echo $id_concepto?>">
          <input class="input" type="text"  id="diferido <?php echo $id_concepto?>" name="diferido <?php echo $id_concepto?>" value="<?php echo $tipo_concepto?>" size="2"/>
         </div>
        </td>
        <td class="tda" style="width:70px; text-align:center">
         <div style="padding:0px" align="center" id="content_mora_<?php echo $id_concepto?>">
          <input class="input" type="text"  id="mora <?php echo $id_concepto?>" name="mora <?php echo $id_concepto?>" value="<?php echo $mora?>" size="2"/>
         </div>
        </td>
        <td class="tda" style="width:70px;">
         <div style="padding:0px" align="center" id="content_limite_max_cuotas_<?php echo $id_concepto?>">
          <input class="input" type="text"  id="limite_max_cuotas <?php echo $id_concepto?>" name="limite_max_cuotas <?php echo $id_concepto?>" value="<?php echo $num_cuotas?>"size="2"  /></div>
        </td>

       </tr>
       <?php
       }
       ?>
       </tbody>
      </table>

     </div>
    </form ></div></div>
  <script type="text/javascript">
      <!--


      $('.flexme1').flexigrid();
      $('.flexme2').flexigrid({height:'auto',striped:false});
      $("#flex1").flexigrid	({
          // url: './../datos/datos.adminconceptos2.php',
          // dataType: 'json',
          /*colModel : [
               {display: 'No', name: 'rnum', width:15,  align: 'center'},
               {display: 'C\xF3digo', name: 'COD_SERVICIO', width: 60, sortable: true, align: 'center'},
               {display: 'Descripci\xF3n', name: 'DESC_SERVICIO', width: 180, sortable: true, align: 'center'},
               {display: 'Orden', name: 'ORDEN', width: 40, sortable: true, align: 'center'},
               {display: 'Tipo', name: 'DIFERIDO', width: 50, sortable: true, align: 'center'},
               {display: 'Mora', name: 'MORA', width: 40, sortable: true, align: 'center'},
               {display: 'Cuotas', name: 'LIMITE_MAX_CUOTAS', width: 50, sortable: true, align: 'center'}
           ],*/
          buttons: [
              //{name:'Agregar', bclass:'add', onpress: test},
              //{separator: true}
              //{name:'Eliminar', bclass:'delete', onpress: test},
              //{separator: true}
              //{name:'Guardar', bclass:'save', onpress: test},
              //{separator: true}
          ],
          /* searchitems : [
               {display: 'C\xF3digo', name: 'COD_SERVICIO', isdefault: true},
               {display: 'Tipo', name: 'DIFERIDO'},
               {display: 'Mora', name: 'MORA'}
           ],*/
          //sortname: "COD_SERVICIO",
          //sortorder: "ASC",
          //usepager: true,
          title: '<?php echo '<div align="left" style="margin-left:-7px; height:20px; margin-top:-4px">Listado de Conceptos</div>';?>',
          //useRp: true,
          //rp: 100,
          //page: 1,
          //showTableToggleBtn: true,
          //width: 1046,
          height: 350
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
                  url: "../datos/datos.adminconceptos.php",
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
          popup("vista.agregaconcepto.php",770,610,'yes');
      }

      /*function guarda() { // Traer la fila seleccionada
          popup("vista.guardaconcepto.php?num_cuotas=<?php//echo $num_cuotas?>",770,610,'yes');
    }*/

      function elimina(id) { // Traer la fila seleccionada
          popup("vista.eliminaconcepto.php?id_concepto="+id,800,600,'yes');
      }

      function test(com,grid)
      {

          if (com=='Agregar')
          {
              //alert('Add New Item Action');
              agrega();

          }

          /*  if (com=='Eliminar')
            {
                if($('.trSelected',grid).length>0){

                    var items = $('.trSelected',grid);
                    var itemlis ='';
                    for(i=0;i<items.length;i++){
                        itemlis+= items[i].id.substr(3)+",";
                    }
                    itemlis=itemlis.substr(0,itemlis.length-1);
                    return elimina(itemlis);


                } else {
                    return false;
                }
            }*/

      }
      //-->
  </script>
  </body>
  </html>

<?php endif;
if ($verificarPermisos==false):
 include "../../general/vistas/vista.PlantillaError.php";
 endif; ?>

