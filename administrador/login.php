<?php 
error_reporting(0);
session_start("adm2.0");
session_name("adm2.0");
require_once("../configuracoes.php");
require_once("dependencias.php");

// VERIFICA SE JA ESTA LOGADO
if(isset($_SESSION['cod_usuario'])) { 
	echo "<script>$(location).attr('href','index.php')</script>";
	exit;
}


if($acao=="login") {

	$query = mysql_query("SELECT * FROM admin_usuarios WHERE email='".$_POST['login-email']."' AND senha='".$_POST['login-senha']."'");
	$usuario = mysql_fetch_object($query);
	$total = mysql_num_rows($query);

	if($total>0) {

		// SALVA O COOKIE DO LEMBRAR
		if($_POST['login-lembrar']=="on") { 
			setcookie("cookieUsuario", $_POST['login-email'], time()+3600*24*182);
			setcookie("cookieSenha", $_POST['login-senha'], time()+3600*24*182);
			setcookie("cookieCodigo", $usuario->codigo, time()+3600*24*182);
		}
		session_register("adm2.0");
	
		$_SESSION['cod_usuario'] = $usuario->codigo;
		$_SESSION['nomeUsuario'] = $usuario->nome;

		echo "<script>$(location).attr('href','index.php')</script>";

	} else {
		//echo "<script>$('#modal-login-error').modal('show')</script>";
	}


} elseif($acao=="enviar-senha") {
	// VERIFICA SE EXISTE O LOGIN
	$vExistente = mysql_num_rows(mysql_query("SELECT * FROM admin_usuarios WHERE email='{$_POST['esqueci-email']}' "));

	if($vExistente>0) {
		$cadastro = mysql_fetch_object(mysql_query("SELECT * FROM admin_usuarios WHERE email='{$_POST['esqueci-email']}'"));
		$categoria = "ADMINISTRADOR | Lembrete de senha";

		$msg = "
			<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
			<html>
				<head>
					<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-2'>
					<style type='text/css'>* { margin:0; list-style:none; text-decoration:none; border:none; list-style-type:none; }</style>
				</head>
				<body style='margin:0; list-style:none; text-decoration:none; border:none; list-style-type:none; background:#FFF;'>
					<div style='width:551px; margin:0 auto;' align='center'>
						<div style='width:551px; margin:20px 0; float:left;'>
							<div style='width:551px; height:32px; background:url($urlserver./administrador/envioSMTP/images/topoItem01.png) no-repeat; float:left;'>&nbsp;</div>
							<div style='width:480px; padding:17px 24px 0 24px; background:#FFF; border:11px solid #e1e1e1; border-top:0; border-bottom:0; float:left;'>
								<div style='width:480px; float:left;'>
									<div style='width:288px; height:57px; background:url($urlserver./administrador/envioSMTP/images/topoLogo.png) no-repeat center; float:left;'>&nbsp;</div>	
									<div style='width:100px; padding-top:42px; float:right;' align='right'><a href='$urlserver./administrador/' target='_blank' style='font-family:Arial; font-size:13px; text-decoration:none; font-weight:bolder; color:#9e9e9e;'>Ir para o site</a></div>
								</div>
								<div style='width:480px; height:24px; background:url($urlserver./administrador/envioSMTP/images/separador01.png) repeat-x center; float:left;'>&nbsp;</div>
								<div style='width:480px; float:left;' align='left'><font style='font-family:Arial; font-size:10px; color:#666666;'>".converteData($dataDeHoje, 'enviodemail')." &nbsp;|&nbsp; Mensagem autom&aacute;tica</font></div>
								<div style='width:480px; height:24px; background:url($urlserver./administrador/envioSMTP/images/separador01.png) repeat-x center; float:left;'>&nbsp;</div>
								<div style='width:480px; padding-bottom:25px; float:left;' align='left'><font style='font-family:Arial; font-size:18px; font-weight:bold; text-align:center; color:#222222;'>Ola $cadastro->nome</font></div>

								<div style='width:480px; padding-bottom:25px; float:left;' align='justify'><font style='font-family:Arial; font-size:13px; color:#666666;'>Abaixo estao os dados de de acesso nosso administrador que voce solicitou:</font></div>
								<div style='width:480px; margin-bottom:10px; background:#98B75A; padding:15px 0; font-family:Arial; font-size:18px; font-weight:bold; text-align:center; color:#FFF; float:left;' align='justify'>EMAIL: $cadastro->email</div>
								<div style='width:480px; margin-bottom:10px; background:#0095DA; padding:15px 0; font-family:Arial; font-size:18px; font-weight:bold; text-align:center; color:#FFF; float:left;' align='justify'>SENHA: $cadastro->senha</div>
								<div><a href='$urlserver./administrador/' style='width:480px; margin-bottom:44px; background:#D22830; padding:20px 0; font-family:Arial; font-size:18px; text-align:center; text-decoration:none; color:#FFF; float:left;' target='_blank'>ACESSAR ADMINISTRADOR</a></div>

								<div style='width:480px; padding-bottom:6px; margin-bottom:21px; border-bottom:1px solid #b6b6b6; font-family:Arial; font-size:11px; color:#666666; float:left;' align='justify'>Este email foi enviado dia ".converteData($dataDeHoje, 'enviodemail2')." atrav&eacute;s do ip: $ip.</div>
								<div style='width:480px; padding-bottom:25px; float:left;'>
									<div style='width:125px; height:30px; float:left;'><img src='$urlserver./administrador/envioSMTP/images/rodapeLogo.png' width='125' height='30' border='0'>&nbsp;</div>
									<div style='width:216px; height:15px; padding:8px 0; float:right;'><img src='$urlserver./administrador/envioSMTP/images/rodapeFrase.png' width='216' height='15' border='0'></div>
								</div>
							</div>
							<div style='width:551px; height:37px; float:left;'><img src='$urlserver./administrador/envioSMTP/images/rodapeItem01.png'></div>
						</div>
					</div>
				</body>
			</html>
		";

		require 'envioSMTP/phpmailer/PHPMailerAutoload.php';
		$mail = new PHPMailer;
		$mail->SetLanguage("br");

		$eMailRemetente      = "noreply@unika.net.br";
		$eMailRemetenteSenha = "33190623";
		$nomeRemetente       = "$cadastro->nome";
		$servidorSMTP        = "mail.unika.net.br";
		$assunto             = "$categoria";
		$msgFinal            = $msg;

		$mail->isSMTP();						// Set mailer to use SMTP
		$mail->Host = $servidorSMTP;			// Specify main and backup SMTP servers
		$mail->SMTPAuth = true;					// Enable SMTP authentication
		$mail->Username = $eMailRemetente;		// SMTP username
		$mail->Password = $eMailRemetenteSenha;	// SMTP password
		$mail->SMTPSecure = 'tcp';				// Enable TLS encryption, `ssl` also accepted
		$mail->Port = 25;						// TCP port to connect to
		
		
		$mail->isHTML(true);
		$mail->Subject = "$categoria";
		$mail->Body = $msgFinal;
		$mail->setFrom($eMailRemetente, $nomeRemetente);
		
		// EMAIL QUE RECEBE O CONTEUDO
		$mail->addAddress("{$_POST['esqueci-email']}");

		if(!$mail->send()) {
		    echo 'Email não enviado: '.$mail->ErrorInfo.', entre em contato com seu programador.';
		} else {
			echo "<script>$('#modal-esqueci-sucess').modal('show')</script>";
		}
	} else { 
		echo "<script>$('#modal-esqueci-error').modal('show')</script>";
	}

}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
		<title>Login | Administrador</title>
	</head>
	<body>
		<main class="container">
			<section class="col-sm-6 offset-sm-3 col-md-4 offset-md-4 login animated fadeInUp delay1s">
				<svg class="login-logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 360"><path fill="#FFFFFF" d="M137.5 217.04h89.225v17.744H191.01v89.543h-17.784V234.89H137.5v-17.85zm155.622-52.234c-.09-15.194-.053-30.395-.04-45.594 0-.938.08-1.845.138-3.373 24.71 21.974 48.962 43.577 73.514 65.392V73.92c-5.308 4.142-10.227 8.094-15.275 11.845-1.746 1.315-2.44 2.682-2.417 4.878.08 15.814.057 31.63.057 47.438v3.406c-24.938-22.848-49.207-45.106-73.826-67.672v107.552c5.455-4.263 10.494-8.308 15.664-12.168 1.584-1.18 2.202-2.386 2.184-4.392zM430.118 0v360H69.882V0h360.236zm-11.35 11.392H81.27v337.43h337.497V11.39zm-56.72 261.604c6.4-7.15 8.575-15.675 8.466-25.107-.215-18.68-12.52-31.066-31.225-31.066H289.035v18.01h3.362c15.64 0 31.292.03 46.92 0 4.69-.024 8.653 1.505 11.122 5.574 3.08 5.122 3.027 10.642.56 15.843-2.32 4.902-6.78 6.595-12.137 6.57-15.55-.08-31.104-.032-46.665-.032h-3.23V280.8h3.084c11.377 0 22.742.03 34.13-.05 1.825-.032 2.846.532 3.73 2.168 6.754 12.492 13.718 24.896 20.3 37.47 1.688 3.214 3.55 4.394 7.12 4.15 4.9-.32 9.832-.08 15.192-.08-8.097-14.737-15.92-29-23.88-43.496 5.547-1.37 9.872-4.047 13.403-7.966zM168.977 92.892c9.383-2.168 18.358-1.602 25.76 6.037 4.706-3.65 9.422-7.333 14.273-11.103-.417-.534-.66-.897-.937-1.205-15.493-17.3-42.017-15.3-55.668-5.624-15.13 10.72-16.03 33.728-1.703 44.914 5.267 4.1 11.403 6.326 17.97 7.64 4.853.97 9.7 2.146 14.367 3.808 4.206 1.498 7.1 4.662 7.742 9.378.965 7.185-3.11 13.64-10.306 15.624-9.04 2.495-17.206.618-24.334-6.028-4.73 3.673-9.474 7.37-14.313 11.125.35.476.578.827.873 1.148.588.67 1.18 1.343 1.85 1.933 14.096 12.54 30.068 13.964 46.897 7.02 14.425-5.95 21.93-22.837 17.562-37.87-3.555-12.222-12.52-18.253-24.27-20.92-5.025-1.135-10.147-1.767-15.184-2.827-6.503-1.34-9.7-4.838-9.918-10.412-.227-6.762 2.638-11.08 9.338-12.638zm42.363-50.116c.163-2.467-.81-2.908-3.044-2.896-22.822.07-45.626.042-68.437.058-.94 0-1.875.08-2.76.134v12.594h74.185c0-3.514-.174-6.725.056-9.89z"/></svg>
				<form method="post" action="" role="form">
					<input type="hidden" id="acao" name="acao" value="login">
					<div class="form-group">
						<input type="email" class="form-control" id="login-email" name="login-email" value="<?php echo (isset($_POST['login-email']) ? $_POST['login-email'] : $_COOKIE['cookieUsuario']); ?>" placeholder="Email de acesso" required>
					</div>
					<div class="form-group">
						<input type="password" class="form-control" id="login-senha" name="login-senha" value="<?php echo $_COOKIE['cookieSenha']; ?>" placeholder="Senha" required>
					</div>
					<div class="form-group">
						<ul>
							<li><input id="login-lembrar" name="login-lembrar" type="radio"><label for="login-lembrar">Lembrar meu acesso</label></li>
						</ul>
					</div>
					<div class="form-group" align="center">
						<button type="button" data-toggle="modal" data-target="#modal-esqueci" class="btn btn-default">Esqueci minha senha</button><button type="submit" class="btn btn-default">Acessar</button>
					</div>
				</form>
			</section>
		</main>

		<!-- ESQUECI A SENHA MODAL -->
		<div class="modal fade" id="modal-esqueci" tabindex="-1" role="dialog" aria-labelledby="modalEsqueciLabel">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="modalEsqueciLabel">Lembrar minha senha</h4>
					</div>
					<div class="modal-body">
						<form id="form-esqueci" name="form-esqueci" action="" method="post">
							<input type="hidden" id="acao" name="acao" value="enviar-senha">
							<div class="form-group">
								<input type="email" class="form-control" id="esqueci-email" name="esqueci-email" placeholder="Informe seu e-mail de acesso" required>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">FECHAR</button>
						<button type="submit" form="form-esqueci" class="btn btn-primary">ENVIAR SENHA</button>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>

