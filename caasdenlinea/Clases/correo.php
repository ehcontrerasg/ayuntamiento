<?php
include './../mail/class.phpmailer.php';


class correo{
	public function enviarcorreo($destino,$contrato,$estado){
		$mail = new PHPMailer();
		$mail->isSMTP();
		$mail->SMTPDebug=2;
		$mail->SMTPAuth=true;
		$mail->SMTPSecure="ssl";
		//$mail->Host="172.16.1.142";
        $mail->Host="smtpout.secureserver.net";
		$mail->Port='465';
		$mail->Username="info@caasdenlinea.com";
		$mail->Password="Aceasoft2015";
		$mail->setFrom("info@caasdenlinea.com","info@caasdenlinea.com");
		//$mail->addReplyTo("info@mail.caasdenlinea.com.do","info@mail.caasdenlinea.com.do");
		$mail->Subject="Prueba de envio de correo";
		$mail->msgHTML("la cuenta del contrato numero ".$contrato." ha sido ".$estado);
		//$mail->addAddress($destino,$contrato);
		$mail->addAddress("edwin_contrerass@hotmail.com","edwin");
		if(!$mail->send()){
			error_log("\n-------------------------------".$mail->ErrorInfo, 1, "holguinjean1@gmail.com");
			echo "Error al enviar: ".$mail->ErrorInfo;
		}
		else{
			echo "mensaje enviado";
			error_log("\n-------------------------------\n Enviado correctamente contrato: ".$contrato, 1,"holguinjean1@gmail.com");
		}
	}
}