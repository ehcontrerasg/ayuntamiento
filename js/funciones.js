/************************************************************************/
/* PROGRAMA PARA EL PROCESO DE CRITICA									*/
/* AGUAZUL - BOGOTA	S.A. E.S.P.											*/
/* CREADO POR: ING. DIEGO ALEXANDER PRADA BUITRAGO (25/11/2011)			*/
/************************************************************************/
function compare_dates(fecha, fecha2)
{
	var xMonth=fecha.substring(3, 5);
	var xDay=fecha.substring(0, 2);   
    var xYear=fecha.substring(6,10);   
    var yMonth=fecha2.substring(3, 5);   
    var yDay=fecha2.substring(0, 2);   
    var yYear=fecha2.substring(6,10);   
    if (xYear> yYear)   
    {
		return(true)   
    }   
    else  
    {
		if (xYear == yYear)
		{
			if (xMonth> yMonth)
			{
				return(true)
			}
			else
			{
				if (xMonth == yMonth)
				{
					if (xDay> yDay)
						return(true);
					else
						return(false);
				}
				else
					return(false);
			}
		}
		else
			return(false);
	}
} 

//Funcion TAB con Enter
//Esto se coloca en el form: onkeypress="return handleEnter(this, event)
function handleEnter (field, event)
{
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
	if (keyCode == 13)
	{
		var i;
		for (i = 0; i < field.form.elements.length; i++)
			if (field == field.form.elements[i])
				break;
		i = field.form.elements[i].tabIndex + 1;
		for( j = 0 ; j < field.form.elements.length; j++)
		{
			if( field.form.elements[j].tabIndex == i)
			{
				break;
			}
		}
		field.form.elements[j].focus();
		return false;
	}
	else
		return true;
}

/* Funcion para buscxar opcion en un ComboBox */
var puntero = 0;
var buffer = new Array(5); //declaración del array Buffer (Como máximo va a permitir presionar 5 letras)
var cadena = "";
function buscar_op (obj, objfoco, digitos)
{
	/* La variable 'obj' indica el objeto en donde realizara la busqueda de la opción */
	/* La variable 'objfoco' indica el objeto que enfocara una vez se termine la busqueda (se presione enter) */
	/* La variable 'digitos' indica la cantidad de letras con la cual va a realizar la busqueda */
	var letra = String.fromCharCode(event.keyCode);
	if(puntero >= digitos)
	{
		cadena = "";
		puntero = 0;
	}
	//si se presiona la tecla ENTER, borro el array de teclas presionadas y salto a otro objeto...
	if(event.keyCode == 13)
	{
		borrar_buffer();
		if(objfoco!=0) objfoco.focus(); //evita foco a otro objeto si objfoco=0
	}
	//sino busco la cadena tipeada dentro del combo...
	else
	{
		buffer[puntero] = letra;
		//guardo en la posicion puntero la letra tipeada
		cadena = cadena + buffer[puntero]; //armo una cadena con los datos que van ingresando al array
		puntero++;
		//barro todas las opciones que contiene el combo y las comparo la cadena...
		for (var opcombo=0;opcombo < obj.length;opcombo++)
		{
			if(obj[opcombo].text.substr(0,puntero).toLowerCase()==cadena.toLowerCase())
			{
				obj.selectedIndex = opcombo;
			}
		}
	}
	event.returnValue = false; //invalida la acción de pulsado de tecla para evitar busqueda del primer caracter
} 

function borrar_buffer()
{
	//inicializa la cadena buscada
	cadena="";
	puntero=0; 
} 

//FUNCION PARA ABRIR UN POPUP
var popped = null;
function popup(uri, awid, ahei)
{
	var params;
	if (uri != "")
	{
		if (popped && !popped.closed)
		{
			popped.location.href = uri;
			popped.focus();
		} 
		else
		{
			params = "toolbar=no,width=" + awid + ",height="+ ahei +",directories=no,status=yes,scrollbars=yes,menubar=no,resizable=no";
			popped = window.open(uri, "popup", params);
		}
	}
}