<div id="modal-login-error" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">ACESSO INVÁLIDO</h4>
			</div>
			<div class="modal-body"><p>Email e/ou senha incorreto(s). Tente novamente..</p></div>
		</div>
	</div>
</div>

<div id="modal-esqueci-error" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">EMAIL INVÁLIDO</h4>
			</div>
			<div class="modal-body"><p>Este e-mail não existe no sistema, tente novamente.</p></div>
		</div>
	</div>
</div>
<div id="modal-esqueci-sucess" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">ENVIADO COM SUCESSO</h4>
			</div>
			<div class="modal-body"><p>Sua senha foi enviada com sucesso para <?php $parseEmail = explode("@", $_POST['esqueci-email']); echo $parseEmail[0]."@".substr($parseEmail[1], 0, 1)."..."; ?></p></div>
		</div>
	</div>
</div>

<script type="text/javascript">
if(document.createElement('svg').getAttributeNS) {
	var radiobxsFill = Array.prototype.slice.call( document.querySelectorAll( '.login form input[type="radio"]' ) ),
		pathDefs = {
			fill : ['M15.833,24.334c2.179-0.443,4.766-3.995,6.545-5.359 c1.76-1.35,4.144-3.732,6.256-4.339c-3.983,3.844-6.504,9.556-10.047,13.827c-2.325,2.802-5.387,6.153-6.068,9.866 c2.081-0.474,4.484-2.502,6.425-3.488c5.708-2.897,11.316-6.804,16.608-10.418c4.812-3.287,11.13-7.53,13.935-12.905 c-0.759,3.059-3.364,6.421-4.943,9.203c-2.728,4.806-6.064,8.417-9.781,12.446c-6.895,7.477-15.107,14.109-20.779,22.608 c3.515-0.784,7.103-2.996,10.263-4.628c6.455-3.335,12.235-8.381,17.684-13.15c5.495-4.81,10.848-9.68,15.866-14.988 c1.905-2.016,4.178-4.42,5.556-6.838c0.051,1.256-0.604,2.542-1.03,3.672c-1.424,3.767-3.011,7.432-4.723,11.076 c-2.772,5.904-6.312,11.342-9.921,16.763c-3.167,4.757-7.082,8.94-10.854,13.205c-2.456,2.777-4.876,5.977-7.627,8.448 c9.341-7.52,18.965-14.629,27.924-22.656c4.995-4.474,9.557-9.075,13.586-14.446c1.443-1.924,2.427-4.939,3.74-6.56 c-0.446,3.322-2.183,6.878-3.312,10.032c-2.261,6.309-5.352,12.53-8.418,18.482c-3.46,6.719-8.134,12.698-11.954,19.203 c-0.725,1.234-1.833,2.451-2.265,3.77c2.347-0.48,4.812-3.199,7.028-4.286c4.144-2.033,7.787-4.938,11.184-8.072 c3.142-2.9,5.344-6.758,7.925-10.141c1.483-1.944,3.306-4.056,4.341-6.283c0.041,1.102-0.507,2.345-0.876,3.388 c-1.456,4.114-3.369,8.184-5.059,12.212c-1.503,3.583-3.421,7.001-5.277,10.411c-0.967,1.775-2.471,3.528-3.287,5.298 c2.49-1.163,5.229-3.906,7.212-5.828c2.094-2.028,5.027-4.716,6.33-7.335c-0.256,1.47-2.07,3.577-3.02,4.809']
		},
		animDefs = {
			fill : { speed :.8, easing : 'ease-in-out' }
		};

		function createSVGEl( def ) {
			var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
			if( def ) {
				svg.setAttributeNS( null, 'viewBox', def.viewBox );
				svg.setAttributeNS( null, 'preserveAspectRatio', def.preserveAspectRatio );
			} else {
				svg.setAttributeNS( null, 'viewBox', '0 0 100 100' );
			}
			svg.setAttribute( 'xmlns', 'http://www.w3.org/2000/svg' );
			return svg;
		}
		function controlRadiobox( el, type ) {
			var svg = createSVGEl();
			el.parentNode.appendChild( svg );
			el.addEventListener( 'change', function() {
				resetRadio( el );
				draw( el, type );
			} );
		}
		
		radiobxsFill.forEach( function( el, i ) { controlRadiobox( el, 'fill' ); } );
		
		function draw( el, type ) {
			var paths = [], pathDef, animDef, svg = el.parentNode.querySelector( 'svg' );
			switch( type ) {
				case 'fill': pathDef = pathDefs.fill; animDef = animDefs.fill; break;
			};
			paths.push( document.createElementNS('http://www.w3.org/2000/svg', 'path' ) );

			if( type === 'cross' || type === 'list' ) {
				paths.push( document.createElementNS('http://www.w3.org/2000/svg', 'path' ) );
			}

			for( var i = 0, len = paths.length; i < len; ++i ) {
				var path = paths[i];
				svg.appendChild( path );
				path.setAttributeNS( null, 'd', pathDef[i] );
				var length = path.getTotalLength();
				path.style.strokeDasharray = length + ' ' + length;
				if( i === 0 ) {
					path.style.strokeDashoffset = Math.floor( length ) - 1;
				} else path.style.strokeDashoffset = length;
				path.getBoundingClientRect();
				path.style.transition = path.style.WebkitTransition = path.style.MozTransition  = 'stroke-dashoffset ' + animDef.speed + 's ' + animDef.easing + ' ' + i * animDef.speed + 's';
				path.style.strokeDashoffset = '0';
			}
		}
		function reset( el ) {
			Array.prototype.slice.call( el.parentNode.querySelectorAll( 'svg > path' ) ).forEach( function( el ) { el.parentNode.removeChild( el ); } );
		}
		function resetRadio( el ) {
			Array.prototype.slice.call( document.querySelectorAll( 'input[type="radio"][name="' + el.getAttribute( 'name' ) + '"]' ) ).forEach( function( el ) { 
			var path = el.parentNode.querySelector( 'svg > path' );
			if( path ) {
				path.parentNode.removeChild( path );
			}
		});
	}
}
</script>