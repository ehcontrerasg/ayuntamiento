<?php

    class PlantillaCorreo extends ConexionClass{

        //private $_FILAS_OPCIONALES  = [];

        public function _construct(){
            parent::__construct();
            //$this->_FILAS_OPCIONALES = ['FECHA_COMPROMISO','COMENTARIO'];
        }

        //Función que contiene los estilos para los correos de Solicitudes TI.
        private function EstilosCorreosSolicitud(){

            $estilos = 'section{
                                    background-color: #fafafa;
                                    padding: 30px;
                                }
                        
                                table{
                                    margin: 0px auto;
                                    background-color: white;
                                    width: 40%;
                                    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
                                }
                        
                                table thead td {
                                    padding: 12px;
                                    background: linear-gradient(135deg, #29b94a 39%, #41981d 100%);
                                    color: white;
                                    font-size: 20px;
                                    text-align: center;
                                }
                        
                                table tbody td{
                                    color: #0e0d0e;
                                    font-size: 19px;
                                    padding: 21px;
                                }
                                
                                #dvResumenCorreo{
                                    margin: 0px auto;
                                    background-color: white;
                                    width: 40%;
                                    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
                                }
                                #dvResumenCorreo p{
                                    text-align: justify;
                                    padding: 2%;
                                }
                                
                                #dvResumenCorreo #encabezado
                                {
                                    padding: 12px;
                                    background: linear-gradient(135deg, #29b94a 39%, #41981d 100%);
                                    color: white;
                                    font-size: 20px;
                                    text-align: center;
                                } ';

            return $estilos;
        }

        /*private function FilasOpcionales($filas_opcionales = []){
            $filas = '';
            return $filas;
        }*/

        //Cuerpo del correo cuando llega una solicitud nueva lista para validar calidad.
        public function SolicitudParaValidarCalidad($datos_solicitud,$informacion_adicional){

            $html = '<!doctype html>
                     <html>
                        <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                            <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"/>
                            <style>'
                                .$this->EstilosCorreosSolicitud().
                                /*section{
                                    background-color: #fafafa;
                                    padding: 30px;
                                }
                        
                                table{
                                    margin: 0px auto;
                                    background-color: white;
                                    width: 40%;
                                    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
                                }
                        
                                table thead td {
                                    padding: 12px;
                                    background: linear-gradient(135deg, #29b94a 39%, #41981d 100%);
                                    color: white;
                                    font-size: 20px;
                                    text-align: center;
                                }
                        
                                table tbody td{
                                    color: #0e0d0e;
                                    font-size: 19px;
                                    padding: 21px;
                                }*/
                            '</style>
                        </head>
                        <body>
                            <section>
                                <div id="dvResumenCorreo">
                                    <p id="encabezado">Información</p>
                                    <p>'.$informacion_adicional.'</p>
                                </div>
                                <table class="table-striped">
                                    <thead>
                                        <tr>
                                            <td colspan="2">Datos de la solicitud</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td> <strong>Número de solicitud:</strong></td>
                                            <td>'.$datos_solicitud["ID_SOLICITUD"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Solicitador:</strong></td>
                                            <td>'.$datos_solicitud["SOLICITADOR"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fecha de solicitud:</strong></td>
                                            <td>'.$datos_solicitud["FECHA_SOLICITUD"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Pantalla:</strong></td>
                                            <td>'.$datos_solicitud["PANTALLA"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Descripción:</strong></td>
                                            <td>'.$datos_solicitud["DESCRIPCION"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Estado:</strong></td>
                                            <td>'.$datos_solicitud["ESTADO"].'</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </section>
                        </body>
                     </html>';

            return utf8_decode($html);
        }

        //Cuerpo del correo cuando llega una solicitud nueva lista para validar calidad.
        public function SolicitudAtendidaPorCalidad($datos_solicitud,$informacion_adicional,$comentario= ''){

            $p_comentario = '';
            if($comentario !=""){
                $p_comentario = '<p><i>"'.$comentario["COMENTARIO"].'"</i></p>
                                 <p style="margin-left: 30%;"><strong>Usuario que comentó: </strong>'.$comentario["USUARIO_COMENTA"].'</p>
                                ';
            }

            $html = '<!doctype html>
                     <html>
                        <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                            <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"/>
                            <style>'
                                .$this->EstilosCorreosSolicitud().
                            '</style>
                        </head>
                        <body>
                            <section>
                                <div id="dvResumenCorreo">
                                    <p id="encabezado">Información</p>
                                    <p>'.$informacion_adicional.'</p>'
                                    .$p_comentario.
                                '</div>
                                <table class="table-striped">
                                    <thead>
                                        <tr>
                                            <td colspan="2">Datos de la solicitud</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td> <strong>Número de solicitud:</strong></td>
                                            <td>'.$datos_solicitud["ID_SOLICITUD"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Solicitador:</strong></td>
                                            <td>'.$datos_solicitud["SOLICITADOR"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fecha de solicitud:</strong></td>
                                            <td>'.$datos_solicitud["FECHA_SOLICITUD"].'</td>
                                        </tr>';

                                        if($comentario ==""){
                                            $html .= '<tr>
                                                       <td><strong>Fecha de compromiso:</strong></td>
                                                       <td>'.$datos_solicitud["FECHA_COMPROMISO"].'</td>
                                                    </tr>';
                                        }


            $html .=                     '<tr>
                                            <td><strong>Pantalla:</strong></td>
                                            <td>'.$datos_solicitud["PANTALLA"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Estado:</strong></td>
                                            <td>'.$datos_solicitud["ESTADO"].'</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </section>
                        </body>
                     </html>';

            return utf8_decode($html);
        }

        //Cuerpo del correo cuando llega una solicitud nueva lista para asignar a un desarrollador.
        public function SolicitudParaAsignar($datos_solicitud,$informacion_adicional){

            $html = '<!doctype html>
                     <html>
                        <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                            <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"/>
                            <style>'
                                .$this->EstilosCorreosSolicitud().
                                /*section{
                                    background-color: #fafafa;
                                    padding: 30px;
                                }
                        
                                table{
                                    margin: 0px auto;
                                    background-color: white;
                                    width: 40%;
                                    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
                                }
                        
                                table thead td {
                                    padding: 12px;
                                    background: linear-gradient(135deg, #29b94a 39%, #41981d 100%);
                                    color: white;
                                    font-size: 20px;
                                    text-align: center;
                                }
                        
                                table tbody td{
                                    color: #0e0d0e;
                                    font-size: 19px;
                                    padding: 21px;
                                }*/
                            '</style>
                        </head>
                        <body>
                            <section>
                                <div id="dvResumenCorreo">
                                    <p id="encabezado">Información</p>
                                    <p>'.$informacion_adicional.'</p>
                                </div>
                                <table class="table-striped">
                                    <thead>
                                        <tr>
                                            <td colspan="2">Datos de la solicitud</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td> <strong>Número de solicitud:</strong></td>
                                            <td>'.$datos_solicitud["ID_SOLICITUD"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Solicitador:</strong></td>
                                            <td>'.$datos_solicitud["SOLICITADOR"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fecha de solicitud:</strong></td>
                                            <td>'.$datos_solicitud["FECHA_SOLICITUD"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fecha de compromiso:</strong></td>
                                            <td>'.$datos_solicitud["FECHA_COMPROMISO"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Pantalla:</strong></td>
                                            <td>'.$datos_solicitud["PANTALLA"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Descripción:</strong></td>
                                            <td>'.$datos_solicitud["DESCRIPCION"].'</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </section>
                        </body>
                     </html>';

            return utf8_decode($html);
        }

        //Cuerpo del correo cuando llega una solicitud nueva lista para validar calidad.
        public function SolicitudAnulada($datos_solicitud, $informacion_adicional,$comentario = ''){

            //Se verifica si hay comentarios o no.
            if($comentario != ''){
                /*$html_comentario = '<tr>
                                            <td><strong>Comentario de '.$comentario["USUARIO_COMENTA"].':</strong></td>
                                            <td>'.$comentario["COMENTARIO"].'</td>
                                    </tr>';*/
                $html_comentario = '<p><i>"'.$comentario["COMENTARIO"].'"</i></p>
                                    <p style="margin-left: 30%"><strong>Usuario que comentó: </strong>'.$comentario["USUARIO_COMENTA"].'</p>
                                   ';
            }

            $html = '<!doctype html>
                     <html>
                        <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                            <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"/>
                            <style>'
                                .$this->EstilosCorreosSolicitud().
                                /*section{
                                    background-color: #fafafa;
                                    padding: 30px;
                                }
                        
                                table{
                                    margin: 0px auto;
                                    background-color: white;
                                    width: 40%;
                                    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
                                }
                        
                                table thead td {
                                    padding: 12px;
                                    background: linear-gradient(135deg, #29b94a 39%, #41981d 100%);
                                    color: white;
                                    font-size: 20px;
                                    text-align: center;
                                }
                        
                                table tbody td{
                                    color: #0e0d0e;
                                    font-size: 19px;
                                    padding: 21px;
                                }
                                
                                #dvResumenCorreo{
                                    margin: 0px auto;
                                    background-color: white;
                                    width: 40%;
                                    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
                                }
                                #dvResumenCorreo p{
                                    text-align: justify;
                                    padding: 2%;
                                }
                                
                                #dvResumenCorreo #encabezado
                                {
                                    padding: 12px;
                                    background: linear-gradient(135deg, #29b94a 39%, #41981d 100%);
                                    color: white;
                                    font-size: 20px;
                                    text-align: center;
                                } */
                            '</style>
                        </head>
                        <body>
                            <section>
                                <div id="dvResumenCorreo">
                                    <p id="encabezado">Información</p>
                                    <p>'.$informacion_adicional.'</p>'
                                        .$html_comentario.
                                '</div>
                                <table class="table-striped">
                                    <thead>
                                        <tr>
                                            <td colspan="2">Datos de la solicitud</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td> <strong>Número de solicitud:</strong></td>
                                            <td>'.$datos_solicitud["ID_SOLICITUD"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Solicitador:</strong></td>
                                            <td>'.$datos_solicitud["SOLICITADOR"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fecha de solicitud:</strong></td>
                                            <td>'.$datos_solicitud["FECHA_SOLICITUD"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Pantalla:</strong></td>
                                            <td>'.$datos_solicitud["PANTALLA"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Descripción:</strong></td>
                                            <td>'.$datos_solicitud["DESCRIPCION"].'</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </section>
                        </body>
                     </html>';

            return utf8_decode($html);
        }

        //Cuerpo del correo cuando una solicitud es asignada a un desarrollador.
        public function SolicitudAsignada($datos_solicitud, $informacion_adicional,$desarrollador){

            $html = '<!doctype html>
                     <html>
                        <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                            <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"/>
                            <style>'
                                .$this->EstilosCorreosSolicitud().
                                /*section{
                                    background-color: #fafafa;
                                    padding: 30px;
                                }
                        
                                table{
                                    margin: 0px auto;
                                    background-color: white;
                                    width: 40%;
                                    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
                                }
                        
                                table thead td {
                                    padding: 12px;
                                    background: linear-gradient(135deg, #29b94a 39%, #41981d 100%);
                                    color: white;
                                    font-size: 20px;
                                    text-align: center;
                                }
                        
                                table tbody td{
                                    color: #0e0d0e;
                                    font-size: 19px;
                                    padding: 21px;
                                }
                                
                                #dvResumenCorreo{
                                    margin: 0px auto;
                                    background-color: white;
                                    width: 40%;
                                    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
                                }
                                #dvResumenCorreo p{
                                    text-align: justify;
                                    padding: 2%;
                                }
                                
                                #dvResumenCorreo #encabezado
                                {
                                    padding: 12px;
                                    background: linear-gradient(135deg, #29b94a 39%, #41981d 100%);
                                    color: white;
                                    font-size: 20px;
                                    text-align: center;
                                }       */
                            '</style>
                        </head>
                        <body>
                            <section>
                                <div id="dvResumenCorreo">
                                    <p id="encabezado">Información</p>
                                    <p>'.$informacion_adicional.'</p>
                                </div>
                                <table class="table-striped">
                                    <thead>
                                        <tr>
                                            <td colspan="2">Datos de la solicitud</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td> <strong>Número de solicitud:</strong></td>
                                            <td>'.$datos_solicitud["ID_SOLICITUD"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Solicitador:</strong></td>
                                            <td>'.$datos_solicitud["SOLICITADOR"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fecha de solicitud:</strong></td>
                                            <td>'.$datos_solicitud["FECHA_SOLICITUD"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Pantalla:</strong></td>
                                            <td>'.$datos_solicitud["PANTALLA"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Descripción:</strong></td>
                                            <td>'.$datos_solicitud["DESCRIPCION"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Desarrollador:</strong></td>
                                            <td>'.$desarrollador.'</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </section>
                        </body>
                     </html>';

            return utf8_decode($html);
        }

        //Cuerpo del correo cuando una solicitud ha sido finalizada.
        public function SolicitudFinalizada($datos_solicitud, $informacion_adicional,$desarrollador){

            $html = '<!doctype html>
                     <html>
                        <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                            <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"/>
                            <style>'
                                .$this->EstilosCorreosSolicitud().
                                /*section{
                                    background-color: #fafafa;
                                    padding: 30px;
                                }
                        
                                table{
                                    margin: 0px auto;
                                    background-color: white;
                                    width: 40%;
                                    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
                                }
                        
                                table thead td {
                                    padding: 12px;
                                    background: linear-gradient(135deg, #29b94a 39%, #41981d 100%);
                                    color: white;
                                    font-size: 20px;
                                    text-align: center;
                                }
                        
                                table tbody td{
                                    color: #0e0d0e;
                                    font-size: 19px;
                                    padding: 21px;
                                }
                                
                                #dvResumenCorreo{
                                    margin: 0px auto;
                                    background-color: white;
                                    width: 40%;
                                    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
                                }
                                #dvResumenCorreo p{
                                    text-align: justify;
                                    padding: 2%;
                                }
                                
                                #dvResumenCorreo #encabezado
                                {
                                    padding: 12px;
                                    background: linear-gradient(135deg, #29b94a 39%, #41981d 100%);
                                    color: white;
                                    font-size: 20px;
                                    text-align: center;
                                } */
                            '</style>
                        </head>
                        <body>
                            <section>
                                <div id="dvResumenCorreo">
                                    <p id="encabezado">Información</p>
                                    <p>'.$informacion_adicional.'</p>
                                </div>
                                <table class="table-striped">
                                    <thead>
                                        <tr>
                                            <td colspan="2">Datos de la solicitud</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td> <strong>Número de solicitud:</strong></td>
                                            <td>'.$datos_solicitud["ID_SOLICITUD"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Solicitador:</strong></td>
                                            <td>'.$datos_solicitud["SOLICITADOR"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fecha de solicitud:</strong></td>
                                            <td>'.$datos_solicitud["FECHA_SOLICITUD"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Pantalla:</strong></td>
                                            <td>'.$datos_solicitud["PANTALLA"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Descripción:</strong></td>
                                            <td>'.$datos_solicitud["DESCRIPCION"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Desarrollador:</strong></td>
                                            <td>'.$desarrollador.'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Estado:</strong></td>
                                            <td>'.$datos_solicitud["ESTADO"].'</td>
                                        </tr>
                                        <tr>
                                        <td><strong>Nota:</strong></td>
                                        <td>El solicitante '.$datos_solicitud["SOLICITADOR"].' tiene un plazo de <strong>7 días</strong> para aprobar o rechazar la solicitud.</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </section>
                        </body>
                     </html>';

            return utf8_decode($html);
        }
        //Cuerpo del correo cuando una solicitud ha sido rechazada por el solicitante.
        public function SolicitudRechazadaPorSolicitante($datos_solicitud,$informacion_adicional,$desarrollador,$comentario){

            $html = '<!doctype html>
                     <html>
                        <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                            <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"/>
                            <style>'
                                .$this->EstilosCorreosSolicitud().
                                /*section{
                                    background-color: #fafafa;
                                    padding: 30px;
                                }
                        
                                table{
                                    margin: 0px auto;
                                    background-color: white;
                                    width: 40%;
                                    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
                                }
                        
                                table thead td {
                                    padding: 12px;
                                    background: linear-gradient(135deg, #29b94a 39%, #41981d 100%);
                                    color: white;
                                    font-size: 20px;
                                    text-align: center;
                                }
                        
                                table tbody td{
                                    color: #0e0d0e;
                                    font-size: 19px;
                                    padding: 21px;
                                }
                                
                                #dvResumenCorreo{
                                    margin: 0px auto;
                                    background-color: white;
                                    width: 40%;
                                    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
                                }
                                #dvResumenCorreo p{
                                    text-align: justify;
                                    padding: 2%;
                                }
                                
                                #dvResumenCorreo #encabezado
                                {
                                    padding: 12px;
                                    background: linear-gradient(135deg, #29b94a 39%, #41981d 100%);
                                    color: white;
                                    font-size: 20px;
                                    text-align: center;
                                }          
                                
                                #pComentario{
                                    text-align: center;
                                }  */
                            '</style>
                        </head>
                        <body>
                            <section>
                                <div id="dvResumenCorreo">
                                    <p id="encabezado">Información</p>
                                    <p>'.$informacion_adicional.'</p>
                                    <p id="pComentario"><i>"'.$comentario["COMENTARIO"].'"</i></p>
                                </div>
                                <table class="table-striped">
                                    <thead>
                                        <tr>
                                            <td colspan="2">Datos de la solicitud</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td> <strong>Número de solicitud:</strong></td>
                                            <td>'.$datos_solicitud["ID_SOLICITUD"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Solicitador:</strong></td>
                                            <td>'.$datos_solicitud["SOLICITADOR"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fecha de solicitud:</strong></td>
                                            <td>'.$datos_solicitud["FECHA_SOLICITUD"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Pantalla:</strong></td>
                                            <td>'.$datos_solicitud["PANTALLA"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Descripción:</strong></td>
                                            <td>'.$datos_solicitud["DESCRIPCION"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Desarrollador:</strong></td>
                                            <td>'.$desarrollador.'</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </section>
                        </body>
                     </html>';

            return utf8_decode($html);
        }
        //Cuerpo del correo cuando una solicitud ha sido aprobada por el solicitante.
        public function SolicitudAprobadaPorSolicitante($datos_solicitud,$informacion_adicional,$desarrollador){

            $html = '<!doctype html>
                     <html>
                        <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                            <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"/>
                            <style>'
                                .$this->EstilosCorreosSolicitud().
                                /*section{
                                    background-color: #fafafa;
                                    padding: 30px;
                                }
                        
                                table{
                                    margin: 0px auto;
                                    background-color: white;
                                    width: 40%;
                                    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
                                }
                        
                                table thead td {
                                    padding: 12px;
                                    background: linear-gradient(135deg, #29b94a 39%, #41981d 100%);
                                    color: white;
                                    font-size: 20px;
                                    text-align: center;
                                }
                        
                                table tbody td{
                                    color: #0e0d0e;
                                    font-size: 19px;
                                    padding: 21px;
                                }
                                
                                #dvResumenCorreo{
                                    margin: 0px auto;
                                    background-color: white;
                                    width: 40%;
                                    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
                                }
                                #dvResumenCorreo p{
                                    text-align: justify;
                                    padding: 2%;
                                }
                                
                                #dvResumenCorreo #encabezado
                                {
                                    padding: 12px;
                                    background: linear-gradient(135deg, #29b94a 39%, #41981d 100%);
                                    color: white;
                                    font-size: 20px;
                                    text-align: center;
                                }   */
                            '</style>
                        </head>
                        <body>
                            <section>
                                <div id="dvResumenCorreo">
                                    <p id="encabezado">Información</p>
                                    <p>'.$informacion_adicional.'</p>
                                </div>
                                <table class="table-striped">
                                    <thead>
                                        <tr>
                                            <td colspan="2">Datos de la solicitud</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td> <strong>Número de solicitud:</strong></td>
                                            <td>'.$datos_solicitud["ID_SOLICITUD"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Solicitador:</strong></td>
                                            <td>'.$datos_solicitud["SOLICITADOR"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fecha de solicitud:</strong></td>
                                            <td>'.$datos_solicitud["FECHA_SOLICITUD"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Pantalla:</strong></td>
                                            <td>'.$datos_solicitud["PANTALLA"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Descripción:</strong></td>
                                            <td>'.$datos_solicitud["DESCRIPCION"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Desarrollador:</strong></td>
                                            <td>'.$desarrollador.'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Estado:</strong></td>
                                            <td>'.$datos_solicitud["ESTADO"].'</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </section>
                        </body>
                     </html>';

            return utf8_decode($html);
        }

        //Cuerpo del correo cuando una solicitud ha sido iniciada por el desarrollador.
        public function SolicitudIniciadaPorDesarrollador($datos_solicitud,$informacion_adicional,$desarrollador){

            $html = '<!doctype html>
                     <html>
                        <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                            <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"/>
                            <style>'
                                .$this->EstilosCorreosSolicitud().
                            '</style>
                        </head>
                        <body>
                            <section>
                                <div id="dvResumenCorreo">
                                    <p id="encabezado">Información</p>
                                    <p>'.$informacion_adicional.'</p>
                                </div>
                                <table class="table-striped">
                                    <thead>
                                        <tr>
                                            <td colspan="2">Datos de la solicitud</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td> <strong>Número de solicitud:</strong></td>
                                            <td>'.$datos_solicitud["ID_SOLICITUD"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Solicitador:</strong></td>
                                            <td>'.$datos_solicitud["SOLICITADOR"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fecha de solicitud:</strong></td>
                                            <td>'.$datos_solicitud["FECHA_SOLICITUD"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Pantalla:</strong></td>
                                            <td>'.$datos_solicitud["PANTALLA"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Descripción:</strong></td>
                                            <td>'.$datos_solicitud["DESCRIPCION"].'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Desarrollador:</strong></td>
                                            <td>'.$desarrollador.'</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Estado:</strong></td>
                                            <td>'.$datos_solicitud["ESTADO"].'</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </section>
                        </body>
                     </html>';

            return utf8_decode($html);
        }

        public function recuperacionPass($nombreSolicitante = '', $passProvisional = ''){
            return "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta http-equiv='X-UA-Compatible' content='IE=edge○>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Document</title>
                <style>
                    html{
                        height: 100%;
                    }
                    
                    body {
                        height: 95%;
                        background: linear-gradient(40deg, rgba(0,94,169,1) 0%, rgba(0,149,176,1) 63%, rgba(135,197,78,1) 100%);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }
            
                    #content {
                        min-width: 50%;
                        margin: 0 auto;
                        background: white;
                        padding: 5px;
                        border-radius: 10px;
                        box-shadow: 0px 0px 20px #514b4b;
                    }
            
                    hr {
                        border: 0.1px solid #e6e6e6;
                    }
            
                    #encabezado img{
                        width: 70px;
                    }
            
                    #encabezado {
                        display: flex;
                    }
            
                    #logo {
                        display: flex;
                        width: 40%;
                    }
            
                    #logo #spnAceasoft {
                        font-size: 20px;
                        color: #0073ac;
                    }
            
                    #titulo {
                        width: 70%;
                        font-size: 22px;
                        font-weight: bold;
                        color: #065c9d;
                    }
            
                    #cuerpo{
                        padding: 10px;
                    }
            
                    #cuerpo p {
                        font-size: 18px;
                    }
            
                    #dvPassProvisional {
                        background: #ececec;
                        text-align: center;
                        padding: 15px;
                        font-size: 20px;
                        color: #065c9d;
                        font-weight: bold;
                        border: 1px solid #cacaca;
                        border-radius: 3px;
                    }

                    #pNota {
                        font-style: italic;
                        opacity: 0.6;
                    }
                </style>
            </head>
            <body>
                <div id='content'>
                    <div id='encabezado' class='row'>
                        <label id='logo'>
                            <img src='../../images/ACEA-Logo.svg' alt='Logo de ACEA'><span id='spnAceasoft'>SOFT</span>
                        </label>
                        <label id='titulo'>Recuperación de contraseña</label>
                    </div>
                    <hr>
                    <div id='cuerpo'>
                        <p>Hola, hemos recibido una solicitud de recuperación de contraseña del sr./sra. <strong>".$nombreSolicitante."</strong>.</p>
                        <p>A continuación, la contraseña provisional: </p>
                        <div id='dvPassProvisional'>
                            <label>".$passProvisional."</label>
                        </div>
                        <p id='pNota'>Nota: Esta contraseña será válida sólo por 30 minutos.</p>
                    </div>
                </div>
            </body>
            </html>";
        }

    }