/*************************************
*
*	@Author : Allendy Valdez Pillier
*	@Fecha  : 20/02/2017
*
*************************************/
$(document).ready(function(){
	elements = {};
	$.getJSON("../js/json/elements.json?1", function(datos,status,xhr) {
		elements = datos;
		//console.log(datos);
		$("#btnSolCerradas").click(function(){
			var exist = $('#conSolCerradas').attr('exist');
			//console.log(exist);
			//dibujar_frm_busqueda("#conSolCerradas");
			if (exist == 1) {
				if($('#frmBsqRecpPqr').is(":visible") ){
				    $('#frmBsqRecpPqr').slideUp(function(){
				    	$('#btnSolCerradas span').attr('class', 'glyphicon glyphicon-chevron-down');
				    });
				}else{
					$('#frmBsqRecpPqr').slideDown(function(){
						$('#btnSolCerradas span').attr('class', 'glyphicon glyphicon-chevron-up');
					});
				}
			}else{
				dibujar_frm_busqueda("#conSolCerradas");
				$('#btnSolCerradas span').attr('class', 'glyphicon glyphicon-chevron-up');
			}
			
		});

	});
});
function dibujar_frm_busqueda (idContainer) {
	var html = '<form ';
	$.each(elements['form'][0], function(attrib, valor) {
		html +=  attrib+'='+valor+' ';
	});
	html+='> \n';
	var i = 0;
	var x = 0;
	$.each(elements['input'], function(key, value) {
		//console.log(value);
		if (i==0) {
			html += '\t <div class="row"> \n';
		}
		if (i==(x+2)) {
			html += '\t <div class="row"> \n';
			x=i;
		}
		html += '\t \t <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"> \n';
			html += '\t \t \t <div class="form-group"> \n';
				html += '\t \t \t \t <label for="'+elements['input'][key]['id']+'" class="'+elements['input'][key]['label-class']+'">'+elements['input'][key]['label']+': </label>';
				html += '\t \t \t \t <div class="'+elements['input'][key]['ctn-class']+'"> \n';
					switch (elements['input'][key]['type']) {
						case 'input':
							html += dibInput (key);
							break;
						case 'select':
							html += dibSelect(key);
							break;
					}
				html += '\t \t \t \t </div> \n'
			html += '\t \t \t </div> \n';
		html += '\t \t </div> \n';
		if (i==1) {
			html += '\t </div> \n';
		}
		if (i==(x+3)) {
			html += '\t </div> \n';
		}
		i++;
	});
	html += '<div align="center">';
		html += '<button type="submit" id="bntEnviar" class="btn btn-success" style="margin-right: 2%;"><span class="glyphicon glyphicon-search"></span> Buscar</button>';
		html += '<button type="button" value="Cancelar" class="btn btn-danger" onClick="cancelar()"><span class="glyphicon glyphicon glyphicon-remove"></span> Cancelar</button>';
	html += '</div><br>';
	html += '</form> \n';
	/*html += '<`div class="row">'+
				'<div class="form-horizontal">'+
					'<div calss="col-sm-6 col-lg-6">'+
						'<div class="form-group">'+
							'<label for="" >Registros/Pagina</label>'
							'<div class="col-sm-2">'+
								'<select class="form-control" id="">';
									/*for (var i = 10; i < 100; i+10) {
										html+='<option value="'+i+'">'+i+'</option>'
									}*
	html+=						'</select>'+
							'</div>'+
						'</div>'+
					'</div>'+
				'</div>'+
			'</div>';*/
	html += '<div class="table-responsive">';
		html += '<table ';
		$.each(elements['table'][0]['attrib'], function(key, value) {
			html+=key+'='+value+' ';
		});
		html += '>\n';
			html += '<thead>';
				html += '<tr>';
					$.each(elements['table'][0]['thead'], function(key, value) {
						html+='<th>'+value+'</th>';
					});
				html += '</tr>';
			html += '</thead>';
			html += '<tbody>'
					'</tbody>';
		html += '</table>';
	html += '</div>';
	html += '<div style="width: 100%" id="paginacion">'+
				'<nav aria-label="...">'+
					'<ul class="pagination">'+
					'</ul>'+
				'</nav>'+
			'</div>'; 
	/*
		<nav aria-label="...">
		  <ul class="pagination">
		    <li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
		    <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
		    ...
		  </ul>
		</nav>
	*/
	//console.log(html);
	//console.log(idContainer);
	//console.log(html);
	$(idContainer).html(html);
	$(idContainer).attr('exist', '1');
	buscar();
	//console.log(html);
}
function dibInput (key) {
	html = '\t \t \t \t \t <input ';
		$.each(elements['input'][key], function(attrib, valor) {
			if (attrib!='label' && attrib!='label-class' && attrib!='ctn-class' && attrib!='data-select' && attrib!='required') {
				html+= attrib+'='+valor+' ';	
			}
			if (attrib == 'required' && valor==true) {
				html+='required'+' ';
			}
		});
	html += '/> \n';
	return html;
}
function dibSelect (key) {
	html = '<select ';
		$.each(elements['input'][key], function(attrib, valor) {
			if (attrib!='label' && attrib!='label-class' && attrib!='ctn-class' && attrib!='data-select') {
				if (attrib != 'required' && valor!=true) {
					html+=attrib+'='+valor+' ';
				}else{
					html+='required'+' ';	
				}
				html+=attrib+'='+valor+' ';	
			}
			/*if (attrib == 'required' && valor=='true') {
				html+='required'+' ';
			}*/
		});
		html += ' >';
		eval(elements['input'][key]['data-select'] + '("#'+elements['input'][key]['id']+'")');
	html += '</select>';
	return html;
}

