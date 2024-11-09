<?php

extract($_POST);
$html = "

    <!doctype html>
    <html>
        <head></head>
        <style>
           /*Media Query*/
 @media print{
 @page {
    size: 297mm 210mm; /* landscape */
    /* you can also specify margins here: */
    margin: 25mm;
    margin-right: 45mm; /* for compatibility with both A4 and Letter */
  }
     .quiz-details{
         border: 1px solid black !important;
     }

     #quiz-details-header {
         display: grid !important;
         grid-template-columns: repeat(2,1fr) !important;
         justify-items: center !important;
         align-items: center !important;
         border:1px solid black !important;
         background: #c5d9f1 !important;
     }

     #dvPercepcion {
         grid-column: 2/3 !important;
         display: grid !important;
         grid-template-rows: repeat(2,1fr) !important;
         width: 100% !important;
         justify-content: center !important;
         justify-items: center !important;
         align-items: center !important;
         border:1px solid black !important;
     }

     #dvCalificaciones {
         display: grid !important;
         grid-template-columns: repeat(5,1fr) !important;
         border:1px solid black !important;
         background: #ffffcc !important;
     }

     #dvCalificaciones p {
         border: 1px solid black !important;
         height: 100% !important;
         text-align: center !important;
         display: flex !important;
         align-items: center !important;
     }

     #quiz-body {
         display: grid !important;
         grid-template-rows: repeat(14,1fr) !important;
     }

     #quiz-body .dvRespuesta {
         display: grid !important;
         grid-template-columns: 7.3% 42.5% auto !important;
     }

     #quiz-body .dvRespuesta label{
         display: flex !important;
         justify-content: center !important;
         align-items: center !important;
         border: 1px solid !important;
         height: 100% !important;
     }

     .dvRespuestaCalificacion {
         display: grid !important;
         /*grid-template-columns: 18.6% 17% 24.8% 19.5% auto !important;*/
         grid-template-columns: repeat(4,20.1%) auto;
         border: 1px solid black !important;
     }

     .dvRespuestaCalificacion div{
         border: 1px solid black !important;
         display: flex !important;
     }

     .casilla {
         width: 40px !important;
         height: 50% !important;
         align-self: center !important;
         margin: 0 auto !important;
         justify-content: center !important;
         align-items: center !important;
     }

     #dvPregunta15 {
         display: grid !important;
         grid-template-columns: 7.3% auto !important;
     }

     #dvPregunta15 label {
         font-weight: bold !important;
         display: flex !important;
         align-items: center !important;
         justify-content: center !important;
     }

     #dvPregunta15>div{
         border: 1px solid black !important;
     }

     #respuestaPregunta_15 {
         height: 200px !important;
         text-align: justify !important;
     }
 }
