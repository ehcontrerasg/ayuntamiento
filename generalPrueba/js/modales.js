(function(){
	var valMod = getParameterByName('mod');
	var numMod = 0;
$(document).ready(function() {
    $('#botConsGen').click(function(e) {
    	//e.preventDefault();
    	numMod++;
    	
    	//$('#botConsGen').attr('data-target', '#consultar-' + numMod);
    	var codInm = ($('#busGenInpCodSis').val() != '') ? $('#busGenInpCodSis').val() : 'Consulta ' + numMod;
        var proy = $('#busGenSelPro').val();
    		$('#ConsGenForm').submit(function() {
                setTimeout(function(){
                    $('#body-consulta').append(createModal(numMod + 1));
                    //$('#ConsGenForm')[0].reset();
                },1e3);
            });



    		$('#modales-line').prepend(newModal(codInm, numMod));

    $('button[data-close]').on('click', function(e) {
    	//e.preventDefault();
    	//e.stopPropagation();
    	var num = $(this).attr('data-close');
    	$('.modal-window[data-target=#consultar-'+(num)+']').remove();
        $('#ConsGenForm')[0].reset();
        $('#busGenSelPro').val(proy);
        $(`#consultar-${num}`).on('hidden.bs.modal', function(){
            $(this).remove();
        });
    	
    });
    $('#minimize-buttom').click(function(){
        $('#ConsGenForm')[0].reset();
         $('#busGenSelPro').val(proy);
    })

 /*   $('#consultar-'numMod).on('hidden.bs.modal', function (e) {
    	console.log('Holaa Mundo')
  		$('.modal-window[data-target="#consultar-'+numMod+'"]').remove();
	})*/
    $('.close-btn').click(function(e) {
           	e.preventDefault();
           	e.stopImmediatePropagation();
            var num = $(this).attr('data-close');
           	$(this).parent().remove();
            $('#ConsGenForm')[0].reset();
            $('#busGenSelPro').val(proy);
            $(`#consultar-${num}`).on('hidden.bs.modal', function(){
                $(this).remove();
            });
        });

     		if (valMod == '1') {
    	        $(".modal-window").addClass("fondCat");
    	    } else if (valMod == '2') {
    	        $(".modal-window").addClass("fondFac");
    	    } else if (valMod == '3') {
    	        $(".modal-window").addClass("fondCorte");
    	    } else if (valMod == '6') {
    	        $(".modal-window").addClass("fondGer");
    	    } else if (valMod == '7') {
    	        $(".modal-window").addClass("fondCassd");
    	    } else if (valMod == '9') {
    	        $(".modal-window").addClass("fondSerCli");
    	    } else if (valMod == '10') {
    	        $(".modal-window").addClass("fondMed");
    	    } else if (valMod == '11') {
    	        $(".modal-window").addClass("fondRec");
    	    } else if (valMod == '13') {
    	        $(".modal-window").addClass("fondGraCli");
    	    } else if (valMod == '15') {
    	        $(".modal-window").addClass("fondArc");
    	    }
    }); 

});

        function newModal(inm, num) {
            var modal = ' <div class="modal-window" data-toggle="modal" data-target="#consultar-' + num + '">';
            modal += '<b>' + inm + '</b> <i class="fa fa-times close-btn" aria-hidden="true"></i>';
            modal += '</div>';
            return modal;
        }

        function createModal(num) {
            var modal = '<div class="modal fade" id="consultar-' + num + '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">';
            modal += ' <div class="modal-dialog" role="document">';
            modal += '<div class="modal-content">';
            modal += '<div class="modal-header">';
            modal += '<button type="button" class="close" data-dismiss="modal" aria-label="Close" data-close="'+num+'"><span aria-hidden="true">&times;</span></button>';
            modal += '<button type="button" class="close" data-dismiss="modal" aria-label="Close" id="minimize-buttom">&minus;</button>';
            modal += '<h4 class="modal-title" id="myModalLabel">Consulta General</h4>';
            modal += '</div>';
            modal += ' <div class="modal-body">';
            modal += '<iframe frameborder="0" width="100%" class="modal-consulta-body" name="modal-consulta-' + num + '"></iframe>';
            modal += '</div>';
            modal += '<div class="modal-footer">';
            modal += '<button type="button" class="btn btn-default" data-dismiss="modal" data-close="'+num+'">Cerrar</button>';
            modal += '</div>';
            modal += '</div>';
            modal += '</div>';
            modal += '</div>';

            $('#botConsGen').attr('data-target', '#consultar-' + (num));
            $('#ConsGenForm').attr('target', 'modal-consulta-' + (num));
            return modal;
        }

        function getParameterByName(name) {
            name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
            var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);
            return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
        }
    })()