function lstProyecto (idContainer) {
	//console.log('hola mundo');
	var parametros = 'caso=proyecto'
	$.ajax({
		url: '../webService/ws.consulta_pqr_catastral.php',
		data: parametros,
		type: 'POST',
		success: function(resp) {
			jresp = $.trim(resp);
			jresp = JSON.parse([resp]);
			//console.log(jresp);
			var html = '<option value="" selected></option>';
			var row = jresp['CODIGO'].length;
			for (var i = 0; i < row; i++) {
				html += '<option value="'+jresp['CODIGO'][i]+'">'+jresp['DESCRIPCION'][i]+'</option>';
			}
			
			$(idContainer).html(html);
		},
		error: function(jqXHR, estado, error) {
			console.log(estado+' : '+ error);
		}
	});
}

function lstTipoSol (idContainer) {
	//console.log('hola mundo');
	var parametros = 'caso=tipoSol'
	$.ajax({
		url: '../webService/ws.consulta_pqr_catastral.php',
		data: parametros,
		type: 'POST',
		success: function(resp) {
			jresp = $.trim(resp);
			jresp = JSON.parse([resp]);
			//console.log(jresp);
			var html = '<option value="" selected></option>';
			var row = jresp['CODIGO'].length;
			for (var i = 0; i < row; i++) {
				html += '<option value="'+jresp['CODIGO'][i]+'">'+jresp['DESCRIPCION'][i]+'</option>';
			}

			$(idContainer).html(html);
		},
		error: function(jqXHR, estado, error) {
			console.log(estado+' : '+ error);
		}
	});
}
function buscar () {
	$('#frmBsqRecpPqr').submit(function(e){
		e.preventDefault();
		var data = $('#frmBsqRecpPqr').serialize()+'&caso=bsqPqr';
		//console.log(data);
		consultar(data, elements['form'][0]['action'], elements['form'][0]['method']);
	});
}
function consultar (parametros, url, method) {
	//'proyecto='+proyecto+'&'+'nom_cliente='+nom_cliente+'&'+'codigo_pqr='+codigo_pqr+'&'+'doc_cliente='+doc_cliente;
	// '../webService/ws.consulta_pqr_catastral.php'
	var spinner = new Spinner();
	var target = document.getElementById('bntEnviar');
	$.ajax({
		url: url,
		data: parametros,
		type: method,
		beforeSend : function(){
			spinner.spin(target);
		},
		success: function(resp) {
			var jresp = $.trim(resp);
			var jresp = JSON.parse([resp]);
			//document.write(jresp);
			data = jresp;
			llenar_table('#tblRecpPqr');
			spinner.stop();
		},
		error: function(jqXHR, estado, error) {
			console.log(estado+' : '+ error);
		}
	});
}
function llenar_table (container) {
	row = data['CODIGO_PQR'].length;
	pagActual = 1;
	totalReg = 10;
	var totalPag = Math.round(row/totalReg);
	var x = 1;
	var html = '';
	var foot = '<!-- li id="btnPrevCtn"><a href="#" id="btnPrev" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li -->';
	//console.log(data)  Math.round(
	for (var i = 0; i<totalReg; i++) {
		if (i == row) {
			break;
		}
		html += '<tr>';
			html += '<td>'+(i+1)+'</td>';
			html += '<td>'+data['CODIGO_PQR'][i]+'</td>';
			html += '<td>'+data['FECHA_PQR'][i]+'</td>';
			html += '<td>'+data['NOM_CLIENTE'][i]+'</td>';
			html += '<td>'+data['DOC_CLIENTE'][i]+'</td>';
			html += '<td>'+data['COD_ENTIDAD'][i]+'</td>';
			html += '<td>'+data['COD_CAJA'][i]+'</td>';
			html += '<td>'+data['FECHA_REGISTRO'][i]+'</td>';
			html += '<td>'+data['FECHA_CIERRE'][i]+'</td>';
			html += '<td>'+data['RESPUESTA'][i]+'</td>';
		html += '</tr>';
		//console.log(i);
	}
	for (var i = 0; i < totalPag; i++) {
		var x = (i+1);
		if (pagActual!=(i+1)) {

			foot += '<li class="" id="pag'+x+'"><a href="#">'+x+' </a></li>';	
		}else{
			foot += '<li class="active" id="pag'+x+'"><a href="#">'+x+'</a></li>';
		}
	}
	foot += '<!-- li id="btnNextCtn"><a href="#" id="btnNext" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li -->';
	$(container+' tbody').html(html);
	$('#paginacion ul').html(foot);
	$('#paginacion ul a').click(function(e){
		e.preventDefault();
		var id = e.target.id;
		var pagina = 1;
		if (id!='btnPrev' && id!='btnPrevCtn' && id!='btnNext' && id!='btnNextCtn') {
			pagina = e.target.text;
		}else{
			if (id == 'btnNext' || id=='btnNextCtn') {
				pagina = pagActual+1;
				if (pagina>=totalPag) {
					pagina = totalPag;
				}
			}else{
				pagina = pagActual+1;
				if (pagina<=1) {
					pagina = 1;
				}
			}
		}
		//console.log(pagina);
		saltarPag(pagina, '#paginacion', container+' tbody');
	});
	/*$('#btnNext').click(function(){
		var pagina = parseInt(pagActual)+1;
		console.log(pagina);
		saltarPag(pagina, '#paginacion', container+' tbody');
	});
	$('#btnPrev').click(function(){
		var pagina = pagActual-1;
		console.log(pagina);
		saltarPag(pagina, '#paginacion', container+' tbody');
	});*/
}
function saltarPag(pagina, cantainer, tabla) {
	pagActual = pagina;
	var registros = ((totalReg*pagina)+totalReg)-1;
	//console.log((totalReg*pagina)-(totalReg+1));
	//console.log((totalReg*pagina)-1);
	//console.log(registros);
	//console.log((totalReg*pagina)-1);

	//console.log(i = (totalReg*pagina)-1);
	//console.log((((totalReg*pagina)+totalReg)-1));
	//totalReg = 10;
	var html = '';
	//console.log(data)
	for (var i = (totalReg*pagina)-(totalReg+1); i<(totalReg*pagina); i++) {
		if (i==-1) {
			i = 0;	
		}
		if (i==row) {
			break;
		}
		html += '<tr>';
			html += '<td>'+(i+1)+'</td>';
			html += '<td>'+data['CODIGO_PQR'][i]+'</td>';
			html += '<td>'+data['FECHA_PQR'][i]+'</td>';
			html += '<td>'+data['NOM_CLIENTE'][i]+'</td>';
			html += '<td>'+data['DOC_CLIENTE'][i]+'</td>';
			html += '<td>'+data['COD_ENTIDAD'][i]+'</td>';
			html += '<td>'+data['COD_CAJA'][i]+'</td>';
			html += '<td>'+data['FECHA_REGISTRO'][i]+'</td>';
			html += '<td>'+data['FECHA_CIERRE'][i]+'</td>';
			html += '<td>'+data['RESPUESTA'][i]+'</td>';
		html += '</tr>';
		
		//console.log(i);
	}
	$(cantainer+' ul li').removeClass('active');
	$('#pag'+pagina).addClass('active');
	$(tabla).html(html);
	//for(var i = 109; i <119; i++){console.log(data['CODIGO_PQR'][i]);}
}
function cancelar(id){
	  document.getElementById("frmBsqRecpPqr").reset();
}
function recarga(){
	document.recepcion.submit();
}
/*function habilitadiv(){
	if(document.getElementById("cod_inmueble").value == ''){
		showDialog('Error Cargando Datos','El Inmueble N&deg; <?php echo $cod_inmueble?> No Existe.<br><br>Por Favor Verifique.','error',3);
	}
	if(document.getElementById("cod_inmueble").value != ''){
		showDialog('Ok','El Inmueble N&deg; <?php echo $cod_inmueble?> Si Existe.<br><br>Por Favor Verifique.','success',3);
	}
}*/

/*var opts = {
					  lines: 13 // The number of lines to draw
					, length: 28 // The length of each line
					, width: 14 // The line thickness
					, radius: 42 // The radius of the inner circle
					, scale: 1 // Scales overall size of the spinner
					, corners: 1 // Corner roundness (0..1)
					, color: '#000' // #rgb or #rrggbb or array of colors
					, opacity: 0.25 // Opacity of the lines
					, rotate: 0 // The rotation offset
					, direction: 1 // 1: clockwise, -1: counterclockwise
					, speed: 1 // Rounds per second
					, trail: 60 // Afterglow percentage
					, fps: 20 // Frames per second when using setTimeout() as a fallback for CSS
					, zIndex: 2e9 // The z-index (defaults to 2000000000)
					, className: 'spinner' // The CSS class to assign to the spinner
					, top: '50%' // Top position relative to parent
					, left: '50%' // Left position relative to parent
					, shadow: false // Whether to render a shadow
					, hwaccel: false // Whether to use hardware acceleration
					, position: 'absolute' // Element positioning
				}
	//var target = document.getElementById('foo')
	var spinner = new Spinner(opts).spin(target);*/