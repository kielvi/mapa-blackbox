<? require_once("phpmailer/class.phpmailer.php");
$mail = new PHPMailer();
$mail->SetLanguage("br");
$mail->IsMail();
$mail->IsHTML(true);
$mail->From = $eMailRemetente; 	//email do remetente
$mail->FromName = $nomeRemetente; 	//Nome de formatado do remetente
$mail->Host = $servidorSMTP; 	//Seu servidor SMTP
$mail->Mailer = "smtp";			//Usando protocolo SMTP
$mail->AddAddress($destino); 	//O destino do email
$mail->Subject = $assunto;	//Assunto do email
$mail->Body = "<br>"; //Body of the message
$mail->Body .= '<font face=verdana size=2>';
$mail->Body .= $msgFinal;
$mail->Body .= "<br>";
$mail->Body.='</font>';
$mail->SMTPAuth = "true";
$mail->Username = $eMailRemetente; // Utilize uma conta valida para seu servidor
$mail->Password = $eMailRemetenteSenha; //Utilize a senha do Email-Valido valida

$mail->Send()
/*
if(!$mail->Send()){ //Check for result of sending mail
	echo "Um erro ocorreu ao tentar enviar o E-mail"; //Write an error message if mail isn't sent
	exit; //Exit the script without executing the rest of the code
}

echo $mess;
echo "Email enviado com sucesso";*/?>