/*
*	Created By AMOSQUEA 27/11/2017
 */

var proyecto;
var concepto;
var periodo;
var genRepDepCon;
var btnSave;
var history;

function regDepConInicio() {

	proyecto = document.getElementById('proyecto');
	concepto = document.getElementById('concepto');
	periodo = document.getElementById('periodo');
	genRepDepCon = document.getElementById('genRepDepCon');

	genRepDepCon.addEventListener("click",repPagConLlegaGenRep);
	repPagConLlegaSelAcu();
}

function repPagConLlegaSelAcu(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var option=document.createElement("OPTION");
            option.value="";
            option.text="Proyecto";
            proyecto.add(option,proyecto[0]);
            var datos=obj = JSON.parse(xmlhttp.responseText);
            for(var x=0;x<datos.length;x++){
                var option=document.createElement("OPTION");
                option.value=(datos[x]["ID_PROYECTO"]);
                option.text=datos[x]["SIGLA_PROYECTO"];
                proyecto.add(option,proyecto[x+1]);
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.repPagConc.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=acu");

}

function repPagConLlegaGenRep(){
    if(validaCampos()) {

        swal({
           title: "Advertencia!",
           text: "El formulario puede tardar algunos segundos en generarse.",
           type: "info",
           showConfirmButton: true,
           confirmButtonText: "Continuar!",
           showCancelButton: true,
           showLoaderOnConfirm: true,
           closeOnConfirm: false,
           closeOnCancel: true
       },
       function(isConfirm)
       {
           if (isConfirm)
           {
               generaRep();
           }
       });
    }
}

function generaRep(){
    $.ajax
    ({
        url : "../datos/datos.registro_depositos.php?tipo=get&proy="+proyecto.value+"&concepto="+concepto.value+"&periodo="+periodo.value,
        type : 'GET',
        dataType : 'html',
        success : function(data) {
          generaTabla(JSON.parse(data),concepto.value, periodo.value, proyecto.value);
        },
        error : function(xhr, status) {
            swal("Error!", "error desconocido contacte a sistemas", "error");
        }
    });
}

function generaTabla(data, concepto, periodo, proyecto)
{
  var table = document.getElementById('depositTable');

  table.innerHTML = ' ';
  var ths = '<tr>';
      ths += '<th>Fecha</th>';
      ths += '<th>Cantidad</th>';
      ths += '<th>Reporte</th>';
      ths += '<th>Fecha Deposito</th>';
      ths += '<th>Comprobante</th>';
      ths += '<th>Monto</th>';
      ths += '<th></th>';
      ths += '</tr>';

  $(table).append(ths);

  var tds = '';

  historial(periodo, concepto, proyecto, function(reg){
     
      for (var i = 0; i < data['TOTAL'].length; i++) {

        if (reg['fechaP'].indexOf(data['FECHAPAGO'][i]) != -1) {
          var index = reg['fechaP'].indexOf(data['FECHAPAGO'][i]);
          var tr = document.createElement('tr');
          tds  = '<td>'+ data['FECHAPAGO'][i] + '</td>';
          tds += '<td>'+ data['CANTIDAD'][i] + '</td>';
          tds += '<td>'+ data['TOTAL'][i] + '</td>';
          tds += '<td><input type="date" class="fechaDep readonly" value="'+ reg['fechaD'][index] +'" readonly /></td>';
          tds += '<td><input type="text" class="comprobanteDep readonly" value="'+ reg['comprobante'][index] +'" readonly /></td>';
          tds += '<td><input type="text" class="montoDep readonly" value="'+ reg['monto'][index] +'" readonly /></td>';
          tds += '<td><i class="fa fa-pencil-square updateField"></i></td>';
          } else {
          var tr = document.createElement('tr');
          tds  = '<td>'+ data['FECHAPAGO'][i] + '</td>';
          tds += '<td>'+ data['CANTIDAD'][i] + '</td>';
          tds += '<td>'+ data['TOTAL'][i] + '</td>';
          tds += '<td><input type="date" class="fechaDep" /></td>';
          tds += '<td><input type="text" class="comprobanteDep" /></td>';
          tds += '<td><input type="text" class="montoDep" /></td>';
          tds += '<td><i class="fa fa-check-circle saveField"></i></td>';
        }


        tr.innerHTML = tds;
        $(table).append(tr);
      }

    $('.saveField').click(function(e) {
        setNewReg(e.target.parentNode.parentNode, concepto, proyecto);
    });

    $('.updateField').click( function(e) {
      e.stopPropagation();
        var el = e.target.parentNode.parentNode;

        $(el).find('input').removeAttr('readonly').removeClass('readonly');
        $(el).find('i').removeClass('updateField').addClass('updateField2');


      $('.updateField2').click( function(e) {
          setUpdReg(e.target.parentNode.parentNode, concepto, proyecto, periodo);
      });
    });

  });

  //console.log(data);

  swal(
  {
    title: "Formulario Generado!",
    text: "Has generado correctamente el formulario",
    type: "success",
    html: true,
    confirmButtonColor: "#66CC33",
    confirmButtonText: "Ok!",
    closeOnConfirm: true},
  );

}
function setUpdReg(elem, concepto, proyecto, periodo) {
  var datos = $(elem).find('td'),
      fecha = $(datos[0]).text(),
      fechaDep = $(datos).find('.fechaDep').val(),
      comprobante = $(datos).find('.comprobanteDep').val(),
      monto = $(datos).find('.montoDep').val();

    if (validaCampos2(fechaDep,comprobante,monto)) {
      $.ajax({
          url : "../datos/datos.registro_depositos.php?tipo=upd&fecha="+fecha+"&fechaDep="+fechaDep+"&comprobante="+comprobante+"&monto="+monto+"&concepto="+concepto+"&proy="+proyecto+"&periodo="+periodo,
          type : 'GET',
          dataType : 'html',
          success : function(data) {
            if (data == 1) {
              swal("Actualizado!", "Deposito actualizado satisfactoriamente", "success");
              $(datos).find('input').attr('readonly', 'true').addClass('readonly');
              $(datos).find('i').removeClass('fa-check-circle').removeClass('updateField2').addClass('fa-pencil-square updateField');

            } else {
              swal("Error!", "El deposito no pudo ser Actualizado. Contacte a sistemas", "error");
            }
          },
          error : function(xhr, status) {
              swal("Error!", "error desconocido contacte a sistemas", "error");
          }
      });
    }
}


function setNewReg(elem, concepto, proyecto) {
  var datos = $(elem).find('td'),
      fecha = $(datos[0]).text(),
      cantidad = $(datos[1]).text(),
      reporte = $(datos[2]).text(),
      fechaDep = $(datos[3]).find('.fechaDep').val(),
      comprobante = $(datos[4]).find('.comprobanteDep').val(),
      monto = $(datos[5]).find('.montoDep').val();

    if (validaCampos2(fechaDep,comprobante,monto)) {
      $.ajax({
          url : "../datos/datos.registro_depositos.php?tipo=set&fecha="+fecha+"&cantidad="+cantidad+"&reporte="+reporte+"&fechaDep="+fechaDep+"&comprobante="+comprobante+"&monto="+monto+"&concepto="+concepto+"&proy="+proyecto,
          type : 'GET',
          dataType : 'html',
          success : function(data) {
            if (data == 1) {
              swal("Registrado!", "Deposito registrado satisfactoriamente", "success");
              $(datos).find('input').attr('readonly', 'true').addClass('readonly');
              $(datos).find('i').hide();
            } else if (data == 2) {
              swal("Error!", "Ya Existe un deposito para el dia " + fecha, "error");
            } else if (data == 4) {
              swal("Registrado!", "El deposito ya se encuentra registrado en el sistema, actualize!", "error");
            } else {
              swal("Error!", "El deposito no pudo ser registrado. Contacte a sistemas", "error");
            }

          },
          error : function(xhr, status) {
              swal("Error!", "error desconocido contacte a sistemas", "error");
          }
      });
    }
}

function validaCampos2(fechaDep, comprobante, monto){
    if(fechaDep == '' ){
        swal("Error!", "Ingrese una fecha de deposito", "error");
        return false;
    }

    if(comprobante == null || comprobante.length < 6){
        swal("Error!", "Numero de comprobante incorrecto", "error");
        return false;
    }
    
    if(monto <= 0 || monto == null || isNaN(monto)){
        swal("Error!", "Ingrese un Monto correcto", "error");
        return false;
    }

    return true;
}

function historial(periodo, concepto, proyecto, result) {
       $.ajax({
          url : "../datos/datos.registro_depositos.php?tipo=his&periodo="+periodo+"&concepto="+concepto+"&proy="+proyecto,
          type : 'GET',
          dataType : 'json',
          success : function(data) {
            result(data);
          }
      });
       //return history;
}

function validaCampos(){
    if(proyecto.selectedIndex==0){
        swal("Error!", "Debe seleccionar el acueducto", "error");
        return false;
    }

    if(concepto.selectedIndex==0){
        swal("Error!", "Debe seleccionar un concepto", "error");
        return false;
    }
    
    if(periodo.value == "" || periodo.value.length != 6 || isNaN(periodo.value)){
        swal("Error!", "Debe seleccionar un periodo Correcto", "error");
        return false;
    }

    return true;
}
