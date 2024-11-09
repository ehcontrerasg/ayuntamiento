/**
 * Created by ehcontrerasg on 7/4/2016.
 */

function eliminafoto(url) {

    swal({  title: "Deseas Eliminar la foto?",
        text: "una vez eliminada no se puede recuperar la foto!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "si, Eliminar!",
        closeOnConfirm: false }, function(){
        eliminafotoserv(url)

    })



}


function eliminafotoserv(url){
    urlSer="../../webservice/fotos_sgc/"+url;
    urlBd="../"+url;
    console.log(urlSer);
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            resp=xmlhttp.responseText;

            if(resp=="true"){
                eliminafotoBd(urlBd);
                return true;
            }else{
                swal("Error!", "No se pudo eliminar la foto "+ urlSer+" del servidor111", "error");
                return false;
            }
        }

    }
    xmlhttp.open("POST", "../datos/datos.fotos.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=elFoto&urlf="+urlSer);

}



function eliminafotoBd(url){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            resp=xmlhttp.responseText;

            if(resp=="true"){
                swal("Transaccion Exitosa!", "Has Eliminado correctamente la foto", "success");

                location.reload();
                return true;
            }else{
                swal("Error!", "No se pudo eliminar la foto "+ url+"del servidor", "error");
                return false;
            }
        }

    }
    xmlhttp.open("POST", "../datos/datos.fotos.php", true);   // async
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("tip=elFotoBd&urlf="+url);

}


var angulotot=0;
function rotate(angle,ob,url){
    angle=angle+angulotot;
    angle=angle%360;
    angulotot=angle;
    var rotation = Math.PI * angle / 180;
    var costheta = Math.cos(rotation);
    var sintheta = Math.sin(rotation);
    if(!window.ActiveXObject){
        ob.style.webkitTransform ='rotate('+angle+'deg)';
        ob.style.khtmlTransform ='rotate('+angle+'deg)';
        ob.style.MozTransform='rotate('+angle+'deg)';
        ob.style.OTransform='rotate('+angle+'deg)';
        ob.style.transform='rotate('+angle+'deg)';
    }else{
        ob.style.filter="progid:DXImageTransform.Microsoft.Matrix(sizingMethod='auto expand')";
        ob.filters.item(0).M11 = costheta;
        ob.filters.item(0).M12 = -sintheta;
        ob.filters.item(0).M21 = sintheta;
        ob.filters.item(0).M22 = costheta;
        ob.style.top= ((this.parentNode.offsetHeight/2)-(this.offsetHeight/2))+'px';
        ob.style.left=  ((this.parentNode.offsetWidth/2)-(this.offsetWidth/2))+'px';
    }

}


function redimension(obj){
    var imag = document.getElementById(obj);

    ancho=imag.width;
    alto=imag.height;

    if(ancho>alto){
        imag.width=640;
        imag.height=480;
    }else{
        imag.width=480;
        imag.height=640;
    }


}

