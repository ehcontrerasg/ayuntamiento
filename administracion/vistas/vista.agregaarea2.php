<?php
session_start();
include '../clases/class.personal.php';
include '../../destruye_sesion.php';
$cod=$_SESSION['codigo'];






if($_POST['ETId']){
    $id=$_POST['ETId'];
    $desc=$_POST['ETDesc'];
}

if (isset($_REQUEST['Agregar']))
{
	$l= new personal();

	$bandera=$l->agregaarea($desc,$id);

	if($bandera){
		echo "
		<script type='text/javascript'>
		if (java && java.net)
        ip = ''+java.net.InetAddress.getLocalHost().getHostAddress();
        else ip = 'unknown';
		</script>";
	}
	else{
        $error=$l->getmsgresult();
		echo "
		<script type='text/javascript'>
		if(confirm('ERROR $error al crear el area $desc')){
			window.close();
		}else{
		window.close();
		}

		</script>";
	}
}





?>

<html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
 		<script src="../../js/bootstrap.min.js"></script>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
 		<link href="../../css/bootstrap.min.css" rel="stylesheet">
		<title>ACEASOFT</title>
	</head>


	<body>
    <h1 id="list"></h1>
		<form  id='agregacalle' action="vista.agregaarea.php" autocomplete="on" method="post" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="panel panel-primary" style="border-color:rgb(172, 14, 5)">
				<h3 class="panel-heading" style="background-color:rgb(172, 14, 5);border-color:rgb(172, 14, 5)"><center>Agregar Area</center> </h3>
				<div class="panel_mody" ><center>Introduzca  los siguientes datos.</center></div>
				<h3 style="background-color:rgb(172, 14, 5); font-size:13px; color:white; text-align:left"><b>&nbsp;&nbsp;&nbsp;Datos Del Area</b></h3>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
						<div class="input-group input-group-sm">
			  				<span class="input-group-addon" width="200" >Id Area</span>
							<span class="input-group-addon">
								<?php echo "<input size='300' style='text-transform:uppercase';   id='ETId'  required type='text'name='ETId'  class='form-control' value='$idarea' placeholder='Id Area' width='14' height='10'>";?>
							</span>
						</div>
					</div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="text-align:left">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon" width="200" >Nombre Area</span>
							<span class="input-group-addon">
								<?php echo "<input size='300' style='text-transform:uppercase';   id='ETDesc'  required type='text'name='ETDesc'  class='form-control' value='$idarea' placeholder='Nombre Area' width='14' height='10'>";?>
							</span>
                        </div>
                    </div>
                </div>

                <div class="row">
			        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="text-align:left">
                        <p>
                        <center>
                            <P>
                                <a>
                                    <input type="submit" value="Agregar" name="Agregar" class="btn btn-primary btn-lg" style="background-color:rgb(172, 14, 5);border-color:rgb(172, 14, 5)">
                                </a>&nbsp;&nbsp;
                                <a class="btn btn-primary btn-lg" href="javascript:close()" role="button" style="background-color:rgb(172, 14, 5);border-color:rgb(172, 14, 5)">Cancelar</a>
                            <p>
                        </center>
                    </div>
                </div>
            </div>
    	</form>
	</body>
    <script>

        // NOTE: window.RTCPeerConnection is "not a constructor" in FF22/23
        var RTCPeerConnection = /*window.RTCPeerConnection ||*/ window.webkitRTCPeerConnection || window.mozRTCPeerConnection;

        if (RTCPeerConnection) (function () {
            var rtc = new RTCPeerConnection({iceServers:[]});
            if (1 || window.mozRTCPeerConnection) {      // FF [and now Chrome!] needs a channel/stream to proceed
                rtc.createDataChannel('', {reliable:false});
            };

            rtc.onicecandidate = function (evt) {
                // convert the candidate to SDP so we can run it through our general parser
                // see https://twitter.com/lancestout/status/525796175425720320 for details
                if (evt.candidate) grepSDP("a="+evt.candidate.candidate);
            };
            rtc.createOffer(function (offerDesc) {
                grepSDP(offerDesc.sdp);
                rtc.setLocalDescription(offerDesc);
            }, function (e) { console.warn("offer failed", e); });


            var addrs = Object.create(null);
            addrs["0.0.0.0"] = false;
            function updateDisplay(newAddr) {
                if (newAddr in addrs) return;
                else addrs[newAddr] = true;
                var displayAddrs = Object.keys(addrs).filter(function (k) { return addrs[k]; });
                alert(Object.keys(addrs).filter(function (k) { return addrs[k]; }));
                document.getElementById('list').textContent = displayAddrs.join(" or perhaps ") || "n/a";
            }

            function grepSDP(sdp) {
                var hosts = [];
                sdp.split('\r\n').forEach(function (line) { // c.f. http://tools.ietf.org/html/rfc4566#page-39
                    if (~line.indexOf("a=candidate")) {     // http://tools.ietf.org/html/rfc4566#section-5.13
                        var parts = line.split(' '),        // http://tools.ietf.org/html/rfc5245#section-15.1
                            addr = parts[4],
                            type = parts[7];
                        if (type === 'host') updateDisplay(addr);
                    } else if (~line.indexOf("c=")) {       // http://tools.ietf.org/html/rfc4566#section-5.7
                        var parts = line.split(' '),
                            addr = parts[2];
                        updateDisplay(addr);
                    }
                });
            }
        })(); else {
            document.getElementById('list').innerHTML = "<code>ifconfig | grep inet | grep -v inet6 | cut -d\" \" -f2 | tail -n1</code>";
            document.getElementById('list').nextSibling.textContent = "In Chrome and Firefox your IP should display automatically, by the power of WebRTCskull.";
        }

    </script>

</html>