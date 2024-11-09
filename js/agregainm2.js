$(document).ready(function()
{
	
	$(document).bind("contextmenu",function(e)
	{
		return false;
	});
	
   	$("#proyecto").change(function ()
   	{
   		
   		$("#proyecto option:selected").each(function ()
   		{
   			
   			id_proyecto = $(this).val();
            $.post("../datos/datos.agragainm.php", { id_proyecto: id_proyecto, tipo:"tipo_via" }, function(data)
            {
            	$("#tipo_via").html(data);
            });
            
            $.post("../datos/datos.agragainm.php", { id_proyecto: id_proyecto, tipo:"sector" }, function(data)
                    {
                    	$("#sector").html(data);
                    }); 
            
        });
   	})
   	
   	
   	
   	$("#sector").change(function ()
   	{
   		
   		$("#sector option:selected").each(function ()
   		{
   			
   			id_proyecto = $("#proyecto").val();
   			sector=$(this).val();
            $.post("../datos/datos.agragainm.php", { id_proyecto: id_proyecto,sector:sector, tipo:"ruta" }, function(data)
            {
            	$("#ruta").html(data);
            });
            
            $.post("../datos/datos.agragainm.php", { id_proyecto: id_proyecto, sector:sector, tipo:"zona" }, function(data)
            {
              	$("#zona").html(data);
            }); 
            
        });
   	})
   	
   	
   	$("#uso").change(function ()
   	{
   		
   		$("#uso option:selected").each(function ()
   		{
   			
   			id_uso = $(this).val();
   			$("#HidUso").val(id_uso);
            $.post("../datos/datos.agragainm.php", { uso:id_uso, tipo:"actividad" }, function(data)
            {
            	$("#actividad").html(data);
            });
            $.post("../datos/datos.agragainm.php", { uso:id_uso, tipo:"uso" }, function(data)
            {
                  $("#uso1").html(data);
            });
            

            
        });
   	})
   	
   	$("#concepto1").change(function ()
   	{
   		
   		$("#concepto1 option:selected").each(function ()
   		{
   			
   			id_proyecto = $("#proyecto").val();
   			id_uso = $("#uso").val();
   			//id_uso = 'ususus';
   			id_concepto = $(this).val();
            $.post("../datos/datos.agragainm.php", { uso:id_uso,id_proyecto:id_proyecto,concepto:id_concepto, tipo:"tarifa" }, function(data)
            {
            	$("#tarifa1").html(data);
            });
           
  
            
        });
   	})
   	
   	   	$("#tarifa1").change(function ()
   	{
   		
   	$("#tarifa1 option:selected").each(function ()
   	{
   			
   		tarifa = $(this).val();
        $.post("../datos/datos.agragainm.php", { tarifa:tarifa, tipo:"cupobas" }, function(data)
        {	
        	total=data*$("#unidadesh1").val();
        	$("#CMinimo").val(total);
            $("#cupobasico").val(data);
            if(data>0 && $("#HidUso").val()=="R" )
            {
            	$("#cupobasico").prop('readonly', true);
            }else
            {
            	$("#cupobasico").prop('readonly', false);
            }
        });
           
  
            
        });
   	})
   	
   	
   	
   	
   	
   	$("#urbanizacion").autocomplete({
   		source: "./../datos/datos.urbanizacion.php",
   		minLength: 1,
   		html: true, 
   		open: function(event, ui) {
   			$(".ui-autocomplete").css("z-index", 1000);
   		}
   	});

   	$("#ETapartamento").autocomplete({

   		source: "./../datos/datos.apartamento.php",
   		minLength: 1,
   		html: true, 
   		open: function(event, ui) {
   			$(".ui-autocomplete").css("z-index", 1000);
   		}
   	});
   	


   	
   	$("#nom_via").autocomplete({
   		source: "./../datos/datos.nombrevia.php?tipovia="+$("#tipo_via").val()+"&proyecto="+$("#proyecto").val(),
   		minLength: 1,
   		html: true, 
   		open: function(event, ui) 
   		{
   			$(".ui-autocomplete").css("z-index", 1000);
   		}
   	});
   	

});



function popup2(uri, awid, ahei, scrollbar) {
    var params;
    var popped = null;
    if (uri != "") {
        if (popped && !popped.closed) {
            popped.location.href = uri;
            popped.focus();
        } 
        else {
            params = "toolbar=no,width=" + awid + ",height="+ ahei +",directories=no,status=no,scrollbars=" + scrollbar + ",menubar=no,resizable=no,location=no";                               
            popped = window.open(uri, "popup4", params);
        }
    }
}	
function upCliente() {
	
	popup2("vista.agregaurb.php",600,300,'yes');
}	

function upNomVia() {
	
	popup2("vista.agregacalle.php",600,300,'yes');
}	

function upApto() {
	
	popup2("vista.agregaApto.php",600,300,'yes');
}	

function infomante(){
	document.agregainm.proc.value = 1;
		return true;
}

function agregarurb(){
	if(document.agregainm.urbanizacion.value == 'Agregar Urbanizacion'){
		upCliente();
		document.agregainm.urbanizacion.value ='';
	}
}

function agreganomvia(){
	if(document.agregainm.nom_via.value == 'Agregar Nombre Via'){
		upNomVia();
		document.agregainm.nom_via.value ='';
	}
}

function validacampos()
{
	if( parseInt($("#unidadesh1").val())> parseInt( $("#unidadest1").val()))
	{
		alert("las unidades totales tienen que ser mayores a las habitadas!!!!");
		return false;
	}
}

function nombreurb()
{
	$("#urbanizacion2").val($("#urbanizacion").val());
}


function cambiapar() 
{
	consumomin= $("#cupobasico").val();
	$("#CMinimo").val(consumomin*$("#unidadesh1").val());
	unidadesdes= $("#unidadest1").val()-$("#unidadesh1").val();
	$("#unidadesd1").val(unidadesdes);
}



function agregaapto(){
	if(document.agregainm.ETapartamento.value == 'Agregar Apto'){
		upApto();
		document.agregainm.ETapartamento.value ='';
	}
}