/**
 * Created by Algenis Mosquea on 12/01/2018.
 */

var selProyEstPqr;
var inpFecIniEstPqr;
var inpFecFinEstPqr;
var butGenEstPqr;
var genRepPqrReSolForm;
var inpHidValRes;
var inpTipoPqr;
var motivo_pqr;

$(document).ready(function(){
    selProyEstPqr=document.getElementById("selProyEstPqr");
    inpFecIniEstPqr=document.getElementById("inpFecIniEstPqr");
    inpFecFinEstPqr=document.getElementById("inpFecFinEstPqr");
    inpHidValRes=document.getElementById("inpHidValRes");
    inpTipoPqr=document.getElementById("inpTipoPqr");
    motivo_pqr=document.getElementById("inpMotivoPqr");
    butGenEstPqr=document.getElementById("butGenEstPqr");
    genRepPqrReSolForm=document.getElementById("genRepPqrReSolForm");
    //butGenEstPqr.addEventListener("click",verificaSession);
    genRepPqrReSolForm.addEventListener("submit",genRepPqrReSolForm );

    inpTipoPqr.addEventListener("change",getMotivoPqr);

    $("#genRepPqrReSolForm").submit(
        function(){
            if(validaCampos()) {

                swal
                ({
                        title: "Advertencia!",
                        text: "El reporte demorara unos minutos en salir.",
                        showConfirmButton: true,
                        showCancelButton: true,
                        showLoaderOnConfirm: true,
                        closeOnConfirm: false,
                        closeOnCancel: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            compSession(genRepPqrsReSol);
                        }
                    });
            }
        });
});


function repEstPqrInicio(){


	selProyEstPqr=document.getElementById("selProyEstPqr");
	inpFecIniEstPqr=document.getElementById("inpFecIniEstPqr");
	inpFecFinEstPqr=document.getElementById("inpFecFinEstPqr");
	inpHidValRes=document.getElementById("inpHidValRes");
	inpTipoPqr=document.getElementById("inpTipoPqr");
	motivo_pqr=document.getElementById("inpMotivoPqr");
	butGenEstPqr=document.getElementById("butGenEstPqr");
	genRepPqrReSolForm=document.getElementById("genRepPqrReSolForm");
	//butGenEstPqr.addEventListener("click",verificaSession);
	genRepPqrReSolForm.addEventListener("submit",genRepPqrReSolForm );

    inpTipoPqr.addEventListener("change",getMotivoPqr);

    $("#genRepPqrReSolForm").submit(
        function(){
            if(validaCampos()) {

                swal
                ({
                        title: "Advertencia!",
                        text: "El reporte demorara unos minutos en salir.",
                        showConfirmButton: true,
                        showCancelButton: true,
                        showLoaderOnConfirm: true,
                        closeOnConfirm: false,
                        closeOnCancel: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            compSession(genRepPqrsReSol);
                        }
                    });
            }
        });
}

function compSession(callback)
{
    $.ajax
    ({
        url : '../../configuraciones/session.php',
        type : 'POST',
        data:{tip : 'sess'},
        dataType : 'json',
        success : function(json) {
            if(json==true){
                if(callback){
                    callback();
                }
            }else if(json==false){
                $("#ingResInsInpCodSis").focus(false);
                swal({
                        title: "Mensaje ",
                        html: true,
                        type: "input",
                        text: "Su sesion ha finalizado ingrese los datos para continuar.<br>" +
                            "<input type='text' class='estilo-inp' required placeholder='Usuario' id='usr'><br> <input type='password' tabindex='4' placeholder='Password' class='estilo-inp' required id='pass'>",
                        showCancelButton: true,
                        closeOnConfirm: false,
                        closeOnCancel: true,
                        inputPlaceholder: "Usuario",
                        animation: "slide-from-top"

                    },

                    function(inputValue){
                        if (inputValue === false)
                            return false;
                        if ($("#usr").val() == "") {
                            swal.showInputError("El usuario no puede ser vacio !");
                            return false
                        }else  if ($("#pass").val() == "") {
                            swal.showInputError("La contraseña no puede ser vacia !");
                            return false
                        }

                        else{
                            $("#ingResInsInpCodSis").focus();
                            iniSes();
                        }
                    }
                );


                return false;
            }
        },
        error : function(xhr, status) {
            swal
            (
                {
                    title: "Mensaje!",
                    text: "Su sesion ha finalizado.",
                    showConfirmButton: true
                },
                function(isConfirm)
                {
                    if (isConfirm)
                    {
                        top.location.replace("../../index.php")
                    }
                }
            );
            return false;
        }
    });
}

function getMotivoPqr(e)
{
    var data = e.target.selectedIndex;
    motivo_pqr.innerHTML = '';

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var datos=xmlhttp.responseText;
            var motivos = JSON.parse(datos);
            var option = document.createElement('option');
                option.setAttribute('value', '0');
                option.innerText = 'Motivo PQR:';
                motivo_pqr.appendChild(option);

            console.log(motivos['DESC_MOTIVO_REC'][0])
            for(var i = 0 ; i < motivos['DESC_MOTIVO_REC'].length; i++){
                var option = document.createElement('option');
                option.setAttribute('value', motivos['ID_MOTIVO_REC'][i]);
                option.innerText = motivos['ID_MOTIVO_REC'][i] + '-' + motivos['DESC_MOTIVO_REC'][i];

                motivo_pqr.appendChild(option);
            };
        }
    }
    xmlhttp.open("POST", "../datos/datos.datPqrsReSolicitadas.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=motiv&group="+data);

}