/*Media Query*/
        </style>
        <body>
            
            <div class='grid-container' id='dvDetalleEncuesta'>
                <div class='header'>
                    <div class='grid-logo'>
                        <img src='' alt='logo aceasoft' />
                    </div>
                    <div class='form-name'>
                        <p id='pNombreFormulario'></p>
                    </div>
                    <div class='form-info'>
                        <div id='codigo-formulario'><span class='title'>Código:</span> <span id='spnCodigoFormulario'></span></div>
                        <div id='edicion-formulario'><span class='title'>Edición No.:</span> <span id='spnEdicionFormulario'></span></div>
                        <div id='codigo-formulario'><span class='title'>Fecha Emisión:</span> <span id='spnFechaEmisionFormulario'></span></div>
                    </div>
                </div>
                <div class='blank'></div>
                <div class='quiz-general-info'>
                    <div class='group-info'>
                        <label>Oficina:</label><span id=respuestaOficina></span>
                    </div>
                    <div class='group-info'>
                        <label>Fecha:</label><span id=respuestaFecha></span>
                    </div>
                    <div class='group-info'>
                        <label>Cliente:</label><span id=respuestaNombreCliente></span>
                    </div>
                    <div class='group-info'>
                        <label>Encuestador:</label><span id=respuestaEncuestador></span>
                    </div>
                    <P id='sugerencia'>Favor marcar con una ✔ la respuesta que describa su nivel de satisfacción con cada una de las características de calidad.</P>
                </div>
                <div class='quiz-details'>
                    <div id='quiz-details-header'>
                        CARACTERÍSTICAS DE CALIDAD
                        <div id='dvPercepcion'>
                            PERCEPCIÓN DEL CLIENTE
                            <div id='dvCalificaciones'>
                                <p>Totalmente Satisfecho</p>
                                <p>Satisfecho</p>
                                <p>Medianamente Satisfecho</p>
                                <p>Insatisfecho</p>
                                <p>Muy Insatisfecho</p>                                
                            </div>
                        </div>
                    </div>
                    <div id='quiz-body'>
                        <div class='dvRespuesta'>
                            <label>1</label>
                            <label>Amabilidad y cortesía del personal de servicio al usuario.</label>
                            <div class='dvRespuestaCalificacion' id='respuestaPregunta_1'>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                            </div>
                        </div>
                        <div class='dvRespuesta'>
                            <label>2</label>
                            <label>Facilidad para comunicarse vía telefónica.</label>
                            <div class='dvRespuestaCalificacion' id='respuestaPregunta_2'>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                            </div>
                        </div>
                        <div class='dvRespuesta'>
                            <label>3</label>
                            <label>Amabilidad y cortesía del personal de brigada.</label>
                            <div class='dvRespuestaCalificacion' id='respuestaPregunta_3'>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                            </div>
                        </div>
                        <div class='dvRespuesta'>
                            <label>4</label>
                            <label>Claridad en la información ofrecida.</label>
                            <div class='dvRespuestaCalificacion' id='respuestaPregunta_4'>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                            </div>
                        </div>
                        <div class='dvRespuesta'>
                            <label>5</label>
                            <label>Nivel de conocimiento del personal. </label>
                            <div class='dvRespuestaCalificacion' id='respuestaPregunta_5'>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                            </div>
                        </div>   
                        <div class='dvRespuesta'>
                            <label>6</label>
                            <label>Tiempo de espera para la atención al cliente. </label>
                            <div class='dvRespuestaCalificacion' id='respuestaPregunta_6'>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                            </div>
                        </div>
                        <div class='dvRespuesta'>
                            <label>7</label>
                            <label>Tiempo de respuesta a las solicitudes y reclamaciones.</label>
                            <div class='dvRespuestaCalificacion' id='respuestaPregunta_7'>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                            </div>
                        </div>
                        <div class='dvRespuesta'>
                            <label>8</label>
                            <label>Entrega a tiempo de la factura.</label>
                            <div class='dvRespuestaCalificacion' id='respuestaPregunta_8'>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                            </div>
                        </div>
                        <div class='dvRespuesta'>
                            <label>9</label>
                            <label>Claridad en el desglose de los cargos facturados.</label>
                            <div class='dvRespuestaCalificacion' id='respuestaPregunta_9'>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                            </div>
                        </div>
                        <div class='dvRespuesta'>
                            <label>10</label>
                            <label>Facilidad para realizar pagos en plataforma virtual (CAASD en línea/CORAABO en línea).</label>
                            <div class='dvRespuestaCalificacion' id='respuestaPregunta_10'>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                            </div>
                        </div>
                        <div class='dvRespuesta'>
                            <label>11</label>
                            <label>Exactitud en la lectura del medidor.</label>
                            <div class='dvRespuestaCalificacion' id='respuestaPregunta_11'>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                            </div>
                        </div>
                        <div class='dvRespuesta'>
                            <label>12</label>
                            <label>Rapidez en la reconexión del servicio.</label>
                            <div class='dvRespuestaCalificacion' id='respuestaPregunta_12'>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                            </div>
                        </div>
                        <div class='dvRespuesta'>
                            <label>13</label>
                            <label>Condiciones de las instalaciones de la oficina.</label>
                            <div class='dvRespuestaCalificacion' id='respuestaPregunta_13'>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                            </div>
                        </div>
                        <div class='dvRespuesta'>
                            <label>14</label>
                            <label>En términos generales, ¿Cuál es su nivel de satisfacción con la gestión comercial del servicio?</label>
                            <div class='dvRespuestaCalificacion' id='respuestaPregunta_14'>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                                <div><div class='casilla'></div></div>
                            </div>
                        </div> 
                        <div id='dvPregunta15'>
                            <label>15</label>
                            <div>
                                <label>Los  principales aspectos que de inmediato entiende usted debe mejorar la empresa son:</label>
                                <div id='respuestaPregunta_15'>
                                     
                                </div>
                            </div>
                        </div>                                             
                    </div>
                </div>
            </div>  
        </body>
        
        <!--LOGICA-->
        <script>
            $(document).ready(()=>{            
                getEncuesta();
            });
            
            getEncuesta=()=>{
                let options = {url:'../datos/datos.EncuestaSatisfaccionUsuarios.php',method:'POST',data:{tip:'getEncuesta',id_encuesta:$id_encuesta}};
                $.ajax(options).done((res)=>{
                    let json = JSON.parse(res); 
                    let respuestasGenerales = json.data[0];
                    let respuestas = json.data[0].respuesta;      
                                  
                    $('#respuestaOficina').append(respuestasGenerales.oficina);
                    $('#respuestaFecha').append(respuestasGenerales.fecha);
                    $('#respuestaNombreCliente').append(respuestasGenerales.cliente);
                    $('#respuestaEncuestador').append(respuestasGenerales.encuestador);  
                    $('#pNombreFormulario').append(respuestasGenerales.nombre_formulario); 
                    $('#spnCodigoFormulario').append(respuestasGenerales.codigo_formulario); 
                    $('#spnEdicionFormulario').append(respuestasGenerales.edicion_formulario); 
                    $('#spnFechaEmisionFormulario').append(respuestasGenerales.fecha_emision_formulario); 
                    $('.grid-logo img').attr('src',respuestasGenerales.imagen_formulario);                     
                    
                    respuestas.forEach((respuesta)=>{                  
                      llenarRespuestas(respuesta);
                    })
                }).fail((error)=>{
                    console.error(error);
                })
            }
            
            llenarRespuestas = (respuesta)=>{
                let valorRespuesta = parseInt(respuesta.value);
                let respuestaTexto = respuesta.value;    
                let numeroPregunta = respuesta.name.split('_')[1]; 
                let elemento = $('#respuestaPregunta_'+numeroPregunta+' .casilla');
                
                if(!isNaN(valorRespuesta)){
                    let index = 5-valorRespuesta;
                    elemento[index].append('✔');   
                    return true             
                }
                
                $('#respuestaPregunta_'+numeroPregunta).append(respuestaTexto);                
            }
        </script>
    </html>
";

echo $html;