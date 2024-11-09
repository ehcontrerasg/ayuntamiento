$(document).ready(function()
{
   	$("#SServicio").change(function ()
   	{
   		
   		$("#SServicio option:selected").each(function ()
   		{
   			
   			uso=$("#SUso").val();
   			inm=$("#HInmueble").val();

            id_servicio = $(this).val();
            $.post("../datos/datos.editaservicio.php", { id_servicio: id_servicio, uso:uso,tipo:"tarifa",inmueble:inm }, function(data)
            {
            	$("#STarifa").html(data);
            });            
        });
   	})

	$("#STarifa").change(function ()
	{
		$("#STarifa option:selected").each(function ()
		{
			tarifa=$("#STarifa").val();
			$.post("../datos/datos.editaservicio.php", { id_tarifa:tarifa, tipo:"cupbas" }, function(data)
			{
				total=data*$("#ETUnidadesHab").val();
				$("#ETConsMin").val(total);
                $("#ETCupoBas").val(data);
                if(data>0)
                {
                	$("#ETCupoBas").prop('readonly', false);
                }else
                {
                	$("#ETCupoBas").prop('readonly', false);
                }
            });            
        });
   	})
});

function cambiapar() 
{
	consumomin= $("#ETCupoBas").val();
	$("#ETConsMin").val(consumomin*$("#ETUnidadesHab").val());
}

function validacampos()
{
	if( parseInt($("#ETUnidadesHab").val())> parseInt( $("#ETUnidadesTot").val()))
	{
		alert("las unidades totales tienen que ser mayores a las habitadas!!!!");
		return false;
	}
}