function validaCampos(){
    console.log("valida");

    if(selProyEstPqr.selectedIndex==0){
		selProyEstPqr.focus();
        swal("Error!", "Debe seleccionar el proyecto", "error");
        return false;
    }

    /*
    if(inpTipoPqr.selectedIndex==0){
		inpTipoPqr.focus();
        swal("Error!", "Debe indicar un tipo de PQR", "error");
        return false;
    }


    if(inpFecIniEstPqr.value!="" && inpFecFinEstPqr.value==""){
		inpFecIniEstPqr.focus();
        swal("Error!", "Debe indicar una fecha final", "error");
        return false;
    }*/

/*
	if(inpFecFinEstPqr.value!="" && inpFecIniEstPqr.value=="" ){
		inpFecFinEstPqr.focus();
        swal("Error!", "Debe indicar una fecha inicial", "error");
        return false;
    }
	*/
    return true;
}

function genRepPqrsReSol2(){

    var datos=$("#genRepPqrReSolForm").serializeArray();
    datos.push({name:"tip",value:"PqrReSol"});
    $.ajax
    ({
        url : '../datos/datos.datPqrsReSolicitadas.php',
        type : 'POST',
        dataType : 'text',
        data : datos ,
        success : function(urlPdf) {

            if (urlPdf.substr(0,11)=="../../temp/"){

                window.location.href = urlPdf;
                //window.close();
                swal("Mensaje!", "Has Generado correctamente el reporte", "success");
            }else{
                swal
                (
                    {
                        title: "Error",
                        text: "Contacte a sistemas",
                        type: "error",
                        html: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ok!",
                        closeOnConfirm: true});

            }

        },
        error : function(xhr, status) {
            swal("Error!", "error desconocido contacte a sistemas", "error");
        }
    });
}

function genRepPqrsReSol()
{

/*
    var proyecto =$("#selRepPagConcAcu").val();
    var concepto  = 20;
    var fechaIn = $("#inpRepPagConcIniFec").val();
    var fechaFn = $("#inpRepPagConcFinFec").val();*/

    var datos=$("#genRepPqrReSolForm").serializeArray();
    datos.push({name:"tip",value:"PqrReSol"});

    $.ajax
    ({

        url : '../datos/datos.datPqrsReSolicitadas.php',
        type : 'POST',
        dataType : 'json',
        data : datos ,
        success : function(res) {
           // swal.close();
           //  var dat = JSON.parse(res);
            swal("Mensaje!", "Has Generado correctamente el reporte", "success");

            if ( $.fn.dataTable.isDataTable( '#dataTable' ) ) {
                $('#dataTable').DataTable().destroy();
            }

            $('#dataTable').DataTable( {
                data: res,
                dom: 'lBfrtip',
                columns: [
                    {title: "CODIGO INMUENLE"},
                    {title: "MOTIVO"},
                    {title: "DIAS RESOLUCION"},
                    {title: "CODIGO PQR"},
                    {title: "FECHA REGISTRO PQR"},

                ],
                buttons: [
                    { extend: 'copy', text:' Copiar', className: 'btn btn-primary glyphicon glyphicon-duplicate' },
                    { extend: 'csv',  text:' CSV', className: 'btn btn-primary glyphicon glyphicon-save-file' },


                    {
                        extend: 'excel',
                        className: 'btn btn-primary glyphicon glyphicon-list-alt',
                        title: 'Reporte PQRs Re-Solicitadas',

                    },

                    {extend:'print',text:' Imprimir', className: 'btn btn-primary glyphicon glyphicon-print' },

                    { extend: 'pdfHtml5',
                        title: 'Reporte PQRs Re-Solicitadas',
                        pageSize: 'A3',//A0 is the largest A5 smallest(A0,A1,A2,A3,legal,A4,A5,letter))
                        fontSize:'5',
                        className: 'btn btn-primary glyphicon glyphicon-file',
                        customize: function ( doc ) {

                            // Para el formato del titulo
                            doc.styles.title = {

                                fontSize: '14',
                                alignment: 'center',
                                margin: [ 0, 0, 0, 0 ]

                            };

                        }
                    },
                ],


                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                "info": false,
                "paging": true,
                "ordering": true,
                "searching": true,
                "language":  {url: '../../js/DataTables-1.10.15/Spanish.json'}

            });

            $('#dataTable').show();

        },

        error : function(xhr, status) {
            swal
            (
                {
                    title: "Error",
                    text: "Contacte a sistemas",
                    type: "error",
                    html: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ok!",
                    closeOnConfirm: true});
        }

    });

}

function verificaSession(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var datos=xmlhttp.responseText;
            if(datos=="true"){
				if(validaCampos()){
					 swal({
                     	title: "Mensaje",
                        text: "El reporte puede tomar algunos minutos.\nDesea generarlo ahora?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Si!",
                        cancelButtonText: "No!",
                        showLoaderOnConfirm: true,
                        closeOnConfirm: false,
                        closeOnCancel: true 
					},
                    function(isConfirm){
                    	if (isConfirm) {
                        	genRepPqrsReSol();
                        }
                    });
				}
            }else{
                swal({
                        title: "Mensaje!",
                        text: "Su sesion ha finalizado.",
                        showConfirmButton: true },
                    function(isConfirm)
                    {
                        if (isConfirm)
                        {
							 top.location.replace("../../index.php");
                        }
                    }
                );
                return false;
            }
        }
    }
    xmlhttp.open("POST", "../datos/datos.datPqrsReSolicitadas.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=sess");
}